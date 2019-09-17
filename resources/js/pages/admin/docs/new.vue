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
      <v-flex text-xs-right sm2 xs12>
        <v-select
          v-model="status"
          :items="statuses"
          single-line solo
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
      <v-flex md7 sm12>
        <v-card>
          <v-card-title class="subheading text-uppercase font-weight-bold">Basic information</v-card-title>
          <v-divider></v-divider>
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
                single-line outline
                v-model="title"
                v-validate="'required'"
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
                single-line outline
                :items="typeOptions"
                v-model="type"
                v-validate="'required'"
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
                single-line outline
                v-model="description"
                v-validate="'required'"
                data-vv-name="description"
                :error-messages="errors.collect('description')"
                label="Short description"
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
                Document Template
              </v-layout>
              <v-select
                single-line outline
                :items="templateOptions"
                v-model="template"
                v-validate="'required'"
                data-vv-name="template"
                :error-messages="errors.collect('template')"
                label="Document Template"
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
                single-line outline
                v-model="notes"
                v-validate="'required'"
                data-vv-name="notes"
                :error-messages="errors.collect('notes')"
                label="Notes / Additional Info"
              />
            </v-flex>
          </v-layout>
        </v-card>
      </v-flex>
      <v-flex md4 sm12>
        <v-card>
          <v-card-title class="subheading text-uppercase font-weight-bold">Publishing</v-card-title>
          <v-divider></v-divider>
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
                    single-line outline
                    :items="assignOptions"
                    v-model="assignType"
                    v-validate="'required'"
                    data-vv-name="assignType"
                    :error-messages="errors.collect('assignType')"
                  />
                </v-flex>
                <v-flex
                  md6
                  sm12
                >
                  <v-autocomplete
                    v-model="assignId"
                    cache-items
                    hide-no-data
                    hide-details
                    single-line outline
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
                range
                v-model="expireDate"
                only-date
                format="YYYY-MM-DD"
                formatted="MM-DD-YYYY"
                input-size="lg"
                label="Leave blank for none"
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
                    class="primary"
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
import { mapGetters, mapActions } from 'vuex'
import 'vue-ctk-date-time-picker/dist/vue-ctk-date-time-picker.css'
import VueCtkDateTimePicker from 'vue-ctk-date-time-picker'
export default {
  layout: 'admin',
  components: {
    VueCtkDateTimePicker
  },
  data () {
    return {
      pageTitle: 'Document Details',
      title: '',
      status: 1,
      statuses: [
        { value: 1, text: 'Pending' },
        { value: 2, text: 'Approved' },
        { value: 3, text: 'Rejected' },
        { value: 4, text: 'Expiring' },
        { value: 5, text: 'Expired' }
      ],
      type: 1,
      typeOptions: [
        { value: 1, text: 'From Template' },
        { value: 2, text: 'Downloadable' }
      ],
      template: null,
      templateOptions: [],
      description: '',
      notes: '',
      assignType: null,
      assignOptions: [
        {  value: 1, text: 'User' },
        {  value: 2, text: 'Fleet Member' },
        {  value: 3, text: 'Venue' },
        {  value: 4, text: 'Event' },
        {  value: 5, text: 'Event/Fleet Mem' },
        {  value: 6, text: 'Event/Venue' }
      ],
      assignId: null,
      expireDate: null
    }
  },
  computed: {
    isLoadingList () {
      return get(this.$store, 'state.users.pending.items', true)
    },
    ...mapGetters('page', ['isLoading'])
  },
  methods: {
    ...mapActions('page', {
      setPageLoading: 'setLoading'
    })
  },
  beforeRouteEnterOrUpdate (vm, to, from, next) {
    vm.setPageLoading(true)
    vm.$store.dispatch('users/setFilters', {
      ...vm.$route.query
    })
    Promise.all([
      vm.$store.dispatch('userLevels/getUserlevels'),
      vm.$store.dispatch('userTypes/getItems'),
      vm.$store.dispatch('userStatuses/getUserstatuses'),
      vm.$store.dispatch('companies/getCompanies')
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

