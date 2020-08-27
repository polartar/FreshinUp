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
      </v-layout>
    </v-card-title>
    <v-divider />
    <v-card-text class="primary--text">
      <div
        class="ff-fleet-members__operation-text"
      >
        Nothing here yet.
        <v-dialog
          v-model="newOperation"
          max-width="400"
        >
          <template v-slot:activator="{ on }">
            <a
              slot="activator"
              href="#"
              @click.prevent="newOperation = true"
            >
              Add new item
            </a>
          </template>
          <v-card>
            <v-divider />
            <v-card-text class="grey--text">
              Coming Soon
            </v-card-text>
            <v-divider />
          </v-card>
        </v-dialog>
      </div>
    </v-card-text>
    <v-divider />
  </v-card>
</template>

<script>
export default {
  name: 'AreasOfOperation',
  props: {
    operations: {
      type: Array,
      default: () => []
    }
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
