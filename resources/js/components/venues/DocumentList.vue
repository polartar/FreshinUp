<template>
  <v-card>
    <v-card-title class="px-3">
      <v-layout
        align-center
        justify-center
        row
        fill-height
      >
        <v-flex>
          <h3 class="grey--text">
            Venue Documents
          </h3>
        </v-flex>
        <v-flex shrink>
          <v-dialog
            v-model="newDocumentDialog"
            max-width="400"
          >
            <template v-slot:activator="{ on }">
              <v-btn
                slot="activator"
                color="primary"
                dark
                @click="newDocumentDialog = true"
              >
                <v-icon
                  dark
                  left
                >
                  add_circle_outline
                </v-icon>Add New Document
              </v-btn>
            </template>
            <v-card>
              <v-divider />
              <v-card-text class="grey--text">
                Coming Soon
              </v-card-text>
              <v-divider />
            </v-card>
          </v-dialog>
        </v-flex>
      </v-layout>
    </v-card-title>
    <hr>

    <v-card-text class="ma-2">
      <f-data-table
        :headers="headers"
        :items="items"
        :is-loading="isLoading"
        :item-actions="itemActions"
        :multi-item-actions="itemActions"
        item-key="id"
        v-bind="$attrs"
        v-on="$listeners"
      >
        <template v-slot:item-inner-status="{ item }">
          <status-select
            v-model="item.status"
            :options="statuses"
            @input="changeStatus($event, item)"
          />
        </template>
        <template v-slot:item-inner-title="{ item }">
          <div class="grey--text">
            {{ item.title }}
          </div>
        </template>
        <template v-slot:item-inner-created_at="{ item }">
          <div class="grey--text">
            {{ formatDate(item.created_at, "MMM DD, YYYY") }}
          </div>
        </template>
        <template v-slot:item-inner-expiration_at="{ item }">
          <div class="grey--text">
            {{ formatDate(item.expiration_at, "MMM DD, YYYY") }}
          </div>
        </template>
      </f-data-table>
    </v-card-text>
  </v-card>
</template>

<script>
import FormatDate from 'fresh-bus/components/mixins/FormatDate'
import StatusSelect from '~/components/docs/StatusSelect'
import FDataTable from '@freshinup/core-ui/src/components/FDataTable'
export default {
  components: { StatusSelect, FDataTable },
  mixins: [FormatDate],
  props: {
    items: {
      type: Array,
      default: () => []
    },
    statuses: {
      type: Array,
      default: () => []
    }
  },
  data () {
    return {
      selected: [],
      headers: [
        { text: 'Status', sortable: true, value: 'status', align: 'center', width: '200' },
        { text: 'Document', sortable: true, value: 'title', align: 'left', width: '300' },
        { text: 'Submitted on', sortable: true, value: 'created_at', align: 'center' },
        { text: 'Expiration date', sortable: true, value: 'expiration_at', align: 'center' },
        { text: 'Manage', sortable: false, value: 'manage', align: 'center' }
      ],
      itemActions: [
        { action: 'view', text: 'View / Edit' },
        { action: 'delete', text: 'Delete' }
      ],
      actionBtnTitle: 'Manage',
      newDocumentDialog: false
    }
  },
  computed: {
    selectedDocActions () {
      if (!this.selected.length) return []
      let actions = []
      actions.push({ action: 'delete', text: 'Delete' })
      return actions
    }
  },
  methods: {
    changeStatus (value, doc) {
      this.$emit('change-status', value, doc)
    }
  }
}
</script>
