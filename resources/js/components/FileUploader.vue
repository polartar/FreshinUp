<template>
  <v-layout
    column
    fill-height
  >
    <v-layout
      row
      class="subheading font-weight-bold"
    >
      <v-flex
        v-if="uploading"
      >
        <v-progress-linear v-model="uploadPercentage" />
        <div>{{ uploadPercentage }}%</div>
      </v-flex>
      <v-flex
        v-else
      >
        File:
        <a
          v-if="isDownloadable"
          flat
          :href="src"
          download
        >
          {{ name }}
        </a>
      </v-flex>
    </v-layout>
    <v-layout
      row
      wrap
      justify-space-between
    >
      <v-flex
        xs5
      >
        <v-btn
          block
          color="primary"
          @click="launchFilePicker"
        >
          Upload file
        </v-btn>
      </v-flex>
      <v-flex
        xs5
      >
        <v-btn
          block
          color="error"
          @click="remove"
        >
          Delete File
        </v-btn>
      </v-flex>
    </v-layout>

    <input
      v-show="false"
      ref="file"
      type="file"
      @change="onFileChange($event.target.files)"
    >

    <!-- error dialog displays any potential errors -->
    <v-dialog
      v-model="errorDialog"
      max-width="300"
    >
      <v-card>
        <v-card-text class="subheading">
          {{ errorText }}
        </v-card-text>
        <v-card-actions>
          <v-spacer />
          <v-btn
            flat
            @click="errorDialog = false"
          >
            Got it!
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-layout>
</template>

<script>
import axios from 'axios'
import MapValueKeysToData from '../mixins/MapValueKeysToData'
import get from 'lodash/get'

const MB = 1024 * 1024
export const DEFAULT_VALUE = {
  name: '',
  src: ''
}

/**
 * @property string name
 * @property string src
 */
export default {
  mixins: [MapValueKeysToData],
  model: {
    prop: 'value',
    event: 'onValueChange'
  },
  props: {
    value: { type: Object, default: () => DEFAULT_VALUE },
    maxFileSize: { type: Number, default: 100 }
  },
  data () {
    return {
      ...DEFAULT_VALUE,
      errorDialog: false,
      errorText: '',
      uploadPercentage: 0,
      uploading: false
    }
  },
  computed: {
    maxInBytes () {
      return MB * this.maxFileSize
    },
    isDownloadable () {
      return Boolean(this.src && this.name)
    }
  },
  methods: {
    launchFilePicker () {
      this.$refs.file.click()
    },
    async onFileChange (files) {
      const fileSource = get(files, '0')
      if (!fileSource) {
        this.showError(`No file selected.`)
        return false
      }
      const size = fileSource.size - this.maxInBytes
      if (size > 1) {
        this.showError(`Your file is too big! Please select a file under ${this.maxFileSize}MB`)
        return false
      }
      const filePath = await this.submitFile(fileSource)
      if (filePath) {
        this.src = filePath
        this.name = fileSource.name
        this.$emit('input', { name: fileSource.name, src: filePath })
      }
    },
    async submitFile (file) {
      try {
        this.uploading = true
        let formData = new FormData()
        formData.append('file', file)
        const response = await axios.post('/foodfleet/tmp-media',
          formData,
          {
            headers: {
              'Content-Type': 'multipart/form-data'
            },
            onUploadProgress: function (progressEvent) {
              this.uploadPercentage = parseInt(Math.round((progressEvent.loaded * 100) / progressEvent.total)) || 0
            }.bind(this)
          }
        )
        this.uploading = false
        this.uploadPercentage = 0
        return get(response, 'data')
      } catch (e) {
        this.uploading = false
        this.uploadPercentage = 0
        return false
      }
    },
    showError (text) {
      this.errorText = text
      this.errorDialog = true
    },
    remove () {
      this.$refs.file.value = ''
      this.$emit('input', DEFAULT_VALUE)
    }
  }
}
</script>
