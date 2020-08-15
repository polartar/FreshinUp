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
      <v-flex
        d-flex
        align-center
        justify-space-between
        ma-2
      >
        <v-layout
          row
          align-center
        >
          <h2 class="white--text">
            {{ pageTitle }}
          </h2>
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
            <v-card>
              <v-progress-linear
                v-if="duplicating"
                indeterminate
              />
              <v-card-title>
                <v-layout
                  row
                  space-between
                  align-center
                >
                  <v-flex>
                    <h3>Duplicate Event</h3>
                  </v-flex>
                  <v-btn
                    small
                    round
                    color="grey"
                    class="white--text"
                    @click="duplicateDialog = false"
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
                <small class="font-weight-bold">SELECT</small>
                <p>Choose what will be carried over to the duplicate event</p>
                <v-checkbox
                  v-model="duplicate.basicInformation"
                  class="mt-0 mb-0 p-0"
                  label="Basic Information"
                />
                <v-checkbox
                  v-model="duplicate.venue"
                  class="mt-0 mb-0 p-0"
                  label="Venue/lovation (coming soon)"
                />
                <v-checkbox
                  v-model="duplicate.fleetMember"
                  class="mt-0 mb-0 p-0"
                  label="Fleet Member (coming soon)"
                />
                <v-checkbox
                  v-model="duplicate.customer"
                  class="mt-0 mb-0 p-0"
                  label="Customer (coming soon)"
                />
              </v-card-text>
              <v-divider />
              <v-card-actions>
                <v-layout
                  row
                  justify-end
                >
                  <v-btn
                    @click="duplicateDialog = false"
                  >
                    Cancel
                  </v-btn>
                  <v-btn
                    color="primary"
                    @click="onDuplicate"
                  >
                    Duplicate
                  </v-btn>
                </v-layout>
              </v-card-actions>
            </v-card>
          </v-dialog>
        </v-layout>
        <v-flex
          text-xs-right
          sm2
          xs12
        >
          <status-select
            v-model="status_id"
            :options="statuses"
          />
        </v-flex>
      </v-flex>
      <v-divider />
      <br>
      <v-layout
        row
        wrap
        pa-2
        justify-space-between
        class="event-new-wrap"
      >
        <v-flex
          md12
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
import isNull from 'lodash/isNull'
import get from 'lodash/get'
import { mapActions, mapGetters } from 'vuex'
import { createHelpers } from 'vuex-map-fields'
import Validate from 'fresh-bus/components/mixins/Validate'
import BasicInformation from '~/components/events/BasicInformation.vue'
import Stores from '~/components/events/Stores.vue'
import Customers from '~/components/events/Customers.vue'
import StatusSelect from '~/components/events/StatusSelect.vue'
import moment from 'moment'

const { mapFields } = createHelpers({
  getterType: 'getField',
  mutationType: 'updateField'
})

export const getFileNameCopy = (name) => {
  const regex = /\s*\(([0-9]+)\)$/gm
  const matches = name.match(regex) || []
  const count = (
    parseInt(
      get(matches, '[0]', '')
        .replace('(', '')
        .replace(')', '')
    ) || 0
  ) + 1
  return `Copy of ${name.replace(get(matches, '[0]', ''), '')} (${count})`
}

export default {
  layout: 'admin',
  components: {
    Stores,
    StatusSelect,
    BasicInformation,
    Customers
  },
  mixins: [Validate],
  data () {
    return {
      duplicating: false,
      duplicateDialog: false,
      duplicate: {
        basicInformation: true,
        venue: false,
        fleetMember: true,
        customer: true
      },
      isNew: false,
      types: []
    }
  },
  computed: {
    ...mapGetters('page', ['isLoading']),
    ...mapGetters('events', { event: 'item' }),
    ...mapGetters('events/stores', { storeItems: 'items' }),
    ...mapGetters('storeStatuses', { storeStatuses: 'items' }),
    ...mapGetters('eventStatuses', { 'statuses': 'items' }),
    ...mapFields('events', [
      'status_id'
    ]),
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
    ...mapActions('page', {
      setPageLoading: 'setLoading'
    }),
    onDuplicate () {
      // TODO: future work: https://github.com/FreshinUp/foodfleet/issues/385
      // Fields remaining/not found: $location_uuid
      const toDuplicate = [
        {
          condition: this.duplicate.basicInformation,
          action: (payload) => {
            return {
              ...payload,
              ...this.$refs.basicInfo.eventData
            }
          }
        },
        // TODO: future work: https://github.com/FreshinUp/foodfleet/issues/385
        {
          condition: this.duplicate.venue,
          action: (payload) => {
            return {
              ...payload
            }
          }
        },
        {
          condition: this.duplicate.fleetMember,
          action: (payload) => {
            // fields to consider: this.types,this.storeStatuses,this.stores
            return {
              ...payload
            }
          }
        },
        {
          condition: this.duplicate.customer,
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
      const today = moment()
      const tomorrow = moment().add(1, 'day')
      const startsInTheFuture = moment(data.start_at).diff(today) > 0
      const endsInTheFuture = moment(data.end_at).diff(today) > 0
      if (!startsInTheFuture) {
        data.start_at = `${tomorrow.format('YYYY-MM-DD')} 00:00`
      }
      if (!endsInTheFuture) {
        data.end_at = `${tomorrow.format('YYYY-MM-DD')} 23:59`
      }
      this.duplicating = true
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
          this.duplicating = false
          this.duplicateDialog = false
        })
    },
    changeBasicInfo (data) {
      this.event.attendees = data.attendees
      this.event.budget = data.budget
      this.event.commission_rate = data.commission_rate
      this.event.commission_type = data.commission_type
      this.event.type = data.type
      this.event.start_at = data.start_at
      this.event.end_at = data.end_at
      this.event.staff_notes = data.staff_notes
      this.event.member_notes = data.member_notes
      this.event.customer_notes = data.customer_notes
      this.event.event_tags = data.event_tags
      this.event.host_uuid = data.host_uuid
      this.event.manager_uuid = data.manager_uuid
      this.event.name = data.name
      this.event.schedule = data.schedule
      this.event.event_recurring_checked = data.event_recurring_checked
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
        ...this.event,
        host_uuid: get(this.event, 'host.uuid', this.event.host_uuid),
        manager_uuid: get(this.event, 'manager.uuid', this.event.manager_uuid)
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
        return false
      }
      if (this.isNew) {
        data.id = 'new'
        await this.$store.dispatch('events/createItem', { data })
        await this.$store.dispatch('generalMessage/setMessage', 'Saved')
        this.$router.push('/admin/events/')
      } else {
        await this.$store.dispatch('events/updateItem', { data, params: { id: data.uuid } })
        await this.$store.dispatch('generalMessage/setMessage', 'Modified')
      }
    },
    onCancel () {
      this.$router.push({ path: '/admin/events' })
    },
    async onDelete () {
      await this.$store.dispatch('events/deleteItem', { getItems: false, params: { id: this.event.uuid } })
      await this.$store.dispatch('generalMessage/setMessage', 'Deleted')
      this.$router.push('/admin/events/')
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
    changeStatus () {}
  },
  beforeRouteEnterOrUpdate (vm, to, from, next) {
    vm.setPageLoading(true)
    const id = to.params.id || 'new'
    let params = { id }
    let promise = []
    if (id !== 'new') {
      params = {
        id,
        include: 'manager,host,event_tags'
      }
      promise.push(vm.$store.dispatch('storeStatuses/getItems'))
      promise.push(vm.$store.dispatch('events/stores/getItems', {
        params: { eventId: id }
      }))
    }
    promise.push(vm.$store.dispatch('events/getItem', { params }))
    promise.push(vm.$store.dispatch('eventStatuses/getItems'))

    Promise.all(promise).then(() => {
      vm.$store.dispatch('page/setLoading', false)
      if (next) next()
    })
  }
}
</script>
<style scoped>
  .event-new-wrap{
    background-color: #fff;
  }
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
