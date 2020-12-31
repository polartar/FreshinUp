<template>
  <v-layout>
    <v-card
      width="100%"
    >
      <v-card-title class="justify-space-between px-4">
        <span class="black--text font-weight-bold title text-uppercase">Customer</span>
      </v-card-title>
      <v-divider/>
      <v-layout
        row
        class="pa-4"
      >
        <v-flex v-if="statuses.length">
          <customer-list
            :customers="customers"
            :statuses="statuses"
            v-bind="$attrs"
            v-on="$listeners"
          />
        </v-flex>
      </v-layout>
    </v-card>
  </v-layout>
</template>

<script>
import CustomerList from './CustomerList.vue'
export default {
  components: {
    CustomerList
  },
  props: {
    customers: { type: Array, default: () => [] },
    statuses: { type: Array, default: () => [] }
  },
}
</script>
