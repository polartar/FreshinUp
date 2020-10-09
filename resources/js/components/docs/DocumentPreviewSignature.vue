<template>
  <v-layout
    column
    mb-3
  >
    <v-layout>
      <v-flex
        v-for="item in signatureItems"
        :key="item.name"
        sm4
      >
        <document-preview-signature-item
          :item="item.name"
          :class="item.class"
        />
      </v-flex>
    </v-layout>
  </v-layout>
</template>

<script>
import DocumentPreviewSignatureItem from '~/components/docs/DocumentPreviewSignatureItem'

export default {
  components: {
    DocumentPreviewSignatureItem
  },
  props: {
    signeeName: {
      type: String,
      required: true
    }
  },
  data() {
    return {
      signatureItems: [
        {
          name: this.signeeName,
          class: 'mr-3'
        },
        {
          name: 'Printed Name',
          class: 'mx-3'
        },
        {
          name: 'Date',
          class: 'ml-3'
        }
      ]
    }
  },
}

</script>
