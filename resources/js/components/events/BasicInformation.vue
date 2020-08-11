<template>
  <v-card>
    <v-card-title class="px-3">
      <h3>Basic Information</h3>
    </v-card-title>
    <hr>
    <v-form
      ref="form"
      v-model="isValid"
      lazy-validation
    >
      <v-card-text>
        <v-layout
          row
          wrap
          :pr-3="$vuetify.breakpoint.mdAndUp"
        >
          <v-flex
            xs12
            md8
          >
            <v-layout
              row
              wrap
            >
              <v-flex
                xs12
                md9
                :pr-3="$vuetify.breakpoint.mdAndUp"
              >
                Event Name
                <v-text-field
                  v-model="eventData.name"
                  v-validate="'required|max:255'"
                  solo
                  :counter="255"
                  data-vv-name="name"
                  required
                  :error-messages="errors.collect('name')"
                  :disabled="readOnly"
                />
              </v-flex>
              <v-flex
                xs12
                md3
              >
                Event type
                <v-select
                  v-model="eventData.type"
                  v-validate="'required'"
                  :items="eventTypes"
                  data-vv-name="type"
                  :error-messages="errors.collect('type')"
                  item-value="id"
                  item-text="label"
                  solo
                  :disabled="readOnly"
                />
              </v-flex>
            </v-layout>
            <v-layout
              row
              wrap
            >
              <v-flex
                xs12
                md6
                :pr-3="$vuetify.breakpoint.mdAndUp"
              >
                Manager
                <simple
                  url="users?filter[type]=1"
                  term-param="term"
                  results-id-key="uuid"
                  :value="eventData.manager_uuid"
                  placeholder="Search / select FF Staff Member"
                  background-color="white"
                  class="mt-0 pt-0"
                  height="48"
                  not-clearable
                  solo
                  flat
                  :disabled="readOnly"
                  @input="selectManager"
                />
              </v-flex>
              <v-flex
                xs12
                md6
              >
                Customer
                <simple
                  url="companies?filter[type_key]=host"
                  term-param="filter[name]"
                  results-id-key="uuid"
                  :value="eventData.host_uuid"
                  placeholder="All Customer Companies"
                  background-color="white"
                  class="mt-0 pt-0"
                  height="48"
                  not-clearable
                  solo
                  flat
                  :disabled="readOnly"
                  @input="selectHost"
                />
              </v-flex>
            </v-layout>
            <v-layout
              row
              wrap
              pt-4
            >
              <v-flex
                xs12
                md3
                :pr-3="$vuetify.breakpoint.mdAndUp"
              >
                Budget
                <v-text-field
                  v-model="eventData.budget"
                  v-validate="'required'"
                  type="number"
                  :disabled="readOnly"
                  solo
                  data-vv-name="budget"
                  :error-messages="errors.collect('budget')"
                />
              </v-flex>
              <v-flex
                xs12
                md3
                :pr-3="$vuetify.breakpoint.mdAndUp"
              >
                Attendees
                <v-text-field
                  v-model="eventData.attendees"
                  v-validate="'required'"
                  type="number"
                  :disabled="readOnly"
                  solo
                  data-vv-name="attendees"
                  :error-messages="errors.collect('attendees')"
                />
              </v-flex>
              <v-flex
                xs12
                md3
                :pr-3="$vuetify.breakpoint.mdAndUp"
              >
                Commission Rate
                <v-text-field
                  v-model="eventData.commission_rate"
                  v-validate="'required'"
                  type="number"
                  :disabled="readOnly"
                  solo
                  data-vv-name="commission_rate"
                  required
                  :error-messages="errors.collect('commission_rate')"
                />
              </v-flex>
              <v-flex
                xs12
                md3
              >
                Commission type
                <v-select
                  v-model="eventData.commission_type"
                  :items="commissionTypes"
                  item-value="id"
                  item-text="label"
                  solo
                  :disabled="readOnly"
                />
              </v-flex>
            </v-layout>
            <v-layout
              row
              wrap
            >
              <v-flex
                xs12
              >
                Tags
                <v-combobox
                  v-model="eventData.event_tags"
                  item-text="name"
                  label="Enter a tag and hit enter"
                  chips
                  clearable
                  solo
                  multiple
                  :disabled="readOnly"
                />
              </v-flex>
            </v-layout>
            <v-layout
              row
              wrap
            >
              <v-flex
                xs12
                md6
                :pr-3="$vuetify.breakpoint.mdAndUp"
              >
                Start Date and Time
                <vue-ctk-date-time-picker
                  v-model="eventData.start_at"
                  v-validate="'required'"
                  data-vv-name="start_at"
                  required
                  :error-messages="errors.collect('start_at')"
                  format="YYYY-MM-DD hh:mm"
                  formatted="dddd, MMMM D YYYY • h:mma"
                  input-size="lg"
                  label="Select date"
                  :color="$vuetify.theme.primary"
                  :button-color="$vuetify.theme.primary"
                  :disabled="readOnly"
                />
              </v-flex>
              <v-flex
                xs12
                md6
              >
                End Date and Time
                <vue-ctk-date-time-picker
                  v-model="eventData.end_at"
                  v-validate="'required'"
                  data-vv-name="end_at"
                  required
                  :error-messages="errors.collect('end_at')"
                  format="YYYY-MM-DD hh:mm"
                  formatted="dddd, MMMM D YYYY • h:mma"
                  input-size="lg"
                  label="Select date"
                  :color="$vuetify.theme.primary"
                  :button-color="$vuetify.theme.primary"
                  :disabled="readOnly"
                />
              </v-flex>
            </v-layout>
            <v-layout
              row
              wrap
            >
              <v-flex
                :pr-3="$vuetify.breakpoint.mdAndUp"
              >
                <event-settings-modal
                  :schedule="eventData.schedule"
                  @is-checked="isCheckRecurringEvent"
                  @save="eventSettingsSave"
                />
              </v-flex>
            </v-layout>
          </v-flex>
        </v-layout>
      </v-card-text>
      <hr>
      <v-card-actions class="px-3 py-4">
        <v-btn
          @click="cancel"
        >
          Cancel
        </v-btn>
        <v-btn
          color="primary"
          :disabled="readOnly || !isValid"
          @click="whenValid(save)"
        >
          {{ edit ? 'Save changes' : 'Submit' }}
        </v-btn>
        <v-spacer />
        <v-btn
          :disabled="!edit || readOnly"
          @click="deleteEvent"
        >
          Delete event
        </v-btn>
      </v-card-actions>
    </v-form>
  </v-card>
</template>

<script>
import { get } from 'lodash'
import Simple from 'fresh-bus/components/search/simple'
import VueCtkDateTimePicker from 'vue-ctk-date-time-picker'
import 'vue-ctk-date-time-picker/dist/vue-ctk-date-time-picker.css'
import Validate from 'fresh-bus/components/mixins/Validate'
import EventSettingsModal from '~/components/events/EventSettingsModal.vue'

export default {
  components: { Simple, VueCtkDateTimePicker, EventSettingsModal },
  mixins: [
    Validate
  ],
  props: {
    event: {
      type: Object,
      default: null
    },
    readOnly: {
      type: Boolean,
      default: false
    }
  },
  data () {
    let edit = get(this.event, 'uuid') !== null
    return {
      eventData: {
        name: edit ? get(this.event, 'name') : null,
        manager_uuid: edit ? get(this.event, 'manager.uuid') : null,
        host_uuid: edit ? get(this.event, 'host.uuid') : null,
        budget: edit ? get(this.event, 'budget') : null,
        attendees: edit ? get(this.event, 'attendees') : null,
        commission_rate: edit ? get(this.event, 'commission_rate') : 5,
        commission_type: edit ? get(this.event, 'commission_type') : 1,
        type: edit ? get(this.event, 'type') : 1,
        event_tags: edit ? get(this.event, 'event_tags') : [],
        start_at: edit ? get(this.event, 'start_at') : null,
        end_at: edit ? get(this.event, 'end_at') : null,
        schedule: edit ? get(this.event, 'schedule') : null,
        event_recurring_checked: null // TODO: should be a simple boolean
      },
      edit: edit,
      commissionTypes: [
        { id: 1, label: 'Percentage(%)' },
        { id: 2, label: 'Flat ($)' }
      ],
      eventTypes: [
        { id: 1, label: 'Catering' },
        { id: 2, label: 'Cash and Carry' }
      ]
    }
  },
  watch: {
    eventData: {
      handler (val) {
        this.$emit('data-change', val)
      },
      deep: true
    }
  },
  methods: {
    cancel () {
      this.$emit('cancel')
    },
    isCheckRecurringEvent (checked) {
      // TODO should be a simple boolean
      this.eventData.event_recurring_checked = 'yes'
      if (!checked) {
        this.eventData.event_recurring_checked = 'no'
        this.eventData.schedule = null
      }
    },
    eventSettingsSave (params) {
      this.eventData.schedule = params
    },
    save () {
      this.$emit('save', this.eventData)
    },
    deleteEvent () {
      this.$emit('delete')
    },
    selectManager (manager) {
      this.eventData.manager_uuid = manager ? manager.uuid : null
    },
    selectHost (host) {
      this.eventData.host_uuid = host ? host.uuid : null
    }
  }
}
</script>
<style lang="styl" scoped>
  .v-card {
    border-radius: 6px;
  }
</style>
