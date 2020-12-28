<template>
  <v-container
    :class="`fresh-company__edit fresh-company__edit--${company ? company.status : ''}`"
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
                              <v-list-tile-content v-text="data.item.name"/>
                            </template>
                          </v-select>
                        </v-flex>
                        <v-flex
                          md6
                          hidden-sm-and-down
                        >
                          <v-spacer/>
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
                          <v-select
                            v-model="company_types"
                            :items="types"
                            attach
                            chips
                            item-text="name"
                            item-value="id"
                            label="Types"
                            multiple
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
            <v-divider class="mb-2"/>
            <v-card-actions class="mb-2">
              <v-spacer/>
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
        v-if="company"
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
            <v-spacer/>
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
    <v-layout>

      <v-card class="mb-5"
              v-if="companyId && company">
        <v-card-title>
          <h3>Company Members</h3>
        </v-card-title>

        <v-divider/>

        <v-alert
          :value="true"
          color="warning"
          icon="warning"
        >
          Coming soon
        </v-alert>
        <!-- Commented out for now
        <user-filter
          v-if="!isLoading"
          :sortables="sortables"
          @runFilter="filterUsers"
        />
        <user-list
          v-if="!isLoading"
          :users="users"
          :levels="userlevels"
          :statuses="userstatuses"
          :is-loading="isLoading || isLoadingList"
          :rows-per-page="pagination.rowsPerPage"
          :page="pagination.page"
          :total-items="pagination.totalItems"
          :sort-by="sorting.sortBy"
          :descending="sorting.descending"
          :headers="headers"
          :item-actions="itemActions"
          class="users-list"
          must-sort
          @paginate="onUsersPaginate"
          @change-status="changeStatus"
          @change-level="changeLevel"
          @manage-teams="showUserTeamsAssigner"
          @manage-view="userView"
          @manage-edit="userEdit"
          @manage-delete="deleteUser"
          @manage-multiple-delete="deleteMultiple"
        />
        -->
      </v-card>

      <v-card
        v-if="companyId && company && isCustomer"
        class="mb-5"
      >
        <v-card-title>
          <h3>Company Venues</h3>
        </v-card-title>

        <v-divider/>

        <v-alert
          :value="true"
          color="warning"
          icon="warning"
        >
          Coming soon
        </v-alert>
      </v-card>

      <v-card
        v-if="companyId && company && isSupplier"
        class="mb-5"
      >
        <v-card-title>
          <h3>Company Fleet</h3>
        </v-card-title>

        <v-divider/>

        <v-alert
          :value="true"
          color="warning"
          icon="warning"
        >
          Coming soon
        </v-alert>
      </v-card>

      <v-card v-if="companyId && company">
        <v-card-title>
          <h3>Company Events</h3>
        </v-card-title>

        <v-divider/>

        <v-alert
          :value="true"
          color="warning"
          icon="warning"
        >
          Coming soon
        </v-alert>
      </v-card>
    </v-layout>
  </v-container>
</template>
<script>
  import CreateUpdate from 'fresh-bus/components/pages/admin/companies/CreateUpdate.vue'
  import BusCreateUpdate from 'fresh-bus/components/pages/admin/companies/CreateUpdate.vue'
  import UsersPage from 'fresh-bus/pages/admin/users/index.vue'
  import { mapActions, mapGetters } from 'vuex'

  export default {
    extends: CreateUpdate,
    mixins: [UsersPage],
    data () {
      return {
        isNew: true,
        companyId: null,
        disableStatus: !this.isAdmin
      }
    },
    computed: {
      ...mapGetters('currentUser', {
        isCurrentUserAdmin: 'isAdmin'
      }),
      ...mapGetters('companies', {
        company: 'item'
      }),
      ...mapGetters('companyDetails/users', {
        users: 'items',
        pagination: 'pagination',
        sorting: 'sorting',
        sortBy: 'sortBy'
      }),
      disableStatus () {
        return !this.isAdmin
      },
      // TODO: replace with backend implementation
      isSupplier () {
        return this.company.company_types.reduce((result, type) => {
          return result || (type.key_id === 'supplier')
        }, false)
      },
      isCustomer () {
        return this.company.company_types.reduce((result, type) => {
          return result || (type.key_id === 'host')
        }, false)
      }
    },

    methods: {
      ...mapActions('page', {
        setPageLoading: 'setLoading'
      }),
      onUsersPaginate (value) {
        this.$store.dispatch('companyDetails/users/setPagination', value)
        this.$store.dispatch('companyDetails/users/getItems', { params: { companyId: this.companyId } })
      },
      filterUsers (params) {
        this.lastFilterParams = params
        this.$store.dispatch('companyDetails/users/setSort', params.sort)
        this.$store.dispatch('companyDetails/users/setFilters', {
          ...this.$route.query,
          ...this.lastFilterParams
        })
        this.$store.dispatch('companyDetails/users/getItems', { params: { companyId: this.companyId } })
      }
    },

    beforeRouteEnterOrUpdate (vm, to, from, next) {
      vm.companyId = to.params.id
      BusCreateUpdate.beforeRouteEnterOrUpdate(vm, to, from, () => {
        if (!vm.companyId) {
          return next && next()
        }
        Promise.all([
          vm.$store.dispatch('companyDetails/users/getItems', {
            params: {
              companyId: vm.companyId
            }
          }),
          vm.$store.dispatch('userLevels/getItems'),
          vm.$store.dispatch('userStatuses/getItems')
        ])
          .then()
          .catch(console.error)
          .then(() => {
            vm.$store.dispatch('page/setLoading', false)
            next && next()
          })
      })
    }
  }
</script>


<style lang="scss">
  .fresh-company__edit--1 .v-select__selections {
    color: #71b179 !important;
  }

  .fresh-company__edit--4 .v-select__selections {
    color: #f9ad36 !important;
  }

  .fresh-company__edit--3 .v-select__selections {
    color: #888888 !important;
  }
</style>
