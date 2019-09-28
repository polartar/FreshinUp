<template>
  <v-layout
    row
    wrap
    pa-3
    justify-space-between
  >
    <v-flex
      md7
      sm12
    >
      <v-layout
        row
        mb-2
      >
        Title
      </v-layout>
      <v-text-field
        v-model="doc.title"
        v-validate="'required'"
        single-line
        outline
        data-vv-name="title"
        :error-messages="errors.collect('title')"
        label="Title"
      />
    </v-flex>
    <v-flex
      md4
      sm12
    >
      <v-layout
        row
        mb-2
      >
        Type
      </v-layout>
      <v-select
        v-model="doc.type"
        v-validate="'required'"
        single-line
        outline
        :items="types"
        data-vv-name="type"
        :error-messages="errors.collect('type')"
        label="Type"
      />
    </v-flex>
    <v-flex
      md7
      sm12
    >
      <v-layout
        row
        mb-2
      >
        Short Description
      </v-layout>
      <v-textarea
        v-model="doc.description"
        v-validate="'required'"
        single-line
        outline
        data-vv-name="description"
        :error-messages="errors.collect('description')"
        label="Short description"
      />
    </v-flex>
    <v-flex
      v-if="doc.type === 1"
      md7
      sm12
    >
      <v-layout
        row
        mb-2
      >
        Document Template
      </v-layout>
      <v-select
        v-model="doc.template"
        single-line
        outline
        :items="templateOptions"
        data-vv-name="template"
        :error-messages="errors.collect('template')"
        label="Document Template"
      />
    </v-flex>
    <v-flex
      v-else-if="doc.type === 2"
      md7
      sm12
      mb-4
      pt-5
    >
      <file-uploader v-model="doc.file" />
    </v-flex>
    <v-flex
      xs12
    >
      <v-layout
        row
        mb-2
      >
        Notes / Additional Info
      </v-layout>
      <v-textarea
        v-model="doc.notes"
        single-line
        outline
        data-vv-name="notes"
        :error-messages="errors.collect('notes')"
        label="Notes / Additional Info"
      />
    </v-flex>
  </v-layout>
</template>
<script>
import FileUploader from '~/components/FileUploader.vue'
export default {
  components: {
    FileUploader
  },
  props: {
    types: {
      type: Array,
      default: () => []
    },
    templates: {
      type: Array,
      default: () => []
    },
    initdata: {
      type: Object,
      default: () => {
        return {
          title: '',
          type: '',
          description: '',
          notes: '',
          template: null,
          file: { name: '', src: '' }
        }
      }
    }
  },
  data () {
    return {
      doc: this.initdata
    }
  },
  watch: {
    doc: {
      handler (val) {
        this.$emit('data-change', val)
      },
      deep: true
    }
  }
}
</script>
