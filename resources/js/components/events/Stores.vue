<template>
  <v-layout>
    <v-card width="100%">
      <v-card-title class="justify-space-between px-4 py-2">
        <span class="black--text font-weight-bold title text-uppercase">Event Fleet</span>
        <v-btn
          depressed
          color="primary"
          @click.stop="showNewMemberDialog = true"
        >
          <v-icon
            left
          >
            add_circle
          </v-icon>
          Add new fleet member
        </v-btn>
      </v-card-title>
      <v-dialog
        v-model="showNewMemberDialog"
        max-width="900"
      >
        <v-card>
          <v-card-title class="justify-space-between px-4 py-2">
            <span
              class="subheading font-weight-bold grey--text text--darken-1"
            >
              Add fleet member
            </span>
            <v-btn
              small
              round
              depressed
              color="blue-grey lighten-3 white--text"
              @click="showNewMemberDialog = false"
            >
              <v-icon
                left
                class="white--text"
              >
                close
              </v-icon>
              Close
            </v-btn>
          </v-card-title>
          <hr>
          <add-store
            :event="event"
            :stores="stores"
            :store-types="types"
            class="mb-2"
          />
        </v-card>
      </v-dialog>
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
import AddStore from './AddStore'

export default {
  components: {
    StoreList,
    StoreFilter,
    AddStore
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
    },
    event: {
      type: Object,
      default: null
    }
  },
  data () {
    return {
      showNewMemberDialog: false
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
