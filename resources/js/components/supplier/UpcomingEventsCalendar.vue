<template>
  <v-layout justify-between>
    <span class="grey--text">Upcoming events</span>
    <v-btn color="primary" round @click="viewAll">View All Events</v-btn>
  </v-layout>
</template>

<script>
  export default {
    methods: {
      viewAll () {
        this.$emit('view-all')
      }
    }
  }
</script>
