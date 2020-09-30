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
                </v-icon>
                Add New Document
              </v-btn>
            </template>
            <v-card>
              <v-divider />
              <v-card-text class="grey--text">
                <document-form />
              </v-card-text>
              <v-divider />
            </v-card>
          </v-dialog>
        </v-flex>
      </v-layout>
    </v-card-title>
    <hr>
    <v-card-text class="ma-2">
      <document-list
        :is-loading="isLoading"
        :items="items"
        :statuses="statuses"
        v-bind="$attrs"
        v-on="$listeners"
      />
    </v-card-text>
  </v-card>
</template>

<script>
import DocumentList from './DocumentList'
import DocumentForm from './DocumentForm'

export default {
  components: { DocumentList, DocumentForm },
  props: {
    isLoading: { type: Boolean, default: false },
    items: { type: Array, default: () => [] },
    statuses: { type: Array, default: () => [] }
  },
  data () {
    return {
      newDocumentDialog: false
    }
  }
}
</script>
