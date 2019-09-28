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
          <BasicInfoForm
            :types="types"
            :templates="templates"
            :initdata="doc"
            :errors="errors"
            @data-change="changeBasicInfo"
          />
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
import AssignedSearch from '~/components/docs/AssignedSearch.vue'
import BasicInfoForm from '~/components/docs/BasicInfoForm.vue'
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
    AssignedSearch,
    BasicInfoForm
  },
  mixins: [Validate, FormatDate],
  data () {
    return {
      pageTitle: 'Document Details',
      assigned_uuid: '',
      template: null,
      templates: [],
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
      'assigned'
    ])
  },
  methods: {
    ...mapActions('page', {
      setPageLoading: 'setLoading'
    }),
    selectAssigned (uuid) {
      this.assigned_uuid = uuid
    },
    changeAssignedType (value) {
      this.assigned_type = value
    },
    changeBasicInfo (data) {
      this.title = data.title
      this.type = data.type
      this.description = data.description
      this.notes = data.notes
      this.template = data.template
      this.file = data.file
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
