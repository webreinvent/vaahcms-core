<script src="./EditJs.js"></script>
<template>
    <div class="column" v-if="page.assets && item">

        <div class="card">

            <!--header-->
            <header class="card-header">

                <div class="card-header-title">
                    <span>{{$vaah.limitString(item.name, 25)}}</span>
                </div>


                <div class="card-header-buttons">

                    <div class="field has-addons is-pulled-right">
                        <p class="control">
                            <b-button @click="$vaah.copy(item.id)"  type="is-light">
                                <small><b>#{{item.id}}</b></small>
                            </b-button>
                        </p>

                        <p class="control">
                            <b-button icon-left="save"
                                      type="is-light"
                                      :loading="is_btn_loading"
                                      @click="store()">
                                Save
                            </b-button>
                        </p>

                        <p class="control">


                            <b-dropdown aria-role="list" position="is-bottom-left">
                                <button class="button is-light"
                                        slot="trigger">
                                    <b-icon icon="caret-down"></b-icon>
                                </button>

                                <b-dropdown-item aria-role="listitem"
                                                 @click="setLocalAction('save-and-close')">
                                    <b-icon icon="check"></b-icon>
                                    Save & Close
                                </b-dropdown-item>

                                <b-dropdown-item aria-role="listitem"
                                                 @click="setLocalAction('save-and-new')">
                                    <b-icon icon="plus"></b-icon>
                                    Save & New
                                </b-dropdown-item>

                                <b-dropdown-item aria-role="listitem"
                                                 @click="setLocalAction('save-and-clone')">
                                    <b-icon icon="copy"></b-icon>
                                    Save & Clone
                                </b-dropdown-item>

                            </b-dropdown>


                        </p>

                        <p class="control">
                            <b-button tag="router-link"
                                      type="is-light"
                                      :to="{name: 'user.view', params:{id:item.id}}"
                                      icon-left="times">
                            </b-button>
                        </p>



                    </div>


                </div>

            </header>
            <!--/header-->

            <!--content-->
            <div class="card-content">
                <div class="block">

                    <article class="media">
                        <figure class="media-left" v-if="item.avatar">
                            <p class="image is-64x64">
                                <img class="is-rounded"
                                     :src="item.avatar">
                            </p>
                        </figure>
                        <div class="media-content" >

                            <AvatarUploader
                                max_size="200KB"
                                label="Upload user avatar"
                                aspect_ratio="1:1"
                                :upload_url="root.assets.urls.upload"
                                @afterUpload="storeAvatar"/>

                            <br/>
                            <b-button type="is-primary"
                                      v-if="item.avatar_url"
                                      @click="removeAvatar()">
                                Remove Avatar
                            </b-button>

                        </div>
                    </article>


                    <hr/>
                    <br/>

                    <b-field label="Email" :label-position="labelPosition">
                        <b-input type="email"  name="user-email" dusk="user-email"
                                 v-model="item.email"></b-input>
                    </b-field>


                    <b-field label="Username" :label-position="labelPosition">
                        <b-input v-model="item.username"  name="user-username"
                                 dusk="user-username" ></b-input>
                    </b-field>

                    <b-field label="New Password" :label-position="labelPosition">
                        <b-input type="password" v-model="item.password"
                                 name="user-password" dusk="user-password" ></b-input>
                    </b-field>

                    <b-field label="Display Name" :label-position="labelPosition">
                        <b-input v-model="item.display_name"
                                 name="user-display_name" dusk="user-display_name" >
                        </b-input>
                    </b-field>

                    <b-field label="Title" :label-position="labelPosition">
                        <b-select placeholder="Select a title"
                                  name="user-title" dusk="user-title"
                                  v-model="item.title">
                            <option v-for="title in page.assets.name_titles"
                                    :value="title.slug"
                            >{{title.name}}</option>
                        </b-select>
                    </b-field>



                    <b-field label="Designation" :label-position="labelPosition">
                        <b-input v-model="item.designation"
                                 name="user-designation" dusk="user-designation"
                        ></b-input>
                    </b-field>



                    <b-field label="First Name" :label-position="labelPosition">
                        <b-input v-model="item.first_name"
                                 name="user-first_name" dusk="user-first_name"
                        ></b-input>
                    </b-field>

                    <b-field label="Middle Name" :label-position="labelPosition">
                        <b-input v-model="item.middle_name"
                                 name="user-middle_name" dusk="user-middle_name"
                        ></b-input>
                    </b-field>

                    <b-field label="Last Name" :label-position="labelPosition">
                        <b-input v-model="item.last_name"
                                 name="user-last_name" dusk="user-last_name"
                        ></b-input>
                    </b-field>

                    <b-field label="Gender" :label-position="labelPosition">
                        <b-radio-button v-model="item.gender"
                                        name="user-gender" dusk="user-gender"
                                        native-value="m">
                            <b-icon icon="mars"></b-icon>
                            <span>Male</span>
                        </b-radio-button>

                        <b-radio-button v-model="item.gender"
                                        name="user-gender" dusk="user-gender"
                                        native-value="f">
                            <b-icon icon="venus"></b-icon>
                            <span>Female</span>
                        </b-radio-button>

                        <b-radio-button v-model="item.gender"
                                        name="user-gender" dusk="user-gender"
                                        native-value="o">
                            <b-icon icon="transgender-alt"></b-icon>
                            <span>Other</span>
                        </b-radio-button>


                    </b-field>

                    <b-field label="Country Code" :label-position="labelPosition">
                        <b-select placeholder="Select a country code"
                                  name="user-country_code" dusk="user-country_code"
                                  v-model="item.country_calling_code">
                            <option v-for="code in page.assets.country_calling_code"
                                    :value="code.calling_code"
                            >{{code.calling_code}}</option>
                        </b-select>
                    </b-field>

                    <b-field label="Phone" :label-position="labelPosition">
                        <b-input v-model="item.phone"
                                 name="user-phone" dusk="user-phone"
                        ></b-input>
                    </b-field>

                    <b-field label="Bio" :label-position="labelPosition">
                        <b-input maxlength="250"
                                 v-model="item.bio"
                                 name="user-bio" dusk="user-bio"
                                 type="textarea"></b-input>
                    </b-field>

                    <b-field label="Website" :label-position="labelPosition">
                        <b-input v-model="item.website"
                                 name="user-website" dusk="user-website"
                        ></b-input>
                    </b-field>

                    <b-field label="Timezone" :label-position="labelPosition">
                        <AutoCompleteTimeZone
                            :selected_value="item.timezone"
                            :options="page.assets.timezones"
                            :open_on_focus="true"
                            @onSelect="setTimeZone">
                        </AutoCompleteTimeZone>
                    </b-field>

                    <b-field label="Alternate Email" :label-position="labelPosition">
                        <b-input type="email" v-model="item.alternate_email"
                                 name="user-alternate_email" dusk="user-alternate_email"
                        ></b-input>
                    </b-field>

                    <b-field label="Date of Birth" :label-position="labelPosition">
                        <DatePicker
                            :selected_value="item.birth"
                            @onSelect="setBirthDate">
                        </DatePicker>
                    </b-field>

                    <b-field label="Country" :label-position="labelPosition">
                        <AutoCompleteCountry
                            :selected_value="item.country"
                            :options="page.assets.countries"
                            :open_on_focus="true"
                            @onSelect="setCountry">
                        </AutoCompleteCountry>
                    </b-field>

                    <b-field label="Foreign User Id" :label-position="labelPosition">
                        <b-input v-model="item.foreign_user_id" type="number" min="1"
                                 name="user-foreign_user_id" dusk="user-foreign_user_id"
                        ></b-input>
                    </b-field>

                    <b-field label="Status" :label-position="labelPosition">
                        <b-select placeholder="Select a status"
                                  @input="setIsActiveStatus()"
                                  name="user-status" dusk="user-status"
                                  v-model="item.status">
                            <option value="active"
                            >Active</option>
                            <option value="inactive"
                            >Inactive</option>
                            <option value="blocked"
                            >Blocked</option>
                            <option value="banned"
                            >Banned</option>
                        </b-select>
                    </b-field>

                    <b-field label="Is Active" :label-position="labelPosition">
                        <b-radio-button name="user-is_active" @input="setStatus()" dusk="user-is_active"
                                        v-model="item.is_active"
                                        :native-value=1>
                            <span>Yes</span>
                        </b-radio-button>

                        <b-radio-button type="is-danger" @input="setStatus()" name="user-is_active" dusk="user-is_active"
                                        v-model="item.is_active"
                                        :native-value=0>
                            <span>No</span>
                        </b-radio-button>
                    </b-field>


                </div>
            </div>
            <!--/content-->





        </div>




    </div>
</template>


