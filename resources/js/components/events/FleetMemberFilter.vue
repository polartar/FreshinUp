<template>
  <search-filter-sorter
    expanded
    without-filter-label
    without-sort-by
    :autocomplete_url="autocompleteUrl"
    sort-label="Sort by"
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
          <v-flex
            ml-4
          >
            <v-select
              v-model="location"
              :items="locations"
              placeholder="Chicago, IL"
              solo
              flat
              hide-details
              @change="slotProps.run"
            />
          </v-flex>
          <v-flex
            ml-4
          >
            <v-select
              v-model="type"
              :items="types"
              placeholder="All types"
              solo
              flat
              hide-details
              @change="slotProps.run"
            />
          </v-flex>
          <v-flex
            ml-4
          >
            <v-select
              v-model="tag"
              :items="tags"
              placeholder="FOOD TAG"
              solo
              flat
              hide-details
              @change="slotProps.run"
            />
          </v-flex>
        </v-layout>
      </v-container>
    </template>
  </search-filter-sorter>
</template>

<script>
import SearchFilterSorter from 'fresh-bus/components/search/filter-sorter.vue'

export default {
  components: {
    SearchFilterSorter
  },
  props: {
    autocompleteUrl: {
      type: String,
      default: ''
    },
    locations: {
      type: Array,
      default: () => []
    },
    types: {
      type: Array,
      default: () => []
    },
    tags: {
      type: Array,
      default: () => []
    }
  },
  data () {
    return {
      location: null,
      type: null,
      tag: null
    }
  },
  computed: {
    // Get filters from inputs
    filters () {
      let filtersObject = {
        location: this.location,
        type: this.type,
        tags: this.tag
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
      this.location = this.type = this.tag = null
      this.run(params)
    }
  }
}
</script>
