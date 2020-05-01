import GlobalComponents from '../../vaahvue/helpers/GlobalComponents';
import ListLargeView from './partials/ListLargeView';
import ListSmallView from './partials/ListSmallView';

let namespace = 'media';

export default {
    computed:{
        root() {return this.$store.getters['root/state']},
        permissions() {return this.$store.getters['root/state'].permissions},
        page() {return this.$store.getters[namespace+'/state']},
        ajax_url() {return this.$store.getters[namespace+'/state'].ajax_url},
        query_string() {return this.$store.getters[namespace+'/state'].query_string},
    },
    components:{
        ...GlobalComponents,
        ListLargeView,
        ListSmallView,

    },
    data()
    {
        return {
            namespace: namespace,
            is_content_loading: false,
            is_btn_loading: false,
            today: null,
            selected_date: null,
            selected_year: null,
            selected_month: null,
            assets: null,
            range: [-100, 6],
            search_delay: null,
            search_delay_time: 800,
            ids: []
        }
    },
    watch: {
        $route(to, from) {
            this.updateView();
            this.updateQueryString();
            this.updateActiveItem();
        }
    },
    mounted() {
        //----------------------------------------------------
        this.onLoad();
        //----------------------------------------------------
        //----------------------------------------------------
    },
    methods: {
        //---------------------------------------------------------------------
        update: function(name, value)
        {
            let update = {
                state_name: name,
                state_value: value,
                namespace: this.namespace,
            };
            this.$vaah.updateState(update);
        },
        //---------------------------------------------------------------------
        updateView: function()
        {
            this.$store.dispatch(this.namespace+'/updateView', this.$route);
        },
        //---------------------------------------------------------------------
        onLoad: function()
        {
            this.updateView();
            this.updateQueryString();
            this.getAssets();
            this.setDateFilter();
        },
        //---------------------------------------------------------------------
        updateQueryString: function()
        {
            let query = this.$vaah.removeEmpty(this.$route.query);
            if(Object.keys(query).length)
            {
                for(let key in query)
                {
                    this.query_string[key] = query[key];
                }
            }
            this.update('query_string', this.query_string);
            this.$vaah.updateCurrentURL(this.query_string, this.$router);
        },
        //---------------------------------------------------------------------
        async getAssets() {
            await this.$store.dispatch(namespace+'/getAssets');
            this.getList();
        },
        //---------------------------------------------------------------------
        toggleFilters: function()
        {
            if(this.page.show_filters == false)
            {
                this.page.show_filters = true;
            } else
            {
                this.page.show_filters = false;
            }

            this.update('show_filters', this.page.show_filters);

        },
        //---------------------------------------------------------------------
        clearSearch: function () {
            this.query_string.q = null;
            this.update('query_string', this.query_string);
            this.getList();
        },
        //---------------------------------------------------------------------
        resetPage: function()
        {

            //reset query strings
            this.resetQueryString();

            //reset bulk actions
            this.resetBulkAction();

            this.resetSelectedDate();

            this.resetSelectedMonthYear();

            //reload page list
            this.getList();

        },
        //---------------------------------------------------------------------
        resetSelectedDate: function()
        {
            this.selected_date = null;
        },
        //---------------------------------------------------------------------
        resetSelectedMonthYear: function()
        {
            this.selected_month = null;
            this.selected_year = null;
        },
        //---------------------------------------------------------------------
        resetQueryString: function()
        {
            for(let key in this.query_string)
            {
                if(key == 'page')
                {
                    this.query_string[key] = 1;
                } else
                {
                    this.query_string[key] = null;
                }
            }

            this.update('query_string', this.query_string);
        },
        //---------------------------------------------------------------------
        resetBulkAction: function()
        {
            this.page.bulk_action = {
                selected_items: [],
                data: {},
                action: null,
            };
            this.update('bulk_action', this.page.bulk_action);
        },
        //---------------------------------------------------------------------
        paginate: function(page=1)
        {
            this.query_string.page = page;
            this.update('query_string', this.query_string);
            this.getList();
        },
        //---------------------------------------------------------------------
        delayedSearch: function()
        {
            let self = this;
            clearTimeout(this.search_delay);
            this.search_delay = setTimeout(function() {
                self.getList();
            }, this.search_delay_time);

            this.query_string.page = 1;
            this.update('query_string', this.query_string);

        },
        //---------------------------------------------------------------------
        reload: function()
        {
            this.is_btn_loading = true;
            this.getList();
        },
        //---------------------------------------------------------------------
        getList: function () {
            this.$vaah.updateCurrentURL(this.query_string, this.$router);
            let url = this.ajax_url+'/list';
            this.$vaah.ajax(url, this.query_string, this.getListAfter);
        },
        //---------------------------------------------------------------------
        getListAfter: function (data, res) {

            this.update('is_list_loading', false);
            this.update('list', data.list);

            if(data.list.total === 0)
            {
                this.update('list_is_empty', true);
            }else{
                this.update('list_is_empty', false);
            }

            this.is_btn_loading = false;
            this.$Progress.finish();

        },
        //---------------------------------------------------------------------
        actions: function () {

            if(!this.page.bulk_action.action)
            {
                this.$vaah.toastErrors(['Select an action']);
                return false;
            }

            if(this.page.bulk_action.action == 'bulk-change-status'){
                if(!this.page.bulk_action.data.status){
                    this.$vaah.toastErrors(['Select a status']);
                    return false;
                }
            }

            if(this.page.bulk_action.selected_items.length < 1)
            {
                this.$vaah.toastErrors(['Select a record']);
                return false;
            }

            this.$Progress.start();
            this.update('bulk_action', this.page.bulk_action);
            let ids = this.$vaah.pluckFromObject(this.page.bulk_action.selected_items, 'id');

            let params = {
                inputs: ids,
                data: this.page.bulk_action.data
            };

            console.log('--->params', params);

            let url = this.ajax_url+'/actions/'+this.page.bulk_action.action;
            this.$vaah.ajax(url, params, this.actionsAfter);
        },
        //---------------------------------------------------------------------
        actionsAfter: function (data, res) {
            let action = this.page.bulk_action.action;
            if(data)
            {
                this.$root.$emit('eReloadItem');
                this.resetBulkAction();
                this.getList();
            } else
            {
                this.$Progress.finish();
            }
        },
        //---------------------------------------------------------------------
        updateActiveItem: function () {

            if(this.$route.fullPath.includes('media?')){
                this.update('active_item', null);
            }
        },
        //---------------------------------------------------------------------
        hasPermission: function(slug)
        {
            return this.$vaah.hasPermission(this.permissions, slug);
        },
        //---------------------------------------------------------------------
        setDateFilter: function()
        {
            if(this.query_string.month){
                this.selected_month = this.query_string.month;
            }

            if(this.query_string.year){
                let year = parseInt(this.query_string.year);
                this.selected_year = year;
            }

            if(this.query_string.from){
                let from = new Date(this.query_string.from);

                this.selected_date=[
                    from
                ];
            }

            if(this.query_string.to){
                let to = new Date(this.query_string.to);

                this.selected_date[1] = to;
            }
        },
        //---------------------------------------------------------------------
        setQueryString: function()
        {
          this.query_string.month = this.selected_month;
          this.query_string.year = this.selected_year;

          this.getList();
        },
        //---------------------------------------------------------------------
        setDateRange: function()
        {

            if(this.selected_date.length > 0){
                let current_datetime = new Date(this.selected_date[0]);
                this.query_string.from = current_datetime.getFullYear() + "-" + (current_datetime.getMonth() + 1) + "-" + current_datetime.getDate();

                current_datetime = new Date(this.selected_date[1]);
                this.query_string.to = current_datetime.getFullYear() + "-" + (current_datetime.getMonth() + 1) + "-" + current_datetime.getDate();

                this.getList();
            }

        },
        //---------------------------------------------------------------------
        //---------------------------------------------------------------------
    }
}
