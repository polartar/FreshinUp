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
              :key="addressSearchKey"
              url="foodfleet/locations"
              term-param="filter[name]"
              placeholder="Search Address"
              background-color="white"
              class="mt-0 pt-0"
              height="48"
              @input="(address) => { selectAddress(address, slotProps.run) }"
            />
          </v-flex>
        </v-layout>
      </v-card-text>
    </template>
  </search-filter-sorter>
</template>

<script>
import 'vue-ctk-date-time-picker/dist/vue-ctk-date-time-picker.css'
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
      addressSearchKey: 0,
      tagSearchKey: 0,
      status: null,
      tag: null,
      address_uuid: null
    }
  },
  computed: {
    // Get filters from inputs
    filters () {
      let filtersObject = {
        status: this.status
      }

      if (this.address_uuid) {
        filtersObject.address_uuid = this.address_uuid
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
    selectAddress (address, run) {
      this.address_uuid = address ? address.uuid : ''
      run()
    },
    selectTag (tag, run) {
      this.tag = tag ? tag.uuid : ''
      run()
    },
    clearFilters (params) {
      this.status = this.address_uuid = null
      this.addressSearchKey++
      this.tagSearchKey++
      this.run(params)
    }
  }
}
</script>
