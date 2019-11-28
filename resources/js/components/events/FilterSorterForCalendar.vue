<template>
  <search-filter-sorter
    ref="filter"
    class="filter-transparent"
    without-filter-label
    without-sort-by
    without-term
    color="transparent"
    v-bind="$attrs"
    v-on="$listeners"
    @run="run"
  >
    <template v-slot:title>
      <h3 class="headline grey--text">
        Filter events
      </h3>
      <v-divider light />
    </template>
    <template v-slot:filters="slotProps">
      <v-card-text class="px-0 pb-0">
        <div
          class="mr-2 ml-2"
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
                class="grey--text"
              >
                EVENT NAME
              </filter-label>
            </v-layout>
            <v-text-field
              v-model="filters.name"
              placeholder=""
              solo
              single-line
              hide-details
            />
          </v-flex>
          <v-flex
            mt-3
          >
            <v-layout
              row
              justify-space-between
              align-center
              mb-2
            >
              <filter-label
                class="grey--text"
              >
                STATUSES
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
              hide-details
            />
          </v-flex>
          <v-flex
            mt-3
          >
            <v-layout
              row
              justify-space-between
              mb-2
            >
              <filter-label
                class="grey--text"
              >
                MANAGED BY
              </filter-label>
              <clear-button
                v-if="filters.manager_uuid && filters.manager_uuid.length > 0"
                color="white"
                @clear="filters.manager_uuid = null; $refs.manager.resetTerm()"
              />
            </v-layout>
            <multi-simple
              ref="manager"
              v-model="filters.manager_uuid"
              url="users?filter[type]=1"
              placeholder="Select"
              background-color="white"
              class="mt-0 pt-0"
              height="48"
              not-clearable
              solo
            />
          </v-flex>
          <v-flex
            mt-3
          >
            <v-layout
              row
              justify-space-between
              mb-2
            >
              <filter-label
                class="grey--text"
              >
                CUSTOMERS
              </filter-label>
              <clear-button
                v-if="filters.host_uuid && filters.host_uuid.length > 0"
                color="white"
                @clear="filters.host_uuid = null; $refs.host.resetTerm()"
              />
            </v-layout>
            <multi-simple
              ref="host"
              v-model="filters.host_uuid"
              url="companies?filter[type_key]=host"
              term-param="filter[name]"
              results-id-key="uuid"
              placeholder="Select"
              background-color="white"
              class="mt-0 pt-0"
              height="48"
              not-clearable
              solo
            />
          </v-flex>
        </div>
        <div
          class="ma-2 mt-5"
        >
          <v-btn
            color="grey"
            class="white--text"
            block
            @click="clearFilters"
          >
            Clear all Filters
          </v-btn>
        </div>
      </v-card-text>
    </template>
  </search-filter-sorter>
</template>

<script>
import 'vue-ctk-date-time-picker/dist/vue-ctk-date-time-picker.css'
import ClearButton from '~/components/ClearButton'
import FilterLabel from '~/components/FilterLabel'
import SearchFilterSorter from 'fresh-bus/components/search/filter-sorter'
import MultiSelect from 'fresh-bus/components/ui/FMultiSelect'
import MultiSimple from 'fresh-bus/components/ui/FMultiSimple'
export default {
  components: {
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
        name: '',
        status_id: null,
        host_uuid: null,
        manager_uuid: null
      })
    },
    statuses: {
      type: Array,
      default: () => []
    }
  },
  data () {
    return {
      rangeDate: null
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
      if (this.filters.host_uuid) {
        finalParams.host_uuid = this.filters.host_uuid.map(item => item.uuid)
      }
      if (this.filters.manager_uuid) {
        finalParams.manager_uuid = this.filters.manager_uuid.map(item => item.uuid)
      }
      this.$emit('runFilter', finalParams)
    },
    clearFilters (params) {
      this.$refs.host.resetTerm()
      this.$refs.manager.resetTerm()
      this.filters.name = ''
      this.filters.status_id = this.filters.host_uuid = this.filters.manager_uuid = null
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
