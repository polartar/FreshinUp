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
            <multi-simple
              ref="location"
              v-model="filters.location"
              url="companies?filter[type_key]=host"
              term-param="filter[name]"
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
                @clear="filters.type = null; $refs.type.resetTerm()"
              />
            </v-layout>
            <v-select
              ref="type"
              v-model="filters.type"
              :items="filters.types"
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
              url="foodfleet/event-tags"
              term-param="filter[name]"
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
import MultiSimple from 'fresh-bus/components/ui/FMultiSimple'
import SearchFilterSorter from 'fresh-bus/components/search/filter-sorter.vue'

export default {
  components: {
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
    autocompleteUrl: {
      type: String,
      default: ''
    }
  },
  data () {
    return {
      location: null,
      type: null,
      tag: null
    }
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

      this.$emit('runFilter', finalParams)
    },
    clearFilters (params) {
      this.$refs.location.resetTerm()
      this.$refs.type.resetTerm()
      this.$refs.tags.resetTerm()

      this.filters.location = this.filters.type = this.filters.tags = null
      this.run(params)
    }
  }
}
</script>
