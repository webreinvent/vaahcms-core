<script src="./ListJs.js"></script>
<template>
    <div class="form-page-v1-layout">

        <div class="columns">

            <!--left-->
            <div class="column" :class="{'is-6': !page.list_view}">

                <div class="block" v-if="is_content_loading">
                    <Loader/>
                </div>

                <!--card-->
                <div class="card" v-else-if="page.assets">

                    <!--header-->
                    <header class="card-header">

                        <div class="card-header-title">
                            Failed Jobs

                            <span v-if="page.list">
                                 &nbsp; ({{page.list.total}})
                            </span>

                        </div>

                        <b-tooltip label="Reload" type="is-dark">
                            <b-button type="is-text"
                                      dusk="link-reload"
                                      class="card-header-icon has-margin-top-5 has-margin-right-5"
                                      icon-left="redo-alt" @click="getList"></b-button>
                        </b-tooltip>

                        <b-dropdown position="is-bottom-left">
                            <template #trigger="{ active }">
                                <b-button dusk="action-header_dropdown" class="card-header-icon has-margin-top-5  has-margin-right-5"
                                          type="is-text" icon-right="ellipsis-v" >
                                </b-button>
                            </template>

                            <b-dropdown-item dusk="action-delete_all" @click="deleteAllItem">
                                <span>Delete All</span>
                            </b-dropdown-item>

                        </b-dropdown>

                    </header>
                    <!--/header-->

                    <!--content-->
                    <div class="card-content">



                        <div class="block" v-if="page.list">


                            <!--actions-->
                            <div class="level">

                                <!--left-->
                                <div class="level-left" >
                                    <div  class="level-item">
                                        <b-field >

                                            <b-select dusk="input-bulk_action" placeholder="- Bulk Actions -"
                                                      v-model="page.bulk_action.action">
                                                <option value="">
                                                    - Bulk Actions -
                                                </option>
                                                <option value="bulk-delete">
                                                    Delete
                                                </option>
                                            </b-select>

                                            <p class="control">
                                                <button dusk="action-bulk_action" class="button is-primary"
                                                        @click="actions">
                                                    Apply
                                                </button>
                                            </p>

                                        </b-field>
                                    </div>
                                </div>
                                <!--/left-->


                                <!--right-->
                                <div class="level-right">

                                    <div class="level-item">

                                        <b-field>

                                            <b-input dusk="input-search" placeholder="Search"
                                                     type="text"
                                                     icon="search"
                                                     @input="delayedSearch"
                                                     @keyup.enter.prevent="delayedSearch"
                                                     v-model="query_string.q">
                                            </b-input>

                                            <p class="control">
                                                <button dusk="action-filter" class="button is-primary"
                                                        @click="getList">
                                                    Filter
                                                </button>
                                            </p>
                                            <p class="control">
                                                <button dusk="action-reset" class="button is-primary"
                                                        @click="resetPage">
                                                    Reset
                                                </button>
                                            </p>
                                            <p class="control">
                                                <button dusk="action-toggle_filter" class="button is-primary"
                                                        @click="toggleFilters()"
                                                        slot="trigger">
                                                    <b-icon icon="ellipsis-v"></b-icon>
                                                </button>
                                            </p>
                                        </b-field>

                                    </div>

                                </div>
                                <!--/right-->

                            </div>
                            <!--/actions-->

                            <!--filters-->
                            <div class="level" v-if="page.show_filters">

                                <div class="level-left">

                                </div>


                                <div class="level-right">

                                    <div class="level-item">

                                        <b-field>
                                            <b-datepicker dusk="input-date_range"
                                                    position="is-bottom-left"
                                                    placeholder="- Select a dates -"
                                                    v-model="selected_date"
                                                    @input="setDateRange"
                                                    range>
                                            </b-datepicker>
                                        </b-field>

                                    </div>
                                </div>


                            </div>
                            <!--/filters-->


                            <!--list-->
                            <div class="block ">

                                <div class="block" style="margin-bottom: 0px;" >

                                    <div v-if="page.list_view">
                                        <ListLargeView @eReloadList="getList" />
                                    </div>

                                </div>

                                <hr style="margin-top: 0;"/>

                                <div class="block" v-if="page.list">
                                    <b-pagination  :total="page.list.total"
                                                   dusk="input-paginate"
                                                   :current.sync="page.list.current_page"
                                                   :per-page="page.list.per_page"
                                                   range-before=3
                                                   range-after=3
                                                   @change="paginate">
                                    </b-pagination>
                                </div>

                            </div>
                            <!--/list-->


                        </div>
                    </div>
                    <!--/content-->

                </div>
                <!--/card-->


            </div>
            <!--/left-->

            <router-view @eReloadList="getList"></router-view>

        </div>

    </div>
</template>


