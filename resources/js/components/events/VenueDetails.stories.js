import { storiesOf } from '@storybook/vue'

import VenueDetails from './VenueDetails'

import { FIXTURE_VENUE } from '../../../../tests/Javascript/__data__/venues'

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
      venues: FIXTURE_VENUE
    }
  },
  template: `
    <v-container>
      <venue-details :venues="venues"/>
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
