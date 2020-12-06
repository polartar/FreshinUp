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
                v-model="show"
                max-width="500"
              >
                <template v-slot:activator="{ on }">
                  <v-btn
                    slot="activator"
                    color="white"
                    v-on="on"
                    @click="show = true"
                  >
                    <span class="primary--text">Duplicate</span>
                  </v-btn>
                </template>
                <duplicate-dialog
                  :loading="loading"
                  :show="show"
                  @Duplicate="onDuplicate"
                  @manage-duplicate-dialog="changeDuplicateDialogue"
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
          <BasicInformation
            ref="basicInfo"
            :event="event"
            :errors="errors"
            @data-change="changeBasicInfo"
            @cancel="onCancel"
            @save="onSave"
            @delete="onDelete"
          />
        </v-flex>
        <v-flex
          md4
          sm12
        >
          <VenueDetails
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
          :types="types"
          :statuses="storeStatuses"
          :stores="stores"
          @manage-view-details="viewDetails"
        />
      </v-flex>
    </v-layout>

    <v-layout
      row
      px-2
      py-4
    >
      <v-flex>
        <customers
          :customers="customers"
          :statuses="statuses"
          @manage-view-details="viewDocuments"
        />
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
import Customers from '~/components/events/Customers.vue'
import StatusSelect from '~/components/events/StatusSelect.vue'
import VenueDetails from '~/components/events/VenueDetails.vue'
import FormatDate from '@freshinup/core-ui/src/mixins/FormatDate'
import EventStatusTimeline from '~/components/events/EventStatusTimeline'
import DuplicateDialog from '~/components/events/DuplicateDialog.vue'
import getFileNameCopy from '~/components/events/utils.js'

const { mapFields } = createHelpers({
  getterType: 'getField',
  mutationType: 'updateField'
})

export default {
  layout: 'admin',
  components: {
    Stores,
    StatusSelect,
    BasicInformation,
    Customers,
    EventStatusTimeline,
    VenueDetails,
    DuplicateDialog
  },
  mixins: [Validate, FormatDate],
  data () {
    return {
      eventLoading: false,
      loading: false,
      show: false,
      questDialog: false,
      isNew: false,
      types: []
    }
  },
  computed: {
    ...mapGetters('events', { event: 'item' }),
    ...mapGetters('events/stores', { storeItems: 'items' }),
    ...mapGetters('storeStatuses', { storeStatuses: 'items' }),
    ...mapGetters('eventStatuses', { 'statuses': 'items' }),
    ...mapGetters('venues', { 'venues': 'items' }),
    ...mapGetters('eventHistories', { 'eventHistories': 'items' }),
    ...mapFields('events', [
      'status_id'
    ]),
    isLoading () {
      return this.$store.getters['page/isLoading'] || this.eventLoading
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
    changeDuplicateDialogue (value) {
      this.show = value
    },
    onDuplicate (duplicate) {
      // TODO: future work: https://github.com/FreshinUp/foodfleet/issues/385
      // Fields remaining/not found: $location_uuid

      const toDuplicate = [
        {
          condition: duplicate.basicInformation,
          action: (payload) => {
            return {
              ...payload,
              ...this.$refs.basicInfo.eventData
            }
          }
        },
        // TODO: future work: https://github.com/FreshinUp/foodfleet/issues/385
        {
          condition: duplicate.venue,
          action: (payload) => {
            return {
              ...payload
            }
          }
        },
        {
          condition: duplicate.fleetMember,
          action: (payload) => {
            // fields to consider: this.types,this.storeStatuses,this.stores
            return {
              ...payload
            }
          }
        },
        {
          condition: duplicate.customer,
          action: (payload) => {
            // fields to consider: this.customers,this.statuses
            return {
              ...payload
            }
          }
        }
      ]

      const data = toDuplicate.reduce((payload, strategy) => {
        if (strategy.condition) {
          payload = Object.assign({}, payload, strategy.action(payload))
        }
        return payload
      }, {})

      if (data.name) {
        data.name = getFileNameCopy(data.name)
      }
      this.loading = true
      this.$store.dispatch('events/createItem', {
        data
      })
        .then(response => {
          const eventUuid = get(response, 'data.data.uuid')
          if (eventUuid) {
            const path = `/admin/events/${eventUuid}/edit`
            window.location = path
            // TODO: Replace this with the proper route for an event item this.$router.push({ path })
          }
        })
        .catch(error => {
          console.error(error)
        })
        .then(() => {
          this.loading = false
          this.show = false
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
        this.eventLoading = true
        if (this.isNew) {
          await this.$store.dispatch('events/createItem', { data })
          await this.$store.dispatch('generalMessage/setMessage', 'Saved.')
          this.$router.push({ path: '/admin/events/' })
        } else {
          await this.$store.dispatch('events/updateItem', { data, params: { id: data.uuid } })
          await this.$store.dispatch('generalMessage/setMessage', 'Modified.')
        }
      } catch (error) {
        const message = get(error, 'response.data.message', error.message)
        this.$store.dispatch('generalErrorMessages/setErrors', message)
      } finally {
        this.eventLoading = false
      }
    },
    onCancel () {
      this.$router.push({ path: '/admin/events' })
    },
    async onDelete () {
      await this.$store.dispatch('events/deleteItem', { getItems: false, params: { id: this.event.uuid } })
      await this.$store.dispatch('generalMessage/setMessage', 'Deleted.')
      this.$router.push({ path: '/admin/events/' })
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
    vm.eventLoading = true
    vm.$store.dispatch('events/getItem', { params })
      .then()
      .catch(error => {
        console.error(error)
        vm.$router.push({ path: '/admin/events' })
      })
      .then(() => {
        vm.eventLoading = false
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
