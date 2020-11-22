<template>
  <div>
    <div>
      <v-btn
        flat
        small
        @click="backToList"
      >
        <div class="back-btn-inner">
          <v-icon>fas fa-arrow-left</v-icon>
          <span>Return to User list</span>
        </div>
      </v-btn>
    </div>
    <v-container
      v-if="!isLoading"
      fluid
      fill-height
      justify-space-between
    >
      <v-layout column>
        <h3 class="f-page__title f-page__title--admin">
          {{ titleText }}
        </h3>
        <v-layout
          row
          align-center
          fill-height
          justify-space-between
        >
          <v-flex d-flex>
            <v-card>
              <v-card-text>
                <v-layout wrap>
                  <v-flex md8>
                    <v-container grid-list-xl>
                      <v-form
                        ref="form"
                        v-model="isValid"
                        lazy-validation
                      >
                        <v-layout
                          row
                          wrap
                        >
                          <v-flex
                            md6
                            sm12
                          >
                            <v-select
                              v-model="status"
                              v-validate="'required'"
                              data-vv-name="status"
                              :error-messages="errors.collect('status')"
                              :items="userStatuses"
                              item-text="name"
                              item-value="id"
                              label="Status"
                              :disabled="isFieldReadOnly('status')"
                            >
                              <template
                                slot="selection"
                                slot-scope="data"
                              >
                                {{ data.item.name }}
                              </template>
                              <template
                                slot="item"
                                slot-scope="data"
                              >
                                <v-list-tile-content v-text="data.item.name" />
                              </template>
                            </v-select>
                          </v-flex>
                          <v-flex
                            md6
                            hidden-sm-and-down
                          >
                            <v-spacer />
                          </v-flex>
                          <v-flex
                            md6
                            sm12
                          >
                            <v-text-field
                              v-model="first_name"
                              v-validate="'required'"
                              data-vv-name="first_name"
                              :error-messages="errors.collect('first_name')"
                              label="First Name"
                              :disabled="isFieldReadOnly('first_name')"
                            />
                          </v-flex>
                          <v-flex
                            md6
                            sm12
                          >
                            <v-text-field
                              v-model="last_name"
                              v-validate="'required'"
                              data-vv-name="last_name"
                              :error-messages="errors.collect('last_name')"
                              label="Last Name"
                              :disabled="isFieldReadOnly('last_name')"
                            />
                          </v-flex>
                          <v-flex
                            v-if="isFieldEnabled('company')"
                            md6
                            sm12
                          >
                            <f-search-simple
                              ref="company"
                              :url="autocompleteCompanyUrl"
                              term-param="filter[name]"
                              :placeholder="companyLabel"
                              persistent-hint
                              results-id-key="id"
                              :value="company_id"
                              :disabled="isFieldReadOnly('company')"
                              @input="selectCompany"
                            />
                          </v-flex>
                          <v-flex
                            v-if="isFieldEnabled('title')"
                            md6
                            sm12
                          >
                            <v-text-field
                              v-model="title"
                              label="Title"
                              :disabled="isFieldReadOnly('title')"
                            />
                          </v-flex>
                          <v-flex
                            v-if="isPlatformAdmin && isFieldEnabled('industry_roles')"
                            md6
                            sm12
                          >
                            <v-select
                              v-model="industry_roles"
                              :items="industryRoles"
                              placeholder="Search roles"
                              chips
                              color="blue-grey lighten-2"
                              label="Industry Roles"
                              item-text="name"
                              multiple
                              clearable
                              deletable-chips
                              :disabled="isFieldReadOnly('industry_roles')"
                            />
                          </v-flex>
                          <v-flex
                            v-if="$vuetify.breakpoint.mdAndUp && isPlatformAdmin && isFieldEnabled('industry_roles')"
                            md6
                          />
                          <v-flex
                            md6
                            sm12
                          >
                            <v-text-field
                              v-model="email"
                              v-validate="'required|email'"
                              data-vv-name="email"
                              :error-messages="errors.collect('email')"
                              label="Email"
                              required
                              :disabled="isFieldReadOnly('email')"
                            />
                          </v-flex>
                          <v-flex
                            v-if="$vuetify.breakpoint.mdAndUp && isPlatformAdmin"
                            md6
                          />
                          <v-flex
                            v-if="isFieldEnabled('level')"
                            md6
                            sm12
                          >
                            <v-select
                              v-model="level"
                              v-validate="'required|min_value:'+currentUser.level"
                              data-vv-name="level"
                              :error-messages="errors.collect('level')"
                              :items="userLevels"
                              item-text="name"
                              item-value="id"
                              label="BUS Role"
                              :disabled="isFieldReadOnly('level')"
                            >
                              <template
                                slot="selection"
                                slot-scope="data"
                              >
                                {{ data.item.name }}
                              </template>
                            </v-select>
                          </v-flex>
                          <v-flex
                            v-if="isFieldEnabled('type')"
                            md6
                            sm12
                          >
                            <v-select
                              v-model="type"
                              v-validate="'required'"
                              data-vv-name="type"
                              :error-messages="errors.collect('type')"
                              :items="userTypes"
                              item-text="name"
                              item-value="id"
                              label="User Type"
                              :disabled="isFieldReadOnly('type')"
                            >
                              <template
                                slot="selection"
                                slot-scope="data"
                              >
                                {{ data.item.name }}
                              </template>
                              <template
                                slot="item"
                                slot-scope="data"
                              >
                                <v-list-tile-content v-text="data.item.name" />
                              </template>
                            </v-select>
                          </v-flex>
                          <v-flex
                            v-if="isFieldEnabled('office_phone')"
                            md6
                            sm12
                          >
                            <v-text-field
                              v-model="office_phone"
                              v-validate="'phoneNumber'"
                              data-vv-name="Office Phone"
                              :error-messages="errors.collect('Office Phone')"
                              label="Office Phone"
                              placeholder="(XXX) XXX-XXXX"
                              :disabled="isFieldReadOnly('office_phone')"
                            />
                          </v-flex>
                          <v-flex
                            v-if="isFieldEnabled('mobile_phone')"
                            md6
                            sm12
                          >
                            <v-text-field
                              v-model="mobile_phone"
                              v-validate="'phoneNumber'"
                              data-vv-name="Mobile Phone"
                              :error-messages="errors.collect('Mobile Phone')"
                              label="Mobile Phone"
                              placeholder="(XXX) XXX-XXXX"
                              :disabled="isFieldReadOnly('mobile_phone')"
                            />
                          </v-flex>
                          <v-flex
                            v-if="isFieldEnabled('street')"
                            xs12
                          >
                            <v-text-field
                              v-model="address.street"
                              data-vv-name="street_1"
                              :error-messages="errors.collect('street_1')"
                              label="Street address"
                              :disabled="isFieldReadOnly('street')"
                            />
                          </v-flex>
                          <v-flex
                            v-if="isFieldEnabled('street')"
                            xs12
                          >
                            <v-text-field
                              v-model="address.street_2"
                              data-vv-name="street_2"
                              :error-messages="errors.collect('street_2')"
                              label="Street address 2"
                              :disabled="isFieldReadOnly('street')"
                            />
                          </v-flex>
                          <v-flex
                            v-if="isFieldEnabled('city')"
                            md6
                            sm12
                          >
                            <v-text-field
                              v-model="address.city"
                              data-vv-name="city"
                              :error-messages="errors.collect('city')"
                              label="City"
                              :disabled="isFieldReadOnly('city')"
                            />
                          </v-flex>
                          <v-flex
                            v-if="isFieldEnabled('country_id')"
                            md6
                            sm12
                          >
                            <v-select
                              v-model="address.country_id"
                              data-vv-name="country"
                              :error-messages="errors.collect('country')"
                              :items="countries"
                              item-text="name"
                              item-value="id"
                              label="Country"
                              :disabled="isFieldReadOnly('country_id')"
                            >
                              <template
                                slot="selection"
                                slot-scope="data"
                              >
                                {{ data.item.name }}
                              </template>
                              <template
                                slot="item"
                                slot-scope="data"
                              >
                                <v-list-tile-content v-text="data.item.name" />
                              </template>
                            </v-select>
                          </v-flex>
                          <v-flex
                            v-if="isFieldEnabled('post_code')"
                            md6
                            sm12
                          >
                            <v-text-field
                              v-model="address.post_code"
                              data-vv-name="post_code"
                              :error-messages="errors.collect('post_code')"
                              label="Post Code"
                              :disabled="isFieldReadOnly('post_code')"
                            />
                          </v-flex>
                          <v-flex
                            v-if="isFieldEnabled('notes')"
                            md12
                            sm12
                            xs12
                          >
                            <v-textarea
                              v-model="notes"
                              label="Notes"
                              :disabled="isFieldReadOnly('notes')"
                            />
                          </v-flex>
                        </v-layout>
                      </v-form>
                    </v-container>
                  </v-flex>
                  <v-flex
                    lg4
                    xl4
                    md4
                    class="fresh-createUpdate__aside"
                  >
                    <v-layout
                      align-center
                      justify-center
                    >
                      <image-uploader
                        :src="avatar"
                        @change="avatarChanged"
                        @remove="avatarRemoved"
                      >
                        <template v-slot="slotProps">
                          <v-avatar size="128">
                            <img :src="slotProps.src">
                          </v-avatar>
                        </template>
                      </image-uploader>
                    </v-layout>

                    <slot
                      name="extraInfo"
                      :user="user"
                    />
                  </v-flex>
                </v-layout>
              </v-card-text>
              <v-divider class="mb-2" />
              <v-card-actions class="mb-2">
                <v-spacer />
                <v-btn
                  v-if="!isNew"
                  flat
                  small
                  color="error"
                  @click="dialog = true"
                >
                  Delete
                </v-btn>
                <v-btn
                  :disabled="!isValid"
                  color="primary"
                  @click="onSaveClick"
                >
                  Save
                </v-btn>
              </v-card-actions>
            </v-card>
          </v-flex>
        </v-layout>
        <v-dialog
          v-model="dialog"
          max-width="300"
        >
          <v-card>
            <v-card-title class="headline">
              Are you sure you want to delete this user?
            </v-card-title>
            <v-card-text>
              User: {{ user && user.name }}
            </v-card-text>
            <v-card-actions>
              <v-spacer />
              <v-btn
                color="success"
                flat="flat"
                @click="dialog = false"
              >
                No
              </v-btn>
              <v-btn
                color="error"
                flat="flat"
                @click="deleteUser"
              >
                DELETE
              </v-btn>
            </v-card-actions>
          </v-card>
        </v-dialog>
      </v-layout>
    </v-container>
  </div>
</template>

<script>
import CreateUpdate from 'fresh-bus/components/pages/admin/users/CreateUpdate.vue'

export default {
  extends: CreateUpdate,
  layout: CreateUpdate.layout,
  beforeRouteEnterOrUpdate: CreateUpdate.beforeRouteEnterOrUpdate,
  data () {
    return {
      isNew: true,
      enabledFields: [
        'company',
        'title',
        'mobile_phone',
        'office_phone',
        'notes',
        'level',
        'type',
        'street',
        'street_2',
        'city',
        'country_id',
        'post_code'
      ]
    }
  },
  methods: {
    backToList () {
      this.$router.push({ path: '/admin/users' })
    }
  }
}
</script>

<style scoped>
  .back-btn-inner{
    color: #fff;
    display: flex;
    align-items: center;
    font-size: 13px;
  }
  .back-btn-inner span{
    margin-left: 10px;
    font-weight: bold;
    text-transform: initial;
  }
  .back-btn-inner .v-icon{
    font-size: 16px;
  }
</style>
