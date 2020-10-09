<template>
  <v-layout
    column
    px-4
    py-3
    justify-stretch
  >
    <v-layout>
      <v-flex
        sm3
        mr-2
      >
        <v-select
          background-color="primary"
          color="success"
          solo
          flat
          hide-details
          class="body-2"
          :items="['Sample Event: LA Stadium Fest', 'Event Samples']"
        />
      </v-flex>
      <v-flex
        sm3
        ml-2
      >
        <v-select
          background-color="primary"
          color="success"
          solo
          flat
          hide-details
          class="body-2"
          :items="['Contract: 2 Brothers Kitchen', 'Contract samples']"
        />
      </v-flex>
      <v-flex
        sm3
      />
      <v-flex
        sm3
        text-xs-right
      >
        <v-btn
          color="primary"
          class="mx-0"
          @click="downloadPDF"
        >
          Download PDF
        </v-btn>
      </v-flex>
    </v-layout>
    <v-card
      class="my-3 px-3"
    >
      <v-layout
        align-center
        my-3
      >
        <v-flex
          sm3
        >
          <v-img
            src="/images/logo.png"
            alt="main logo"
            :height="50"
            :width="160"
          />
        </v-flex>
        <v-divider
          vertical
        />
        <v-flex
          sm6
          pl-2
          class="title primary--text font-weight-bold"
        >
          {{ doc.title }}
        </v-flex>
        <v-flex
          v-if="doc.expiration_at"
          sm3
          class="body-2 text-xs-right"
        >
          {{ doc.expiration_at | showExpirationDate }}
        </v-flex>
      </v-layout>
      <v-divider />
      <v-layout
        mt-3
      >
        <div
          v-html="doc.description"
        />
      </v-layout>
      <v-layout
        class="mb-3"
      >
        <document-preview-signature
          signee-name="Food Fleet Signature"
        />
      </v-layout>
      <v-layout
        v-if="doc.owner && doc.owner.name.length"
      >
        <document-preview-signature
          :signee-name="doc.owner.name"
        />
      </v-layout>
    </v-card>
  </v-layout>
</template>

<script>
import moment from 'moment'
import DocumentPreviewSignature from '~/components/docs/DocumentPreviewSignature'

export default {
  components: {
    DocumentPreviewSignature
  },
  filters: {
    showExpirationDate (date) {
      return 'Expiration Date:' + moment(date).calendar()
    }
  },
  props: {
    doc: {
      type: Object,
      required: true
    }
  },
  methods: {
    downloadPDF () {
    }
  }
}
</script>
