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
                v-if="status_id && status_id.length > 0"
                color="white"
                @clear="status_id = null;"
              />
            </v-layout>
            <multi-select
              v-model="status_id"
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
                v-if="updated_by_uuid"
                color="white"
                @clear="updated_by_uuid = null; $refs.updatedBy.clearTerm()"
              />
            </v-layout>
            <f-autocomplete
              ref="updatedBy"
              :value="updated_by_uuid"
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
                v-if="updated_at"
                color="white"
                @clear="updated_at = null;"
              />
            </v-layout>
            <date-time-picker
              v-model="updated_at"
              only-date
              format="YYYY-MM-DD"
              formatted="MM-DD-YYYY"
              range
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
import MapValueKeysToData from '../../mixins/MapValueKeysToData'

export const DEFAULT_FILTERS = {
  status_id: [],
  title: '',
  updated_at: '',
  updated_by: ''
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
    // Override value prop from MapValueKeysToData to set the default value
    value: { type: Object, default: () => DEFAULT_FILTERS }
  },
  data () {
    return {
      ...DEFAULT_FILTERS
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
  mixins: [MapValueKeysToData],
  methods: {
    run (params) {
      const finalParams = {
        title: params.term,
        sort: params.orderBy,
        ...this.value
      }
      this.$emit('runFilter', finalParams)
    },
    clearFilters (params) {
      this.title = null
      this.status_id = null
      this.updated_at = null
      this.updated_by_uuid = null
      this.run(params)
    },
    selectModifiedBy (user) {
      this.updated_by_uuid = user ? user.uuid : null
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
