<template>
  <v-card>
    <v-progress-linear
      v-if="isLoading"
      indeterminate
    />
    <v-card-title>
      <v-layout
        row
        space-between
        align-center
      >
        <v-flex>
          <h3>Duplicate Event</h3>
        </v-flex>
        <v-btn
          small
          round
          color="grey"
          class="white--text"
          @click="close()"
        >
          <v-flex>
            <v-icon
              small
              class="white--text"
            >
              fa fa-times
            </v-icon>
          </v-flex>
          <v-flex>
            Close
          </v-flex>
        </v-btn>
      </v-layout>
    </v-card-title>
    <v-divider />
    <v-card-text class="grey--text">
      <small class="font-weight-bold">SELECT</small>
      <p>Choose what will be carried over to the duplicate event</p>
      <v-checkbox
        v-model="basicInformation"
        disabled
        class="mt-0 mb-0 p-0"
        label="Basic Information"
      />
      <v-checkbox
        v-model="venue"
        class="mt-0 mb-0 p-0"
        label="Venue/location"
      />
      <v-checkbox
        v-model="fleetMember"
        class="mt-0 mb-0 p-0"
        label="Fleet Member"
      />
      <v-checkbox
        v-model="customer"
        class="mt-0 mb-0 p-0"
        label="Customer"
      />
    </v-card-text>
    <v-divider />
    <v-card-actions>
      <v-layout
        row
        justify-end
      >
        <v-btn
          @click="close()"
        >
          Cancel
        </v-btn>
        <v-btn
          color="primary"
          :loading="isLoading"
          @click="save()"
        >
          Duplicate
        </v-btn>
      </v-layout>
    </v-card-actions>
  </v-card>
</template>

<script>
import MapValueKeysToData from '~/mixins/MapValueKeysToData'

export const DEFAULT_VALUE = {
  basicInformation: true,
  venue: false,
  fleetMember: false,
  customer: false
}
/**
 * @property boolean basicInformation
 * @property boolean venue
 * @property boolean fleetMember
 * @property boolean customer
 */
export default {
  mixins: [MapValueKeysToData],
  props: {
    value: { type: Object, default: () => DEFAULT_VALUE },
    isLoading: { type: Boolean, default: false }
  },
  data () {
    return {
      ...DEFAULT_VALUE
    }
  },
  methods: {
    close () {
      this.$emit('close')
    }
  }
}
</script>
