import pagination from 'laravel-vue-pagination';
import {isObject} from "vue-resource/src/util";

    export default {

        props: ['urls', 'id'],
        components:{
            'pagination': pagination,
        },
        data()
        {
            let obj = {
                list: null,
                page: 1,
                filters: {
                    q: null,
                },
                permission: null,
            };
            return obj;
        },
        watch: {

            id: function (newVal, oldVal) {
                this.getList();
            }

        },
        mounted() {

            //---------------------------------------------------------------------
            this.getList();
            //---------------------------------------------------------------------
            //---------------------------------------------------------------------
            //---------------------------------------------------------------------
            //---------------------------------------------------------------------

        },
        methods: {
            //---------------------------------------------------------------------
            getList: function (page) {
                var url = this.urls.current+"/roles/"+this.id;

                if(!page || isObject(page))
                {
                    page = this.page;
                }

                this.$helpers.console(page, 'page');

                url = url+"?page="+page;

                if(this.filters.q)
                {
                    url = url+"&q="+this.filters.q;
                }

                var params = {};
                this.$helpers.ajax(url, params, this.getListAfter);
            },
            //---------------------------------------------------------------------
            getListAfter: function (data) {

                this.$helpers.console(data);

                this.list = data.list;
                this.permission = data.permission;

                this.$helpers.stopNprogress();
            },

            //---------------------------------------------------------------------
            //---------------------------------------------------------------------
            //---------------------------------------------------------------------
        }
    }