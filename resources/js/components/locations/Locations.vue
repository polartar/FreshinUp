<template>
  <v-card>
    <v-card-title class="grey--text">
      <v-layout
        row
        space-between
        align-center
      >
        <v-flex>
          <h3>Locations</h3>
        </v-flex>
        <v-flex shrink>
          <v-dialog
            v-model="newLocationDialog"
            max-width="600"
          >
            <template v-slot:activator="{ on }">
              <v-btn
                slot="activator"
                color="primary"
                text
                @click="newLocationDialog = true"
              >
                <v-icon
                  dark
                  left
                >
                  add_circle_outline
                </v-icon>Add new location
              </v-btn>
            </template>
            <v-card>
              <v-card-title>
                <v-layout
                  row
                  space-between
                  align-center
                >
                  <v-flex class="grey--text">
                    <h3>Add Location</h3>
                  </v-flex>
                  <v-btn
                    small
                    round
                    color="grey"
                    class="white--text"
                    @click="newLocationDialog = false"
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
              <v-card-text class="py-4">
                <location-form
                  :is-loading="formIsLoading"
                  :value="location"
                  :categories="categories"
                  @input="onSubmit"
                  @cancel="newLocationDialog = false"
                />
              </v-card-text>
            </v-card>
          </v-dialog>
        </v-flex>
      </v-layout>
    </v-card-title>
    <v-divider />
    <v-card-text class="primary--text">
      <location-list
        :is-loading="isLoading"
        :items="items"
        v-bind="$attrs"
        v-on="$listeners"
      />
    </v-card-text>
    <v-divider />
  </v-card>
</template>

<script>
import get from 'lodash/get'
import LocationList from './LocationList'
import LocationForm, { DEFAULT_LOCATION } from './LocationForm'

export default {
  components: { LocationList, LocationForm },
  props: {
    categories: { type: Array, default: () => [] },
    items: { type: Array, default: () => [] },
    isLoading: { type: Boolean, default: false },
    formIsLoading: { type: Boolean, default: false }
  },
  data () {
    return {
      location: DEFAULT_LOCATION,
      newLocationDialog: false
    }
  },
  methods: {
    get,
    onSubmit (location) {
      this.location = Object.assign({}, this.location, location)
      this.$emit('new-location', location, this)
    }
  }
}
</script>
