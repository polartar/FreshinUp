<template>
  <v-card-text class="grey--text">
    <v-flex
      xs6
      pr-2
    >
      <div class="mb-2 text-uppercase grey--text font-weight-bold">
        Category
      </div>
      <v-select
        v-model="category"
        :items="categories"
        item-text="name"
        item-value="id"
        single-line
        outline
      />
    </v-flex>
    <v-flex
      pa-4
      text-xs-center
    >
      MAP
    </v-flex>
    <v-layout>
      <v-flex pr-2>
        <div class="mb-2 text-uppercase grey--text font-weight-bold">
          Location
        </div>
        <v-text-field
          v-model="name"
          single-line
          outline
        />
      </v-flex>
      <v-flex pl-2>
        <div class="mb-2 text-uppercase grey--text font-weight-bold">
          Capacity
        </div>
        <v-text-field
          v-model="capacity"
          single-line
          outline
        />
      </v-flex>
    </v-layout>
    <v-flex>
      <div class="mb-2 text-uppercase grey--text font-weight-bold">
        Description
      </div>
      <v-textarea
        v-model="details"
        single-line
        outline
      />
    </v-flex>
    <div class="py-3 d-flex justify-space-between">
      <v-btn
        depressed
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
  </v-card-text>
</template>

<script>
import MapValueKeysToData from '../../mixins/MapValueKeysToData'

export const DEFAULT_LOCATION = {
  capacity: 0,
  category: '',
  details: ''
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
