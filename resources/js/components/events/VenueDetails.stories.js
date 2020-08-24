import { storiesOf } from '@storybook/vue'

import VenueDetails from './VenueDetails'

import { FIXTURE_VENUES, FIXTURE_VENUE_ADDITIONAL_DATA } from '../../../../tests/Javascript/__data__/venues'

export const Default = () => ({
  components: { VenueDetails },
  template: `
    <v-container>
      <venue-details />
    </v-container>
  `
})

export const WithData = () => ({
  components: { VenueDetails },
  data () {
    return {
      venues: FIXTURE_VENUES,
      additionalData: FIXTURE_VENUE_ADDITIONAL_DATA
    }
  },
  template: `
    <v-container>
      <venue-details :venues="venues" :venue="additionalData"/>
    </v-container>
  `,
  methods: {
    getDirections () {}
  }
})

storiesOf('FoodFleet|components/event/VenueDetails', module)
  .addParameters({
    backgrounds: [
      { name: 'default', value: '#f1f3f6', default: true }
    ]
  })
  .add('Default', Default)
  .add('With data', WithData)
