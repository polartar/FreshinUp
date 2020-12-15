import { storiesOf } from '@storybook/vue'
import { action } from '@storybook/addon-actions'

import DuplicateEventDialog from './DuplicateEventDialog.vue'

export const Default = () => ({
  components: { DuplicateEventDialog },
  template: `
    <v-container>
      <duplicate-event-dialog />
    </v-container>
  `
})

export const Populated = () => ({
  components: { DuplicateEventDialog },
  data () {
    return {
      duplicate: {
        basicInformation: true,
        venue: true,
        fleetMember: false,
        customer: true
      }
    }
  },
  methods: {
    onInput (payload) {
      action('onInput')(payload)
    },
    onClose () {
      action('onClose')
    }
  },
  template: `
    <v-container>
      <duplicate-event-dialog
        :value="duplicate"
        @close="onClose"
        @input="onInput"
      />
    </v-container>
  `
})

export const IsLoading = () => ({
  components: { DuplicateEventDialog },
  template: `
    <v-container>
      <duplicate-event-dialog
        is-loading
      />
    </v-container>
  `
})

storiesOf('FoodFleet|components/event/DuplicateEventDialog', module)
  .addParameters({
    backgrounds: [
      { name: 'default', value: '#f1f3f6', default: true }
    ]
  })
  .add('Default', Default)
  .add('IsLoading', IsLoading)
  .add('Populated', Populated)
