import { storiesOf } from '@storybook/vue'
import Payments from './Payments'
import PaymentForm from '~/components/payments/PaymentForm'
import { FIXTURE_PAYMENTS } from '../../../../tests/Javascript/__data__/payments'
import { FIXTURE_PAYMENT_STATUSES } from '../../../../tests/Javascript/__data__/paymentStatuses'
import { action } from '@storybook/addon-actions'

export const Default = () => ({
  components: { Payments },
  template: `
      <v-container>
        <payments />
      </v-container>
    `
})

export const Loading = () => ({
  components: { Payments },
  template: `
      <v-container>
        <payments
          is-loading
        />
      </v-container>
    `
})

export const Populated = () => ({
  components: { Payments, PaymentForm },
  data () {
    return {
      items: FIXTURE_PAYMENTS,
      statuses: FIXTURE_PAYMENT_STATUSES,
      newPaymentLoading: false,
      dialog: false,
      pagination: {
        rowsPerPage: 10,
        page: 1,
        totalItems: 35
      },
      sorting: {
        sortBy: 'uuid',
        descending: false
      }
    }
  },
  methods: {
    onManagePay (act, item) {
      action('onManagePay')(act, item)
    },
    onAddPayment (payload) {
      action('onAddPayment')(payload)
    }
  },
  template: `
      <v-container>
        <payments
          :items="items"
          :dialog="dialog"
          :statuses="statuses"
          :rows-per-page="pagination.rowsPerPage"
          :page="pagination.page"
          :total-items="pagination.totalItems"
          :sort-by="sorting.sortBy"
          :descending="sorting.descending"
          @dialog="dialog = $event"
          @manage-pay="onManagePay"
        >
          <template #form>
            <payment-form
              :is-loading="newPaymentLoading"
              class="ma-2"
              @cancel="dialog = false"
              @input="onAddPayment"
            />
          </template>
        </payments>
      </v-container>
    `
})

storiesOf('FoodFleet|components/fleet-members/Payments', module)
  .addParameters({
    backgrounds: [
      { name: 'default', value: '#f1f3f6', default: true }
    ]
  })
  .add('Default', Default)
  .add('Loading', Loading)
  .add('Populated', Populated)
