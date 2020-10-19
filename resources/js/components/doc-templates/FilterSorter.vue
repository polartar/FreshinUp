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
          <v-flex
            ml-2
          >
            <v-layout
              row
              justify-space-between
              mb-2
            >
              <filter-label>
                Last modified by
              </filter-label>
              <clear-button
                v-if="filters.updated_by_uuid"
                color="white"
                @clear="filters.updated_by_uuid = null; $refs.updatedBy.clearTerm()"
              />
            </v-layout>
            <f-autocomplete
              ref="updatedBy"
              :value="filters.updated_by_uuid"
              no-filter
              placeholder="Last modified by"
              value-fetch
              item-value="uuid"
              item-text="name"
              url="/users?filter[status]=1"
              background-color="white"
              hide-details
              class="pt-0"
              height="48"
              not-clearable
              @input="selectModifiedBy"
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
                Last modified date
              </filter-label>
              <clear-button
                v-if="filters.updated_at"
                color="white"
                @clear="filters.updated_at = null;"
              />
            </v-layout>
            <date-time-picker
              v-model="filters.updated_at"
              only-date
              format="YYYY-MM-DD"
              formatted="MM-DD-YYYY"
              input-size="lg"
              label="Last modified date "
              :color="$vuetify.theme.primary"
              :button-color="$vuetify.theme.primary"
              @input="slotProps.run"
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
import FAutocomplete from '~/components/FAutocomplete'
import DateTimePicker from '~/components/DateTimePicker'

export const DEFAULT_FILTERS = {
  status_id: [],
  title: '',
  updated_at: '',
  updated_by_uuid: ''
}
export default {
  components: {
    FilterLabel,
    ClearButton,
    MultiSelect,
    SearchFilterSorter,
    FAutocomplete,
    DateTimePicker
  },
  props: {
    statuses: { type: Array, default: () => [] },
    filters: { type: Object, default: () => DEFAULT_FILTERS }
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
        ...this.filters,
        title: params.term,
        sort: params.orderBy
      }
      this.$emit('runFilter', finalParams)
    },
    clearFilters (params) {
      this.$refs.updatedBy.clearTerm()
      this.filters.updated_by_uuid = null
      this.filters.status_id = null
      this.filters.updated_at = null
      this.run(params)
    },
    selectModifiedBy (user) {
      this.filters.updated_by_uuid = user ? user.uuid : null
      this.$refs.filter.run()
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
