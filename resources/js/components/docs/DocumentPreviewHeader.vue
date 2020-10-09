<template>
  <v-card-title
    class="justify-space-between px-4 py-2"
  >
    <h3
      class="grey--text"
    >
      Preview Document
    </h3>
    <v-btn
      flat
      color="primary"
      class="mr-0"
      @click="$emit('close')"
    >
      Close
    </v-btn>
  </v-card-title>
</template>
