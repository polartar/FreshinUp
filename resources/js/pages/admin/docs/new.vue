<template>
  <div>
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
          v-model="doc.status"
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
            >
              <v-layout
                mb-2
                class="title font-weight-bold"
              >
                Assigned to
              </v-layout>
              <AssignedSearch
                :type="doc.assigned_type"
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
                v-model="doc.expiration_at"
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
import { cloneDeep } from 'lodash'
import { mapGetters, mapActions } from 'vuex'
import 'vue-ctk-date-time-picker/dist/vue-ctk-date-time-picker.css'
import VueCtkDateTimePicker from 'vue-ctk-date-time-picker'
import BasicInfoForm from '~/components/docs/BasicInfoForm.vue'
import Validate from 'fresh-bus/components/mixins/Validate'
import AssignedSearch from '~/components/docs/AssignedSearch.vue'

export default {
  layout: 'admin',
  components: {
    VueCtkDateTimePicker,
    BasicInfoForm,
    AssignedSearch
  },
  mixins: [Validate],
  data () {
    return {
      pageTitle: 'Document Details',
      template: null,
      templates: [],
      doc: {
        title: '',
        status: 1,
        type: 2,
        description: '',
        notes: '',
        assigned_uuid: null,
        assigned_type: 1,
        expiration_at: null
      },
      file: { name: '', src: '' }
    }
  },
  computed: {
    ...mapGetters('page', ['isLoading']),
    ...mapGetters('documentTypes', { 'types': 'items' }),
    ...mapGetters('documentStatuses', { 'statuses': 'items' })
  },
  methods: {
    ...mapActions('page', {
      setPageLoading: 'setLoading'
    }),
    selectAssigned (uuid) {
      this.doc.assigned_uuid = uuid
    },
    changeAssignedType (value) {
      this.doc.assigned_type = value
    },
    changeBasicInfo (data) {
      this.doc.title = data.title
      this.doc.type = data.type
      this.doc.description = data.description
      this.doc.notes = data.notes

      this.template = data.template
      this.file = data.file
    },
    onSaveClick () {
      this.$validator.validate().then(async valid => {
        let data = cloneDeep(this.doc)
        if (valid) {
          await this.$store.dispatch('documents/createItem', { data })
          await this.$store.dispatch('documents/getItems')
          this.$router.push('/admin/docs/')
        }
      })
    }
  },
  beforeRouteEnterOrUpdate (vm, to, from, next) {
    vm.setPageLoading(true)
    Promise.all([
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
