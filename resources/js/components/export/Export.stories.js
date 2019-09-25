import { storiesOf } from '@storybook/vue'
import { action } from '@storybook/addon-actions'

// Components
import Export from './Export.vue'

const items = [
  { uuid: '32423', name: 'Hamburger', quantity: 3 },
  { uuid: '32424', name: 'Coke', quantity: 2 },
  { uuid: '32425', name: 'Beer', quantity: 2 }
]

const eventTags = [
  { uuid: '32423', name: 'Tag 1' },
  { uuid: '930183', name: 'Tag 2' }
]

const transactions = [
  { uuid: '17249929', square_id: '123533', event: { name: 'Burger fest', location: { name: 'New York' }, host: { name: 'Host 1' }, event_tags: eventTags }, store: { name: 'Truck 1', square_id: '12333', supplier: { name: 'Supplier 1' } }, square_created_at: '2018-01-22 19:22:11', square_updated_at: '2018-01-22 19:22:11', total_money: 10000, total_tax_money: 2000, total_discount_money: 0, total_service_charge_money: 200, items: items, customer: { name: 'John Smith', square_id: '123412', reference_id: '12331' } },
  { uuid: '17249930', square_id: '123533', event: { name: 'Burger fest', location: { name: 'New York' }, host: { name: 'Host 1' }, event_tags: eventTags }, store: { name: 'Truck 1', square_id: '12333', supplier: { name: 'Supplier 1' } }, square_created_at: '2018-01-22 19:22:11', square_updated_at: '2018-01-22 19:22:11', total_money: 10000, total_tax_money: 2000, total_discount_money: 0, total_service_charge_money: 200, items: items, customer: { name: 'John Smith', square_id: '123412', reference_id: '12331' } },
  { uuid: '17249931', square_id: '123533', event: { name: 'Song festival', location: { name: 'New York' }, host: { name: 'Host 1' }, event_tags: eventTags }, store: { name: 'Truck 2', square_id: '12333', supplier: { name: 'Supplier 1' } }, square_created_at: '2018-01-22 19:22:11', square_updated_at: '2018-01-22 19:22:11', total_money: 10000, total_tax_money: 2000, total_discount_money: 0, total_service_charge_money: 200, items: items, customer: { name: 'John Smith', square_id: '123412', reference_id: '12331' } },
  { uuid: '17249932', square_id: '123533', event: { name: 'Burger fest', location: { name: 'New York' }, host: { name: 'Host 1' }, event_tags: eventTags }, store: { name: 'Truck 2', square_id: '12333', supplier: { name: 'Supplier 1' } }, square_created_at: '2018-01-22 19:22:11', square_updated_at: '2018-01-22 19:22:11', total_money: 10000, total_tax_money: 2000, total_discount_money: 0, total_service_charge_money: 200, items: items, customer: { name: 'John Smith', square_id: '123412', reference_id: '12331' } },
  { uuid: '17249933', square_id: '123533', event: { name: 'Burger fest', location: { name: 'New York' }, host: { name: 'Host 1' }, event_tags: eventTags }, store: { name: 'Truck 1', square_id: '12333', supplier: { name: 'Supplier 1' } }, square_created_at: '2018-01-22 19:22:11', square_updated_at: '2018-01-22 19:22:11', total_money: 10000, total_tax_money: 2000, total_discount_money: 0, total_service_charge_money: 200, items: items, customer: { name: 'John Smith', square_id: '123412', reference_id: '12331' } },
  { uuid: '17249934', square_id: '123533', event: { name: 'Burger fest', location: { name: 'New York' }, host: { name: 'Host 1' }, event_tags: eventTags }, store: { name: 'Truck 1', square_id: '12333', supplier: { name: 'Supplier 1' } }, square_created_at: '2018-01-22 19:22:11', square_updated_at: '2018-01-22 19:22:11', total_money: 10000, total_tax_money: 2000, total_discount_money: 0, total_service_charge_money: 200, items: items, customer: { name: 'John Smith', square_id: '123412', reference_id: '12331' } },
  { uuid: '17249935', square_id: '123533', event: { name: 'Art Basel', location: { name: 'New York' }, host: { name: 'Host 1' }, event_tags: eventTags }, store: { name: 'Truck 4', square_id: '12333', supplier: { name: 'Supplier 1' } }, square_created_at: '2018-01-22 19:22:11', square_updated_at: '2018-01-22 19:22:11', total_money: 10000, total_tax_money: 2000, total_discount_money: 0, total_service_charge_money: 200, items: items, customer: { name: 'John Smith', square_id: '123412', reference_id: '12331' } },
  { uuid: '17249936', square_id: '123533', event: { name: 'Art Basel', location: { name: 'New York' }, host: { name: 'Host 1' }, event_tags: eventTags }, store: { name: 'Truck 4', square_id: '12333', supplier: { name: 'Supplier 1' } }, square_created_at: '2018-01-22 19:22:11', square_updated_at: '2018-01-22 19:22:11', total_money: 10000, total_tax_money: 2000, total_discount_money: 0, total_service_charge_money: 200, items: items, customer: { name: 'John Smith', square_id: '123412', reference_id: '12331' } }
]

const dataVisibilityPartial = [
  'event_location',
  'square_created_at',
  'total_money',
  'total_tax_money',
  'total_discount_money'
]

const dataVisibility = [
  'event_location',
  'square_created_at',
  'square_updated_at',
  'total_money',
  'total_tax_money',
  'total_discount_money',
  'total_service_charge_money',
  'items',
  'event_tags',
  'square_id',
  'store',
  'store_square_id',
  'host',
  'supplier',
  'customer',
  'customer_square_id',
  'customer_reference_id'
]

storiesOf('FoodFleet|export/Export', module)
  .addParameters({
    backgrounds: [
      { name: 'default', value: '#f1f3f6', default: true }
    ]
  })
  .add('transactions', () => ({
    components: { Export },
    methods: {
      onClose () {
        action('Close')('close')
      }
    },
    data () {
      return {
        transactions: transactions,
        dataVisibility: dataVisibilityPartial
      }
    },
    template: `
  <v-container>
       <export
            :transactions="transactions"
            :="dataVisibility"
            @close="onClose"
            />
  </v-container>
`
  }))
  .add('inside dialog', () => ({
    components: { Export },
    data () {
      return {
        arrayDatas: vehicles,
        tireChartStatuses: tireChartStatuses,
        wheels: wheels,
        branches: branches,
        vehicleDataVisibility: vehicleDataVisibility,
        vehicleParameters: vehicleParameters,
        dialog: false
      }
    },
    methods: {
      closeDialog () {
        this.dialog = false
      },
      onSave (params) {
        this.dialog = false
        action('Save')(params)
      }
    },
    template: `
    <v-container>
        <v-dialog
            v-model="dialog"
            scrollable
            max-width="400"
          >
            <v-btn
              slot="activator"
              color="primary"
              dark
            >
              Export
            </v-btn>
             <export
              data-type="vehicle"
              :array-datas="arrayDatas"
              :wheels="wheels"
              :tire-chart-statuses="tireChartStatuses"
              :branches="branches"
              :visible-parameters="vehicleDataVisibility"
              :parameters="vehicleParameters"
              @close="closeDialog"
              />
        </v-dialog>
    </v-container>
`
  }))
  .add('appraisals', () => ({
    components: { Export },
    methods: {
      onClose () {
        action('Close')('close')
      }
    },
    data () {
      return {
        arrayDatas: appraisals,
        appraisalDataVisibility: appraisalDataVisibility,
        appraisalParameters: appraisalParameters
      }
    },
    template: `
  <v-container>
       <export
            data-type="appraisal"
            :array-datas="arrayDatas"
            :visible-parameters="appraisalDataVisibility"
            :parameters="appraisalParameters"
            @close="onClose"
            />
  </v-container>
`
  }))
