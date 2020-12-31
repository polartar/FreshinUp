<template>
  <v-layout>
    <v-card width="100%">
      <v-card-title class="justify-space-between px-4 py-2">
        <span class="black--text font-weight-bold title text-uppercase">Event Fleet</span>
        <v-btn
          depressed
          color="primary"
          @click="addNew"
        >
          <v-icon
            left
          >
            add_circle
          </v-icon>
          Add new fleet member
        </v-btn>
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
    StoreFilter,
  },
  props: {
    types: { type: Array, default: () => [] },
    statuses: { type: Array, default: () => [] },
    stores: { type: Array, default: () => [] },
  },
  methods: {
    addNew () {
      this.$emit('manage-create')
      this.$emit('manage', 'create')
    },
    viewItem (item) {
      this.$emit('manage', 'view', item)
      this.$emit('manage-view', item)
    },
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
