<template>
  <v-layout
    column
    class="grey--text"
  >
    <v-flex>
      <div class="mb-2 text-uppercase grey--text font-weight-bold">
        Category
      </div>
      <v-select
        v-model="category_id"
        :items="categories"
        item-text="name"
        item-value="id"
        single-line
        outline
      />
    </v-flex>
    <v-flex
      text-xs-center
    >
      MAP coming soon
      <div style="background-color: #e5e5e5; width: 100%; height: 400px;" />
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
    <v-flex class="py-3 d-flex justify-space-between">
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
    </v-flex>
  </v-layout>
</template>

<script>
import MapValueKeysToData from '../../mixins/MapValueKeysToData'

export const DEFAULT_LOCATION = {
  name: '',
  capacity: '',
  category_id: '',
  details: ''
}

/**
   * These comments are just for intellisense (IDE)
   * @property {number} capacity
   * @property {number} category_id
   * @property {String} details
   * @property {String} name
   */
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
