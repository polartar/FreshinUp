<template>
  <v-dialog
    :value="value"
    max-width="1200"
    @input="$emit('input', $event)"
  >
    <v-card>
      <document-preview-header
        @close="$emit('input', false)"
      />
      <v-divider />
      <document-preview-content />
    </v-card>
  </v-dialog>
</template>

<script>
import DocumentPreviewHeader from '~/components/docs/DocumentPreviewHeader'
import DocumentPreviewContent from '~/components/docs/DocumentPreviewContent'

export default {
  components: {
    DocumentPreviewHeader,
    DocumentPreviewContent
  },
  props: {
    value: {
      type: Boolean,
      default: false
    }
  },
  methods: {
    downloadPDF () {
    }
  }
}
</script>
