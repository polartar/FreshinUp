<template>
  <div>
    <v-data-table
      v-model="selected"
      class="elevation-1"
      :headers="headers"
      :items="storeAreas"
      :rows-per-page-items="[5, 10, 15, 25, 30, 50]"
      :pagination.sync="pagination"
      :loading="isLoading"
      :total-items="totalItems"
      item-key="uuid"
      select-all
      disable-initial-sort
    >
      <v-progress-linear
        slot="progress"
        indeterminate
        height="10"
      />
      <template
        slot="headerCell"
        slot-scope="props"
      >
        <span>
          {{ props.header.text }}
        </span>
      </template>
      <template slot="no-data">
        <v-alert
          :value="true"
          color="error"
          icon="warning"
        >
          Sorry, nothing to display here :(
        </v-alert>
      </template>

      <template
        slot="items"
        slot-scope="props"
      >
        <td>
          <v-checkbox
            v-model="props.selected"
            primary
            hide-details
          />
        </td>
        <td>
          <div class="subheading primary--text">
            {{ props.item.name }}
          </div>
        </td>
        <td>
          <div class="grey--text">
            {{ props.item.state }}
          </div>
        </td>
        <td>
          <div class="grey--text">
            {{ props.item.radius }}
          </div>
        </td>
        <td class="justify-center">
          <v-btn
            large
            class="primary"
            @click="delete(props.item)"
          >
            Delete
          </v-btn>
        </td>
      </template>
    </v-data-table>
  </div>
</template>

<script>
import Pagination from 'fresh-bus/components/mixins/Pagination'
export const HEADERS = [
  { text: 'Area Name', sortable: true, value: 'name' },
  { text: 'State', sortable: true, value: 'state' },
  { text: 'Radius', sortable: true, value: 'radius' },
  { text: 'Manage', sortable: false, value: 'manage' }
]
export default {
  mixins: [
    Pagination
  ],
  props: {
    storeAreas: {
      type: Array,
      default: () => []
    }
  },
  data () {
    return {
      selected: [],
      headers: HEADERS,
      actionBtnTitle: 'Delete'
    }
  },
  methods: {
    delete (item) {
      this.$emit('manage', item.action)
    }
  }
}
</script>
