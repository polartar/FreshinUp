<template>
  <v-layout>
    <v-flex
      xs12
      sm12
      md8
      xl9
    >
      <v-card>
        <v-card-title>
          <h3 class="grey--text">
            Basic Information
          </h3>
          <v-progress-linear
            v-if="isLoading"
            indeterminate
          />
        </v-card-title>
        <v-divider />
        <v-card-text>
          <div>
            <v-flex xs12>
              <div class="mb-2 text-uppercase grey--text font-weight-bold">
                Title
              </div>
              <v-text-field
                v-model="title"
                placeholder="Title"
                single-line
                outline
              />
            </v-flex>
            <v-flex xs12>
              <div class="mb-2 text-uppercase grey--text font-weight-bold">
                Short description
              </div>
              <v-text-field
                v-model="description"
                placeholder="Short description"
                single-line
                outline
              />
            </v-flex>
            <v-flex xs12>
              <div class="mb-2 text-uppercase grey--text font-weight-bold">
                Content
              </div>
              <v-textarea
                v-model="content"
                placeholder="Short description"
                single-line
                outline
              />
            </v-flex>
          </div>
        </v-card-text>
        <v-card-actions>
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
            {{ isNew? 'Submit' : 'Save changes' }}
          </v-btn>
          <v-spacer />
          <v-btn
            @click="deleteItem"
          >
            Delete
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-flex>
    <v-flex
      xs12
      sm12
      md4
      xl3
      class="ml-2"
    >
      <v-card>
        <v-card-text>
          <h3
            v-if="!isNew"
            class="font-weight-bold mb-2"
          >
            Last Modified on
          </h3>
          <span
            v-if="!isNew"
            class="mb-2"
          >{{ formatDate(updated_at) }} by <u>{{ updaterName }}</u></span>
          <v-select
            v-model="status_id"
            :items="statuses"
            item-value="id"
            item-text="name"
            outline
            single-line
          />
        </v-card-text>
        <v-card-actions>
          <v-layout
            text-xs-center
            row
            wrap
          >
            <v-flex
              xs12
              class="mb-2"
            >
              <v-btn
                color="primary"
                :loading="isLoading"
                @click="save"
              >
                {{ isNew? 'Submit' : 'Save changes' }}
              </v-btn>
            </v-flex>
            <v-flex
              xs12
              class="mb-2"
            >
              <v-btn
                disabled
              >
                Preview
              </v-btn>
            </v-flex>
            <v-flex xs12>
              <a
                v-if="!isNew"
                href="#/move-to-trash"
                @click.prevent="deleteItem"
              >Move to trash</a>
            </v-flex>
          </v-layout>
        </v-card-actions>
      </v-card>
    </v-flex>
  </v-layout>
</template>

<script>
import MapValueKeysToData from '~/mixins/MapValueKeysToData'
import get from 'lodash/get'
import FormatDate from '@freshinup/core-ui/src/mixins/FormatDate'

export const DEFAULT_TEMPLATE = {
  id: '',
  uuid: '',
  title: '',
  description: '',
  content: '',
  status_id: 1,
  updated_at: '',
  updated_by: null
}

export const EDITOR_OPTIONS = {
  height: 500,
  menubar: false,
  plugins: [
    'advlist autolink lists link image charmap',
    'searchreplace visualblocks code fullscreen',
    'print preview anchor insertdatetime media',
    'paste code help wordcount table'
  ],
  toolbar:
    ['undo', 'redo', '|', 'formatselect', '|', 'bold',
      'italic', 'backcolor', '|', 'alignleft', 'aligncenter',
      'alignright', 'alignjustify', '|', 'bullist', 'numlist',
      'outdent', 'indent', '|', 'removeformat', '|', 'help'].join(' ')
}
export default {
  mixins: [MapValueKeysToData, FormatDate],
  props: {
    isLoading: { type: Boolean, default: false },
    // Override value from MapValueKeysToData mixin to get the default values
    value: { type: Object, default: () => DEFAULT_TEMPLATE },
    statuses: { type: Object, default: () => [] }
  },
  data () {
    return {
      apiKey: '',
      ...DEFAULT_TEMPLATE
    }
  },
  computed: {
    // TODO useful for https://github.com/FreshinUp/foodfleet/issues/517
    editorOptions () {
      return EDITOR_OPTIONS
    },
    isNew () {
      return !get(this, 'uuid')
    },
    updaterName () {
      return get(this, 'updated_by.name')
    }
  },
  methods: {
    get,
    deleteItem () {
      this.$emit('delete', this.payload)
    },
    cancel () {
      this.$emit('cancel')
    }
  }
}
</script>

<style scoped>

</style>
