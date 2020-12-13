import { storiesOf } from '@storybook/vue'
import { action } from '@storybook/addon-actions'
import SupplierFleets from './SupplierFleets'
import { FIXTURE_STORE_STATUS_STATS } from '../../../../tests/Javascript/__data__/storeStatusStats'
import { FIXTURE_STORE_STATUSES } from '../../../../tests/Javascript/__data__/storeStatuses'
import { FIXTURE_STORES } from '../../../../tests/Javascript/__data__/stores'

export const Default = () => ({
  components: { SupplierFleets },
  template: `
      <v-container>
        <supplier-fleets/>
      </v-container>
    `
})

export const Loading = () => ({
  components: { SupplierFleets },
  template: `
      <v-container>
        <supplier-fleets
          is-loading
        />
      </v-container>
    `
})

export const Populated = () => ({
  components: { SupplierFleets },
  data () {
    return {
      storeStatuses: FIXTURE_STORE_STATUSES,
      stores: FIXTURE_STORES,
      statusStats: FIXTURE_STORE_STATUS_STATS
    }
  },
  methods: {
    changeStatus (value, item) {
      action('change-status')(value, item)
    },
    manage (action_, item) {
      action('manage')(action_, item)
    },
    manageMultiple (action_, items, status) {
      action('manage-multiple')(action_, items, status)
    }
  },
  template: `
      <v-container>
        <supplier-fleets
          :stores="stores"
          :store-statuses="storeStatuses"
          :status-stats="statusStats"
          @change-status="changeStatus"
          @manage="manage"
          @manage-multiple="manageMultiple"
        />
      </v-container>
    `
})

storiesOf('FoodFleet|components/supplier/SupplierFleets', module)
  .add('Default', Default)
  .add('Loading', Loading)
  .add('Populated', Populated)
