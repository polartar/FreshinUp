<template>
  <search-filter-sorter
    ref="filter"
    without-filter-label
    without-sort-by
    :autocomplete_url="autocompleteUrl"
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
            <v-layout
              row
              justify-space-between
              align-center
              mb-2
            >
              <filter-label
                color="inherit"
              >
                Location
              </filter-label>
              <clear-button
                v-if="filters.location && filters.location.length > 0"
                color="inherit"
                @clear="filters.location = null; $refs.location.resetTerm()"
              />
            </v-layout>
            <simple
              ref="location"
              v-model="filters.location"
              url="foodfleet/locations"
              term-param="filter[location]"
              results-id-key="uuid"
              placeholder="Select"
              background-color="white"
              class="mt-0 pt-0"
              height="48"
              not-clearable
              solo
              flat
            />
          </v-flex>
          <v-flex
            ml-4
          >
            <v-layout
              row
              justify-space-between
              align-center
              mb-2
            >
              <filter-label
                color="inherit"
              >
                Type
              </filter-label>
              <clear-button
                v-if="filters.type && filters.type.length > 0"
                color="inherit"
                @clear="filters.type = null;"
              />
            </v-layout>
            <v-select
              ref="type"
              v-model="filters.type"
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
            <v-layout
              row
              justify-space-between
              align-center
              mb-2
            >
              <filter-label
                color="inherit"
              >
                Tag
              </filter-label>
              <clear-button
                v-if="filters.tags && filters.tags.length > 0"
                color="inherit"
                @clear="filters.tags = []; $refs.tags.resetTerm()"
              />
            </v-layout>
            <multi-simple
              ref="tags"
              v-model="filters.tags"
              url="foodfleet/store-tags"
              term-param="filter[tags]"
              results-id-key="uuid"
              placeholder="Search Tag"
              background-color="white"
              class="mt-0 pt-0"
              height="48"
              not-clearable
              solo
              flat
            />
          </v-flex>
        </v-layout>
      </v-container>
    </template>
  </search-filter-sorter>
</template>

<script>
import ClearButton from '~/components/ClearButton'
import FilterLabel from '~/components/FilterLabel'
import Simple from 'fresh-bus/components/search/simple'
import MultiSimple from 'fresh-bus/components/ui/FMultiSimple'
import SearchFilterSorter from 'fresh-bus/components/search/filter-sorter.vue'

export default {
  components: {
    Simple,
    MultiSimple,
    ClearButton,
    FilterLabel,
    SearchFilterSorter
  },
  props: {
    filters: {
      type: Object,
      default: () => ({
        location: null,
        type: null,
        tags: []
      })
    },
    types: {
      type: Array,
      default: () => []
    },
    autocompleteUrl: {
      type: String,
      default: ''
    }
  },
  data () {
    return {}
  },
  watch: {
    // Get filters from inputs
    filters: {
      handler: function (value) {
        const params = this.$refs.filter.getRunParams()
        this.run(params)
      },
      deep: true
    }
  },
  methods: {
    run (params) {
      let finalParams = {
        title: params.term,
        ...this.filters
      }

      if (this.filters.tags) {
        finalParams.tags = this.filters.tags.map(item => item.uuid)
      }

      this.$emit('runFilter', finalParams)
    },
    clearFilters (params) {
      this.$refs.location.resetTerm()
      this.$refs.tags.resetTerm()

      this.filters.location = this.filters.type = this.filters.tag = null
      this.run(params)
    }
  }
}
</script>
