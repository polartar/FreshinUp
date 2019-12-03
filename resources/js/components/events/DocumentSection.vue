<template>
  <div class="white">
    <div class="right py-3 px-4">
      <v-btn
        color="primary"
        @click="createNewDoc"
      >
        <v-icon>
          add_circle
        </v-icon>
        <span>
          Add New Document
        </span>
      </v-btn>
    </div>

    <div>
      <DocumentList
        :statuses="statuses"
        :documents="documents"
        @view-details="viewDetails"
        @change-status="changeStatus"
      />
    </div>

    <div class="right py-3 px-4">
      <v-btn
        color="primary"
        @click="createNewDoc"
      >
        <v-icon>
          add_circle
        </v-icon>
        <span>
          Add New Document
        </span>
      </v-btn>
    </div>
  </div>
</template>

<script>
import DocumentList from './DocumentList'

export default {
  name: 'DocumentSection',
  components: { DocumentList },
  props: {
    statuses: {
      type: Array,
      default: () => []
    },
    documents: {
      type: Array,
      default: () => []
    }
  },
  methods: {
    createNewDoc () {
      this.$router.push({ path: '/admin/docs/new' })
    },
    viewDetails (docUUID) {
      this.$router.push({ path: `/admin/docs/${docUUID}` })
    },
    changeStatus (value, doc) {
      this.$emit('change-status', value, doc)
    }
  }
}
</script>

<style scoped>

</style>
