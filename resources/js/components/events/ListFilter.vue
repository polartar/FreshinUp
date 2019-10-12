<template>
  <search-filter-sorter
    ref="filter"
    expanded
    without-filter-label
    without-sort-by
    placeholder="Filter by event name"
    color="transparent"
    v-bind="$attrs"
    v-on="$listeners"
    @run="run"
    @clear="clearFilters"
  >
    <template v-slot:expanded="slotProps">
      <v-card-text class="no-padding-left">
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
              <div class="filter-simple-label font-weight-bold white--text nowrap">
                Statuses
              </div>
              <clear-button
                color="white"
                v-if="filters.status.length > 0"
                @clear="filters.status = [];"
              />
            </v-layout>
            <multi-select
              v-model="filters.status"
              placeholder="Select Status"
              :items="statuses"
              item-value="id"
              item-text="name"
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
              <div class="filter-simple-label font-weight-bold white--text nowrap">
                Customers
              </div>
              <clear-button
                color="white"
                v-if="filters.host_uuid"
                @clear="filters.host_uuid = null; $refs.host.resetTerm()"
              />
            </v-layout>
            <simple
              ref="host"
              url="companies?filter[type_key]=host"
              term-param="filter[name]"
              placeholder="Search Customer"
              background-color="white"
              class="mt-0 pt-0"
              height="48"
              solo
              flat
              @input="selectHost"
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
              <div class="filter-simple-label font-weight-bold white--text nowrap">
                Managed By
              </div>
              <clear-button
                color="white"
                v-if="filters.manager_uuid"
                @clear="filters.manager_uuid = null; $refs.manager.resetTerm()"
              />
            </v-layout>
            <simple
              ref="manager"
              url="users"
              term-param="filter[name]"
              placeholder="Search Manager"
              background-color="white"
              class="mt-0 pt-0"
              height="48"
              solo
              flat
              @input="selectManager"
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
              <div class="filter-simple-label font-weight-bold white--text nowrap">
                Scheduled Date
              </div>
              <clear-button
                color="white"
                v-if="filters.start_at || filters.end_at"
                @clear="filters.start_at = filters.end_at = rangeDate = null"
              />
            </v-layout>
            <vue-ctk-date-time-picker
              v-model="rangeDate"
              class="data-time-picker no-border"
              range
              only-date
              format="YYYY-MM-DD"
              formatted="MM-DD-YYYY"
              input-size="lg"
              label="Scheduled Date"
              :color="$vuetify.theme.primary"
              :button-color="$vuetify.theme.primary"
              @input="changeDate"
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
              <div class="filter-simple-label font-weight-bold white--text">
                Tags
              </div>
              <clear-button
                color="white"
                v-if="filters.event_tag_uuid"
                @clear="filters.event_tag_uuid = null; $refs.tag.resetTerm()"
              />
            </v-layout>
            <simple
              ref="tag"
              url="foodfleet/event-tags"
              term-param="filter[name]"
              placeholder="Search Tag"
              background-color="white"
              class="mt-0 pt-0"
              height="48"
              solo
              flat
              @input="selectTag"
            />
          </v-flex>
        </v-layout>
      </v-card-text>
    </template>
  </search-filter-sorter>
</template>

<script>
import 'vue-ctk-date-time-picker/dist/vue-ctk-date-time-picker.css'
import ClearButton from '~/components/ClearButton'
import Simple from 'fresh-bus/components/search/simple'
import VueCtkDateTimePicker from 'vue-ctk-date-time-picker'
import SearchFilterSorter from 'fresh-bus/components/search/filter-sorter'
import MultiSelect from '~/components/events/MultiSelect'
export default {
  components: {
    Simple,
    ClearButton,
    MultiSelect,
    VueCtkDateTimePicker,
    SearchFilterSorter
  },
  props: {
    filters: {
      type: Object,
      default: () => ({
        status: [],
        host_uuid: null,
        manager_uuid: null,
        event_tag_uuid: null,
        start_at: null,
        end_at: null
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
        sort: params.orderBy,
        ...this.filters
      }
      this.$emit('runFilter', finalParams)
    },
    selectHost (host) {
      this.filters.host_uuid = host ? host.uuid : null
    },
    selectManager (manager) {
      this.filters.manager_uuid = manager ? manager.uuid : null
    },
    selectTag (eventTag) {
      this.filters.event_tag_uuid = eventTag ? eventTag.uuid : null
    },
    changeDate () {
      this.filters.start_at = this.rangeDate ? this.rangeDate.start : null
      this.filters.end_at = this.rangeDate ? this.rangeDate.end : null
    },
    clearFilters (params) {
      this.filters.host_uuid = this.filters.manager_uuid = this.filters.event_tag_uuid = this.filters.start_at = this.filters.end_at = this.rangeDate = null
      this.filters.status = []
      this.$refs.tag.resetTerm()
      this.$refs.host.resetTerm()
      this.$refs.manager.resetTerm()
    }
  }
}
</script>
<style>
  .filter-simple-label{
    font-size: 13px;
    line-height: 22px;
  }
  .no-padding-left{
    padding-left: 0;
  }
  .data-time-picker.no-border input.field-input{
    border: none !important;
  }
  .nowrap{
    white-space: nowrap;
    text-overflow: ellipsis;
    overflow: hidden;
  }
</style>
