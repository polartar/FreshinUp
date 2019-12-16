<template>
  <v-layout>
    <v-card width="100%">
      <v-card-title class="justify-space-between px-4">
        <span class="black--text font-weight-bold title text-uppercase">Event Fleet</span>
      </v-card-title>
      <hr>
      <div class="pa-4">
        <v-layout
          row
        >
          <v-flex>
            <store-filter
              :types="types"
              :statuses="statuses"
              @runFilter="filterStores"
            />
          </v-flex>
        </v-layout>
        <v-layout
          row
        >
          <v-flex>
            <store-list
              :stores="stores"
              :statuses="statuses"
              @manage-view-details="viewDetails"
              @manage-unassign="unassign"
              @manage-multiple-unassign="multipleUnassign"
            />
          </v-flex>
        </v-layout>
      </div>
    </v-card>
  </v-layout>
</template>

<script>
import StoreList from './StoreList.vue'
import StoreFilter from './StoreFilter.vue'
export default {
  components: {
    StoreList,
    StoreFilter
  },
  props: {
    types: {
      type: Array,
      default: () => []
    },
    statuses: {
      type: Array,
      default: () => []
    },
    stores: {
      type: Array,
      default: () => []
    }
  },
  methods: {
    filterStores (params) {
      this.$emit('filter-stores', params)
    },
    viewDetails (params) {
      this.$emit('manage-view-details', params)
    },
    unassign (params) {
      this.$emit('manage-unassign', params)
    },
    multipleUnassign (params) {
      this.$emit('manage-multiple-unassign', params)
    }
  }
}
</script>

<style scoped>
</style>
