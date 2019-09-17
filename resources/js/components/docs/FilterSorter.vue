<template>
  <search-filter-sorter
    expanded
    withoutFilterLabel
    :autocomplete_url="autocompleteUrl"
    sortLabel="Sort by"
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
          <v-flex
          >
            <vue-ctk-date-time-picker
              v-model="expireFrom"
              only-date
              format="YYYY-MM-DD"
              formatted="MM-DD-YYYY"
              input-size="lg"
              label="Expiration date (from)"
              :color="$vuetify.theme.primary"
              :button-color="$vuetify.theme.primary"
            />
          </v-flex>
          <v-flex
            ml-2
          >
            <vue-ctk-date-time-picker
              v-model="expireTo"
              only-date
              format="YYYY-MM-DD"
              formatted="MM-DD-YYYY"
              input-size="lg"
              label="Expiration date (to)"
              :color="$vuetify.theme.primary"
              :button-color="$vuetify.theme.primary"
            />
          </v-flex>
          <v-flex

            ml-2
          >
            <v-select
              v-model="docType"
              :items="docTypes"
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
            <v-select
              v-model="info"
              :items="infoList"
              placeholder="Other Info"
              solo
              flat
              hide-details
              @change="slotProps.run"
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

export default {
  components: {
    SearchFilterSorter,
    VueCtkDateTimePicker
  },
  props: {
    autocompleteUrl: {
      type: String,
      default: '/api/users'
    },
    infoList: {
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
    },
    withoutStatus: {
      type: Boolean,
      default: false
    },
    withoutdocType: {
      type: Boolean,
      default: false
    }
  },
  data () {
    return {
      docType: null,
      docTypes: [
      ],

      status: null,
      info: null,
      expireFrom: null,
      expireTo: null
    }
  },
  computed: {
    // Get filters from inputs
    filters () {
      let filtersObject = {
        docType: this.docType,
        status: this.status
      }

      if (this.infoList.length > 0) {
        filtersObject.info = this.info
      }

      return filtersObject
    }
  },
  methods: {
    run (params) {
      let finalParams = {
        term: params.term,
        sort: params.orderBy,
        'filter[docType]': this.filters.docType,
        'filter[status]': this.filters.status
      }

      if ('info' in this.filters) {
        finalParams['filter[info_id]'] = this.filters.info
      }

      this.$emit('runFilter', finalParams)
    },
    clearFilters (params) {
      this.docType = this.status = this.info = null
      this.run(params)
    }
  }
}
</script>
