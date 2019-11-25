<template>
  <search-filter-sorter
    ref="filter"
    class="filter-transparent"
    expanded
    without-filter-label
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
        >
          <v-flex>
            <v-layout
              row
              justify-space-between
              align-center
              mb-2
            >
              <filter-label>
                Statuses
              </filter-label>
              <clear-button
                v-if="filters.status && filters.status.length > 0"
                color="white"
                @clear="filters.status = null;"
              />
            </v-layout>
            <multi-select
              v-model="filters.status"
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
                Supplier
              </filter-label>
              <clear-button
                v-if="filters.supplier_uuid && filters.supplier_uuid.length > 0"
                color="white"
                @clear="filters.supplier_uuid = null; $refs.host.resetTerm()"
              />
            </v-layout>
            <multi-simple
              ref="supplier"
              v-model="filters.supplier_uuid"
              url="companies?filter[type_key]=supplier"
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
            ml-2
          >
            <v-layout
              row
              justify-space-between
              mb-2
            >
              <filter-label>
                Hometown
              </filter-label>
              <clear-button
                v-if="filters.location_uuid"
                color="white"
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
                v-if="filters.store_tag_uuid && filters.store_tag_uuid.length > 0"
                color="white"
                @clear="filters.store_tag_uuid = null; $refs.tag.resetTerm()"
              />
            </v-layout>
            <multi-simple
              ref="tag"
              v-model="filters.store_tag_uuid"
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
import Simple from 'fresh-bus/components/search/simple'
import MultiSelect from 'fresh-bus/components/ui/FMultiSelect'
import MultiSimple from 'fresh-bus/components/ui/FMultiSimple'
export default {
  components: {
    Simple,
    MultiSimple,
    FilterLabel,
    ClearButton,
    MultiSelect,
    SearchFilterSorter
  },
  props: {
    filters: {
      type: Object,
      default: () => ({
        status: null,
        store_tag_uuid: null,
        location_uuid: null,
        supplier_uuid: null
      })
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
    run (params) {
      let finalParams = {
        name: params.term,
        ...this.filters
      }
      if (this.filters.store_tag_uuid) {
        finalParams.store_tag_uuid = this.filters.store_tag_uuid.map(item => item.uuid)
      }
      if (this.filters.supplier_uuid) {
        finalParams.supplier_uuid = this.filters.supplier_uuid.map(item => item.uuid)
      }
      this.$emit('runFilter', finalParams)
    },
    selectLocation (location) {
      this.filters.location_uuid = location ? location.uuid : null
    },
    clearFilters (params) {
      this.$refs.tag.resetTerm()
      this.$refs.supplier.resetTerm()
      this.$refs.location.resetTerm()
      this.filters.status = this.filters.store_tag_uuid = this.filters.location_uuid = this.filters.supplier_uuid = null
    }
  }
}
</script>
<style lang="styl" scoped>
  /deep/ .data-time-picker.no-border input.field-input{
    border: none !important;
  }
  /deep/ .filter-sorter-expanded-layout{
    align-items: flex-end;
  }
  /deep/ .filter-sorter-expanded-layout>.flex.text-no-wrap>.v-btn{
    margin: 0;
    height: 48px;
  }
  .filter-transparent{
    box-shadow: none;
  }
</style>
