<template>
  <v-layout>
    <v-card
      width="100%"
    >
      <v-card-title class="justify-space-between px-4">
        <span class="black--text font-weight-bold title text-uppercase">Customer</span>
      </v-card-title>
      <hr>
      <div class="pa-4">
        <v-layout
          row
        >
          <v-flex v-if="statuses.length">
            <customer-list
              :customers="customers"
              :statuses="statuses"
              @change-status="changeStatus"
              @view-details="viewDetails"
            />
          </v-flex>
        </v-layout>
      </div>
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
    customers: {
      type: Array,
      default: () => []
    },
    statuses: {
      type: Array,
      default: () => []
    }
  },
  methods: {
    changeStatus (params) {

    },
    viewDetails (params) {
      this.$emit('manage-view-details', params)
    }
  }
}
</script>
