<template>
  <v-container
    v-if="!isLoading"
    fluid
    fill-height
    justify-space-between
  >
    <v-layout column>
      <h3 class="f-page__title f-page__title--admin">
        <span v-if="isNew">New Company</span>
        <span v-else>Edit Company</span>
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
                  <v-container grid-list-md>
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
                            :items="statuses"
                            item-text="name"
                            item-value="id"
                            label="Status"
                            :disabled="disableStatus"
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
                          md12
                          sm12
                        >
                          <v-text-field
                            v-model="name"
                            label="Name"
                          />
                        </v-flex>
                        <v-flex
                          v-if="!isNew"
                          md6
                          sm12
                        >
                          <div>
                            Member Since: <code>{{ dateFormatted(company.created_at) }}</code>
                          </div>
                        </v-flex>
                        <v-flex
                          v-if="!isNew"
                          md6
                          sm12
                        >
                          <div class="text-lg-right text-md-right">
                            Members: {{ company.members_count }}
                          </div>
                        </v-flex>
                        <v-flex
                          md12
                          sm12
                        >
                         <v-text-field
                            v-model="companyType"
                            label="Type"
                            readonly
                          />
                        </v-flex>
                        <v-flex
                          md12
                          sm12
                        >
                          <v-text-field
                            v-model="address"
                            label="Address Line 1"
                          />
                        </v-flex>
                        <v-flex
                          md12
                          sm12
                        >
                          <v-text-field
                            v-model="address2"
                            label="Address Line 2"
                          />
                        </v-flex>
                        <v-flex
                          md6
                          sm12
                        >
                          <v-text-field
                            v-model="city"
                            label="City"
                          />
                        </v-flex>
                        <v-flex
                          md6
                          sm12
                        >
                          <v-text-field
                            v-model="state"
                            label="State / Province"
                          />
                        </v-flex>
                        <v-flex
                          md6
                          sm12
                        >
                          <v-text-field
                            v-model="zip"
                            label="ZIP Code"
                          />
                        </v-flex>
                        <v-flex
                          md12
                          sm12
                        >
                          <v-text-field
                            v-model="website"
                            label="Website"
                          />
                        </v-flex>
                        <v-flex
                          md12
                          sm12
                        >
                          <v-textarea
                            v-model="notes"
                            label="Notes"
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
                >
                  <v-layout
                    column
                    fluid
                    grid-list-lg
                  >
                    <image-uploader
                      :src="logo"
                      @change="logoChanged"
                      @remove="logoRemoved"
                    >
                      <template v-slot="slotProps">
                        <v-avatar
                          tile
                          size="256"
                        >
                          <img :src="slotProps.src">
                        </v-avatar>
                      </template>
                    </image-uploader>
                    <v-layout
                      v-if="!isNew"
                      pt-4
                      justify-center
                      align-center
                      pl-4
                      column
                    >
                      <div class="text-uppercase caption font-weight-bold align-self-start text-left">
                        Managed by
                      </div>
                      <managed-by
                        :admin="admin"
                        hide-divider
                      />
                      <v-btn
                        color="primary"
                        @click="isAdminSelectDialogOpen = true"
                      >
                        Change
                      </v-btn>
                    </v-layout>
                  </v-layout>
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
        v-model="isAdminSelectDialogOpen"
        max-width="400"
      >
        <v-card>
          <v-card-title class="headline">
            Select new Administrator
          </v-card-title>
          <v-card-text>
            <v-select
              v-model="admin"
              :items="company.members"
              item-text="first_name"
              return-object
              style="width: 100%"
              @change="onAdminChange"
            >
              <template
                slot="item"
                slot-scope="data"
              >
                <v-avatar size="24">
                  <img
                    :src="data.item.avatar"
                    :alt="data.item.name"
                  >
                </v-avatar>
                <div class="pl-1">
                  {{ data.item.name }}
                </div>
              </template>
            </v-select>
          </v-card-text>
        </v-card>
      </v-dialog>
      <v-dialog
        v-model="isDeleteDialogOpen"
        max-width="300"
      >
        <v-card>
          <v-card-title class="headline">
            Are you sure you want to delete this company?
          </v-card-title>
          <v-card-text>
            Company: {{ name }}
          </v-card-text>
          <v-card-actions>
            <v-spacer />
            <v-btn
              color="success"
              flat="flat"
              @click="isDeleteDialogOpen = false"
            >
              No
            </v-btn>
            <v-btn
              color="error"
              flat="flat"
              @click="deleteItem"
            >
              DELETE
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </v-layout>
  </v-container>
</template>


<script>
// import CreateUpdate from 'fresh-bus/components/pages/admin/companies/CreateUpdate.vue'
import { mapActions, mapGetters } from 'vuex'
import { createHelpers } from 'vuex-map-fields'
import moment from 'moment'
import ImageUploader from 'fresh-bus/components/ImageUploader'
import ManagedBy from 'fresh-bus/components/companies/ManagedBy'

const { mapFields } = createHelpers({
  getterType: 'getField',
  mutationType: 'updateField'
})

export default {
  // extends: CreateUpdate,
   layout: 'admin',
  $_veeValidate: {
    validator: 'new'
  },
  beforeRouteEnterOrUpdate (vm, to, from, next) {
    vm.setPageLoading(true)
    const id = to.params.id || 'new'
    Promise.all([
      vm.$store.dispatch('companies/getItem', { params: { id, include: 'admin,users' } })
    ]).then(() => {
      vm.setPageLoading(false)
      if (next) next()
    })
  },
  components: {
    ImageUploader,
    ManagedBy
  },

  props: {
    isAdmin: {
      type: Boolean,
      default: false
    },
    companyType:{
      type:String,
      default:'Supplier'
    }
  },
  
  data () {
    return {
      disableStatus: !this.isAdmin,
       isNew: false,
      isValid: true,
      isDeleteDialogOpen: false,
      isAdminSelectDialogOpen: false,
      // disableStatus: false
    }
  },
  watch: {
    isAdmin () {
      this.disableStatus = !this.isAdmin
    }
  },

  computed: {
    id () {
      return this.$store.getters['companies/item'].id
    },
    ...mapGetters('companies', {
      company: 'item'
    }),
    ...mapGetters('companies', [
      'statuses',
      'types'
    ]),
    ...mapFields('companies', [
      'name',
      'status',
      'company_types',
      'address',
      'address2',
      'city',
      'state',
      'notes',
      'zip',
      'website',
      'logo',
      'admin'
    ]),
    ...mapGetters('page', ['isLoading'])
  },
  methods: {
    ...mapActions('page', {
      setPageLoading: 'setLoading'
    }),
    deleteItem () {
      this.$store.dispatch('companies/deleteItem', { params: { id: this.id } })
        .then(() => {
          this.$router.push('/admin/companies/')
        })
    },
    onAdminChange (value) {
      console.log(value)
      this.isAdminSelectDialogOpen = false
      this.admin = value
    },
    logoChanged (image) {
      this.logo = image
    },
    logoRemoved () {
      this.logo = null
    },
    onSaveClick () {
      let data = {
        ...this.company
      }
      if (this.isNew) {
        this.$store.dispatch('companies/createItem', { data }).then((result) => {
          this.$store.dispatch('generalMessage/setMessage', 'Saved')
          this.$router.push(`/admin/companies/${result.data.data.id}`)
        })
      } else {
        this.$store.dispatch('companies/updateItem', { data, params: { id: data.id } }).then(() => {
          this.$store.dispatch('generalMessage/setMessage', 'Saved')
          this.$store.dispatch('companies/getItem', { params: { id: data.id, include: 'admin,users' } })
        })
      }
    },
    dateFormatted (date) {
      return date && date !== 'Invalid date' ? moment(date).format('YYYY-MM-DD') : null
    }
  }
}
</script>
