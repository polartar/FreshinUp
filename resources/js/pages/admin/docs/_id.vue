<template>
  <div v-if="!isLoading">
    <v-layout
      row
      justify-space-between
      ma-2
    >
      <h2 class="white--text">
        {{ pageTitle }}
      </h2>
      <v-flex
        text-xs-right
        sm2
        xs12
      >
        <v-select
          v-model="status"
          :items="statuses"
          single-line
          solo
          flat
          hide-details
        />
      </v-flex>
    </v-layout>
    <v-divider />
    <br>
    <v-layout
      row
      wrap
      pa-2
      justify-space-between
      class="doc-new-wrap"
    >
      <v-flex
        md7
        sm12
      >
        <v-card>
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
                v-model="type"
                v-validate="'required'"
                single-line
                outline
                :items="typeOptions"
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
                v-model="description"
                v-validate="'required'"
                single-line
                outline
                data-vv-name="description"
                :error-messages="errors.collect('description')"
                label="Short description"
              />
            </v-flex>
            <v-flex
              v-if="type === 1"
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
                v-model="template"
                single-line
                outline
                :items="templateOptions"
                data-vv-name="template"
                :error-messages="errors.collect('template')"
                label="Document Template"
              />
            </v-flex>
            <v-flex
              v-else-if="type === 2"
              md7
              sm12
              mb-4
              pt-5
            >
              <file-uploader v-model="file" />
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
                :error-messages="errors.collect('notes')"
                label="Notes / Additional Info"
              />
            </v-flex>
          </v-layout>
        </v-card>
      </v-flex>
      <v-flex
        md4
        sm12
      >
        <v-card>
          <v-card-title class="subheading text-uppercase font-weight-bold">
            Publishing
          </v-card-title>
          <v-divider />
          <v-layout
            row
            wrap
            pa-3
          >
            <v-flex
              xs12
              mb-3
            >
              <v-layout
                mb-2
                class="title font-weight-bold"
              >
                Submitted on
              </v-layout>
              <div>May 28, 2019 • 10:33 am by John Smith</div>
            </v-flex>
            <v-flex
              xs12
            >
              <v-layout
                mb-2
                class="title font-weight-bold"
              >
                Assigned to
              </v-layout>
              <v-layout
                row
                wrap
                justify-space-between
              >
                <v-flex
                  md5
                  sm12
                >
                  <v-select
                    v-model="assignType"
                    single-line
                    outline
                    :items="assignOptions"
                    data-vv-name="assignType"
                    :error-messages="errors.collect('assignType')"
                  />
                </v-flex>
                <v-flex
                  md6
                  sm12
                >
                  <simple
                    url="users"
                    placeholder="All Users"
                    background-color="white"
                    class="mt-0 pt-0"
                    height="48"
                    :init-val="assigned ? assigned.id : ''"
                    :init-items="assigned ? [assigned] : []"
                    @input="selectAssigned"
                  />
                </v-flex>
              </v-layout>
            </v-flex>
            <v-flex
              xs12
            >
              <v-layout
                mb-2
                class="title font-weight-bold"
              >
                Expiration Date
              </v-layout>
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
                  xs5
                >
                  <v-btn
                    block
                  >
                    Preview
                  </v-btn>
                </v-flex>
                <v-flex
                  xs5
                >
                  <v-btn
                    block
                    :disabled="!isValid"
                    class="primary"
                    @click="onSaveClick"
                  >
                    Save Changes
                  </v-btn>
                </v-flex>
              </v-layout>
            </v-flex>
          </v-layout>
        </v-card>
      </v-flex>
    </v-layout>
  </div>
</template>

<script>
import { omit } from 'lodash'
import { mapGetters, mapActions } from 'vuex'
import { createHelpers } from 'vuex-map-fields'
import 'vue-ctk-date-time-picker/dist/vue-ctk-date-time-picker.css'
import VueCtkDateTimePicker from 'vue-ctk-date-time-picker'
import FileUploader from '~/components/FileUploader.vue'
import Validate from 'fresh-bus/components/mixins/Validate'
import Simple from 'fresh-bus/components/search/simple'

const { mapFields } = createHelpers({
  getterType: 'getField',
  mutationType: 'updateField'
})

export default {
  layout: 'admin',
  components: {
    VueCtkDateTimePicker,
    FileUploader,
    Simple
  },
  mixins: [Validate],
  data () {
    return {
      pageTitle: 'Document Details',
      statuses: [
        { value: 1, text: 'Pending' },
        { value: 2, text: 'Approved' },
        { value: 3, text: 'Rejected' },
        { value: 4, text: 'Expiring' },
        { value: 5, text: 'Expired' }
      ],
      typeOptions: [
        { value: 1, text: 'From Template' },
        { value: 2, text: 'Downloadable' }
      ],
      template: null,
      templateOptions: [],
      assignType: 1,
      assignOptions: [
        { value: 1, text: 'User' },
        { value: 2, text: 'Fleet Member' },
        { value: 3, text: 'Venue' },
        { value: 4, text: 'Event' },
        { value: 5, text: 'Event/Fleet Mem' },
        { value: 6, text: 'Event/Venue' }
      ],
      file: { name: '', src: '' }
    }
  },
  computed: {
    ...mapGetters('page', ['isLoading']),
    ...mapGetters('documents', { doc: 'item' }),
    ...mapFields('documents', [
      'title',
      'type',
      'status',
      'expiration_at',
      'description',
      'notes',
      'assigned',
      'assigned_user_uuid'
    ])
  },
  methods: {
    ...mapActions('page', {
      setPageLoading: 'setLoading'
    }),
    selectAssigned (assigned) {
      this.assigned_user_uuid = assigned ? assigned.uuid : ''
    },
    onSaveClick () {
      this.$validator.validate().then(valid => {
        const data = omit(this.doc, ['created_at', 'updated_at', 'assigned', 'owner'])
        if (valid) {
          this.$store.dispatch('documents/updateItem', { data, params: { id: data.id } }).then(() => {
            this.$store.dispatch('generalMessage/setMessage', 'Saved')
          })
        }
      })
    }
  },
  beforeRouteEnterOrUpdate (vm, to, from, next) {
    vm.setPageLoading(true)
    const id = to.params.id
    Promise.all([
      vm.$store.dispatch('documents/getItem', { params: { id } })
    ]).then(() => {
      vm.$store.dispatch('page/setLoading', false)
      if (next) next()
    })
  }
}
</script>
<style scoped>
  .doc-new-wrap{
    background-color: #fff;
  }
</style>
