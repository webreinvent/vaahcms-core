# vaahcms
> Laravel Based Rapid Development CMS

Please consider starring the project to show your :heart: and support.



## Steps to Setup

### Step 1) Install Package
```bash
composer require webreinvent/vaahcms
```

### Step 2) Publish Assets
```bash
php artisan vendor:publish --provider="WebReinvent\VaahCms\VaahCmsServiceProvider" --tag=assets
php artisan vendor:publish --provider="WebReinvent\VaahCms\VaahCmsServiceProvider" --tag=migrations
php artisan vendor:publish --provider="WebReinvent\VaahCms\VaahCmsServiceProvider" --tag=seeds
```

### Step 3) Register Service Provider

Add following service provider in `config/app.php`

```php
/*
 * Package Service Providers...
 */
 'providers' => [
         //...
         WebReinvent\VaahCms\VaahCmsServiceProvider::class,
         //...
     ],

```

### Step 4) Add following in `config/auth.php`
```php
'providers' => [
        //...
        'users' => [
            'driver' => 'eloquent',
            'model' => \WebReinvent\VaahCms\Entities\User::class,
        ],
        //...
    ],
```

### Step 5) Update composer.json file

Create following folder in your laravel root folder

```
vaahcms/Modules
vaahcms/Themes
```

Add following two lines in `psr-4` in `composer.json`
```json
...
"autoload": {
    "files": [],
    "psr-4": {
        "App\\": "app/",
        ...
        "VaahCms\\Modules\\": "vaahcms/Modules/",
        "VaahCms\\Themes\\": "vaahcms/Themes/"
        ...
    },
    "classmap": [
        "database/seeds",
        "database/factories"
    ]
},
...
```

Then run following command
```bash
composer dump-autoload
```

### Step 6) Visit ```<base-url>/public/vaahcms/setup```:


### Step 7) If you get `Numeric value out of range` error then you can fix it by adding  following code in `App\Providers\AppServiceProvider.php`
```php
public function boot()
{
    Schema::defaultStringLength(191);
}
```


### Commands

#### Publish All Assets
```bash
php artisan vendor:publish --provider="WebReinvent\VaahCms\VaahCmsServiceProvider"
```

#### Publish All Configuration
```bash
php artisan vendor:publish --provider="WebReinvent\VaahCms\VaahCmsServiceProvider" --tag=config
```

#### Publish Languages
```bash
php artisan vendor:publish --provider="WebReinvent\VaahCms\VaahCmsServiceProvider" --tag=lang
```

#### Publish Views
```bash
php artisan vendor:publish --provider="WebReinvent\VaahCms\VaahCmsServiceProvider" --tag=views
```

#### Publish Migrations
```bash
php artisan vendor:publish --provider="WebReinvent\VaahCms\VaahCmsServiceProvider" --tag=migrations
```

#### Publish Seeds
```bash
php artisan vendor:publish --provider="WebReinvent\VaahCms\VaahCmsServiceProvider" --tag=seeds
```

#### Publish Assets
```bash
php artisan vendor:publish --provider="WebReinvent\VaahCms\VaahCmsServiceProvider" --tag=assets
```



#### Create migrations
```bash
php artisan make:migration create_vh_users_table --path=/packages/vaahcms/src/Database/Migrations
```

#### Create seeds
```bash
php artisan make:seeder PermissionsTableSeeder --path=/packages/vaahcms/src/Database/Seeders
php artisan make:seeder RolesTableSeeder --path=/packages/vaahcms/src/Database/Seeders
php artisan make:command HealthcheckCommand --path=/packages/vaahcms/src/Database/Seeders
```

## Minify Assets of Admin with Laravel Mix

Install following package
```bash
npm install --save-dev fs
```

Replace the content of `webpack.mix.fs`
```bash
var admin_path = 'resources/assets/vendor/vaahcms/admin/';
var admin_default_theme_path = admin_path+'default/';

mix.setPublicPath(admin_default_theme_path);

var admin_assets_json = JSON.parse(fs.readFileSync(admin_default_theme_path+'assets.json'));

//console.log(admin_assets_json);

var admin_copy_path = './resources/assets/vendor/vaahcms/admin/';
var admin_copy_path_des = './packages/vaahcms/src/Resources/assets/admin/';

fs_extra.removeSync(admin_copy_path_des);

mix.combine(admin_assets_json['css'], admin_default_theme_path+'builds/vaahcms.css')
    .combine(admin_assets_json['js'], admin_default_theme_path+'builds/vaahcms.js')
    .js(admin_default_theme_path+'vue/app-setup.js',  './builds')
    .js(admin_default_theme_path+'vue/app-dashboard.js',  './builds')
    .js(admin_default_theme_path+'vue/app-modules.js',  './builds')
    .copyDirectory(admin_copy_path, admin_copy_path_des, false)
    .version();


//mix.copyDirectory(admin_copy_path, admin_copy_path_des, false);


mix.webpackConfig({
    watchOptions: {
        aggregateTimeout: 2000,
        poll: 20,
        ignored: [
            '/app/',
            '/bootstrap/',
            '/config/',
            '/database/',
            '/packages/',
            '/public/',
            '/routes/',
            '/storage/',
            '/tests/',
            '/vaahcms/',
            '/node_modules/',
            '/vendor/',

        ]
    }
});

```

#### How to Setup Laravel Dusk on Windows
- Step 1) Install `composer require --dev laravel/dusk`

- Step 2) Run `php artisan dusk:install`

- Step 2) To do browser based test, open file `tests/DuskTestCase.php` and comment `static::startChromeDriver();` 

- Step 3) In tests/DuskTestCase.php file comment `'--headless',`:
```php
...
$options = (new ChromeOptions)->addArguments([
            '--disable-gpu',
            //'--headless', <-- comment this line
            '--window-size=1920,1080',
        ]);
...

```

- Step 4) Make sure your `APP_URL` in `.env` is as per the the xampp or actual application url

- Step 5) Run `php artisan dusk`, it may show `Warning: TTY mode is not supported on Windows platform.` error
  you can ignore this error. If it run successfully, it will  will open chrome and run your tests.

If it does works then:
- Download latest chrome driver from https://sites.google.com/a/chromium.org/chromedriver/downloads

- Unzip and run chromedriver.exe

- Then run `php artisan dusk`

#### Create Tests for VaahCms Modules:
- Change path of dusk in `phpunit.dusk.xml` to following:
```xml
...
<directory suffix="Test.php">./VaahCms/Modules/<module_name>/Tests/Browser</directory>
...
```

## Tools

- https://www.mkdocs.org - for docs 
- https://saojs.org - For creating standalone installer

## Tutorials
### Update Packagist with GitHub Webhooks

- As the PayLoad URL type in:
Visit: `https://packagist.org/profile/` and get the `API Token` and 
```
https://packagist.org/api/github?username=PACKAGIST_USERNAME
```

- Go to your GitHub repository and select  Settings -> WebHooks -> Add Webhook
- In `Payload URL` enter the packagist url
- In `Content type` choose `application/json`
- In `Secrete` add `API Token`
- Choose `Let me select individual events.` and select `Pushes`
- Click on `Save`

## Support us

[WebReinvent](https://www.webreinvent.com) is a web agency based in Delhi, India. You'll find an overview of all our open source projects [on github](https://github.com/webreinvent).

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.


### Tips

#### New Releases
- composer.json version should match with your release via github then only it will be updated on `packagist` 
