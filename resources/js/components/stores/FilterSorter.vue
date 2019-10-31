<template>
  <search-filter-sorter
    expanded
    without-filter-label
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
              :key="tagSearchKey"
              url="foodfleet/store-tags"
              term-param="filter[name]"
              placeholder="Search Tag"
              background-color="white"
              class="mt-0 pt-0"
              height="48"
              @input="(tag) => { selectTag(tag, slotProps.run) }"
            />
          </v-flex>
          <v-flex
            ml-2
          >
            <simple
              :key="locationSearchKey"
              url="foodfleet/locations"
              term-param="filter[name]"
              placeholder="Search Address"
              background-color="white"
              class="mt-0 pt-0"
              height="48"
              @input="(location) => { selectLocation(location, slotProps.run) }"
            />
          </v-flex>
        </v-layout>
      </v-card-text>
    </template>
  </search-filter-sorter>
</template>

<script>
import SearchFilterSorter from 'fresh-bus/components/search/filter-sorter.vue'
import Simple from 'fresh-bus/components/search/simple'
export default {
  components: {
    Simple,
    SearchFilterSorter
  },
  props: {
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
      locationSearchKey: 0,
      tagSearchKey: 0,
      status: null,
      tag: null,
      location_uuid: null
    }
  },
  computed: {
    // Get filters from inputs
    filters () {
      let filtersObject = {
        status: this.status
      }

      if (this.location_uuid) {
        filtersObject.location_uuid = this.location_uuid
      }

      if (this.tag) {
        filtersObject.tag = this.tag
      }

      return filtersObject
    }
  },
  methods: {
    run (params) {
      let finalParams = {
        name: params.term,
        sort: params.orderBy,
        ...this.filters
      }
      this.$emit('runFilter', finalParams)
    },
    selectLocation (location, run) {
      this.location_uuid = location ? location.uuid : ''
      run()
    },
    selectTag (tag, run) {
      this.tag = tag ? tag.uuid : ''
      run()
    },
    clearFilters (params) {
      this.status = this.location_uuid = null
      this.locationSearchKey++
      this.tagSearchKey++
      this.run(params)
    }
  }
}
</script>
