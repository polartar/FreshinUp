<template>
  <v-card class="ff-venue_details">
    <v-card-title class="font-weight-bold">
      Venue Details
    </v-card-title>
    <v-divider />
    <v-card-text>
      <v-layout
        row
        wrap
        class="align-center"
      >
        <v-flex
          xs6
          pr-2
        >
          <v-layout
            row
            class="justify-content-between align-center mb-2"
          >
            <h4 class="text-uppercase grey--text font-weight-bold">
              Venue
            </h4>
            <clear-button
              v-if="location.venue_uuid"
              @clear="onVenueCleared"
            />
          </v-layout>
          <v-select
            :value="location.venue_uuid"
            :items="venues"
            item-text="name"
            item-value="uuid"
            single-line
            outline
            @input="onVenueChanged"
          />
        </v-flex>
        <v-flex
          xs6
          pl-2
        >
          <v-layout
            row
            class="justify-content-between align-center mb-2"
          >
            <h4 class="text-uppercase grey--text font-weight-bold">
              Location
            </h4>
            <clear-button
              v-if="location.uuid"
              @clear="onLocationCleared"
            />
          </v-layout>
          <v-select
            :value="location.uuid"
            :disabled="!location.venue_uuid"
            :items="selectedVenueLocations"
            item-text="name"
            item-value="uuid"
            single-line
            outline
            @input="onLocationChanged"
          />
        </v-flex>
        <v-flex
          xs12
          pb-3
          class="d-flex"
        >
          <h4 class="text-uppercase grey--text font-weight-bold">
            Address
          </h4>
          <p
            v-if="selectedVenue.address"
            class="grey--text text-xs-right"
          >
            {{ selectedVenue.address }}
          </p>
          <span
            v-else
            class="text-xs-center"
          >
            -
          </span>
        </v-flex>
        <v-flex xs12>
          <v-divider />
        </v-flex>
        <v-flex
          xs12
          py-3
          class="d-flex"
        >
          <h4 class="text-uppercase grey--text font-weight-bold">
            Capacity
          </h4>
          <p
            v-if="location.capacity"
            class="grey--text text-xs-right"
          >
            {{ location.capacity }} People
          </p>
          <span
            v-else
            class="text-xs-center"
          >
            -
          </span>
        </v-flex>
        <v-flex xs12>
          <v-divider />
        </v-flex>
        <v-flex
          xs12
          py-3
          class="d-flex"
        >
          <h4 class="text-uppercase grey--text font-weight-bold">
            Spots
          </h4>
          <p
            v-if="location.spots"
            class="grey--text text-xs-right"
          >
            {{ location.spots }}
          </p>
          <span
            v-else
            class="text-xs-center"
          >
            -
          </span>
        </v-flex>
        <v-flex xs12>
          <v-divider />
        </v-flex>
        <v-flex
          xs12
          py-3
        >
          <h4 class="pb-2 text-uppercase grey--text font-weight-bold">
            Location details
          </h4>
          <p v-if="hasLongText && !showMoreActivated">
            {{ minLocationDetail }}...
          </p>
          <p v-else>
            {{ location.details }}
          </p>
          <p
            v-if="hasLongText"
            class="text-xs-center"
          >
            <a
              href="#"
              class="grey--text font-weight-bold ff-venue_details__toggleLink"
              @click.prevent="toggleShowMore"
            >
              Show {{ showMoreActivated ? 'less' : 'more' }}
            </a>
          </p>
        </v-flex>
      </v-layout>
    </v-card-text>
    <v-card-actions>
      <v-layout
        align-center
        justify-space-around
      >
        <v-flex
          xs12
          sm8
          md6
          lg4
        >
          <v-btn
            disabled
            block
            depressed
            @click="getDirections"
          >
            Get Directions
          </v-btn>
        </v-flex>
      </v-layout>
    </v-card-actions>
  </v-card>
</template>
<script>
import ClearButton from '../ClearButton'
import omit from 'lodash/omit'
import get from 'lodash/get'
export const DEFAULT_LOCATION = {
  uuid: '',
  name: '',
  venue_uuid: '',
  capacity: '',
  spots: '',
  details: ''
}
export default {
  components: {
    ClearButton
  },
  props: {
    venues: { type: Array, default: () => [] },
    locationUuid: { type: String, default: '' },
    venueUuid: { type: String, default: '' }
  },
  data () {
    return {
      location: DEFAULT_LOCATION,
      showMoreActivated: false,
      locationDetailMaxChar: 300
    }
  },

  computed: {
    hasLongText () {
      return this.location.details.length > this.locationDetailMaxChar
    },
    minLocationDetail () {
      return this.location.details.slice(0, this.locationDetailMaxChar)
    },
    venuesByUuid () {
      return this.venues.reduce((map, venue) => {
        map[venue.uuid] = venue
        return map
      }, {})
    },
    selectedVenue () {
      return this.venuesByUuid[this.location.venue_uuid] || {}
    },
    selectedVenueLocations () {
      return get(this.selectedVenue, 'locations', [])
    },
    locationsByUuid () {
      return this.selectedVenueLocations.reduce((map, location) => {
        map[location.uuid] = location
        return map
      }, {})
    }
  },
  watch: {
    locationUuid (value) {
      this.onLocationChanged(value)
    },
    venueUuid (value) {
      this.onVenueChanged(value)
    },
    location (value) {
      this.$emit('input', value)
    }
  },
  mounted () {
    this.onVenueChanged(this.venueUuid)
    this.onLocationChanged(this.locationUuid)
  },

  methods: {
    toggleShowMore () {
      this.showMoreActivated = !this.showMoreActivated
    },
    getDirections () {
      this.$emit('get-directions', this.location)
    },
    onVenueCleared () {
      this.location.venue_uuid = ''
      this.onLocationCleared()
    },
    onVenueChanged (value) {
      this.onLocationCleared()
      this.location.venue_uuid = value
    },
    onLocationCleared () {
      this.location = Object.assign({}, this.location, omit(DEFAULT_LOCATION, ['venue_uuid']))
    },
    onLocationChanged (locationUuid) {
      const location = this.locationsByUuid[locationUuid] || DEFAULT_LOCATION
      this.location = Object.assign({}, this.location, omit(location, ['venue_uuid']))
    }
  }
}
</script>

<style>
  .ff-venue_details {
    --clear-button-size: 1rem;
  }

  .ff-venue_details__toggleLink {
    text-decoration: none;
  }
</style>
