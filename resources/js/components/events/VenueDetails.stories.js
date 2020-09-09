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

export const NotSelected = () => ({
  components: { VenueDetails },
  data () {
    return {
      venues: FIXTURE_VENUES
    }
  },
  template: `
    <v-container>
      <VenueDetails
        :venues="venues"
        @input="onInput"
        @get-directions="getDirections"/>
    </v-container>
  `,
  methods: {
    getDirections (payload) {
      action('getDirections')(payload)
    },
    onInput (payload) {
      action('onInput')(payload)
    }
  }
})

export const SelectedVenue = () => ({
  components: { VenueDetails },
  data () {
    return {
      venues: FIXTURE_VENUES,
      venueUuid: FIXTURE_VENUES[0].uuid
    }
  },
  template: `
    <v-container>
      <VenueDetails
        :venues="venues"
        :venue-uuid="venueUuid"
        @input="onInput"
        @get-directions="getDirections"/>
    </v-container>
  `,
  methods: {
    getDirections (payload) {
      action('getDirections')(payload)
    },
    onInput (payload) {
      action('input')(payload)
    }
  }
})

export const SelectedVenueAndLocation = () => ({
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
        @input="onInput"
        @get-directions="getDirections"/>
    </v-container>
  `,
  methods: {
    getDirections (payload) {
      action('getDirections')(payload)
    },
    onInput (payload) {
      action('onInput')(payload)
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
  .add('NotSelected', NotSelected)
  .add('SelectedVenue', SelectedVenue)
  .add('SelectedVenueAndLocation', SelectedVenueAndLocation)
