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
    <v-dialog
      v-model="visibilityDialog"
      scrollable
      max-width="436"
      content-class="right-dialog"
    >
      <data-visibility
        v-if="!isLoading"
        :visible-parameters="currentUser.data_visibility || defaultVisibleParameters"
        :parameters="parameters"
        @close="visibilityDialog = false"
        @save="saveParameters"
      />
    </v-dialog>
    <v-dialog
      v-model="saveDialog"
      scrollable
      max-width="436"
    >
      <save-search
        :modifiers="financialModifiers"
        @close="saveDialog = false"
        @save="onSaveSearch"
      />
    </v-dialog>
    <v-dialog
      v-model="exportDialog"
      scrollable
      max-width="400"
    >
      <export
        v-if="!isLoading"
        :transactions="transactions"
        :data-visibility="currentUser.data_visibility || defaultVisibleParameters"
        @close="exportDialog = false"
      />
    </v-dialog>
    <v-layout
      row
      wrap
      pt-3
    >
      <v-flex
        d-inline-flex
        align-center
        ma-2
      >
        <h2 class="white--text">
          {{ pageTitle }}
        </h2>
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
        <v-btn
          color="primary"
          dark
          @click.stop="visibilityDialog = true"
        >
          Data Visibility
        </v-btn>
        <v-btn
          color="primary"
          dark
          @click.stop="saveDialog = true"
        >
          Save Report
        </v-btn>
        <v-btn
          color="primary"
          dark
          @click.stop="exportDialog = true"
        >
          Export
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
            :sales="financialSummary.sales_time"
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
            v-if="!isLoading"
            :gross="financialSummary.gross"
            :net="financialSummary.net"
            :payment-type-totals="financialSummary.sales_type"
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
            v-if="!isLoading"
            :average-ticket="financialSummary.avg_ticket"
          />
        </v-card>
        <v-card class="mx-2 mt-2">
          <v-card-title class="font-weight-bold subheading">
            Sales By Method
          </v-card-title>
          <v-divider />
          <sales-by-method
            v-if="!isLoading"
            :payment-type-totals="financialSummary.sales_type"
          />
        </v-card>
      </v-flex>
    </v-layout>
    <v-layout
      row
      px-5
      py-4
    >
      <v-flex>
        <v-card>
          <v-card-title>
            <v-layout>
              <v-flex
                xs12
                sm6
              >
                <p class="font-weight-bold mt-3">
                  Showing {{ pagination.totalItems }} Results
                </p>
              </v-flex>
              <v-flex
                xs12
                sm6
              >
                <p class="mt-3 text-sm-right">
                  Tip: use the <a
                    class="primary--text underline"
                    @click.prevent="visibilityDialog = true"
                  > Data visibility
                  </a> settings to list specific parameters
                </p>
              </v-flex>
            </v-layout>
          </v-card-title>
          <transaction-list
            v-if="!isLoading"
            :transactions="transactions"
            :data-visibility="currentUser.data_visibility || defaultVisibleParameters"
            :is-loading="isLoading || isLoadingList"
            :page="pagination.page"
            :rows-per-page="pagination.rowsPerPage"
            :total-items="pagination.totalItems"
            @paginate="onPaginate"
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
import TransactionList from '~/components/financials/TransactionList.vue'
import DataVisibility from '~/components/financials/DataVisibility.vue'
import SaveSearch from '~/components/reportables/SaveSearch.vue'
import Export from '~/components/export/Export.vue'
import AverageTicket from '~/components/financials/AverageTicket.vue'
import SalesByMethod from '~/components/financials/SalesByMethod.vue'
import SalesChart from '~/components/financials/SalesChart.vue'
import TotalSales from '~/components/financials/TotalSales.vue'
import { forEach, get } from 'lodash'

const include = [
  'items',
  'store.supplier',
  'event.host',
  'event.event_tags',
  'event.location.venue',
  'customer'
]

export const VISIBILITY_OPTIONS = [
  { name: 'event_location', label: 'Event / Venue / Location' },
  { name: 'square_created_at', label: 'Creation Date' },
  { name: 'square_updated_at', label: 'Update Date' },
  { name: 'total_money', label: 'Total' },
  { name: 'total_tax_money', label: 'Tax Total' },
  { name: 'total_discount_money', label: 'Total Discount' },
  { name: 'total_service_charge_money', label: 'Total Service Charge' },
  { name: 'items', label: 'Items' },
  { name: 'event_tags', label: 'Event Tags' },
  { name: 'square_id', label: 'Square ID' },
  { name: 'store', label: 'Fleet member' },
  { name: 'store_square_id', label: 'Fleet Member Square ID' },
  { name: 'host', label: 'Customer Company' },
  { name: 'supplier', label: 'Supplier' },
  { name: 'customer', label: 'Customer name' },
  { name: 'customer_square_id', label: 'Customer Square ID' },
  { name: 'customer_reference_id', label: 'Customer Reference ID' }
]

export default {
  layout: 'admin',
  components: {
    AdvancedFilter,
    BasicFilter,
    TransactionList,
    DataVisibility,
    SaveSearch,
    Export,
    AverageTicket,
    SalesByMethod,
    SalesChart,
    TotalSales
  },
  data () {
    return {
      pageTitle: 'Financial Reports',
      advancedDialog: false,
      visibilityDialog: false,
      saveDialog: false,
      exportDialog: false,
      lastFilterParams: {
        include: include
      },
      basicFilters: {
        event_uuid: this.$route.query.event_uuid || null,
        host_uuid: this.$route.query.host_uuid || null,
        store_uuid: this.$route.query.store_uuid || null,
        supplier_uuid: this.$route.query.supplier_uuid || null,
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
      },
      defaultVisibleParameters: [
        'event_location',
        'square_created_at',
        'items',
        'total_money',
        'total_tax_money',
        'total_discount_money'
      ],
      parameters: VISIBILITY_OPTIONS
    }
  },
  computed: {
    isLoadingList () {
      return get(this.$store, 'state.transactions.pending.items', true)
    },
    ...mapGetters('page', ['isLoading']),
    ...mapGetters(['currentUser']),
    ...mapGetters('devices', { 'devices': 'items' }),
    ...mapGetters('paymentTypes', { paymentTypes: 'items' }),
    ...mapGetters('financialModifiers', { financialModifiers: 'items' }),
    ...mapGetters('financialsummary', { financialSummary: 'items' }),
    ...mapGetters('transactions', { 'transactions': 'items', pagination: 'pagination' })
  },
  methods: {
    ...mapActions('page', {
      setPageLoading: 'setLoading'
    }),
    onPaginate (value) {
      this.$store.dispatch('transactions/setPagination', { rowsPerPage: value.rowsPerPage, page: value.page })
      this.$store.dispatch('transactions/getItems', { params: { include: include } })
    },
    onSaveSearch (search) {
      let filters = {}

      const queryParams = this.$route.query

      Object.keys(queryParams).forEach(key => {
        switch (key) {
          case 'store_uuid':
            filters[key] = {
              label: 'Fleet Member',
              value: queryParams[key]
            }
            break
          case 'event_uuid':
          case 'host_uuid':
          case 'supplier_uuid':
          case 'event_tag_uuid':
          case 'location_uuid':
          case 'customer_uuid':
          case 'staff_uuid':
          case 'device_uuid':
          case 'category_uuid':
          case 'item_uuid':
          case 'payment_type_uuid':
          case 'transaction_uuid':
          case 'payment_uuid':
            filters[key] = queryParams[key]
            break
          case 'min_price':
          case 'max_price':
            const arrayPosition = key.startsWith('min_') ? 0 : 1
            const filterName = key.slice(4) + '_range'

            filters[filterName] = filters[filterName] || []
            filters[filterName][0] = filters[filterName][0] || null
            filters[filterName][1] = filters[filterName][1] || null

            filters[filterName][arrayPosition] = parseInt(queryParams[key])
            break
          default:
            filters[key] = queryParams[key]
        }
      })

      const data = {
        name: search.name,
        modifier_1_id: search.modifier_1_id,
        modifier_2_id: search.modifier_2_id,
        filters: JSON.stringify(filters)
      }

      this.$store.dispatch('financialReports/createItem', { data }).then((result) => {
        this.saveDialog = false
        this.$store.dispatch('generalMessage/setMessage', 'Saved')
      })
    },
    saveParameters (parameters) {
      let data = {
        data_visibility: parameters
      }
      this.$store.dispatch('users/updateItem', { data, params: { id: this.currentUser.id } }).then(() => {
        this.visibilityDialog = false
        this.currentUser.data_visibility = [...parameters]
        this.$store.dispatch('generalMessage/setMessage', 'Saved')
      })
    }
  },
  beforeRouteEnterOrUpdate (vm, to, from, next) {
    let filters = {}
    forEach(vm.$route.query, function (value, key) {
      filters[key] = value
    })
    Promise.all([
      vm.$store.dispatch('currentUser/getCurrentUser'),
      vm.$store.dispatch('devices/getItems'),
      vm.$store.dispatch('paymentTypes/getItems'),
      vm.$store.dispatch('financialModifiers/getItems'),
      vm.$store.dispatch('financialsummary/setFilters', filters),
      vm.$store.dispatch('financialsummary/getItems'),
      vm.$store.dispatch('transactions/setFilters', filters),
      vm.$store.dispatch('transactions/getTransactions', { params: { include: include } })
    ])
      .then(() => {
        if (next) next()
      })
      .catch((error) => console.error(error))
      .then(() => vm.setPageLoading(false))
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
