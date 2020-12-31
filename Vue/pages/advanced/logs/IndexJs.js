import GlobalComponents from '../../../vaahvue/helpers/GlobalComponents';

let namespace = 'logs';

export default {

    props: [],
    computed:{
        root() {return this.$store.getters['root/state']},
        assets() {return this.$store.getters['root/state'].assets},
        permissions() {return this.$store.getters['root/state'].permissions},
        page() {return this.$store.getters[namespace+'/state']},
        ajax_url() {return this.$store.getters[namespace+'/state'].ajax_url},
    },
    components:{
        ...GlobalComponents,

    },
    data()
    {
        let obj = {
            namespace: namespace,
            is_list_fetched: null,
            labelPosition: 'on-border',
        };
        return obj;
    },
    watch: {
    },
    mounted() {

        //---------------------------------------------------------------------
        document.title = "Logs";
        //---------------------------------------------------------------------
        this.onLoad();
        //---------------------------------------------------------------------
        //---------------------------------------------------------------------
    },
    methods: {
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
        onLoad: function()
        {
            this.getList();
        },
        //---------------------------------------------------------------------
        getList: function () {
            this.$Progress.start();
            let params = {};
            let url = this.ajax_url+'/list';
            this.$vaah.ajax(url, params, this.getListAfter);
        },
        //---------------------------------------------------------------------
        getListAfter: function (data, res) {
            this.$Progress.finish();
            if(data){
                this.is_list_fetched = true;
                if(data.list && data.list.length > 0)
                {
                    this.update('list_is_empty', false);
                } else
                {
                    this.update('list_is_empty', true);
                }

                this.update('list', data.list);
            }

        },
        //---------------------------------------------------------------------
        setRowClass: function(row, index)
        {

            if(this.page.active_item && row.id == this.page.active_item.id)
            {
                return 'is-selected';
            }

            if(row.deleted_at != null)
            {
                return 'is-danger';
            }

        },
        //---------------------------------------------------------------------
        setActiveItem: function (item) {
            this.update('active_item', item);
            this.$router.push({name: 'logs.details', params:{name:item.name}})
        },
        //---------------------------------------------------------------------
        changeStatus: function (id) {
            this.$Progress.start();
            let url = this.ajax_url+'/actions/bulk-change-status';
            let params = {
                inputs: [id],
                data: null
            };
            this.$vaah.ajax(url, params, this.changeStatusAfter);
        },
        //---------------------------------------------------------------------
        changeStatusAfter: function (data,res) {
            this.$emit('eReloadList');
            this.update('is_list_loading', false);

        },

        //---------------------------------------------------------------------
        copiedData: function (data) {

            this.$vaah.toastSuccess(['copied']);

            // alertify.success('copied');

            this.$vaah.console(data, 'copied data');

        },
        //---------------------------------------------------------------------
        hasPermission: function(slug)
        {
            return this.$vaah.hasPermission(this.permissions, slug);
        },
        //---------------------------------------------------------------------
        deleteItem: function () {
            this.$Progress.start();
            let params = {};
            let url = this.ajax_url+'/list';
            this.$vaah.ajax(url, params, this.deleteItemAfter);
        },
        //---------------------------------------------------------------------
        deleteItemAfter: function (data, res) {
            this.$Progress.finish();
            if(data){
                if(data.list && data.list.length > 0)
                {
                    this.update('list_is_empty', false);
                } else
                {
                    this.update('list_is_empty', true);
                }

                this.update('list', data.list);
            }

        },

        //---------------------------------------------------------------------
    }
}