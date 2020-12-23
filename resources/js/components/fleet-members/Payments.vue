<template>
  <v-card>
    <v-card-title class="py-1">
      <v-layout
        align-center
        justify-space-between
        row
      >
        <v-flex>
          <h3 class="grey--text">
            Payments
          </h3>
        </v-flex>
        <v-flex shrink>
          <v-dialog
            :value="dialog"
            max-width="600"
            @input="$emit('dialog', $event)"
          >
            <template v-slot:activator="{ on }">
              <v-btn
                slot="activator"
                color="primary"
                text
                @click="$emit('dialog', true)"
              >
                <v-icon
                  left
                >
                  add_circle_outline
                </v-icon>Request New Payment
              </v-btn>
            </template>
            <v-card>
              <div class="d-flex justify-space-between align-center">
                <v-card-text class="grey--text subheading font-weight-bold">
                  Request New Payment
                </v-card-text>
                <v-btn
                  small
                  round
                  depressed
                  color="grey"
                  class="white--text"
                  @click="$emit('dialog', false)"
                >
                  <v-flex>
                    <v-icon
                      small
                      class="white--text"
                    >
                      fa fa-times
                    </v-icon>
                  </v-flex>
                  <v-flex>
                    Close
                  </v-flex>
                </v-btn>
              </div>
              <v-divider />
              <slot
                v-if="dialog"
                name="form"
              />
            </v-card>
          </v-dialog>
        </v-flex>
      </v-layout>
    </v-card-title>
    <v-divider />
    <v-layout>
      <v-flex xs12>
        <f-data-table
          :headers="headers"
          :items="items"
          :is-loading="isLoading"
          :item-actions="itemActions"
          :multi-item-actions="multipleItemActions"
          item-key="uuid"
          v-bind="$attrs"
          v-on="$listeners"
        >
          <template v-slot:item-inner-status_id="{ item }">
            <status-select
              :value="item.status_id"
              :options="statuses"
              @input="changeStatus($event, item)"
            />
          </template>
          <template v-slot:item-inner-event="{ item }">
            <div class="subheading primary--text">
              {{ get(item, 'event.name') }}
            </div>
            <div class="grey--text">
              {{ formatDate(get(item, 'event.start_at'), 'MMM DD, YYYY') }}
            </div>
            <div class="grey--text">
              {{ get(item, 'event.location.name') | prefixStr }}
            </div>
          </template>
          <template v-slot:item-inner-name="{ item }">
            <div class="grey--text">
              {{ get(item, 'name') }}
            </div>
          </template>
          <template v-slot:item-inner-due_date="{ item }">
            <div class="grey--text">
              {{ formatDate(get(item, 'due_date'), 'MMM DD, YYYY') }}
            </div>
          </template>
          <template v-slot:item-inner-amount_money="{ item }">
            <div class="grey--text">
              {{ formatMoney(get(item, 'amount_money'), { format: '$0,0.00', precision: 4 }) }}
            </div>
          </template>
          <template v-slot:item-inner-manage="{ item }">
            <v-btn
              v-show="processable(item)"
              depressed
              disabled
              color="primary"
              @click="process(item)"
            >
              {{ manageLabel(item) }}
            </v-btn>
          </template>
        </f-data-table>
      </v-flex>
    </v-layout>
  </v-card>
</template>
<script>
import get from 'lodash/get'
import FormatMoney from '@freshinup/core-ui/src/mixins/FormatMoney'
import FormatDate from '@freshinup/core-ui/src/mixins/FormatDate'
import FDataTable from '@freshinup/core-ui/src/components/FDataTable'
import StatusSelect from './StatusSelect'
import { PAYMENT_STATUS } from '../../store/modules/paymentStatuses'

export const HEADERS = [
  { text: 'Status', value: 'status_id' },
  { text: 'Event name', value: 'event', sortable: false },
  { text: 'Payment name', value: 'name' },
  { text: 'Due date', value: 'due_date' },
  { text: 'Amount', value: 'amount_money' },
  { text: 'Manage', value: 'manage', sortable: false }
]

export const DEFAULT_ITEM_ACTIONS = [
  { action: 'cancel', text: 'Cancel' }
]

export const DEFAULT_MULTIPLE_ITEM_ACTIONS = [
  { action: 'cancel', text: 'Cancel' },
  { action: 'delete', text: 'Delete' }
]

export default {
  components: { FDataTable, StatusSelect },
  filters: {
    prefixStr: function (value, prefix = '@') {
      if (!value) return ''
      return value ? `${prefix} ${value}` : ''
    }
  },
  mixins: [FormatMoney, FormatDate],
  props: {
    dialog: { type: Boolean, default: false },
    isLoading: { type: Boolean, default: false },
    items: { type: Array, default: () => [] },
    statuses: { type: Array, default: () => [] }
  },
  data () {
    return {
      headers: HEADERS,
      multipleItemActions: DEFAULT_MULTIPLE_ITEM_ACTIONS,
      itemActions: DEFAULT_ITEM_ACTIONS
    }
  },
  methods: {
    get,
    processable (item) {
      return [PAYMENT_STATUS.PENDING, PAYMENT_STATUS.FAILED].includes(item.status_id)
    },
    process (item) {
      if (this.processable(item)) {
        this.$emit('manage-pay', item)
      }
    },
    manageLabel (item) {
      const map = {
        [PAYMENT_STATUS.PENDING]: 'Pay now',
        [PAYMENT_STATUS.FAILED]: 'Retry'
      }
      return map[item.status_id] || ''
    },
    changeStatus (value, item) {
      this.$emit('change-status', value, item)
    }
  }
}
</script>
