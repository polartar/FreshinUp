<template>
  <div>
    <v-form
      v-if="!isLoading"
      ref="form"
      v-model="isValid"
    >
      <v-layout
        row
        align-center
        pt-3
      >
        <v-btn
          flat
          small
          @click="backToList"
        >
          <div class="back-btn-inner">
            <v-icon>fas fa-arrow-left</v-icon>
            <span>Return to Fleet Member list</span>
          </div>
        </v-btn>
      </v-layout>
      <v-flex
        d-flex
        align-center
        justify-space-between
        ma-2
      >
        <v-layout
          row
          align-center
        >
          <h2 class="white--text">
            {{ pageTitle }}
          </h2>
        </v-layout>
        <v-flex
          text-xs-right
          sm2
          xs12
        >
          <v-layout
            align-center
          >
            <status-select
              v-model="status_id"
              :options="storeStatuses"
            />
          </v-layout>
        </v-flex>
      </v-flex>
      <v-divider />
      <br>
      <v-layout
        justify-space-around
        column
        px-2
        py-4
      >
        <v-flex
          xs12
          py-2
        >
          <basic-information
            :loading="loading"
            :types="storeTypes"
            :square-locations="squareLocations"
            :value="store"
            @input="saveOrCreate"
            @connect-square="onConnectSquare"
            @disconnect-square="onDisconnectSquare"
            @delete="deleteMember"
            @cancel="onCancel"
          />
        </v-flex>
        <v-flex
          v-if="!isNew"
          xs12
          py-2
        >
          <areas-of-operation
            :is-loading="areasLoading"
            :items="storeAreas"
            :rows-per-page="storeAreaPagination.rowsPerPage"
            :page="storeAreaPagination.page"
            :total-items="storeAreaPagination.totalItems"
            :sort-by="storeAreaSorting.sortBy"
            :descending="storeAreaSorting.descending"
            @paginate="onAreaPaginate"
            @manage-delete="item => onDeleteArea([item])"
            @manage-multiple-delete="onDeleteArea"
          >
            <template v-slot:head>
              <v-flex shrink>
                <v-dialog
                  v-model="newArea"
                  max-width="600"
                >
                  <template v-slot:activator="{ on }">
                    <v-btn
                      slot="activator"
                      color="primary"
                      text
                      @click="newArea = true"
                    >
                      <v-icon
                        left
                      >
                        add_circle_outline
                      </v-icon>Add New Area
                    </v-btn>
                  </template>
                  <v-card>
                    <div class="d-flex justify-space-between align-center">
                      <v-card-text class="grey--text subheading font-weight-bold">
                        Add new area
                      </v-card-text>
                      <v-btn
                        small
                        round
                        depressed
                        color="grey"
                        class="white--text"
                        @click="newArea = false"
                      >
                        <v-flex>
                          <v-icon
                            small
                            class="white--text"
                          >
                            fa fa-times
                          </v-icon>
                        </v-flex>
                        <v-flex>
                          Close
                        </v-flex>
                      </v-btn>
                    </div>
                    <v-divider />
                    <area-form
                      :is-loading="newAreaLoading"
                      class="ma-2"
                      @cancel="newArea = false"
                      @input="onAddArea"
                    />
                  </v-card>
                </v-dialog>
              </v-flex>
            </template>
          </areas-of-operation>

          <delete-dialog
            :value="deletable.storeAreas.dialog"
            item-title-prop="name"
            :is-loading="deletable.storeAreas.processing"
            :progress="deletable.storeAreas.progress"
            :items="deletable.storeAreas.temp"
            @confirm="deleteItems(DELETABLE_RESOURCE.AREA, deletable.storeAreas.temp)"
            @cancel="onCancelDeleteItems(DELETABLE_RESOURCE.AREA)"
          />
        </v-flex>
        <v-flex
          v-if="!isNew"
          xs12
          py-2
        >
          <DocumentList
            :docs="docs"
            :statuses="documentStatuses"
            :types="documentTypes"
            :sortables="sortables"
            :rows-per-page="documentPagination.rowsPerPage"
            :page="documentPagination.page"
            :total-items="documentPagination.totalItems"
            :sort-by="documentSorting.sortBy"
            :descending="documentSorting.descending"
          />
        </v-flex>
        <v-flex
          v-if="!isNew"
          xs12
          py-2
        >
          <MenuItems
            :items="menuItems"
            :rows-per-page="menuItemPagination.rowsPerPage"
            :page="menuItemPagination.page"
            :total-items="menuItemPagination.totalItems"
            :sort-by="menuItemSorting.sortBy"
            :descending="menuItemSorting.descending"
            @paginate="onMenuItemPaginate"
            @manage-delete="item => onMenuItemManageMultipleDelete([item])"
            @manage-multiple-delete="onMenuItemManageMultipleDelete"
          >
            <template #new-form="{ close }">
              <menu-item-form
                :include-servings="false"
                @input="payload => createMenuItem(payload, close)"
                @cancel="close"
              />
            </template>
          </MenuItems>

          <delete-dialog
            :value="deletable.menuItems.dialog"
            item-title-prop="title"
            :is-loading="deletable.menuItems.processing"
            :progress="deletable.menuItems.progress"
            :items="deletable.menuItems.temp"
            @confirm="deleteItems(DELETABLE_RESOURCE.MENU_ITEM, deletable.menuItems.temp)"
            @cancel="onCancelDeleteItems(DELETABLE_RESOURCE.MENU_ITEM)"
          />
        </v-flex>
        <v-flex
          v-if="!isNew"
          xs12
          py-2
        >
          <Events
            :events="events"
          />
        </v-flex>
        <v-flex
          v-if="!isNew"
          xs12
          py-2
        >
          <payments />
        </v-flex>
      </v-layout>
    </v-form>
  </div>
</template>
<script>
import BasicInformation, { DEFAULT_STORE } from './BasicInformation'

import Payments from './Payments'
import DocumentList from './DocumentList'
import { mapGetters } from 'vuex'
import Events from './Events'
import AreasOfOperation from './AreasOfOperation'
import MenuItems from '../menu-items/MenuItems'
import MenuItemForm from '../menu-items/MenuItemForm'
import DeleteDialog from '../DeleteDialog'
import StatusSelect from './StatusSelect'
import { createHelpers } from 'vuex-map-fields'
import Validate from 'fresh-bus/components/mixins/Validate'
import get from 'lodash/get'
import { deletables } from 'fresh-bus/components/mixins/Deletables'
import AreaForm from './AreaForm'

const { mapFields } = createHelpers({
  getterType: 'getField',
  mutationType: 'updateField'
})

const DELETABLE_RESOURCE = {
  MENU_ITEM: 'menuItems',
  STORE: 'stores',
  AREA: 'storeAreas'
}

const SQUARE_APP_ID = process.env.SQUARE_APP_ID
const SQUARE_ENVIRONMENT = process.env.SQUARE_ENVIRONMENT

export default {
  layout: 'admin',
  components: {
    AreaForm,
    BasicInformation,
    DocumentList,
    Payments,
    Events,
    AreasOfOperation,
    MenuItems,
    MenuItemForm,
    StatusSelect,
    DeleteDialog
  },
  mixins: [Validate, deletables],
  data () {
    return {
      menuItemLoading: false,
      newArea: false,
      newAreaLoading: false,

      // TODO: Extract to state machine
      DELETABLE_RESOURCE,
      deletable: Object.values(DELETABLE_RESOURCE).reduce((acc, key) => {
        acc[key] = {
          status: '',
          processing: false,
          progress: 0,
          temp: [],
          dialog: false,
          idProp: key === 'storeAreas' ? 'id' : 'uuid'
        }
        return acc
      }, {}),

      loading: false,
      sortables: [
        { value: '-created_at', text: 'Newest' },
        { value: 'created_at', text: 'Oldest' },
        { value: 'title', text: 'Title (A - Z)' },
        { value: '-title', text: 'Title (Z - A)' }
      ],
      events: ['Event will populate once your restaurant is assigned.'],
      fleetMemberLoading: false
    }
  },
  computed: {
    ...mapGetters('storeAreas', {
      areas: 'items',
      storeAreaPagination: 'pagination',
      storeAreaSorting: 'sorting'
    }),
    ...mapGetters('documents', {
      docs: 'items',
      documentPagination: 'pagination',
      documentSorting: 'sorting'
    }),
    ...mapGetters('menuItems', {
      menuItems: 'items',
      menuItemPagination: 'pagination',
      menuItemSorting: 'sorting'
    }),
    ...mapGetters('documentTypes', { documentTypes: 'items' }),
    ...mapGetters('storeTypes', { storeTypes: 'items' }),
    ...mapGetters('documentStatuses', { documentStatuses: 'items' }),
    ...mapGetters('storeStatuses', { storeStatuses: 'items' }),
    ...mapGetters('companies/squareLocations', { squareLocations: 'items' }),
    ...mapFields('stores', [
      'status_id'
    ]),
    squareUrl () {
      const SCOPES = [
        'PAYMENTS_READ',
        'CUSTOMERS_READ',
        'EMPLOYEES_READ',
        'INVENTORY_READ',
        'ITEMS_READ',
        'MERCHANT_PROFILE_READ',
        'ORDERS_READ'
        // 'SETTLEMENTS_READ',
        // 'BANK_ACCOUNTS_READ',
        // 'CUSTOMERS_WRITE',
        // 'PAYMENTS_WRITE_ADDITIONAL_RECIPIENTS', 'PAYMENTS_WRITE', 'PAYMENTS_READ'
        // Let’s look at the scope that we set for this walkthrough:
        //
        // MERCHANT_PROFILE_READ enables calls to List Merchants and Retrieve Merchant endpoints. It is useful to be able to call these endpoints so you can verify seller information tied to the OAuth token.
        //
        // PAYMENTS_WRITE and PAYMENTS_READ enable you to create, cancel, and retrieve payments using the Payments API. Later in our walkthrough, you call Create Payment with an OAuth token.
        //
        // PAYMENTS_WRITE_ADDITIONAL_RECIPIENTS enables you to add an application fee to a payment. The application fee is taken from the payment taken on behalf of the seller and added to your developer account. The seller gets the balance of the payment (less the Square transaction fee). The application fee is specified as part of a Create Payment call.
      ]
      const BASE_URL = (SQUARE_ENVIRONMENT === 'production')
        ? 'https://connect.squareup.com'
        : 'https://connect.squareupsandbox.com'

      const params = {
        client_id: SQUARE_APP_ID,
        scope: SCOPES.join('+')
        // session: false,
      }
      const queryParams = Object.keys(params).map(key => `${key}=${params[key]}`).join('&')
      return `${BASE_URL}/oauth2/authorize?${queryParams}`
    },
    areasLoading () {
      return get(this.$store, 'state.storeAreas.pending.items', false)
    },
    isNew () {
      return !get(this.store, 'uuid')
    },
    store () {
      // This allow us to have the the object to have the wanted keys in case of creation
      return Object.assign({}, DEFAULT_STORE, this.$store.getters['stores/item'])
    },
    isLoading () {
      return this.$store.getters['page/isLoading'] || this.fleetMemberLoading
    },
    pageTitle () {
      return this.isNew ? 'New Fleet Member' : 'Fleet Member Details'
    },
    storeAreas () {
      // TODO: see https://github.com/FreshinUp/core-ui/issues/135
      return Array.isArray(this.areas) ? this.areas : []
    }
  },
  watch: {
    '$store.getters.currentUser' (user) {
      if (user) {
        this.getSquareLocations(user.company_id)
      }
    }
  },
  methods: {
    getSquareLocations (companyId) {
      this.$store.dispatch('companies/squareLocations/getItems', {
        params: {
          companyId: companyId
        }
      })
        .catch(error => console.error(error))
    },
    // store
    async saveOrCreate (data) {
      try {
        this.loading = true
        if (this.isNew) {
          await this.$store.dispatch('stores/createItem', { data })
          await this.$store.dispatch('generalMessage/setMessage', 'Saved.')
          this.$router.push({ path: '/admin/fleet-members' })
        } else {
          await this.$store.dispatch('stores/updateItem', { data, params: { id: this.$route.params.id } })
          await this.$store.dispatch('generalMessage/setMessage', 'Modified.')
        }
      } catch (error) {
        const message = get(error, 'response.data.message', error.message)
        this.$store.dispatch('generalErrorMessages/setErrors', message)
      } finally {
        this.loading = false
      }
    },
    deleteMember (item) {
      this.loading = true
      this.$store.dispatch('stores/deleteItem', { getItems: false, params: { id: this.$route.params.id } })
        .then(() => {
          this.$store.dispatch('generalMessage/setMessage', 'Deleted')
        })
        .catch(error => {
          const message = get(error, 'response.data.message', error.message)
          this.$store.dispatch('generalErrorMessages/setErrors', message)
        })
      this.$router.push({ path: '/admin/fleet-members' })
    },
    onCancel () {
      this.$router.push({ path: '/admin/fleet-members' })
    },
    backToList () {
      this.$router.push({ path: '/admin/fleet-members' })
    },
    async deleteItems (resource, items) {
      const deletable = this.deletable[resource]
      deletable.processing = true
      this.deletable[resource].progress = 0
      this.deletable[resource].status = ''
      const idProp = this.deletable[resource].idProp
      const dispatcheables = []

      items.forEach(item => {
        dispatcheables.push(
          this.$store.dispatch(`${resource}/deleteItem`, {
            getItems: true,
            params: { id: item[idProp] }
          })
        )
      })

      let chunks = this.chunk(dispatcheables, this.deleteTempParrallelRequest)
      let doneCount = 0

      for (let i in chunks) {
        await Promise.all(chunks[i])
        doneCount += chunks[i].length
        deletable.progress = doneCount
        await this.sleep(this.deletablesSleepTime)
      }
      deletable.processing = false
      deletable.dialog = false
    },
    onCancelDeleteItems (resource) {
      this.deletable[resource].dialog = false
      this.deletable[resource].temp = []
    },
    onConnectSquare () {
      window.location = this.squareUrl
    },
    onDisconnectSquare () {
      // TODO delay to a later time
    },

    // store areas
    onAddArea (area) {
      this.newAreaLoading = true
      this.$store.dispatch('storeAreas/createItem', {
        data: { ...area, store_uuid: this.$route.params.id }
      })
        .then(_ => {
          this.newArea = false
          this.$store.dispatch('generalMessage/setMessage', 'Area created.')
          this.$store.dispatch('storeAreas/getItems')
        })
        .catch(error => {
          const message = get(error, 'response.data.message', error.message)
          this.$store.dispatch('generalErrorMessages/setErrors', message)
        })
        .then(() => {
          this.newAreaLoading = false
        })
    },
    onDeleteArea (areas) {
      this.deletable.storeAreas.temp = areas
      this.deletable.storeAreas.dialog = true
    },
    onAreaPaginate (value) {
      this.$store.dispatch('storeAreas/setPagination', value)
      this.$store.dispatch('storeAreas/getItems')
    },

    // menu items
    onMenuItemPaginate (value) {
      this.$store.dispatch('menuItems/setPagination', value)
      this.$store.dispatch('menuItems/getItems')
    },
    onMenuItemManageMultipleDelete (menuItems) {
      this.deletable.menuItems.temp = menuItems
      this.deletable.menuItems.dialog = true
    },
    createMenuItem (data, onSuccess) {
      this.menuItemLoading = true
      this.$store.dispatch('menuItems/createItem', {
        data: { ...data, store_uuid: this.$route.params.id }
      })
        .then(() => {
          this.$store.dispatch('generalMessage/setMessage', 'Saved.')
          onSuccess()
        })
        .catch(error => {
          const message = get(error, 'response.data.message', error.message)
          this.$store.dispatch('generalErrorMessages/setErrors', message)
        })
        .then(() => {
          this.menuItemLoading = false
        })
    }
  },
  beforeRouteEnterOrUpdate (vm, to, from, next) {
    const id = to.params.id || 'new'
    const promises = []
    if (id !== 'new') {
      promises.push(vm.$store.dispatch('documents/getItems', {
        params: {
          'filter[assigned_uuid]': id
        }
      }))
      vm.fleetMemberLoading = true
      vm.$store.dispatch('stores/getItem', {
        params: {
          id,
          include: 'tags,owner'
        }
      })
        .then()
        .catch(error => {
          console.error(error)
          vm.$router.push({ path: '/admin/fleet-members' })
        })
        .then(() => {
          vm.fleetMemberLoading = false
        })
      vm.$store.dispatch('storeAreas/setFilters', {
        store_uuid: id
      })
      promises.push(vm.$store.dispatch('storeAreas/getItems'))

      vm.$store.dispatch('menuItems/setFilters', {
        store_uuid: id
      })
      promises.push(vm.$store.dispatch('menuItems/getItems'))
      const currentUser = vm.$store.getters['currentUser']
      if (currentUser) {
        vm.getSquareLocations(currentUser.company_id)
      }
    }
    promises.push(vm.$store.dispatch('documentStatuses/getItems'))
    promises.push(vm.$store.dispatch('documentTypes/getItems'))
    promises.push(vm.$store.dispatch('storeTypes/getItems'))
    promises.push(vm.$store.dispatch('storeStatuses/getItems'))

    if (!SQUARE_APP_ID) {
      vm.$store.dispatch('generalErrorMessages/setErrors', 'Unable to find square application id')
      return false
    }
    if (!SQUARE_ENVIRONMENT) {
      vm.$store.dispatch('generalErrorMessages/setErrors', 'Unable to find square environment')
      return false
    }

    vm.$store.dispatch('page/setLoading', true)
    Promise.all(promises)
      .then(() => {})
      .catch((error) => {
        console.error(error)
      })
      .then(() => {
        vm.$store.dispatch('page/setLoading', false)
        if (next) next()
      })
  }
}
</script>
<style scoped>
  .back-btn-inner {
    color: #fff;
    display: flex;
    align-items: center;
    font-size: 13px;
  }
</style>
