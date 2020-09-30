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
          <v-btn
            slot="activator"
            color="primary"
            dark
            href="/admin/docs/new"
          >
            <v-icon
              dark
              left
            >
              add_circle_outline
            </v-icon>
            Add New Document
          </v-btn>
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

export default {
  components: { DocumentList },
  props: {
    isLoading: { type: Boolean, default: false },
    items: { type: Array, default: () => [] },
    statuses: { type: Array, default: () => [] }
  }
}
</script>
