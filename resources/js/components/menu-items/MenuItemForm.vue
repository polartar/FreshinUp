<template>
  <v-card style="background-color: #e5e5e5;">
    <v-card-title>
      <h3 class="grey--text">
        Add menu new item
      </h3>
    </v-card-title>
    <v-layout>
      <v-flex
        xs8
        px-2
      >
        <div class="mb-2 text-uppercase grey--text font-weight-bold">
          Item Title
        </div>
        <v-text-field
          v-model="title"
          placeholder="Enter menu item title"
          single-line
          outline
        />
      </v-flex>
      <v-flex
        xs2
        px-2
      >
        <div class="mb-2 text-uppercase grey--text font-weight-bold">
          servings
        </div>
        <v-text-field
          v-model="servings"
          single-line
          outline
        />
      </v-flex>
      <v-flex
        xs2
        px-2
      >
        <div class="mb-2 text-uppercase grey--text font-weight-bold">
          Cost
        </div>
        <v-text-field
          v-model="cost"
          single-line
          outline
        />
      </v-flex>
    </v-layout>
    <v-layout>
      <v-flex
        xs12
        px-2
      >
        <div class="text-uppercase grey--text font-weight-bold">
          Description
        </div>
        <v-textarea
          v-model="description"
          single-line
          outline
        />
      </v-flex>
    </v-layout>
    <v-card-actions class="pa-2 d-flex">
      <div>
        <v-btn
          depressed
          color="grey"
          disabled
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

export const DEFAULT_MENU_ITEM = {
  title: '',
  description: '',
  servings: '',
  cost: ''
}

/**
 * Menu Item Component
 * @property {String} title
 * @property {String} description
 * @property {Number} servings
 * @property {Number} cost
 */
export default {
  mixins: [MapValueKeysToData],
  data () {
    return {
      ...DEFAULT_MENU_ITEM
    }
  },
  methods: {
    onCancel () {
      this.$emit('cancel')
    }
  }
}
</script>
