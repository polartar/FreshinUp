<template>
  <v-card>
    <v-card-title class="grey--text">
      <v-layout
        row
        space-between
        align-center
      >
        <v-flex>
          <h3>Assigned Events</h3>
        </v-flex>
      </v-layout>
    </v-card-title>
    <v-divider />
    <v-card-text class="primary--text">
      <div
        v-for="(event, index) in events"
        :key="index"
        class="ff-fleet-members__events-text"
      >
        {{ event }}
      </div>
    </v-card-text>
    <v-divider />
  </v-card>
</template>

<script>
export default {
  name: 'Events',
  props: {
    events: {
      type: Array,
      default: () => []
    }
  }
}
</script>

<style lang="scss" scoped>
  .ff-fleet-members__events-text {
    text-align: center;
  }
</style>
