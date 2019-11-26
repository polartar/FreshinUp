<template>
  <search-filter-sorter
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
      <v-container
        pr-0
        pt-1
        pb-0
      >
        <v-layout
          row
          justify-space-between
        >
          <v-flex>
            <date-time-picker
              v-model="expireDate"
              range
              only-date
              format="YYYY-MM-DD"
              formatted="MM-DD-YYYY"
              input-size="lg"
              label="Expiration"
              :color="$vuetify.theme.primary"
              :button-color="$vuetify.theme.primary"
              @input="slotProps.run"
            />
          </v-flex>
          <v-flex ml-4>
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
          <v-flex ml-4>
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
          <v-flex ml-4>
            <AssignedSearch
              ref="assignedSearcher"
              :type="assignedType"
              @type-change="selectAssignedType"
              @assign-change="(assigned) => selectAssigned(assigned, slotProps.run)"
            />
          </v-flex>
        </v-layout>
      </v-container>
    </template>
  </search-filter-sorter>
</template>

<script>
import 'vue-ctk-date-time-picker/dist/vue-ctk-date-time-picker.css'
import DateTimePicker from '~/components/DateTimePicker'
import SearchFilterSorter from 'fresh-bus/components/search/filter-sorter.vue'
import AssignedSearch from '~/components/docs/AssignedSearch'

const defaultAssignedType = 1

export default {
  components: {
    AssignedSearch,
    SearchFilterSorter,
    DateTimePicker
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
      assignedType: defaultAssignedType,
      type: null,
      status: null,
      assigned_uuid: null,
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

      if (this.assigned_uuid) {
        filtersObject.assigned_uuid = this.assigned_uuid
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
        ...this.filters
      }

      this.$emit('runFilter', finalParams)
    },
    selectAssignedType (assignedType) {
      this.assignedType = assignedType
    },
    selectAssigned (assigned, run) {
      this.assigned_uuid = assigned ? assigned.uuid : ''
      run()
    },
    clearFilters (params) {
      this.expireDate = null
      this.assignedType = defaultAssignedType
      if (this.$refs.assignedSearcher && this.$refs.assignedSearcher.resetTerm) {
        this.$refs.assignedSearcher.resetTerm()
      }
      this.type = this.status = this.assigned_uuid = null
      this.userSearchKey++
      this.run(params)
    }
  }
}
</script>
