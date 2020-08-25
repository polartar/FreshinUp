<template>
  <v-card>
    <v-card-title class="grey--text font-weight-bold">
      <h3>Venue Details</h3>
    </v-card-title>
    <v-divider />
    <div>
      Map
    </div>
    <v-layout
      row
      wrap
      class="pa-3"
    >
      <v-flex
        xs6
        pr-2
      >
        <div class="mb-2 text-uppercase grey--text font-weight-bold">
          Venue
        </div>
        <v-select
          v-model="venueData.venue"
          :items="venues"
          item-text="name"
          item-value="uuid"
          single-line
          outline
        />
      </v-flex>
      <v-flex
        xs6
        pl-2
      >
        <div class="mb-2 text-uppercase grey--text font-weight-bold">
          Location
        </div>
        <v-select
          v-model="venueData.location"
          :disabled="!venueData.venue"
          :items="locations"
          item-text="name"
          item-value="uuid"
          single-line
          outline
        />
      </v-flex>
      <v-flex
        xs12
        pb-3
        class="d-flex"
      >
        <div class="text-uppercase grey--text font-weight-bold">
          Address
        </div>
        <div
          v-if="venueData.address"
          class="grey--text text-xs-right"
        >
          {{ venueData.address }}
        </div>
        <div
          v-else
          class="text-xs-center"
        >
          -
        </div>
      </v-flex>
      <v-flex xs12>
        <v-divider />
      </v-flex>
      <v-flex
        xs12
        py-3
        class="d-flex"
      >
        <div class="text-uppercase grey--text font-weight-bold">
          Capacity
        </div>
        <div
          v-if="venueData.capacity"
          class="grey--text text-xs-right"
        >
          {{ venueData.capacity }} People
        </div>
        <div
          v-else
          class="text-xs-center"
        >
          -
        </div>
      </v-flex>
      <v-flex xs12>
        <v-divider />
      </v-flex>
      <v-flex
        xs12
        py-3
        class="d-flex"
      >
        <div class="text-uppercase grey--text font-weight-bold">
          Spots
        </div>
        <div
          v-if="venueData.spots"
          class="grey--text text-xs-right"
        >
          {{ venueData.spots }}
        </div>
        <div
          v-else
          class="text-xs-center"
        >
          -
        </div>
      </v-flex>
      <v-flex xs12>
        <v-divider />
      </v-flex>
      <v-flex
        xs12
        py-3
      >
        <div class="pb-2 text-uppercase grey--text font-weight-bold">
          Location details
        </div>
        <div v-if="hasLongText && !showMoreActivated">
          {{ minLocationDetail }}...
        </div>
        <div v-else>
          {{ venueData.locationDetail }}
        </div>
        <div
          v-if="hasLongText"
          class="text-xs-center"
        >
          <a
            href="#"
            class="grey--text font-weight-bold"
            style="text-decoration: none;"
            @click.prevent="toggleShowMore"
          >
            Show <span v-if="showMoreActivated">less</span><span v-else>more</span>
          </a>
        </div>
      </v-flex>
      <v-flex
        xs12
      >
        <v-btn
          block
          depressed
          class="ma-0"
        >
          Get Directions
        </v-btn>
      </v-flex>
    </v-layout>
  </v-card>
</template>
<script>
import { get } from 'lodash'

export default {
  props: {
    venues: {
      type: Array,
      default: () => []
    },

    venue: {
      type: Object,
      default: null
    }
  },

  data () {
    return {
      venueData: {
        venue: get(this.venue, 'venue', ''),
        location: get(this.venue, 'location', ''),
        address: get(this.venue, 'address', ''),
        capacity: get(this.venue, 'capacity', 0),
        spots: get(this.venue, 'spots', 0),
        locationDetail: get(this.venue, 'location_details', '')
      },
      showMoreActivated: false,
      locationDetailMaxChar: 300
    }
  },

  computed: {
    hasLongText () {
      return this.venueData.locationDetail.length > this.locationDetailMaxChar
    },

    minLocationDetail () {
      return this.venueData.locationDetail.slice(0, this.locationDetailMaxChar)
    },

    locations () {
      const selectedVenue = this.venues.find(v => v.uuid === this.venueData.venue)
      return selectedVenue ? selectedVenue.locations : []
    }
  },

  methods: {
    toggleShowMore () {
      this.showMoreActivated = !this.showMoreActivated
    }
  }
}
</script>
