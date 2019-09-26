import { storiesOf } from '@storybook/vue'
import { action } from '@storybook/addon-actions'

// Components
import DataVisibility from './DataVisibility.vue'

const visibleParameters = [
  'event_location',
  'square_created_at',
  'total_money',
  'total_tax_money',
  'total_discount_money'
]

const parameters = [
  { name: 'event_location', label: 'Event / Location' },
  { name: 'square_created_at', label: 'Creation date' },
  { name: 'total_money', label: 'Total' },
  { name: 'total_tax_money', label: 'Tax total' },
  { name: 'total_discount_money', label: 'Total discount' },
  { name: 'total_service_charge_money', label: 'Total Service Charge' },
  { name: 'square_updated_at', label: 'Update date' },
  { name: 'items', label: 'Items' },
  { name: 'square_id', label: 'Reference ID' }
]

storiesOf('FoodFleet|financials/DataVisibility', module)
  .addParameters({
    backgrounds: [
      { name: 'default', value: '#f1f3f6', default: true }
    ]
  })
  .add('inside dialog', () => ({
    components: { DataVisibility },
    data () {
      return {
        visibleParameters: visibleParameters,
        parameters: parameters,
        dialog: false
      }
    },
    methods: {
      closeDialog () {
        this.dialog = false
      },
      onSave (parameters) {
        this.dialog = false
        action('Save')(parameters)
      }
    },
    template: `
    <v-container>
        <v-dialog
            v-model="dialog"
            scrollable
            max-width="436"
          >
            <v-btn
              slot="activator"
              color="primary"
              dark
            >
              Data Visibility
            </v-btn>

             <data-visibility
              :visible-parameters="visibleParameters"
              :parameters="parameters"
              @close="closeDialog"
              @save="onSave"
              />
        </v-dialog>
    </v-container>
`
  }))
