<?php namespace WebReinvent\VaahCms\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use ZanySoft\Zip\Zip;

class Module extends Model {

    use SoftDeletes;
    //-------------------------------------------------
    protected $table = 'vh_modules';
    //-------------------------------------------------
    protected $dates = [
        'update_checked_at',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    //-------------------------------------------------
    protected $dateFormat = 'Y-m-d H:i:s';
    //-------------------------------------------------
    protected $fillable = [
        'title',
        'name',
        'slug',
        'download_link',
        'excerpt',
        'description',
        'author_name',
        'author_website',
        'vaah_url',
        'version',
        'version_number',
        'db_table_prefix',
        'is_active',
        'is_migratable',
        'is_assets_published',
        'is_sample_data_available',
        'is_update_available',
        'update_checked_at',
    ];

    //-------------------------------------------------
    public function setSlugAttribute( $value ) {
        $this->attributes['slug'] = Str::slug( $value );
    }
    //-------------------------------------------------
    public function scopeActive( $query ) {
        return $query->where( 'is_active', 1 );
    }

    //-------------------------------------------------
    public function scopeInactive( $query ) {
        return $query->whereNull( 'is_active');
    }

    //-------------------------------------------------
    public function scopeUpdateAvailable( $query ) {
        return $query->where( 'is_update_available', 1 );
    }
    //-------------------------------------------------
    public function scopeSlug( $query, $slug ) {
        return $query->where( 'slug', $slug );
    }
    //-------------------------------------------------


    //-------------------------------------------------
    public function scopeCreatedBetween( $query, $from, $to ) {
        return $query->whereBetween( 'created_at', array( $from, $to ) );
    }

    //-------------------------------------------------
    public function scopeUpdatedBetween( $query, $from, $to ) {
        return $query->whereBetween( 'updated_at', array( $from, $to ) );
    }

    //-------------------------------------------------
    public function scopeDeletedBetween( $query, $from, $to ) {
        return $query->whereBetween( 'deleted_at', array( $from, $to ) );
    }
    //-------------------------------------------------
    public function settings()
    {
        return $this->morphMany('WebReinvent\VaahCms\Entities\Setting', 'settingable');
    }
    //-------------------------------------------------
    public function migrations()
    {
        return $this->morphMany('WebReinvent\VaahCms\Entities\Migration', 'migrationable');
    }
    //-------------------------------------------------
    public static function getDetail($id)
    {

        $item = static::where('id', $id)
            ->withTrashed()
            ->first();

        $response['status'] = 'success';
        $response['data'] = $item;

        return $response;

    }
    //-------------------------------------------------
    public static function syncModule($module_path)
    {

        $settings = vh_get_module_settings_from_path($module_path);

        if(is_null($settings) || !is_array($settings) || count($settings) < 1)
        {
            $response['status'] = 'failed';
            $response['errors'][] = 'Fatal with '.$module_path.'\settings.json';
            return $response;
        }

        $rules = array(
            'name' => 'required',
            'title' => 'required',
            'slug' => 'required',
            'thumbnail' => 'required',
            'excerpt' => 'required',
            //'download_link' => 'required',
            'author_name' => 'required',
            'author_website' => 'required',
            'version' => 'required',
        );

        $validator = \Validator::make( $settings, $rules);
        if ( $validator->fails() ) {

            $errors             = errorsToArray($validator->errors());
            $response['status'] = 'failed';
            $response['errors'] = $errors;
            return $response;
        }


        $settings['version_number'] =  str_replace('v','',$settings['version']);
        $settings['version_number'] =  str_replace('.','',$settings['version_number']);

        $module = Module::firstOrCreate(['slug' => $settings['slug']]);
        $module->fill($settings);
        $module->save();


        $removeKeys = [
            'name',
            'title',
            'description',
            'slug',
            'thumbnail',
            'excerpt',
            'github_url',
            'author_name',
            'author_website',
            'is_sample_data_available',
            'version',
        ];


        $other_settings = array_diff_key($settings, array_flip($removeKeys));

        foreach ($other_settings as $key => $setting_input)
        {

            $setting_data = [];

            $setting_data['key'] = $key;

            if(is_array($setting_input) || is_object($setting_input))
            {
                $setting_data['type'] = 'json';
                $setting_data['value'] = json_encode($setting_input);

            } else
            {
                $setting_data['value'] = $setting_input;
            }

            $setting = $module->settings()->where('key', $key)->first();

            if(!$setting)
            {
                $setting = new Setting($setting_data);
                $module->settings()->save($setting);
            }

        }

        $module = Module::where('slug', $module->slug)->with(['settings'])->first();

        return $module;


    }
    //-------------------------------------------------
    public static function syncAllModules()
    {

        $list = vh_get_all_modules_paths();

        $installed = static::orderBy('name', 'asc')->get()
            ->pluck('name')->toArray();



        if($installed && count($list) < 1)
        {
            foreach ($installed as $item)
            {
                $installed_module = static::where('name', $item)->first();
                $installed_module->forceDelete();
            }
        }


        if(count($list) < 1)
        {
            $response['status'] = 'failed';
            $response['errors'][] = 'No module installed/downloaded';
            return $response;
        }

        $installed_module_names = [];

        if(count($list) > 0)
        {
            foreach ($list as $module_path)
            {
                $installed_module_names[] = basename($module_path);
            }
        }

        //remove database records if module folder does not exist
        if(count($installed_module_names) > 0)
        {
            foreach ($installed as $item)
            {
                if(!in_array($item, $installed_module_names))
                {
                    $installed_module = static::where('name', $item)->first();
                    $installed_module->forceDelete();
                }
            }

        }



        foreach($list as $module_path)
        {
            $res = Module::syncModule($module_path);

            /*if($res['status'] == 'failed')
            {
                echo "<pre>";
                print_r($res);
                echo "</pre>";
                die("<hr/>line number=123");

            }*/
        }

    }
    //-------------------------------------------------
    public static function getInstalledModules()
    {
        $list = Model::all();
        return $list;
    }
    //-------------------------------------------------
    public static function getActiveModules()
    {
        return Module::where('is_active', 1)->get();
    }

    //-------------------------------------------------
    public static function validateDependencies($dependencies)
    {

        $response['status'] = 'success';


        foreach ($dependencies as $key => $dependency_list)
        {


            switch($key){

                case 'modules':

                    if(is_array($dependency_list))
                    {
                        foreach ($dependency_list as $dependency_slug)
                        {
                            $module = Module::slug($dependency_slug)->first();

                            if(!$module)
                            {
                                $response['status'] = 'failed';
                                $response['errors'][] = "Please install and activate '".$dependency_slug."'.";
                            }

                            if($module && $module->is_active != 1)
                            {
                                $response['status'] = 'failed';
                                $response['errors'][] = $dependency_slug.' module is not active';
                            }
                        }

                    }

                    break;
                //------------------------
                case 'theme':

                    break;
                //------------------------
                //------------------------
                //------------------------
                //------------------------
            }



        }

        return $response;
    }
    //-------------------------------------------------

    //-------------------------------------------------
    public static function activate($slug)
    {

        $module = Module::slug($slug)->first();

        if(!isset($module->is_migratable) || (isset($module->is_migratable) && $module->is_migratable == true))
        {
            $module_path = config('vaahcms.modules_path').$module->name;
            $path = "/".config('vaahcms.root_folder')."/Modules/".$module->name."/Database/Migrations/";

            Migration::runMigrations($path);

            Migration::syncModuleMigrations($module->id);

            $seeds_namespace = config('vaahcms.root_folder')."\Modules\\{$module->name}\\Database\Seeds\DatabaseTableSeeder";
            Migration::runSeeds($seeds_namespace);

            //copy assets to public folder
            Module::copyAssets($module);

        }

        $module->is_active = 1;
        $module->is_assets_published = 1;
        $module->save();

    }
    //-------------------------------------------------
    public static function copyAssets($module)
    {
        $module_path = config('vaahcms.modules_path').'/'.$module->name;
        $source = $module_path."/Resources/assets";
        $dec = public_path('vaahcms/modules/'.$module->slug.'/assets');

        if(!\File::exists($source)) {
            return false;
        }

        if(!\File::exists($dec)) {
            \File::makeDirectory($dec, 0755, true, true);
        }

        \File::copyDirectory($source, $dec);

        return true;
    }
    //-------------------------------------------------
    public static function getOfficialDetails($slug)
    {

        try{
            $api = config('vaahcms.api_route')."/module/by/slug/".$slug;

            $api_response = @file_get_contents($api);

            if(!isset($api_response) || empty($api_response))
            {
                $response['status'] = 'failed';
                $response['data']['url'] = $api;
                $response['errors'][] = 'API Response Error.';
                return $response;
            }

            $api_response = json_decode($api_response, true);


            if(!isset($api_response) || !isset($api_response['status']) || $api_response['status'] != 'success')
            {
                $response['status'] = 'failed';
                $response['data']['url'] = $api;
                $response['data']['data'] = $api_response;
                $response['errors'][] = 'API Response Error.';


                return $response;

            } else if($api_response['status'] == 'success')
            {
                return $api_response;
            } else
            {
                $response['status'] = 'failed';
                $response['data']['url'] = $api;
                $response['data']['data'] = $api_response;
                $response['errors'][] = 'Unknown Error.';
            }


        }catch(\Exception $e)
        {
            $response['status'] = 'failed';
            $response['errors'][] = $e->getMessage();
            return $response;
        }




    }
    //-------------------------------------------------
    public static function download($name, $download_link)
    {

        //check if module is already installed
        $vaahcms_path = config('vaahcms.modules_path').'/';
        //$vaahcms_path = base_path('Download/Modules').'/';

        $package_path = $vaahcms_path.$name;

        if(is_dir($package_path))
        {
            $response['status'] = 'success';
            $response['data'] = [];
            $response['messages'][] = $name." module already exist.";
            return $response;
        }

        $zip_file = $package_path.".zip";

        copy($download_link, $zip_file);

        try{
            Zip::check($zip_file);
            $zip = Zip::open($zip_file);
            $zip_content_list = $zip->listFiles();
            $zip->extract($vaahcms_path);
            $zip->close();

            if (strpos($download_link, 'github.com') !== false) {
                $extracted_folder_name = $zip_content_list[0];
                rename($vaahcms_path.$extracted_folder_name, $package_path);
            }

            vh_delete_folder($zip_file);

            $response['status'] = 'success';
            $response['data'] = [];
            $response['messages'][] = $name." module is installed.";
            return $response;

        }catch(\Exception $e)
        {
            $response['status'] = 'failed';
            $response['errors'][] = $e->getMessage();
            return $response;
        }

    }
    //-------------------------------------------------
    public static function installUpdates($request)
    {

        $name = $request->name;
        $download_link = $request->download_link;

        $vaahcms_path = config('vaahcms.modules_path').'/';

        $module = static::where('name', $name)->first();


        $package_path = $vaahcms_path.$name;

        $zip_file = $package_path.".zip";

        copy($download_link, $zip_file);

        try{
            Zip::check($zip_file);
            $zip = Zip::open($zip_file);
            $zip_content_list = $zip->listFiles();
            $zip->extract($vaahcms_path);
            $zip->close();

            if (strpos($download_link, 'github.com') !== false) {
                $extracted_folder_name = $zip_content_list[0];
                File::copyDirectory($vaahcms_path.$extracted_folder_name, $package_path);
            }

            vh_delete_folder($vaahcms_path.$extracted_folder_name);
            vh_delete_folder($zip_file);

            //if the modules is active then run migration & seeds
            if($module->is_active)
            {
                static::activate($module->slug);
                $module->version = $request->version;
                $module->version_number = $request->version_number;
                $module->is_update_available = null;
                $module->save();
            }


            $response['status'] = 'success';
            $response['data'] = [];
            $response['messages'][] = $name." module is installed.";
            return $response;

        }catch(\Exception $e)
        {
            $response['status'] = 'failed';
            $response['errors'][] = $e->getMessage();
            return $response;
        }

    }
    //-------------------------------------------------
    public static function importSampleData($slug)
    {
        $module = Module::slug($slug)->first();

        $command = 'db:seed';
        $params = [
            '--class' => config('vaahcms.root_folder')."\Modules\\{$module->name}\\Database\Seeds\SampleDataTableSeeder"
        ];

        \Artisan::call($command, $params);
    }
    //-------------------------------------------------
    public static function storeUpdates($request)
    {
        $updates = 0;
        if(count($request->modules) > 0 )
        {
            foreach ($request->modules as $module)
            {
                $store_modules = static::where('slug', $module['slug'])->first();

                if($store_modules->version_number < $module['version_number'])
                {
                    $store_modules->is_update_available = 1;
                    $store_modules->save();
                    $updates++;
                }

            }
        }

        $response['status'] = 'success';
        $response['data'][] = '';
        if($updates > 0)
        {
            $response['messages'][] = 'New updates are available for '.$updates.' modules.';
        } else{
            $response['messages'][] = 'No new update available.';
        }
        if(env('APP_DEBUG'))
        {
            $response['hint'][] = '';
        }
        return $response;

    }

    //-------------------------------------------------
    //-------------------------------------------------
    //-------------------------------------------------
    //-------------------------------------------------

}
