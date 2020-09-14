<template>
  <v-card>
    <v-card-title class="grey--text">
      <v-layout
        row
        space-between
        align-center
      >
        <v-flex>
          <h3>Areas of Operation</h3>
        </v-flex>
        <v-flex shrink>
          <v-dialog
            v-model="newOperation"
            max-width="600"
          >
            <template v-slot:activator="{ on }">
              <v-btn
                slot="activator"
                color="primary"
                text
                @click="newOperation = true"
              >
                <v-icon
                  dark
                  left
                >
                  add_circle_outline
                </v-icon>Add new area
              </v-btn>
            </template>
            <v-card>
              <v-divider />
              <v-card-text class="grey--text">
                Coming Soon here
              </v-card-text>
              <v-divider />
              <area-form class="ma-2"/>
            </v-card>
          </v-dialog>
        </v-flex>
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
import AreaForm from './AreaForm'

export default {
  components: {
    StoreAreaList,
    AreaForm
  },
  props: {
    isLoading: { type: Boolean, default: false },
    items: { type: Array, default: () => [] }
  },
  data () {
    return {
      newOperation: false
    }
  }
}
</script>

<style lang="scss" scoped>
  .ff-fleet-members__operation-text {
    text-align: center;
  }
</style>
