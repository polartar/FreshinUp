<template>
  <v-card>
    <v-card-title
      class="justify-space-between px-4 py-2"
    >
      <h3
        class="grey--text"
      >
        Preview Document
      </h3>
      <v-btn
        flat
        color="primary"
        class="mr-0"
        @click="onClose"
      >
        Close
      </v-btn>
    </v-card-title>
    <v-divider />
    <v-card-text>
      <v-layout
        column
        px-4
        py-3
        justify-stretch
      >
        <v-layout>
          <v-flex
            xs12
            m3
            mr-2
          >
            <v-select
              v-model="event_store_uuid"
              item-text="name"
              item-value="uuid"
              background-color="primary"
              color="success"
              solo
              flat
              hide-details
              :items="events"
              class="white--text"
            />
          </v-flex>
          <v-flex
            sm3
            ml-2
          >
            <v-select
              v-model="template_uuid"
              disabled
              background-color="primary"
              item-text="title"
              item-value="uuid"
              color="success"
              solo
              flat
              hide-details
              class="white--text"
              :items="templates"
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
              v-if="downloadable"
              :disabled="!attachment"
              :href="attachment"
              download
              target="_blank"
              color="primary"
              class="mx-0"
            >
              Download PDF
            </v-btn>
          </v-flex>
        </v-layout>
        <v-layout
          align-center
          px-3
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
            {{ templateTitle }}
          </v-flex>
          <v-flex
            v-if="expiration_at"
            sm3
            class="body-2 text-xs-right"
          >
            Expiration Date: {{ formatDate(expiration_at, 'MM / DD / YYYY') }}
          </v-flex>
        </v-layout>
        <v-divider />
        <v-layout
          mt-3
        >
          <div
            v-html="content"
          />
        </v-layout>
        <v-layout>
          <v-layout>
            <v-flex
              sm4
              class="mr-3 py-3"
            >
              <v-divider />
              <div
                class="body-1 mt-2"
              >
                Food Fleet Signature
              </div>
            </v-flex>
            <v-flex
              sm4
              class="mx-3 py-3"
            >
              <v-divider />
              <div
                class="body-1 mt-2"
              >
                Printed Name
              </div>
            </v-flex>
            <v-flex
              sm4
              class="ml-3 py-3"
            >
              <v-divider />
              <div
                class="body-1 mt-2"
              >
                Date
              </div>
            </v-flex>
          </v-layout>
        </v-layout>
        <v-layout>
          <v-layout>
            <v-flex
              sm4
              class="mr-3 py-3"
            >
              <v-divider />
              <div
                class="body-1 mt-2"
              >
                Restaurant Owner Name: {{ ownerName }}
              </div>
            </v-flex>
            <v-flex
              sm4
              class="mx-3 py-3"
            >
              <v-divider />
              <div
                class="body-1 mt-2"
              >
                Printed Name
              </div>
            </v-flex>
            <v-flex
              sm4
              class="ml-3 py-3"
            >
              <v-divider />
              <div
                class="body-1 mt-2"
              >
                Date
              </div>
            </v-flex>
          </v-layout>
        </v-layout>
      </v-layout>
    </v-card-text>
  </v-card>
</template>

<script>
import FormatDate from '@freshinup/core-ui/src/mixins/FormatDate'
import get from 'lodash/get'
import MapValueKeysToData from '../../mixins/MapValueKeysToData'
import Mustache from 'mustache'

export const DEFAULT_DOCUMENT = {
  uuid: '',
  title: '',
  status_id: '',
  type_id: '',
  description: '',
  notes: '',
  owner: '',
  assigned: '',
  assigned_type: '',
  expiration_at: '',
  created_at: '',
  updated_at: '',
  template_uuid: '',
  template: {},
  event_store_uuid: '',
  file: {}
}

// TODO who are _restaurant owner_ and _fleet member_ for document
export default {
  mixins: [FormatDate, MapValueKeysToData],
  props: {
    value: { type: Object, default: () => DEFAULT_DOCUMENT },
    templates: { type: Array, default: () => [] },
    events: { type: Array, default: () => [] },
    variables: { type: Object, default: () => {} }
  },
  data () {
    return {
      ...DEFAULT_DOCUMENT
    }
  },
  computed: {
    templateTitle () {
      return get(this, 'template.title')
    },
    attachment () {
      return get(this, 'file.src')
    },
    ownerName () {
      return get(this, 'assigned.name')
    },
    downloadable () {
      return !!get(this, 'template_uuid')
    },
    templatesByUuid () {
      return this.templates.reduce((map, template) => {
        map[template.uuid] = template
        return map
      }, {})
    },
    selectedTemplate () {
      return this.templatesByUuid[this.template_uuid]
    },
    content () {
      const html = get(this.selectedTemplate, 'content', '')
      return Mustache.render(html, this.variables)
    }
  },
  methods: {
    get,
    onClose () {
      this.$emit('close')
    }
  }
}
</script>
