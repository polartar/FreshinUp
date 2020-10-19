<template>
  <v-layout row wrap>
    <v-flex
      xs12
      sm12
      md8
      xl9
    >
      <v-card class="ml-2 mt-2">
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
              <f-rich-text-editor
                :value="content"
                @input="content = $event"
              />
            </v-flex>
          </div>
        </v-card-text>
        <v-card-actions>
          <v-btn
            depressed
            @click="cancel"
          >
            Cancel
          </v-btn>
          <v-btn
            depressed
            color="primary"
            :loading="isLoading"
            @click="save"
          >
            {{ isNew? 'Submit' : 'Save changes' }}
          </v-btn>
          <v-spacer />
          <v-btn
            depressed
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
    >
      <v-card class="ml-2 mt-2">
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
                block
                depressed
                color="primary"
                :loading="isLoading"
                @click="save"
              >
                {{ submitLabel }}
              </v-btn>
            </v-flex>
            <v-flex
              xs12
              class="mb-2"
            >
              <v-btn
                block
                depressed
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
import FRichTextEditor from '~/components/FRichTextEditor'

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

export default {
  components: { FRichTextEditor },
  mixins: [MapValueKeysToData, FormatDate],
  props: {
    isLoading: { type: Boolean, default: false },
    // Override value from MapValueKeysToData mixin to get the default values
    value: { type: Object, default: () => DEFAULT_TEMPLATE },
    statuses: { type: Array, default: () => [] }
  },
  data () {
    return {
      ...DEFAULT_TEMPLATE
    }
  },
  computed: {
    submitLabel () {
      return this.isNew? 'Submit' : 'Save changes'
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
