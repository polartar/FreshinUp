<template>
  <div>
    <v-form
      v-if="!isLoading"
      ref="form"
      v-model="isValid"
    >
      <v-layout
        row
        align-center
        pt-3
      >
        <v-btn
          flat
          small
          @click="backToList"
        >
          <div class="back-btn-inner">
            <v-icon>fas fa-arrow-left</v-icon>
            <span>Return to Events list</span>
          </div>
        </v-btn>
      </v-layout>
      <v-layout
        row
        align-center
        ma-2
      >
        <v-flex sm6>
          <v-layout
            row
            align-center
          >
            <v-flex sm4>
              <div class="headline white--text">
                {{ pageTitle }}
              </div>
            </v-flex>
            <v-flex>
              <v-dialog
                v-model="duplicateDialog"
                max-width="500"
              >
                <template v-slot:activator="{ on }">
                  <v-btn
                    slot="activator"
                    color="white"
                    v-on="on"
                    @click="duplicateDialog = true"
                  >
                    <span class="primary--text">Duplicate</span>
                  </v-btn>
                </template>
                <duplicate-event-dialog
                  :is-loading="duplicating"
                  @input="onDuplicate"
                  @close="duplicateDialog = false"
                />
              </v-dialog>
            </v-flex>
          </v-layout>
        </v-flex>
        <v-flex sm6>
          <v-layout
            row
            align-center
            justify-end
            text-xs-right
          >
            <v-flex>
              <status-select
                v-model="status_id"
                :options="statuses"
              />
            </v-flex>
            <v-flex sm2>
              <v-dialog
                v-if="!isNew"
                v-model="questDialog"
                max-width="700"
              >
                <template v-slot:activator="{ on }">
                  <v-btn
                    depressed
                    fab
                    color="primary"
                    @click="questDialog = true"
                  >
                    <v-icon>far fa-question-circle</v-icon>
                  </v-btn>
                </template>
                <v-card>
                  <v-card-title>
                    <v-layout
                      row
                      space-between
                      align-center
                    >
                      <v-flex>
                        <h3>Event Status</h3>
                      </v-flex>
                      <v-btn
                        small
                        round
                        color="grey"
                        class="white--text"
                        @click="questDialog = false"
                      >
                        <v-flex>
                          <v-icon
                            small
                            class="white--text"
                          >
                            fa fa-times
                          </v-icon>
                        </v-flex>
                        <v-flex>
                          Close
                        </v-flex>
                      </v-btn>
                    </v-layout>
                  </v-card-title>
                  <v-divider />
                  <v-card-text class="grey--text">
                    <EventStatusTimeline
                      :statuses="statuses"
                      :histories="eventHistories"
                      :status="get(event, 'status_id')"
                    />
                  </v-card-text>
                  <v-divider />
                </v-card>
              </v-dialog>
            </v-flex>
          </v-layout>
        </v-flex>
      </v-layout>
      <v-divider />
      <v-layout
        row
        wrap
        py-4
        px-2
        justify-space-between
        class="event-new-wrap"
      >
        <v-flex
          md8
          sm12
        >
          <basic-information
            ref="basicInfo"
            :event="event"
            :errors="errors"
            @data-change="changeBasicInfo"
            @cancel="backToList"
            @save="onSave"
            @delete="onDelete"
          />
        </v-flex>
        <v-flex
          md4
          sm12
        >
          <venue-details
            class="ml-4"
            :venue-uuid="get(event, 'venue_uuid')"
            :location-uuid="get(event, 'location_uuid')"
            :venues="venues"
            @input="onLocationOrVenueChanged"
          />
        </v-flex>
      </v-layout>
    </v-form>

    <v-layout
      row
      px-2
      py-4
    >
      <v-flex>
        <stores
          :types="storeTypes"
          :statuses="storeStatuses"
          :stores="stores"
          @manage-view="viewDetails"
          @manage-create="showNewMemberDialog = true"
        />
        <v-dialog
          v-model="showNewMemberDialog"
          max-width="900"
        >
          <v-card>
            <v-card-title class="justify-space-between px-4 py-2">
            <span
              class="subheading font-weight-bold grey--text text--darken-1"
            >
              Add fleet member
            </span>
              <v-btn
                small
                round
                depressed
                color="blue-grey lighten-3 white--text"
                @click="showNewMemberDialog = false"
              >
                <v-icon
                  left
                  class="white--text"
                >
                  close
                </v-icon>
                Close
              </v-btn>
            </v-card-title>
            <v-divider/>
            <add-store
              :value="event"
              :stores="stores"
              :store-types="types"
              class="mb-2"
            />
          </v-card>
        </v-dialog>
      </v-flex>
    </v-layout>

    <v-layout
      row
      px-2
      py-4
    >
      <v-flex>
        <v-card
        >
          <v-card-title class="justify-space-between px-4">
            <span class="grey--text font-weight-bold title text-uppercase">Customer</span>
          </v-card-title>
          <v-divider/>
          <v-layout
            row
          >
            <v-flex>
              <customer-list
                :customers="customers"
                :statuses="statuses"
                @manage-view="viewDocuments"
              />
            </v-flex>
          </v-layout>
        </v-card>
      </v-flex>
    </v-layout>
  </div>
</template>

<script>
import omitBy from 'lodash/omitBy'
import get from 'lodash/get'
import isNull from 'lodash/isNull'
import { mapActions, mapGetters } from 'vuex'
import { createHelpers } from 'vuex-map-fields'
import Validate from 'fresh-bus/components/mixins/Validate'
import BasicInformation from '~/components/events/BasicInformation.vue'
import Stores from '~/components/events/Stores.vue'
import CustomerList from '~/components/events/CustomerList.vue'
import StatusSelect from '~/components/events/StatusSelect.vue'
import VenueDetails from '~/components/events/VenueDetails.vue'
import FormatDate from '@freshinup/core-ui/src/mixins/FormatDate'
import EventStatusTimeline from '~/components/events/EventStatusTimeline'
import DuplicateEventDialog from '~/components/events/DuplicateEventDialog.vue'
import AddStore from '~/components/fleet-members/AddStore.vue'

const { mapFields } = createHelpers({
  getterType: 'getField',
  mutationType: 'updateField'
})

export default {
  layout: 'admin',
  components: {
    AddStore,
    Stores,
    StatusSelect,
    BasicInformation,
    CustomerList,
    EventStatusTimeline,
    VenueDetails,
    DuplicateEventDialog
  },
  mixins: [Validate, FormatDate],
  data () {
    return {
      showNewMemberDialog: false,
      duplicating: false,
      duplicateDialog: false,
      questDialog: false,
      isNew: false
    }
  },
  computed: {
    ...mapGetters('events', {
      event: 'item',
      eventLoading: 'itemLoading'
    }),
    ...mapGetters('events/stores', { storeItems: 'items' }),
    ...mapGetters('storeTypes', { storeTypes: 'items' }),
    ...mapGetters('storeStatuses', { storeStatuses: 'items' }),
    ...mapGetters('eventStatuses', { 'statuses': 'items' }),
    ...mapGetters('venues', { 'venues': 'items' }),
    ...mapGetters('eventHistories', { 'eventHistories': 'items' }),
    ...mapFields('events', [
      'status_id'
    ]),
    isLoading () {
      return this.$store.getters['page/isLoading']
    },
    pageTitle () {
      return (this.isNew ? 'New Event' : 'Event Details')
    },
    stores () {
      return this.isNew ? [] : this.storeItems
    },
    customers () {
      return (this.event && !this.isNew) ? [
        {
          uuid: this.event.uuid,
          status: this.event.host_status || 1,
          updated_at: this.event.updated_at,
          created_at: this.event.created_at
        }
      ] : []
    }
  },
  methods: {
    get,
    ...mapActions('page', {
      setPageLoading: 'setLoading'
    }),
    editEvent (event) {
      this.$router.push({ path: `/admin/events/${event.uuid}/edit` })
    },
    onDuplicate (options) {
      this.duplicating = true
      this.$store.dispatch('events/duplicate', {
        params: {
          uuid: this.$route.params.id
        },
        data: options
      })
        .then(data => {
          this.duplicateDialog = false
          const eventUuid = get(data, 'data.uuid')
          if (eventUuid) {
            this.editEvent({ uuid: eventUuid })
            this.$store.dispatch('generalMessage/setMessage', 'Duplicated.')
          }
        })
        .catch(error => {
          const message = get(error, 'response.data.message', error.message)
          this.$store.dispatch('generalErrorMessages/setErrors', message)
        })
        .then(() => {
          this.duplicating = false
        })
    },
    changeBasicInfo (data) {
      const fields = [
        'attendees',
        'budget',
        'commission_rate',
        'commission_type',
        'type_id',
        'start_at',
        'end_at',
        'staff_notes',
        'member_notes',
        'customer_notes',
        'event_tags',
        'host_uuid',
        'manager_uuid',
        'name',
        'schedule',
        'event_recurring_checked'
      ]
      fields.forEach(field => {
        this.event[field] = data[field]
      })
    },
    async validator () {
      const valids = await Promise.all([
        this.$validator.validateAll(),
        this.$refs.basicInfo.$validator.validateAll()
      ])
      return valids.every(valid => valid)
    },
    async onSave () {
      const valid = await this.validator()
      // TODO: this heavy logic should be done on the state machine.
      let data = {
        ...this.event
      }
      const extra = ['created_at', 'updated_at', 'host', 'manager', 'event_recurring_checked']
      data = omitBy(data, (value, key) => {
        if (key === 'schedule' && get(value, 'ends_on') === 'on') {
          delete value.occurrences
        }
        return extra.includes(key) || isNull(value)
      })
      if (this.event.event_recurring_checked === 'no') {
        data['schedule'] = null
      }
      // end TODO

      if (!valid) {
        this.$store.dispatch('generalErrorMessages/setErrors', 'Validation error. Please check the form.')
        return false
      }
      try {
        if (this.isNew) {
          await this.$store.dispatch('events/createItem', { data })
          await this.$store.dispatch('generalMessage/setMessage', 'Saved.')
          this.backToList()
        } else {
          await this.$store.dispatch('events/updateItem', { data, params: { id: data.uuid } })
          await this.$store.dispatch('generalMessage/setMessage', 'Modified.')
        }
      } catch (error) {
        const message = get(error, 'response.data.message', error.message)
        this.$store.dispatch('generalErrorMessages/setErrors', message)
      }
    },
    async onDelete () {
      await this.$store.dispatch('events/deleteItem', { getItems: false, params: { id: this.event.uuid } })
      await this.$store.dispatch('generalMessage/setMessage', 'Deleted.')
      this.backToList()
    },
    viewDetails (store) {
      this.$router.push({ path: '/admin/events/' + this.event.uuid + '/stores/' + store.uuid })
    },
    viewDocuments () {
      this.$router.push({ path: '/admin/events/' + this.event.uuid + '/customers' })
    },
    backToList () {
      this.$router.push({ path: '/admin/events' })
    },
    onLocationOrVenueChanged (location) {
      if (!this.event) {
        return false
      }
      this.event.location_uuid = location.uuid
      this.event.venue_uuid = location.venue_uuid
    }
  },
  async beforeRouteEnterOrUpdate (vm, to, from, next) {
    const id = to.params.id || 'new'
    let params = { id }
    let promises = []
    if (id !== 'new') {
      params = {
        id,
        include: 'manager,host,event_tags,venue,location'
      }
      promises.push(vm.$store.dispatch('storeStatuses/getItems'))
      promises.push(vm.$store.dispatch('storeTypes/getItems'))
      promises.push(vm.$store.dispatch('events/stores/getItems', {
        params: { eventId: id }
      }))
      await vm.$store.dispatch('eventHistories/setFilters', {
        event_uuid: id
      })
      promises.push(vm.$store.dispatch('eventHistories/getItems'))
    }
    promises.push(vm.$store.dispatch('eventStatuses/getItems'))
    promises.push(vm.$store.dispatch('venues/getItems', { params: { include: 'locations' } }))

    vm.$store.dispatch('page/setLoading', true)
    vm.$store.dispatch('events/getItem', { params })
      .then()
      .catch(error => {
        console.error(error)
        vm.backToList()
      })
    Promise.all(promises)
      .then(() => {})
      .catch((error) => {
        console.error(error)
      })
      .then(() => {
        vm.$store.dispatch('page/setLoading', false)
        if (next) next()
      })
  }
}
</script>
<style scoped>
  .back-btn-inner{
    color: #fff;
    display: flex;
    align-items: center;
    font-size: 13px;
  }
  .back-btn-inner span{
    margin-left: 10px;
    font-weight: bold;
    text-transform: initial;
  }
  .back-btn-inner .v-icon{
    font-size: 16px;
  }

</style>
