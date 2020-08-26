import { storiesOf } from '@storybook/vue'

import VenueDetails from './VenueDetails'

import { FIXTURE_VENUES } from '../../../../tests/Javascript/__data__/venues'
import { action } from '@storybook/addon-actions'

export const Default = () => ({
  components: { VenueDetails },
  template: `
    <v-container>
      <VenueDetails />
    </v-container>
  `
})

export const WithData = () => ({
  components: { VenueDetails },
  data () {
    return {
      venues: FIXTURE_VENUES,
      locationUuid: FIXTURE_VENUES[0].locations[0].uuid,
      venueUuid: FIXTURE_VENUES[0].uuid
    }
  },
  template: `
    <v-container>
      <VenueDetails
        :venues="venues"
        :location-uuid="locationUuid"
        :venue-uuid="venueUuid"
        @get-directions="getDirections"/>
    </v-container>
  `,
  methods: {
    getDirections (payload) {
      action('getDirections')(payload)
    }
  }
})

storiesOf('FoodFleet|components/events/VenueDetails', module)
  .addParameters({
    backgrounds: [
      { name: 'default', value: '#f1f3f6', default: true }
    ]
  })
  .add('Default', Default)
  .add('WithData', WithData)
