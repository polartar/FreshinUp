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
    >
      <v-flex>
        <v-card>
          <v-card-title>
            <p class="font-weight-bold mt-3">
              Your Saved Financial Reports
            </p>
          </v-card-title>
          <reportable-filter
            @runFilter="filterReportables"
          />
          <reportable-list
            v-if="!isLoading"
            :reportables="financialReports"
            :selectables="selectables"
            :is-loading="isLoading || isLoadingList"
            :rows-per-page="pagination.rowsPerPage"
            :page="pagination.page"
            :total-items="pagination.totalItems"
            :sort-by="sorting.sortBy"
            :descending="sorting.descending"
            base-url="/admin/financials/transactions"
            must-sort
            @paginate="onPaginate"
            @delete="deleteReport"
            @deleteMultiple="deleteMultipleReports"
          />
        </v-card>
      </v-flex>
    </v-layout>

    <v-dialog
      v-model="deleteReportDialog"
      max-width="500"
    >
      <simple-confirm
        :class="{ 'deleting': deletablesProcessing }"
        :title="deleteDialogTitle"
        ok-label="Yes"
        cancel-label="No"
        @ok="deleteReports"
        @cancel="deleteReportDialog = false"
      >
        <div class="py-5 px-2">
          <template v-if="deletablesProcessing">
            <div class="text-xs-center">
              <p class="subheading">
                Processing, please wait...
              </p>
              <v-progress-circular
                :rotate="-90"
                :size="200"
                :width="15"
                :value="deletablesProgress"
                color="primary"
              >
                {{ deletablesStatus }}
              </v-progress-circular>
            </div>
          </template>
          <template v-else>
            <p class="subheading">
              <span v-if="deletables.length < 2">Report</span>
              <span v-else> Reports</span>
              : {{ deleteableNames }}
            </p>
          </template>
        </div>
      </simple-confirm>
    </v-dialog>
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'
import AdvancedFilter from '~/components/financials/AdvancedFilter.vue'
import BasicFilter from '~/components/financials/BasicFilter.vue'
import ReportableFilter from '~/components/reportables/Filter.vue'
import ReportableList from '~/components/reportables/ReportableList.vue'
import simpleConfirm from 'fresh-bus/components/SimpleConfirm.vue'
import { deletables } from 'fresh-bus/components/mixins/Deletables'
import get from 'lodash/get'

export default {
  layout: 'admin',
  components: {
    simpleConfirm,
    AdvancedFilter,
    BasicFilter,
    ReportableFilter,
    ReportableList
  },
  mixins: [deletables],
  data () {
    return {
      pageTitle: 'Financial Reports',
      advancedDialog: false,
      deleteReportDialog: false,
      deleteUserDialog: false,
      lastFilterParams: {},
      basicFilters: {
        event_uuid: null,
        host_uuid: null,
        store_uuid: null,
        supplier_uuid: null,
        date_after: null,
        date_before: null
      },
      advancedFilters: {
        event_tag_uuid: null,
        location_uuid: null,
        customer_uuid: null,
        staff_uuid: null,
        device_uuid: null,
        category_uuid: null,
        item_uuid: null,
        min_price: null,
        max_price: null,
        payment_type_uuid: null,
        transaction_uuid: null,
        payment_uuid: null
      }
    }
  },
  computed: {
    isLoadingList () {
      return get(this.$store, 'state.financialReports.pending.items', true)
    },
    ...mapGetters('financialReports', {
      financialReports: 'items',
      pagination: 'pagination',
      sorting: 'sorting',
      sortBy: 'sortBy'
    }),
    ...mapGetters('page', ['isLoading']),
    ...mapGetters('devices', { 'devices': 'items' }),
    ...mapGetters('paymentTypes', { paymentTypes: 'items' }),
    deleteDialogTitle () {
      return this.deletables.length < 2 ? 'Are you sure you want to delete this report?' : 'Are you sure you want to delete the following reports?'
    },
    deleteableNames () {
      return this.deletables.map((report) => {
        return report.name
      }).join(', ')
    },
    selectables () {
      return {
        'devices': this.devices,
        'payment_types': this.paymentTypes
      }
    }
  },
  methods: {
    ...mapActions('page', {
      setPageLoading: 'setLoading'
    }),
    deleteReportDialogUp (report) {
      this.deleteReportDialog = true
      this.report = report
    },
    deleteReport (report) {
      this.deleteDialogUp(report)
    },
    async deleteReports () {
      this.deletablesProcessing = true
      this.deletablesProgress = 0
      this.deletablesStatus = ''
      let dispatcheables = []

      this.deletables.forEach((report) => {
        dispatcheables.push(this.$store.dispatch('financialReports/deleteItem', { getItems: false, params: { id: report.id } }))
      })

      let chunks = this.chunk(dispatcheables, this.deletablesParrallelRequest)
      let doneCount = 0

      for (let i in chunks) {
        await Promise.all(chunks[i])
        doneCount += chunks[i].length
        this.deletablesStatus = doneCount + ' / ' + this.deletables.length + ' Done'
        this.deletablesProgress = doneCount / this.deletables.length * 100
        await this.sleep(this.deletablesSleepTime)
      }

      this.filterReportables(this.lastFilterParams)
      await this.sleep(500)
      this.deleteReportDialog = false
      this.deletaReportProcessing = false
    },
    deleteMultipleReports (reports) {
      this.deleteDialogUp(reports)
    },
    deleteDialogUp (reports) {
      if (!Array.isArray(reports)) {
        reports = [reports]
      }
      this.deleteReportDialog = true
      this.deletables = reports
    },
    onPaginate (value) {
      this.$store.dispatch('financialReports/setPagination', value)
      this.$store.dispatch('financialReports/getItems')
    },
    filterReportables (params) {
      this.lastFilterParams = params
      this.$store.dispatch('financialReports/setFilters', {
        ...this.lastFilterParams
      })
      this.$store.dispatch('financialReports/getItems')
    }
  },
  beforeRouteEnterOrUpdate (vm, to, from, next) {
    vm.setPageLoading(true)
    Promise.all([
      vm.$store.dispatch('devices/getItems'),
      vm.$store.dispatch('paymentTypes/getItems'),
      vm.$store.dispatch('financialReports/getItems')
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
