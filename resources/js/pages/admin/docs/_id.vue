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
              <div>{{ formatDate(doc.created_at, 'MMM DD, YYYY • HH:mm a') }} by {{ doc.owner.name }}</div>
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
              <AssignedSearch
                :init-val="assigned ? assigned.uuid : ''"
                :init-items="assigned ? [{ ...assigned, id: assigned.uuid}] : []"
                :type="assigned_type"
                @assign-change="selectAssigned"
                @type-change="changeAssignedType"
              />
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
import AssignedSearch from '~/components/docs/AssignedSearch.vue'
import Validate from 'fresh-bus/components/mixins/Validate'
import FormatDate from 'fresh-bus/components/mixins/FormatDate'

const { mapFields } = createHelpers({
  getterType: 'getField',
  mutationType: 'updateField'
})

export default {
  layout: 'admin',
  components: {
    VueCtkDateTimePicker,
    FileUploader,
    AssignedSearch
  },
  mixins: [Validate, FormatDate],
  data () {
    return {
      pageTitle: 'Document Details',
      template: null,
      templateOptions: [],
      assigned_uuid: '',
      file: { name: '', src: '' }
    }
  },
  computed: {
    ...mapGetters('page', ['isLoading']),
    ...mapGetters('documents', { doc: 'item' }),
    ...mapGetters('documentTypes', { 'types': 'items' }),
    ...mapGetters('documentStatuses', { 'statuses': 'items' }),
    ...mapFields('documents', [
      'title',
      'type',
      'status',
      'expiration_at',
      'description',
      'assigned_type',
      'notes',
      'assigned',
      'event_store_uuid',
      'event_location_uuid'
    ])
  },
  methods: {
    ...mapActions('page', {
      setPageLoading: 'setLoading'
    }),
    selectAssigned (assigned) {
      this.assigned_uuid = assigned.uuid
      this.event_store_uuid = assigned.event_store_uuid
      this.event_location_uuid = assigned.event_location_uuid
    },
    changeAssignedType (value) {
      this.assigned_type = value
    },
    onSaveClick () {
      this.$validator.validate().then(valid => {
        let data = omit(this.doc, ['created_at', 'updated_at', 'assigned', 'owner'])
        data = { ...data, assigned_uuid: this.assigned_uuid }
        if (valid) {
          this.$store.dispatch('documents/updateItem', { data, params: { id: data.uuid } }).then(() => {
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
      vm.$store.dispatch('documents/getItem', { params: { id } }),
      vm.$store.dispatch('documentStatuses/getItems'),
      vm.$store.dispatch('documentTypes/getItems')
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
