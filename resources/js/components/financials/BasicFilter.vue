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
          @clear="filters.event_uuid = null; eventKey += 1"
        />
      </v-layout>
      <simple
        :key="eventKey"
        url="foodfleet/events"
        term-param="filter[name]"
        placeholder="All Events"
        background-color="white"
        class="mt-0 pt-0"
        height="48"
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
        Companies
        <clear-button
          v-if="filters.company_uuid"
          @clear="filters.company_uuid = null; companyKey += 1"
        />
      </v-layout>
      <simple
        :key="companyKey"
        url="companies"
        term-param="filter[name]"
        placeholder="All Companies"
        background-color="white"
        class="mt-0 pt-0"
        height="48"
        @input="selectCompany"
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
        Truck
        <clear-button
          v-if="filters.truck_uuid"
          @clear="filters.truck_uuid = null; truckKey += 1"
        />
      </v-layout>
      <simple
        :key="truckKey"
        url="foodfleet/fleet-members"
        term-param="filter[name]"
        placeholder="All Trucks"
        background-color="white"
        class="mt-0 pt-0"
        height="48"
        @input="selectTruck"
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
        Customer
        <clear-button
          v-if="filters.customer_uuid"
          @clear="filters.customer_uuid = null; customerKey += 1"
        />
      </v-layout>
      <simple
        :key="customerKey"
        url="companies?filter[type_key]=contractor"
        term-param="filter[name]"
        placeholder="All Customers"
        background-color="white"
        class="mt-0 pt-0"
        height="48"
        @input="selectCustomer"
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
      companyKey: 0,
      truckKey: 0,
      customerKey: 0,
      range: null
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
      return '/admin/transactions?' + encodeURI(preparedParams.slice(1))
    }
  },
  methods: {
    selectEvent (event) {
      this.filters.event_uuid = event ? event.uuid : null
    },
    selectCompany (company) {
      this.filters.company_uuid = company ? company.uuid : null
    },
    selectTruck (truck) {
      this.filters.fleet_member_uuid = truck ? truck.uuid : null
    },
    selectCustomer (customer) {
      this.filters.contractor_uuid = customer ? customer.uuid : null
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
