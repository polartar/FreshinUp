<template>
  <div>
    <v-dialog
      v-model="advancedDialog"
      scrollable
      max-width="436"
      content-class="right-dialog"
    >
      <advanced-filter
        v-if="!isLoading"
        :max-width-bottom-row="422"
        :base-filters="basicFilters"
        :advanced-filters="advancedFilters"
        :payment-types="paymentTypes"
        :devices="devices"
        @close="advancedDialog = false"
      />
    </v-dialog>
    <v-layout
      row
      wrap
    >
      <v-flex
        d-inline-flex
        align-center
        ma-2
      >
        <h2>{{ pageTitle }}</h2>
      </v-flex>
      <v-flex
        text-xs-right
      >
        <v-btn
          color="primary"
          dark
          @click.stop="advancedDialog = true"
        >
          Advanced Filters
        </v-btn>
      </v-flex>
    </v-layout>
    <v-divider />

    <br>
    <v-card
      class="pa-4"
    >
      <basic-filter
        v-if="!isLoading"
        :filters="basicFilters"
      />
    </v-card>

    <v-layout
      row
      px-5
      py-2
      wrap
    >
      <v-flex
        xs12
        sm12
        md6
      >
        <v-card class="mx-2">
          <v-card-title class="font-weight-bold subheading">
            Total Sales / Time
          </v-card-title>
          <v-divider />
          <sales-chart
            v-if="!isLoading"
            :sales="sales"
          />
        </v-card>
      </v-flex>
      <v-flex
        xs12
        sm12
        md3
      >
        <v-card class="mx-2">
          <v-card-title class="font-weight-bold subheading">
            Total Sales
          </v-card-title>
          <v-divider />
          <total-sales
            :gross="gross"
            :net="net"
            :payment-type-totals="paymentTypeTotals"
          />
        </v-card>
      </v-flex>
      <v-flex
        xs12
        sm12
        md3
      >
        <v-card class="mx-2">
          <v-card-title class="font-weight-bold subheading">
            AVG. Ticket
          </v-card-title>
          <v-divider />
          <average-ticket
            :average-ticket="avgTicket"
          />
        </v-card>
        <v-card class="mx-2 mt-2">
          <v-card-title class="font-weight-bold subheading">
            Sales By Method
          </v-card-title>
          <v-divider />
          <sales-by-method
            :payment-type-totals="paymentTypeTotals"
          />
        </v-card>
      </v-flex>
    </v-layout>
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'
import AdvancedFilter from '~/components/financials/AdvancedFilter.vue'
import BasicFilter from '~/components/financials/BasicFilter.vue'
import AverageTicket from '~/components/financials/AverageTicket.vue'
import SalesByMethod from '~/components/financials/SalesByMethod.vue'
import SalesChart from '~/components/financials/SalesChart.vue'
import TotalSales from '~/components/financials/TotalSales.vue'
import moment from 'moment'

// https://github.com/FreshinUp/foodfleet/issues/56

function getFirstDayOfYear () {
  let thisYear = (new Date()).getFullYear()
  return moment(thisYear + '-01-01')
}

function getDates (startDate, dateEnd) {
  let dateArray = []
  let currentDate = startDate ? moment(startDate) : getFirstDayOfYear()
  let stopDate = dateEnd ? moment(dateEnd) : moment()
  while (currentDate <= stopDate) {
    dateArray.push(moment(currentDate).format('YYYY-MM-DD'))
    currentDate = moment(currentDate).add(1, 'days')
  }
  return dateArray
}

function stubSales (dateStart, dateEnd) {
  let dateArray = getDates(dateStart, dateEnd)
  return dateArray.map((item) => {
    return { value: Math.floor(Math.random() * (20000 - 10000 + 1) + 10000), date: item }
  })
}

export default {
  layout: 'admin',
  components: {
    AdvancedFilter,
    BasicFilter,
    AverageTicket,
    SalesByMethod,
    SalesChart,
    TotalSales
  },
  data () {
    return {
      pageTitle: 'Financial Reports',
      advancedDialog: false,
      lastFilterParams: {},
      basicFilters: {
        event_uuid: this.$route.query.event_uuid || null,
        company_uuid: this.$route.query.company_uuid || null,
        fleet_member_uuid: this.$route.query.fleet_member_uuid || null,
        contractor_uuid: this.$route.query.contractor_uuid || null,
        date_after: this.$route.query.date_after || null,
        date_before: this.$route.query.date_before || null
      },
      advancedFilters: {
        event_tag_uuid: this.$route.query.event_tag_uuid || null,
        location_uuid: this.$route.query.location_uuid || null,
        customer_uuid: this.$route.query.customer_uuid || null,
        staff_uuid: this.$route.query.staff_uuid || null,
        device_uuid: this.$route.query.device_uuid || null,
        category_uuid: this.$route.query.category_uuid || null,
        item_uuid: this.$route.query.item_uuid || null,
        min_price: Number(this.$route.query.min_price) || null,
        max_price: Number(this.$route.query.max_price) || null,
        payment_type_uuid: this.$route.query.payment_type_uuid || null,
        transaction_uuid: this.$route.query.transaction_uuid || null,
        payment_uuid: this.$route.query.payment_uuid || null
      }
    }
  },
  computed: {
    ...mapGetters('page', ['isLoading']),
    ...mapGetters('devices', { 'devices': 'items' }),
    ...mapGetters('paymentTypes', { paymentTypes: 'items' }),
    gross () {
      return Math.floor(Math.random() * (200000 - 100000 + 1) + 100000)
    },
    net () {
      return Math.floor(this.gross * 0.80)
    },
    paymentTypeTotals () {
      return [
        { name: 'VISA', value: Math.floor(this.gross * 0.20) },
        { name: 'MASTERCARD', value: Math.floor(this.gross * 0.23) },
        { name: 'AMEX', value: Math.floor(this.gross * 0.30) },
        { name: 'CASH', value: Math.floor(this.gross * 0.27) }
      ]
    },
    avgTicket () {
      return Math.floor(this.gross / (Math.random() * (50 - 10 + 1) + 10))
    },
    sales () {
      return stubSales(this.$route.query.date_after, this.$route.query.date_before)
    }
  },
  methods: {
    ...mapActions('page', {
      setPageLoading: 'setLoading'
    })
  },
  beforeRouteEnterOrUpdate (vm, to, from, next) {
    vm.setPageLoading(true)
    Promise.all([
      vm.$store.dispatch('devices/getItems'),
      vm.$store.dispatch('paymentTypes/getItems')
    ]).then(() => {
      vm.$store.dispatch('page/setLoading', false)
      if (next) next()
    })
  }
}
</script>

<style scoped>
  /deep/ .right-dialog  {
    bottom: 0;
    right: 0;
    position: absolute;
    margin-right: 0px;
    margin-bottom: 0px;
  }
</style>
