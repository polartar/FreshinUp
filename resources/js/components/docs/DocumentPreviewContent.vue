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
          Fleet Member AGREEMENT
        </v-flex>
        <v-flex
          sm3
          class="body-2 text-xs-right"
        >
          Expiration Date: 5/22/2020
        </v-flex>
      </v-layout>
      <v-divider />
      <v-layout
        mt-3
      >
        <div
          v-html="'<p> I am here</p>'"
        />
      </v-layout>
      <v-layout
        class="mb-3"
      >
        <document-preview-signature
          signeeName="Food Fleet Signature"
        />
      </v-layout>
      <v-layout>
        <document-preview-signature
          signeeName="Restaurant Owner Name - 2 Brothers Kitchen"
        />
      </v-layout>
    </v-card>
  </v-layout>
</template>

<script>
import DocumentPreviewSignature from '~/components/docs/DocumentPreviewSignature'

export default {
  components: {
    DocumentPreviewSignature
  },
  methods: {
    downloadPDF() {
    }
  }
}
</script>
