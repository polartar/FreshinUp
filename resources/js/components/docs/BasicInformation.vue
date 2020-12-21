<template>
  <v-layout
    row
    wrap
  >
    <v-flex
      sm12
      md8
      lg9
    >
      <v-card class="ml-2 mt-2">
        <v-card-title class="subheading text-uppercase font-weight-bold">
          Basic information
        </v-card-title>
        <v-divider />
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
              v-model="title"
              v-validate="'required'"
              single-line
              outline
              data-vv-name="title"
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
              v-model="type_id"
              v-validate="'required'"
              data-vv-name="type_id"
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
              v-model="description"
              v-validate="'required'"
              single-line
              outline
              data-vv-name="description"
              label="Short description"
            />
          </v-flex>
          <v-flex
            v-if="type_id === 1"
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
              v-model="template_uuid"
              single-line
              outline
              :items="templates"
              item-text="title"
              item-value="uuid"
              data-vv-name="template_uuid"
              label="Document Template"
              @input="changeTemplate"
            />
          </v-flex>
          <v-flex
            v-else-if="type_id === 2"
            md7
            sm12
            mb-4
            pt-5
          >
            <file-uploader
              :value="file"
              @input="file = $event"
            />
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
              v-model="notes"
              single-line
              outline
              data-vv-name="notes"
              label="Notes / Additional Info"
            />
          </v-flex>
          <v-flex>
            <v-btn
              @click="cancel"
            >
              Cancel
            </v-btn>
            <v-btn
              color="primary"
              :loading="isLoading"
              @click="save"
            >
              {{ submitLabel }}
            </v-btn>
          </v-flex>
        </v-layout>
      </v-card>
    </v-flex>
    <v-flex
      sm12
      md4
      lg3
    >
      <v-card class="ml-2 mt-2">
        <v-card-title>
          Publishing
        </v-card-title>
        <v-layout
          row
          wrap
          pa-3
        >
          <v-flex
            xs12
            mb-3
          >
            <h3
              class="title font-weight-bold mb-2"
            >
              Submitted on
            </h3>
            <div v-if="ownerName">
              {{ createdAt }} by {{ ownerName }}
            </div>
          </v-flex>
          <v-flex
            xs12
          >
            <h4
              class="title font-weight-bold mb-2"
            >
              Assigned to
            </h4>
            <AssignedSearch
              :value="assigned_uuid"
              :type="assigned_type"
              @assign-change="selectAssigned"
              @type-change="changeAssignedType"
            />
          </v-flex>
          <v-flex
            xs12
          >
            <h4
              class="title font-weight-bold mb-2"
            >
              Expiration Date
            </h4>
            <vue-ctk-date-time-picker
              v-model="expiration_at"
              only-date
              format="YYYY-MM-DD"
              formatted="MM-DD-YYYY"
              input-size="lg"
              :color="$vuetify.theme.primary"
              :button-color="$vuetify.theme.primary"
            />
          </v-flex>
          <v-flex
            xs12
            mt-3
          >
            <v-layout
              row
              wrap
              justify-space-between
            >
              <v-flex
                xs12
                sm-6
              >
                <v-btn
                  block
                  :disabled="downloadable"
                  @click="previewOrDownload"
                >
                  {{ previewOrDownloadLabel }}
                </v-btn>
              </v-flex>
              <v-flex
                xs12
                sm-6
              >
                <v-btn
                  block
                  class="primary"
                  :loading="isLoading"
                  @click="save"
                >
                  {{ submitLabel }}
                </v-btn>
              </v-flex>
            </v-layout>
          </v-flex>
        </v-layout>
      </v-card>
    </v-flex>
  </v-layout>
</template>
<script>
import FileUploader from '~/components/FileUploader.vue'
import Validate from 'fresh-bus/components/mixins/Validate'
import MapValueKeysToData from '../../mixins/MapValueKeysToData'
import get from 'lodash/get'
import 'vue-ctk-date-time-picker/dist/vue-ctk-date-time-picker.css'
import VueCtkDateTimePicker from 'vue-ctk-date-time-picker'
import AssignedSearch from '~/components/docs/AssignedSearch.vue'
import FormatDate from '@freshinup/core-ui/src/mixins/FormatDate'

export const DEFAULT_DOCUMENT = {
  uuid: null,
  title: null,
  type_id: null,
  status_id: 1,
  description: null,
  notes: null,
  template: {},
  template_uuid: null,
  file: { name: null, src: null },
  owner: null,
  created_at: null,
  assigned: null,
  assigned_uuid: null,
  assigned_type: 1,
  expiration_at: null,
  event_store_uuid: null
}

/**
 * @property string uuid
 * @property string title
 * @property int type_id
 * @property string description
 * @property string notes
 * @property string template_uuid
 * @property {{ name: null, src: null }} file
 */
export default {
  components: {
    FileUploader,
    VueCtkDateTimePicker,
    AssignedSearch
  },
  mixins: [Validate, FormatDate, MapValueKeysToData],
  props: {
    isLoading: { type: Boolean, default: false },
    types: { type: Array, default: () => [] },
    templates: { type: Array, default: () => [] },
    // override value props from
    value: { type: Object, default: () => DEFAULT_DOCUMENT }
  },
  data () {
    return {
      ...DEFAULT_DOCUMENT
    }
  },
  computed: {
    submitLabel () {
      return this.isNew ? 'Submit' : 'Save changes'
    },
    ownerName () {
      return get(this.owner, 'name')
    },
    createdAt () {
      return this.created_at
        ? this.formatDate(this.created_at, 'MMM DD, YYYY • HH:mm a')
        : null
    },
    downloadable () {
      return get(this, 'type_id') === 2
    },
    previewOrDownloadLabel () {
      return this.downloadable ? 'Download' : 'Preview'
    },
    isNew () {
      return !get(this.value, 'uuid')
    }
  },
  methods: {
    previewOrDownload () {
      return this.downloadable ? this.download() : this.preview()
    },
    download () {
      this.$emit('download')
    },
    preview () {
      this.$emit('preview')
    },
    cancel () {
      this.$emit('cancel')
    },
    selectAssigned (assigned) {
      this.assigned_uuid = assigned.uuid
      this.event_store_uuid = assigned.event_store_uuid
    },
    changeAssignedType (value) {
      this.assigned_type = value
    },
    changeTemplate (template) {
      this.$emit('selectTemplate', template)
    }
  }
}
</script>
