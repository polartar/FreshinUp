<template>
  <search-filter-sorter
    ref="filter"
    class="filter-transparent"
    expanded
    without-filter-label
    placeholder="Filter by fleet member name"
    sort-label="Sort by"
    :sort-options="sortables"
    color="transparent"
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
          class="mb-4"
        >
          <v-flex
            ml-2
          >
            <v-layout
              row
              justify-space-between
              mb-2
            >
              <filter-label>
                Statuses
              </filter-label>
              <clear-button
                v-if="filters.status_id && filters.status_id.length > 0"
                color="white"
                @clear="filters.status_id = null;"
              />
            </v-layout>
            <multi-select
              v-model="filters.status_id"
              placeholder="Select Status"
              :items="statuses"
              item-value="id"
              item-text="name"
              select-all-name="All Status"
              solo
              flat
              hide-details
            />
          </v-flex>
          <v-flex
            ml-2
          >
            <v-layout
              row
              justify-space-between
              mb-2
            >
              <filter-label>
                Owner
              </filter-label>
              <clear-button
                v-if="filters.owner_uuid"
                color="white"
                @clear="filters.owner_uuid = null; $refs.owner.clearTerm()"
              />
            </v-layout>
            <f-autocomplete
              ref="owner"
              :value="filters.owner_uuid"
              no-filter
              placeholder="Owned by"
              value-fetch
              item-value="uuid"
              item-text="name"
              url="/users?filter[status]=1"
              background-color="white"
              hide-details
              class="pt-0"
              height="48"
              not-clearable
              @input="selectOwner"
            />
          </v-flex>
          <v-flex
            ml-2
          >
            <v-layout
              row
              justify-space-between
              mb-2
            >
              <filter-label>
                State
              </filter-label>
              <clear-button
                v-if="filters.state_of_incorporation"
                color="white"
                @clear="filters.state_of_incorporation = null"
              />
            </v-layout>
            <v-text-field
              class="pt-0"
              background-color="white"
              height="48"
              :value="filters.state_of_incorporation"
              placeholder="State of incorporation"
              single-line
              @input="onStateChanged"
            />
          </v-flex>
          <v-flex
            ml-2
          >
            <v-layout
              row
              justify-space-between
              mb-2
            >
              <filter-label>
                Tags
              </filter-label>
              <clear-button
                v-if="filters.tag && filters.tag.length > 0"
                color="white"
                @clear="filters.tag = []; $refs.tag.resetTerm()"
              />
            </v-layout>
            <multi-simple
              ref="tag"
              v-model="filters.tag"
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
import SearchFilterSorter from 'fresh-bus/components/search/filter-sorter'
import MultiSelect from 'fresh-bus/components/ui/FMultiSelect'
import MultiSimple from 'fresh-bus/components/ui/FMultiSimple'
import FAutocomplete from '~/components/FAutocomplete'
import debounce from 'lodash/debounce'

export const DEFAULT_FILTERS = {
  status_id: null,
  tag: null,
  state_of_incorporation: null,
  owner_uuid: null
}

export default {
  components: {
    MultiSimple,
    FilterLabel,
    ClearButton,
    MultiSelect,
    SearchFilterSorter,
    FAutocomplete
  },
  props: {
    filters: {
      type: Object,
      default: () => DEFAULT_FILTERS
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
  watch: {
    filters: {
      handler: function (value) {
        const params = this.$refs.filter.getRunParams()
        this.run(params)
      },
      deep: true
    }
  },
  methods: {
    // mutating the filter immediately would cause too much network request
    // while the user is still typing
    onStateChanged: debounce(function (value) {
      this.filters.state_of_incorporation = value
    }, 400),
    selectOwner (user) {
      this.filters.owner_uuid = user ? user.uuid : null
    },
    run (params) {
      const finalParams = {
        name: params.term,
        ...this.filters
      }
      if (this.filters.tag) {
        finalParams.tag = this.filters.tag.map(item => item.uuid)
      }
      this.$emit('runFilter', finalParams)
    },
    clearFilters () {
      this.$refs.tag.resetTerm()
      this.$refs.owner.clearTerm()
      this.filters.status_id = null
      this.filters.tag = null
      this.filters.owner_uuid = null
      this.filters.state_of_incorporation = null
    }
  }
}
</script>
<style lang="styl" scoped>
  /deep/ .data-time-picker.no-border input.field-input{
    border: none !important;
  }
  /deep/ .filter-sorter-expanded-layout{
    align-items: center;
  }
  /deep/ .filter-sorter-expanded-layout>.flex.text-no-wrap>.v-btn{
    margin: 0;
    height: 48px;
  }
  .filter-transparent{
    box-shadow: none;
  }
</style>
