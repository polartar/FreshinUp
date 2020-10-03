<template>
  <search-filter-sorter
    ref="filter"
    class="filter-transparent"
    without-filter-label
    without-sort-by
    placeholder="Filter by template title"
    color="transparent"
    v-bind="$attrs"
    v-on="$listeners"
    @run="run"
    @clear="clearFilters"
  >
    <template v-slot:expanded="slotProps">
      <v-card-text class="px-0">
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

export default {
  components: {
    FilterLabel,
    ClearButton,
    MultiSelect,
    SearchFilterSorter
  },
  props: {
    statuses: { type: Array, default: () => [] }
  },
  data () {
    return {
      status_id: []
    }
  },
  computed: {
    filters () {
      return {
        status_id: this.status_id
      }
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
      const finalParams = {
        title: params.term,
        sort: params.orderBy,
        ...this.filters
      }

      this.$emit('runFilter', finalParams)
    },
    clearFilters (params) {
      this.status_id = null
      this.run(params)
    }
  }
}
</script>
<style lang="styl" scoped>
  /deep/ .data-time-picker.no-border input.field-input {
    border: none !important;
  }

  /deep/ .filter-sorter-expanded-layout {
    align-items: center;
  }

  /deep/ .filter-sorter-expanded-layout > .flex.text-no-wrap > .v-btn {
    margin: 0;
    height: 48px;
  }

  .filter-transparent {
    box-shadow: none;
  }

  /deep/ .v-form > .container {
    padding: 0;
  }
</style>
