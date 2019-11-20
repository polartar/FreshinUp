import { storiesOf } from '@storybook/vue'
import { action } from '@storybook/addon-actions'

// Components
import DateTimePicker from './DateTimePicker.vue'

storiesOf('FoodFleet|ui/DateTimePicker', module)
  .addParameters({
    backgrounds: [
      { name: 'default', value: '#f1f3f6', default: true }
    ]
  })
  .add('default', () => ({
    components: { DateTimePicker },
    data () {
      return {
        expireDate: null
      }
    },
    methods: {
      onInput (params) {
        action('input')(params)
      }
    },
    template: `
      <v-container>
        <date-time-picker
          v-model="expireDate"
          range
          only-date
          format="YYYY-MM-DD"
          formatted="MM-DD-YYYY"
          input-size="lg"
          label="Expiration date range"
          :color="$vuetify.theme.primary"
          :button-color="$vuetify.theme.primary"
          @input="onInput"
        />
      </v-container>
    `
  }))
