<template>
  <v-layout
    column
    fill-height
  >
    <v-layout
      row
      class="subheading font-weight-bold"
    >
      File: {{ value.name }}
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

const MB = 1024 * 1024

export default {
  name: 'FileUploader',
  model: {
    prop: 'value',
    event: 'onValueChange'
  },
  props: {
    value: {
      type: Object,
      default: () => {
        return { name: '', src: '' }
      }
    },
    maxFileSize: {
      type: Number,
      default: 100
    }
  },
  data () {
    return {
      errorDialog: false,
      errorText: ''
    }
  },
  computed: {
    maxInBytes () {
      return MB * this.maxFileSize
    }
  },
  methods: {
    launchFilePicker () {
      this.$refs.file.click()
    },
    onFileChange (file) {
      if (file.length > 0) {
        let fileSouce = file[0]
        let size = fileSouce.size - this.maxInBytes

        if (fileSouce.type !== 'application/pdf') {
          // check file type is pdf
          this.showError('Please choose a pdf file')
        } else if (size > 1) {
          // check size of file
          this.showError(`Your file is too big! Please select a file under ${this.maxFileSize}MB`)
        } else {
          const internalSrc = URL.createObjectURL(fileSouce)
          this.$emit('onValueChange', { name: fileSouce.name, src: internalSrc })
        }
      }
    },
    showError (text) {
      this.errorText = text
      this.errorDialog = true
    },
    remove () {
      this.$refs.file.value = ''
      this.$emit('onValueChange', { name: '', src: '' })
    }
  }
}
</script>

<style scoped>

</style>
