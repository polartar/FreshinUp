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
              <location-form />
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
import LocationForm from './LocationForm'

export default {
  components: { LocationList, LocationForm },
  props: {
    items: { type: Array, default: () => [] },
    isLoading: { type: Boolean, default: false }
  },
  data () {
    return {
      newLocationDialog: false
    }
  },
  methods: {
    get
  }
}
</script>
