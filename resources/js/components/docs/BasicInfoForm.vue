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
        data-vv-name="type"
        :error-messages="errors.collect('type')"
        single-line
        outline
        :items="types"
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
        :items="templates"
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
import Validate from 'fresh-bus/components/mixins/Validate'
export default {
  components: {
    FileUploader
  },
  mixins: [Validate],
  props: {
    types: {
      type: Array,
      required: true
    },
    templates: {
      type: Array,
      default: () => []
    },
    initdata: {
      type: Object,
      default: () => {
        return {
          title: null,
          type: null,
          description: null,
          notes: null,
          template: null,
          file: { name: null, src: null }
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
