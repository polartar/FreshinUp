<template>
  <v-card>
    <v-card-title class="grey--text">
      <v-layout
        row
        space-between
        align-center
      >
        <v-flex>
          <h3 class="grey--text">
            Areas of Operation
          </h3>
        </v-flex>
        <slot name="head" />
      </v-layout>
    </v-card-title>
    <v-divider />
    <v-card-text class="primary--text">
      <store-area-list
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
import StoreAreaList from './StoreAreaList'

export default {
  components: {
    StoreAreaList
  },
  props: {
    isLoading: { type: Boolean, default: false },
    items: { type: Array, default: () => [] }
  }
}
</script>

<style lang="scss" scoped>
  .ff-fleet-members__operation-text {
    text-align: center;
  }
</style>
