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
          <AreasOfOperation />
          <Menu />
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
import Menu from './Menu'
import StatusSelect from './StatusSelect'
import { createHelpers } from 'vuex-map-fields'
import Validate from 'fresh-bus/components/mixins/Validate'
import get from 'lodash/get'

const { mapFields } = createHelpers({
  getterType: 'getField',
  mutationType: 'updateField'
})

export default {
  layout: 'admin',
  components: {
    BasicInformation,
    DocumentList,
    Payments,
    Events,
    AreasOfOperation,
    Menu,
    StatusSelect
  },
  mixins: [Validate],
  data () {
    return {
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
    ...mapGetters('documents', { docs: 'items' }),
    ...mapGetters('documentTypes', { documentTypes: 'items' }),
    ...mapGetters('storeTypes', { storeTypes: 'items' }),
    ...mapGetters('documentStatuses', { documentStatuses: 'items' }),
    ...mapGetters('storeStatuses', { storeStatuses: 'items' }),
    ...mapGetters('companies/squareLocations', { squareLocations: 'items' }),
    ...mapFields('stores', [
      'status_id'
    ]),
    store () {
      return this.$store.getters['stores/item'] || DEFAULT_STORE
    },
    isLoading () {
      return this.$store.getters['page/isLoading'] || this.fleetMemberLoading
    },
    pageTitle () {
      return this.isNew ? 'New Fleet Member' : 'Fleet Member Details'
    }
  },
  methods: {
    async saveOrCreate (data) {
      this.loading = true
      try {
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
