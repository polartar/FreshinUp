<template>
  <v-layout
    row
    wrap
  >
    <v-flex
      sm2
    >
      <v-layout
        row
        justify-space-between
        mb-2
      >
        Events
        <clear-button
          v-if="filters.event_uuid"
          @clear="filters.event_uuid = null; $refs.event.resetTerm()"
        />
      </v-layout>
      <simple
        ref="event"
        url="foodfleet/events"
        term-param="filter[name]"
        results-id-key="uuid"
        :value="filters.event_uuid"
        placeholder="All Events"
        background-color="white"
        class="mt-0 pt-0"
        height="48"
        not-clearable
        @input="selectEvent"
      />
    </v-flex>
    <v-flex
      sm2
      :class="{'px-2': $vuetify.breakpoint.smAndUp, 'mt-3': $vuetify.breakpoint.xs}"
    >
      <v-layout
        row
        justify-space-between
        mb-2
      >
        Date range
        <clear-button
          v-if="filters.date_after || filters.date_before"
          @clear="filters.date_after = filters.date_before = range = null"
        />
      </v-layout>
      <vue-ctk-date-time-picker
        v-model="range"
        only-date
        range
        format="YYYY-MM-DD"
        formatted="MM-DD-YYYY"
        input-size="lg"
        label="Select date"
        :color="$vuetify.theme.primary"
        :button-color="$vuetify.theme.primary"
        @input="changeDate"
      />
    </v-flex>
    <v-flex
      sm2
      :class="{'px-2': $vuetify.breakpoint.smAndUp}"
    >
      <v-layout
        row
        justify-space-between
        mb-2
      >
        Customer Companies
        <clear-button
          v-if="filters.host_uuid"
          @clear="filters.host_uuid = null; $refs.host.resetTerm()"
        />
      </v-layout>
      <!-- https://github.com/FreshinUp/foodfleet/issues/117 -->
      <simple
        ref="host"
        url="companies?filter[type_key]=host"
        term-param="filter[name]"
        placeholder="All Customer Companies"
        background-color="white"
        class="mt-0 pt-0"
        height="48"
        not-clearable
        @input="selectHost"
      />
    </v-flex>
    <v-flex
      sm2
      :class="{'px-2': $vuetify.breakpoint.smAndUp, 'mt-3': $vuetify.breakpoint.xs}"
    >
      <v-layout
        row
        justify-space-between
        mb-2
      >
        Fleet members
        <clear-button
          v-if="filters.store_uuid"
          @clear="filters.store_uuid = null; $refs.store.resetTerm()"
        />
      </v-layout>
      <simple
        ref="store"
        url="foodfleet/stores"
        term-param="filter[name]"
        results-id-key="uuid"
        :value="filters.store_uuid"
        placeholder="All fleet members"
        background-color="white"
        class="mt-0 pt-0"
        height="48"
        not-clearable
        @input="selectStore"
      />
    </v-flex>
    <v-flex
      sm2
      :class="{'px-2': $vuetify.breakpoint.smAndUp, 'mt-3': $vuetify.breakpoint.xs}"
    >
      <v-layout
        row
        justify-space-between
        mb-2
      >
        Suppliers
        <clear-button
          v-if="filters.supplier_uuid"
          @clear="filters.supplier_uuid = null; $refs.supplier.resetTerm()"
        />
      </v-layout>
      <!-- https://github.com/FreshinUp/foodfleet/issues/117 -->
      <simple
        ref="supplier"
        url="companies?filter[type_key]=supplier"
        term-param="filter[name]"
        placeholder="All Suppliers"
        background-color="white"
        class="mt-0 pt-0"
        height="48"
        not-clearable
        @input="selectSupplier"
      />
    </v-flex>
    <v-flex
      sm2
      pt-4
      :class="{'pl-2': $vuetify.breakpoint.smAndUp}"
      fill-height
      justify-end
    >
      <v-btn
        large
        class="primary"
        :href="searchLink"
        :class="{'mx-0': $vuetify.breakpoint.xsOnly}"
      >
        Generate
      </v-btn>
    </v-flex>
  </v-layout>
</template>

<script>
import ClearButton from '~/components/ClearButton.vue'
import Simple from 'fresh-bus/components/search/simple'
import VueCtkDateTimePicker from 'vue-ctk-date-time-picker'
import 'vue-ctk-date-time-picker/dist/vue-ctk-date-time-picker.css'
import reduce from 'lodash/reduce'

export default {
  components: { ClearButton, Simple, VueCtkDateTimePicker },
  props: {
    filters: {
      type: Object,
      default: () => ({})
    }
  },
  data () {
    return {
      eventKey: 0,
      hostKey: 0,
      storeKey: 0,
      supplierKey: 0,
      range: {
        start: this.filters.date_after,
        end: this.filters.date_before
      }
    }
  },
  computed: {
    searchLink () {
      let preparedParams = reduce(this.filters, (result, value, key) => {
        let param = ''
        if (value) {
          param = '&' + key + '=' + value
        }
        return result + param
      }, '')
      return '/admin/financials/transactions?' + encodeURI(preparedParams.slice(1))
    }
  },
  methods: {
    selectEvent (event) {
      this.filters.event_uuid = event ? event.uuid : null
    },
    selectHost (host) {
      this.filters.host_uuid = host ? host.uuid : null
    },
    selectStore (store) {
      this.filters.store_uuid = store ? store.uuid : null
    },
    selectSupplier (supplier) {
      this.filters.supplier_uuid = supplier ? supplier.uuid : null
    },
    changeDate () {
      this.filters.date_after = this.range ? this.range.start : null
      this.filters.date_before = this.range ? this.range.end : null
    }
  }
}
</script>
<style lang="styl" scoped>
  /deep/ .v-text-field > .v-input__control > .v-input__slot:before {
    border-style: hidden;
  }
  /deep/ .v-text-field--box > .v-input__control > .v-input__slot {
    min-height: 0;
  }
  /deep/ .v-autocomplete .v-input__slot {
    padding: 0 12px
  }
</style>
