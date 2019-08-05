import { storiesOf } from '@storybook/vue'
import { action } from '@storybook/addon-actions'

// Components
import ReportableList from './ReportableList.vue'

const paymentTypes = [
  { uuid: 1, label: 'Credit Card' },
  { uuid: 2, label: 'Money Transfer' },
  { uuid: 3, label: 'Google Pay' },
  { uuid: 4, label: 'Apple Pay' }
]

const filterFleetMember = {
  label: 'Fleet Member 1',
  value: 1
}

const filterEvent = {
  label: ['Meeting', 'Furt'],
  value: [2, 3]
}

const modifier1 = {
  name: 'event_uuid',
  resource_name: 'events',
  label: 'Event',
  placeholder: 'All events',
  type: 'autocomplete'
}

const modifier2 = {
  name: 'payment_uuid',
  resource_name: 'payment_types',
  label: 'Payment type',
  placeholder: 'All payment types',
  type: 'select'
}

const modifier3 = {
  name: 'date_after',
  resource_name: 'date_after',
  label: 'Min Date',
  placeholder: 'Any',
  type: 'date'
}

const modifier4 = {
  name: 'date_before',
  resource_name: 'date_before',
  label: 'Max Date',
  placeholder: 'Any',
  type: 'date'
}

const modifier5 = {
  name: 'min_price',
  resource_name: null,
  label: 'Min price',
  placeholder: 'Min price',
  type: 'text'
}

const modifier6 = {
  name: 'max_price',
  resource_name: null,
  label: 'Max price',
  placeholder: 'Max price',
  type: 'text'
}

const reportables = [
  {
    id: 4,
    name: 'Custom Report #1',
    filters: { fleet_member_uuid: filterFleetMember, date_after: '2019-01-01', date_before: '2019-06-01' },
    modifier_1: modifier1,
    modifier_2: modifier2
  },
  { id: 2, name: 'Custom Report #2', filters: { event: filterEvent }, modifier_1: modifier3, modifier_2: modifier4 },
  {
    id: 1,
    name: 'Custom Report #3',
    filters: { date_after: '2019-01-01', date_before: '2019-06-01' },
    modifier_1: modifier1,
    modifier_2: modifier2
  },
  {
    id: 11,
    name: 'Search 1',
    filters: { fleet_member: filterFleetMember, event: filterEvent, price_range: [100000, 200000] },
    modifier_1: modifier3,
    modifier_2: modifier4
  },
  {
    id: 12,
    name: 'Best trucks',
    filters: { fleet_member: filterFleetMember, event: filterEvent, price_range: [100000, null] },
    modifier_1: modifier1
  },
  {
    id: 13,
    name: 'Best trucks EVER',
    filters: { fleet_member: filterFleetMember, event: filterEvent, price_range: [null, 100000] },
    modifier_1: modifier1,
    modifier_2: modifier2
  },
  {
    id: 14,
    name: 'Custom Financial Report #1',
    filters: { company_uuid: 4, prepared_after: '2019-01-01', prepared_before: '2019-07-01' },
    modifier_1: modifier5,
    modifier_2: modifier6
  }
]

storiesOf('reportables/ReportableList', module)
  .addParameters({
    backgrounds: [
      { name: 'default', value: '#f1f3f6', default: true }
    ]
  })
  .add('defaults', () => {
    return {
      components: { ReportableList },
      template: `
          <v-container>
            <reportable-list
              base-url="/admin/reportables"
            />
          </v-container>
      `
    }
  })
  .add('with reportables', () => {
    return {
      components: { ReportableList },
      methods: {
        onDelete (item) {
          action('delete')(item)
        },
        onDeleteMultiple (array) {
          action('delete')(array)
        }
      },
      data () {
        return {
          reportables,
          selectables: {
            'payment_types': paymentTypes
          }
        }
      },
      template: `
          <v-container>
            <reportable-list
              :reportables="reportables"
              :selectables="selectables"
              base-url="/admin/reportables"
              @delete="onDelete"
              @delete-multiple="onDeleteMultiple"
            />
          </v-container>
      `
    }
  })
