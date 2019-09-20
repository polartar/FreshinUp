<template>
  <search-filter-sorter
    expanded
    without-filter-label
    :autocomplete_url="autocompleteUrl"
    sort-label="Sort by"
    :sort-options="sortables"
    :filters="filters"
    v-bind="$attrs"
    v-on="$listeners"
    @run="run"
    @clear="clearFilters"
  >
    <template v-slot:expanded="slotProps">
      <v-card-text>
        <v-layout
          row
          justify-space-between
        >
          <v-flex>
            <vue-ctk-date-time-picker
              v-model="expireDate"
              range
              only-date
              format="YYYY-MM-DD"
              formatted="MM-DD-YYYY"
              input-size="lg"
              label="Expiration date range"
              :color="$vuetify.theme.primary"
              :button-color="$vuetify.theme.primary"
              @input="slotProps.run"
            />
          </v-flex>
          <v-flex
            ml-2
          >
            <v-select
              v-model="type"
              :items="types"
              placeholder="Document Type"
              solo
              flat
              hide-details
              @change="slotProps.run"
            />
          </v-flex>
          <v-flex
            ml-2
          >
            <v-select
              v-model="status"
              :items="statuses"
              placeholder="Status: All"
              solo
              flat
              hide-details
              @change="slotProps.run"
            />
          </v-flex>
          <v-flex
            ml-2
          >
            <simple
              :key="userSearchKey"
              url="users"
              placeholder="Search Users"
              background-color="white"
              class="mt-0 pt-0"
              height="48"
              @input="(user) => { selectUsers(user, slotProps.run) }"
            />
          </v-flex>
        </v-layout>
      </v-card-text>
    </template>
  </search-filter-sorter>
</template>

<script>
import 'vue-ctk-date-time-picker/dist/vue-ctk-date-time-picker.css'
import VueCtkDateTimePicker from 'vue-ctk-date-time-picker'
import SearchFilterSorter from 'fresh-bus/components/search/filter-sorter.vue'
import Simple from 'fresh-bus/components/search/simple'

export default {
  components: {
    Simple,
    SearchFilterSorter,
    VueCtkDateTimePicker
  },
  props: {
    autocompleteUrl: {
      type: String,
      default: ''
    },
    types: {
      type: Array,
      default: () => []
    },
    statuses: {
      type: Array,
      default: () => []
    },
    sortables: {
      type: Array,
      default: () => []
    }
  },
  data () {
    return {
      userSearchKey: +new Date(),
      type: null,
      status: null,
      assigned_user_uuid: null,
      expireDate: null
    }
  },
  computed: {
    // Get filters from inputs
    filters () {
      let filtersObject = {
        type: this.type,
        status: this.status
      }

      if (this.assigned_user_uuid) {
        filtersObject.assigned_user_uuid = this.assigned_user_uuid
      }

      if (this.expireDate) {
        filtersObject.expiration_from = this.expireDate.start
        filtersObject.expiration_to = this.expireDate.end
      }

      return filtersObject
    }
  },
  methods: {
    run (params) {
      let finalParams = {
        title: params.term,
        sort: params.orderBy,
        'filter[type]': this.filters.type,
        'filter[status]': this.filters.status
      }

      if ('assigned_user_uuid' in this.filters) {
        finalParams['filter[assigned_user_uuid]'] = this.filters.assigned_user_uuid
      }

      if ('expiration_from' in this.filters) {
        finalParams['filter[expiration_from]'] = this.filters.expiration_from
      }

      if ('expiration_to' in this.filters) {
        finalParams['filter[expiration_to]'] = this.filters.expiration_to
      }

      this.$emit('runFilter', finalParams)
    },
    selectUsers (user, run) {
      this.assigned_user_uuid = user ? user.uuid : ''
      run()
    },
    clearFilters (params) {
      this.type = this.status = this.assigned_user_uuid = null
      this.userSearchKey++
      this.run(params)
    }
  }
}
</script>
