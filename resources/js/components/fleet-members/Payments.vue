<template>
  <v-card>
    <v-card-title class="py-1">
      <v-layout
        align-center
        justify-space-between
        row
      >
        <v-flex>Payments</v-flex>
        <v-flex class="text-xs-right">
          <v-btn
            depressed
            color="primary"
          >
            <v-icon
              left
            >
              add_circle
            </v-icon>
            Add new item
          </v-btn>
        </v-flex>
      </v-layout>
    </v-card-title>
    <v-divider />
    <v-layout>
      <v-flex xs12>
        <v-data-table
          :headers="headers"
          :items="payments"
          item-key="uuid"
        >
          <template v-slot:items="props">
            <td class="py-2">
              <v-btn
                depressed
                class="white--text"
                :class="getStatus(props.item).class"
              >
                {{ getStatus(props.item).text }}
              </v-btn>
            </td>
            <td class="py-2">
              <div class="subheading primary--text">
                {{ get(props, 'item.event_name') }}
              </div>
              <div class="grey--text">
                {{ formatDate(get(props, 'item.venue_due_date'), 'MMM DD, YYYY') }}
              </div>
              <div class="grey--text">
                @ {{ get(props, 'item.venue') }}
              </div>
            </td>
            <td class="py-2 grey--text">
              {{ get(props, 'item.payment_name') }}
            </td>
            <td class="py-2 grey--text">
              {{ formatDate(get(props, 'item.due_date'), 'MMM DD, YYYY') }}
            </td>
            <td class="py-2 grey--text">
              {{ formatMoney(get(props, 'item.amount_money'), { format: '$0,0.00', precision: 4 }) }}
            </td>
            <td class="py-2">
              <v-btn
                v-if="processable(props.item)"
                depressed
                color="primary"
              >
                {{ manageLabel(props.item) }}
              </v-btn>
            </td>
          </template>
        </v-data-table>
      </v-flex>
    </v-layout>
  </v-card>
</template>
<script>
import get from 'lodash/get'
import FormatMoney from 'fresh-bus/components/mixins/FormatMoney'
import FormatDate from 'fresh-bus/components/mixins/FormatDate'

export default {
  mixins: [FormatMoney, FormatDate],
  props: {
    payments: {
      type: Array,
      default: () => [
        {
          status: 1,
          event_name: 'Random event name',
          payment_name: 'Event venue fee',
          due_date: '2020-07-08',
          amount_money: 300,
          venue: 'Convention center',
          venue_due_date: '2020-09-09'
        },
        {
          status: 2,
          event_name: 'Random event name',
          payment_name: 'Event venue fee',
          due_date: '2020-07-08',
          amount_money: 300,
          venue: 'Convention center',
          venue_due_date: '2020-09-09'
        },
        {
          status: 3,
          event_name: 'Random event name',
          payment_name: 'Event venue fee',
          due_date: '2020-07-08',
          amount_money: 300,
          venue: 'Convention center',
          venue_due_date: '2020-09-09'
        }
      ]
    },
    paymentStatuses: {
      type: Array,
      default: () => [
        {
          id: 1,
          text: 'Pending'
        },
        {
          id: 2,
          text: 'Paid'
        },
        {
          id: 3,
          text: 'Failed'
        },
        {
          id: 4,
          text: 'Refunded'
        }
      ]
    }
  },
  data () {
    return {
      headers: [
        { text: 'Status', value: 'status' },
        { text: 'Event name', value: 'event_name' },
        { text: 'Payment name', value: 'payment_name' },
        { text: 'Due date', value: 'due_date' },
        { text: 'Amount', value: 'amount_money' },
        { text: 'Manage', value: 'manage' }
      ]
    }
  },

  methods: {
    get,

    getStatus (item) {
      let status = this.paymentStatuses.find(s => item.status === s.id)

      if (!status) { return }

      switch (status.id) {
        case 1:
          status = Object.assign({}, status, { class: 'grey' })
          break
        case 2:
          status = Object.assign({}, status, { class: 'green' })
          break
        case 3:
          status = Object.assign({}, status, { class: 'red' })
          break
        case 4:
          status = Object.assign({}, status, { class: 'orange' })
          break
        default:
          status = Object.assign({}, status, { class: '', text: 'Button' })
          break
      }

      return status
    },

    processable (item) {
      return item.status === 1 || item.status === 3
    },

    manageLabel (item) {
      if (item.status === 1) { return 'Pay now' }

      if (item.status === 3) { return 'Retry' }

      return ''
    }
  }
}
</script>
