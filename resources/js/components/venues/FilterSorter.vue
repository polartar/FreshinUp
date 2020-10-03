<template>
  <search-filter-sorter
    ref="filter"
    class="filter-transparent"
    without-filter-label
    without-sort-by
    placeholder="Filter by template name"
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
                Owner
              </filter-label>
              <clear-button
                v-if="owner_uuid"
                color="white"
                @clear="owner_uuid = null; $refs.owner.clearTerm()"
              />
            </v-layout>
            <f-autocomplete
              ref="owner"
              :value="owner_uuid"
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

export default {
  components: {
    FilterLabel,
    ClearButton,
    MultiSelect,
    SearchFilterSorter,
    FAutocomplete
  },
  props: {
    statuses: { type: Array, default: () => [] }
  },
  data () {
    return {
      owner_uuid: null,
      status_id: null
    }
  },
  computed: {
    filters () {
      return {
        owner_uuid: this.owner_uuid,
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
    selectOwner (user) {
      this.owner_uuid = user ? user.uuid : null
    },
    run (params) {
      const finalParams = {
        name: params.term,
        ...this.filters
      }
      this.$emit('runFilter', finalParams)
    },
    clearFilters () {
      this.$refs.owner.clearTerm()
      this.status_id = null
      this.owner_uuid = null
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
</style>
