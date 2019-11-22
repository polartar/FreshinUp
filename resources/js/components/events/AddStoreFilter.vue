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
      <v-card-text class="px-0 pb-0">
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
                v-if="filters.location_uuid"
                color="inherit"
                @clear="filters.location_uuid = null; $refs.location.resetTerm()"
              />
            </v-layout>
            <simple
              ref="location"
              url="foodfleet/locations"
              term-param="filter[name]"
              results-id-key="uuid"
              :value="filters.location_uuid"
              placeholder="Select"
              background-color="white"
              class="mt-0 pt-0"
              height="48"
              not-clearable
              solo
              flat
              @input="selectLocation"
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
              item-value="uuid"
              item-text="name"
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
                v-if="filters.tag_uuid && filters.tag_uuid.length > 0"
                color="inherit"
                @clear="filters.tag_uuid = null; $refs.tags.resetTerm()"
              />
            </v-layout>
            <multi-simple
              ref="tags"
              v-model="filters.tag_uuid"
              url="foodfleet/store-tags"
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
      </v-card-text>
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
        location_uuid: null,
        type: null,
        tag_uuid: null
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
    selectLocation (location) {
      this.filters.location_uuid = location ? location.uuid : null
    },
    run (params) {
      let finalParams = {
        title: params.term,
        ...this.filters
      }

      if (this.filters.tag_uuid) {
        finalParams.tag_uuid = this.filters.tag_uuid.map(item => item.uuid)
      }

      this.$emit('runFilter', finalParams)
    },
    clearFilters (params) {
      this.$refs.location.resetTerm()
      this.$refs.tags.resetTerm()

      this.filters.location_uuid = this.filters.type = this.filters.tag_uuid = null
      this.run(params)
    }
  }
}
</script>
<style lang="styl" scoped>
  /deep/ .filter-sorter-expanded-layout{
    align-items: flex-end;
  }
  /deep/ .filter-sorter-expanded-layout>.flex.text-no-wrap>.v-btn{
    margin: 0;
    height: 48px;
  }
</style>
