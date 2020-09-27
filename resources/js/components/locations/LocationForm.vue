<template>
  <div
    class="grey--text"
  >
    <v-flex>
      <v-layout row>
        <v-flex
          xs12
          sm6
        >
          <div class="mb-2 text-uppercase grey--text font-weight-bold">
            Category
          </div>
          <v-select
            v-model="category_id"
            :items="categories"
            item-text="name"
            item-value="id"
            single-line
            outline
          />
        </v-flex>
      </v-layout>
    </v-flex>
    <div v-if="isIndoor">
      <input
        type="file"
        multiple
        class="ff-add-location__file_input"
        :accept="acceptedFormats"
        @change="onFileChange"
      >
      <div
        v-for="(file, fileIndex) in files"
        :key="fileIndex"
      >
        <div class="ff-add-location__document_item">
          <span class="text-uppercase primary--text font-weight-bold">{{ file.name }}</span>
          <span class="font-weight-bold">
            {{ file.size | formatFileSize }}
            <v-btn
              flat
              icon
              @click="removeFile(file)"
            >
              <v-icon
                color="grey"
                size="15"
              >fas fa-trash
              </v-icon>
            </v-btn>
          </span>
        </div>
        <v-divider />
      </div>
      <v-layout
        column
        class="align-center"
      >
        <v-flex>
          <v-btn
            class="white--text"
            depressed
            color="grey"
            @click="triggerFilePicker"
          >
            <v-icon
              dark
              left
            >
              fas fa-upload
            </v-icon>
            upload Floor Image / Attachment
          </v-btn>
        </v-flex>
      </v-layout>
    </div>
    <v-flex
      v-if="!isIndoor"
      text-xs-center
    >
      MAP coming soon
      <div style="background-color: #e5e5e5; height: 150px" />
    </v-flex>
    <v-layout>
      <v-flex pr-2>
        <div class="mb-2 text-uppercase grey--text font-weight-bold">
          Location
        </div>
        <v-text-field
          v-model="name"
          single-line
          outline
        />
      </v-flex>
      <v-flex pl-2>
        <div class="mb-2 text-uppercase grey--text font-weight-bold">
          Capacity
        </div>
        <v-text-field
          v-model="capacity"
          single-line
          outline
        />
      </v-flex>
    </v-layout>
    <v-flex>
      <div class="mb-2 text-uppercase grey--text font-weight-bold">
        Description
      </div>
      <v-textarea
        v-model="details"
        single-line
        outline
      />
    </v-flex>
    <v-flex class="py-3 d-flex justify-space-between">
      <v-btn
        depressed
        @click="onCancel"
      >
        Cancel
      </v-btn>
      <v-btn
        depressed
        color="primary"
        @click="save"
      >
        Save changes
      </v-btn>
    </v-flex>
  </div>
</template>

<script>
import MapValueKeysToData from '../../mixins/MapValueKeysToData'
import FormatFileSize from '../../mixins/FormatFileSize'
import axios from 'axios'
import get from 'lodash/get'
import keys from 'lodash/keys'
import pick from 'lodash/pick'

export const DEFAULT_LOCATION = {
  name: '',
  venue_uuid: '',
  spots: '',
  capacity: '',
  details: '',
  category_id: '',
  files: []
}

/**
   * These comments are just for intellisense (IDE)
   * @property {string} name
   * @property {string} venue_uuid
   * @property {number} spots
   * @property {number} capacity
   * @property {number} category_id
   * @property {string} details
   * @property {Object[]} files
   */
export default {
  filters: {
    formatFileSize: FormatFileSize.methods.formatFileSize
  },
  mixins: [MapValueKeysToData, FormatFileSize],
  props: {
    categories: { type: Array, default: () => [] },
    documents: { type: Array, default: () => [] },
    allowedFormats: {
      type: Array,
      default: () => [
        'PDF', 'JPG', 'JPEG', 'PNG', 'GIF', 'DOCX', 'PPTX'
      ]
    }
  },
  data () {
    return {
      ...DEFAULT_LOCATION
    }
  },
  computed: {
    isIndoor () {
      return this.category_id === 1
    },
    acceptedFormats () {
      return this.allowedFormats.map(format => `.${format.toLowerCase()}`).join(',')
    }
  },
  watch: {
    documents (value) {
      this.files = this.documents.map(document => document.file)
    }
  },
  mounted () {
    this.files = this.documents.map(document => document.file)
  },
  methods: {
    save () {
      this.$emit('input', pick(this, [...keys(this.value), 'files']))
    },
    onCancel () {
      this.$emit('cancel')
    },
    triggerFilePicker () {
      const image = this.$el.querySelector('.ff-add-location__file_input')
      if (!image) {
        return false
      }
      image.click()
      return true
    },
    async onFileChange (e) {
      const files = e.target.files
      for (const file of files) {
        await this.submitFile(file)
        this.files.push(file)
      }
    },
    submitFile (file) {
      return new Promise(async (resolve, reject) => {
        try {
          file.uploading = true
          let formData = new FormData()
          formData.append('file', file)
          const response = await axios.post('/foodfleet/tmp-media',
            formData,
            {
              headers: {
                'Content-Type': 'multipart/form-data'
              },
              onUploadProgress: function (progressEvent) {
                file.uploadPercentage = parseInt(Math.round((progressEvent.loaded * 100) / progressEvent.total)) || 0
              }
            }
          )
          file.uploading = false
          file.uploadPercentage = 0
          resolve(get(response, 'data', {}))
        } catch (e) {
          file.uploading = false
          file.uploadPercentage = 0
          reject(e)
        }
      })
    },
    removeFile (file) {
      // Server will handle deletion of tmp file
      this.files = this.files.filter(f => f !== file)
    }
  }
}
</script>

<style scoped>
  .ff-add-location__file_input {
    display: none;
  }

  .ff-add-location__document_item {
    display: flex;
    justify-content: space-between;
    align-items: center
  }
</style>
