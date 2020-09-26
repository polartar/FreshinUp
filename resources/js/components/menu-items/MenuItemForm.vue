<template>
  <div class="ff-menu-item__form">
    <h3 class="headline grey--text font-weight-bold">
      Add menu new item
    </h3>
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
          background-color="white"
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
          background-color="white"
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
          background-color="white"
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
          Item description
        </div>
        <v-textarea
          v-model="description"
          background-color="white"
          single-line
          outline
        />
      </v-flex>
    </v-layout>
    <div>
      <v-btn
        depressed
        color="grey"
        class="white--text"
        @click="onCancel"
      >
        Cancel
      </v-btn>
      <v-btn
        depressed
        color="primary"
        @click="save"
      >
        Save
      </v-btn>
    </div>
  </div>
</template>

<script>
import MapValueKeysToData from '../../mixins/MapValueKeysToData'

export const DEFAULT_MENU_ITEM = {
  uuid: '',
  title: '',
  description: '',
  servings: '',
  cost: '',
  store_uuid: ''
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

<style lang="styl" scoped>
  .ff-menu-item__form {
    background-color: rgba(160,169,186,0.1); /*rgba(#a0a9ba, .10)*/;
    padding: 2rem;
  }

  .ff-menu-item__form h3 {
    color: #a0a9ba;
    margin-bottom: 1rem;
  }

  /deep/ .v-text-field--outline > .v-input__control > .v-input__slot {
    background: white!important;
  }
</style>
