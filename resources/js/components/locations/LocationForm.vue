<template>
  <v-card>
    <v-card-text>
      <v-layout
        row
        wrap
      >
        <v-flex
          class="grey--text"
          xs6
          pr-3
        >
          <div class="mb-2 text-uppercase grey--text font-weight-bold">
            Category
          </div>

          <v-select
            v-model="category_id"
            :items="categories"
            item-value="id"
            item-text="name"
            solo
          />
        </v-flex>
        <v-flex
          xs8
          pa-2
        >
          <div
            v-if="category_id == 1"
            class=" grey--text align-center"
          >
            <v-btn
              class="white--text"
              depressed
              color="grey"
            >
              <v-icon
                dark
                left
              >
                fas fa-upload
              </v-icon>
              upload Floor Image / Attachment
            </v-btn>
          </div>
          <div
            v-else
            class="mb-2  grey--text"
          >
            Coming Soon
          </div>
        </v-flex>
        <v-flex
          xs6
          pr-2
        >
          <div class="mb-2 text-uppercase grey--text font-weight-bold">
            Location Name
          </div>
          <v-text-field
            v-model="name"
            single-line
            outline
          />
        </v-flex>
        <v-flex
          xs6
          pl-2
        >
          <div class="mb-2 text-uppercase grey--text font-weight-bold">
            Capacity
          </div>
          <v-text-field
            v-model="capacity"
            single-line
            outline
          />
        </v-flex>
        <v-flex xs12>
          <div class="mb-2 text-uppercase grey--text font-weight-bold">
            Description
          </div>
          <v-textarea
            v-model="details"
            single-line
            outline
          />
        </v-flex>
      </v-layout>
    </v-card-text>
    <v-divider />
    <v-card-actions class="pa-3 d-flex justify-space-between">
      <div>
        <v-btn
          class="white--text"
          depressed
          color="grey"
          @click="onCancel"
        >
          Cancel
        </v-btn>
        <v-btn
          depressed
          color="primary"
          @click="save"
        >
          Save changes
        </v-btn>
      </div>
    </v-card-actions>
  </v-card>
</template>

<script>

import MapValueKeysToData from '../../mixins/MapValueKeysToData'

export const DEFAULT_LOCATION = {
  name: '',
  venue_uuid: '',
  spots: 0,
  capacity: '',
  details: '',
  category_id: ''
}
export default {
  mixins: [MapValueKeysToData],
  props: {
    categories: {
      type: Array,
      default: () => []
    }
  },
  data () {
    return {
      ...DEFAULT_LOCATION
    }
  },
  methods: {
    onCancel () {
      this.$emit('cancel')
    }
  }
}
</script>
