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
            :locations="locations"
            :value="store"
            @input="saveOrCreate"
            @delete="deleteMember"
            @cancel="onCancel"
          />
        </v-flex>
        <v-flex
          xs12
          py-2
        >
          <DocumentList
            :docs="docs"
            :statuses="documentStatuses"
            :types="documentTypes"
            :sortables="sortables"
            :rows-per-page="pagination.rowsPerPage"
            :page="pagination.page"
            :total-items="pagination.totalItems"
            :sort-by="sorting.sortBy"
            :descending="sorting.descending"
          />
        </v-flex>
        <v-flex
          xs12
          py-2
        >
          <payments />
        </v-flex>
        <v-flex
          xs12
          py-2
        >
          <Events
            :events="events"
          />
        </v-flex>
        <v-flex
          xs12
          py-2
        >
          <AreasOfOperation
            :items="storeAreas"
            :rows-per-page="storeAreaPagination.rowsPerPage"
            :page="storeAreaPagination.page"
            :total-items="storeAreaPagination.totalItems"
            :sort-by="storeAreaSorting.sortBy"
            :descending="storeAreaSorting.descending"
            @paginate="onAreaPaginate"
            @manage-delete="onDeleteArea"
            @manage-multiple-delete="onDeleteArea"
          />

          <v-dialog
            v-model="deleteDialog"
            max-width="500"
          >
            <simple-confirm
              :class="{ 'deleting': deletablesProcessing }"
              :title="deleteDialogTitle"
              ok-label="Yes"
              cancel-label="No"
              @ok="onSubmitDelete"
              @cancel="onCancelDelete"
            >
              <div class="py-5 px-2">
                <template v-if="deletablesProcessing">
                  <div class="text-xs-center">
                    <p class="subheading">
                      Processing, please wait...
                    </p>
                    <v-progress-circular
                      :rotate="-90"
                      :size="200"
                      :width="15"
                      :value="deletablesProgress"
                      color="primary"
                    >
                      {{ deletablesStatus }}
                    </v-progress-circular>
                  </div>
                </template>
                <template v-else>
                  <p class="subheading">
                    <span v-if="deletables.length < 2">Area</span>
                    <span v-else>Areas</span>
                    : {{ deleteTemp | formatDeleteTitles }}
                  </p>
                </template>
              </div>
            </simple-confirm>
          </v-dialog>
        </v-flex>
        <v-flex
          xs12
          py-2
        >
          <Menu />
        </v-flex>
      </v-layout>
    </v-form>
  </div>
</template>
<script>
import BasicInformation from './BasicInformation'

import Payments from './Payments'
import DocumentList from './DocumentList'
import { mapGetters } from 'vuex'
import Events from './Events'
import AreasOfOperation from './AreasOfOperation'
import Menu from './Menu'
import StatusSelect from './StatusSelect'
import { createHelpers } from 'vuex-map-fields'
import Validate from 'fresh-bus/components/mixins/Validate'
import get from 'lodash/get'
import { deletables } from 'fresh-bus/components/mixins/Deletables'
import SimpleConfirm from 'fresh-bus/components/SimpleConfirm.vue'

const { mapFields } = createHelpers({
  getterType: 'getField',
  mutationType: 'updateField'
})

export default {
  layout: 'admin',
  components: {
    SimpleConfirm,
    BasicInformation,
    DocumentList,
    Payments,
    Events,
    AreasOfOperation,
    Menu,
    StatusSelect
  },
  filters: {
    formatDeleteTitles (value) {
      return value.map(item => item.name).join(', ')
    }
  },
  mixins: [Validate, deletables],
  data () {
    return {
      deleteTemp: [],
      deleteDialog: false,
      loading: false,
      locations: ['Square'], // TODO: static to Square only until we know better
      pagination: {
        page: 1,
        rowsPerPage: 10,
        totalItems: 5
      },
      sorting: {
        descending: false,
        sortBy: ''
      },
      sortables: [
        { value: '-created_at', text: 'Newest' },
        { value: 'created_at', text: 'Oldest' },
        { value: 'title', text: 'Title (A - Z)' },
        { value: '-title', text: 'Title (Z - A)' }
      ],
      events: ['Event will populate once your restaurant is assigned.'],
      fleetMemberLoading: false,
      isNew: false
    }
  },
  computed: {
    ...mapGetters('storeAreas', {
      areas: 'items',
      storeAreaPagination: 'pagination',
      storeAreaSorting: 'sorting'
    }),
    ...mapGetters('stores', { store: 'item' }),
    ...mapGetters('documents', { docs: 'items' }),
    ...mapGetters('documentTypes', { documentTypes: 'items' }),
    ...mapGetters('storeTypes', { storeTypes: 'items' }),
    ...mapGetters('documentStatuses', { documentStatuses: 'items' }),
    ...mapGetters('storeStatuses', { storeStatuses: 'items' }),
    ...mapGetters('companies/squareLocations', { squareLocations: 'items' }),
    ...mapFields('stores', [
      'status_id'
    ]),
    isLoading () {
      return this.$store.getters['page/isLoading'] || this.fleetMemberLoading
    },
    pageTitle () {
      return this.isNew ? 'New Fleet Member' : 'Fleet Member Details'
    },
    storeAreas () {
      return this.isNew ? [] : this.areas
    },
    deleteDialogTitle () {
      return this.deleteTemp.length < 2
        ? 'Are you sure you want to delete this area?'
        : 'Are you sure you want to delete the following areas?'
    }
  },
  methods: {
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
    deleteMember (item) {},
    onCancel () {
      this.$router.push({ path: '/admin/fleet-members' })
    },
    backToList () {
      this.$router.push({ path: '/admin/fleet-members' })
    },
    onDeleteArea (area) {
      this.deleteTemp = Array.isArray(area) ? area : [area]
      this.deleteDialog = true
    },
    async onSubmitDelete () {
      this.deletablesProcessing = true
      this.deletablesProgress = 0
      this.deletablesStatus = ''
      let dispatcheables = []

      this.deleteTemp.forEach(area => {
        dispatcheables.push(
          this.$store.dispatch('storeAreas/deleteItem', {
            getItems: true,
            params: { id: area.id }
          })
        )
      })

      let chunks = this.chunk(dispatcheables, this.deleteTempParrallelRequest)
      let doneCount = 0

      for (let i in chunks) {
        await Promise.all(chunks[i])
        doneCount += chunks[i].length
        this.deleteTempStatus =
          doneCount + ' / ' + this.deleteTemp.length + ' Done'
        this.deleteTempProgress = (doneCount / this.deleteTemp.length) * 100
        await this.sleep(this.deletablesSleepTime)
      }

      this.deletablesProcessing = false
      this.deleteDialog = false
    },
    onCancelDelete () {
      this.deleteDialog = false
      this.deleteTemp = []
    },
    onAreaPaginate (value) {
      this.$store.dispatch('storeAreas/setPagination', value)
      this.$store.dispatch('storeAreas/getItems')
    }
  },
  beforeRouteEnterOrUpdate (vm, to, from, next) {
    const id = to.params.id || 'new'
    const promises = []
    const params = { id }
    if (id !== 'new') {
      promises.push(vm.$store.dispatch('documents/getItem', { params }))

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
        'filter[store_uuid]': id
      })
      promises.push(vm.$store.dispatch('storeAreas/getItems'))
    }
    vm.$store.dispatch('page/setLoading', true)
    // TODO: next step is to gather squareLocations from API
    // promises.push(vm.$store.dispatch('companies/squareLocations/getItems', {
    //   params: {
    //     id: vm.$store.getters.currentUser.company_id
    //   }
    // }))
    promises.push(vm.$store.dispatch('documentStatuses/getItems'))
    promises.push(vm.$store.dispatch('documentTypes/getItems'))
    promises.push(vm.$store.dispatch('storeTypes/getItems'))
    promises.push(vm.$store.dispatch('storeStatuses/getItems'))
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
