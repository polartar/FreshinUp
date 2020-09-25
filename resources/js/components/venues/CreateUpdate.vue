<template>
  <div>
    <v-layout pt4 class="px-4" column>
      <v-flex>
        <v-btn
          flat
          small
          class="mx-0"
          @click="returnToList"
        >
          <div class="d-flex align-content-center white--text">
            <v-icon>fas fa-arrow-left</v-icon>
            <span
              class="mx-3"
              style="line-height: 1.60rem;"
            >Return to Venues list</span>
          </div>
        </v-btn>
      </v-flex>
      <v-flex class="d-flex justify-space-between align-content-center my-3">
        <div class="white--text headline">
          {{ pageTitle }}
        </div>
        <v-flex
          text-xs-right
          sm2
          xs12
        >
          <status-select
            :value="get(venue, 'status_id')"
            :options="statuses"
            @input="value => changeStatus(value)"
          />
        </v-flex>
      </v-flex>
      <v-flex class="mt-5">
        <basic-information
          :value="venue"
          @input="onSave"
          @cancel="onCancel"
          @delete="onDelete"
        />
      </v-flex>
      <v-flex class="mt-4" v-if="!isNew">
        <locations
          :is-loading="locationLoading"
          :items="locations"
          :rows-per-page="locationPagination.rowsPerPage"
          :page="locationPagination.page"
          :total-items="locationPagination.totalItems"
          :sort-by="locationSorting.sortBy"
          :descending="locationSorting.descending"
          @paginate="onLocationPaginate"
          @manage-delete="item => deleteResource(DELETABLE_RESOURCE.LOCATION, item)"
          @manage-multiple-delete="items => deleteResources(DELETABLE_RESOURCE.LOCATION, items)"
        />
      </v-flex>
      <v-flex class="mt-4" v-if="!isNew">
        <documents
          :is-loading="documentLoading"
          :items="documents"
          :statuses="documentStatuses"
          :rows-per-page="documentPagination.rowsPerPage"
          :page="documentPagination.page"
          :total-items="documentPagination.totalItems"
          :sort-by="documentSorting.sortBy"
          :descending="documentSorting.descending"
          @paginate="onDocumentPaginate"
          @manage-view="viewDocument"
          @manage-edit="editDocument"
          @manage-delete="item => deleteResource(DELETABLE_RESOURCE.DOCUMENT, item)"
          @manage-multiple-delete="items => deleteResources(DELETABLE_RESOURCE.DOCUMENT, items)"
          @change-status="changeDocumentStatus"
          @change-status-multiple="changeDocumentStatuses"
        />
      </v-flex>
      <v-flex class="mt-4" v-if="!isNew">
        <events
          :items="events"
          :statuses="eventStatuses"
          @paginate="onEventPaginate"
          @manage-view="viewEvent"
          @manage-delete="item => deleteResource(DELETABLE_RESOURCE.EVENT, item)"
          @manage-multiple-delete="items => deleteResources(DELETABLE_RESOURCE.EVENT, items)"
          @change-status="changeEventStatus"
          @change-status-multiple="changeEventStatuses"
          @runFilter="filterEvents"
        />
      </v-flex>
    </v-layout>
    <v-dialog
      v-if="!isNew"
      v-model="deletable.dialog"
      max-width="500"
    >
      <simple-confirm
        :class="{ 'deleting': deletable.processing }"
        :title="deletable.dialogTitle(deletable.temp)"
        ok-label="Yes"
        cancel-label="No"
        @ok="onSubmitDelete(deletable.resource)"
        @cancel="onCancelDelete(deletable.resource)"
      >
        <div class="py-5 px-2">
          <template v-if="deletable.processing">
            <div class="text-xs-center">
              <p class="subheading">
                Processing, please wait...
              </p>
              <v-progress-circular
                :rotate="-90"
                :size="200"
                :width="15"
                :value="deletable.progress"
                color="primary"
              >
                {{ deletable.status }}
              </v-progress-circular>
            </div>
          </template>
          <template v-else>
            <p class="subheading">
              Deletable(s) : {{ deletable.temp | formatDeleteTitles }}
            </p>
          </template>
        </div>
      </simple-confirm>
    </v-dialog>
  </div>
</template>
<script>
import { mapActions, mapGetters } from 'vuex'
import BasicInformation, { DEFAULT_VENUE } from './BasicInformation'
import Documents from './Documents'
import get from 'lodash/get'
import StatusSelect from '../events/StatusSelect'
import { deletables } from 'fresh-bus/components/mixins/Deletables'
import SimpleConfirm from 'fresh-bus/components/SimpleConfirm.vue'
import Locations from '../locations/Locations'
import Events from './Events'

const INCLUDE = [
  'owner'
]
const LOCATION_INCLUDES = [
  'category',
  'events'
]
const EVENT_INCLUDES = [
  'event_tags',
  'host',
  'manager'
]
const DELETABLE_RESOURCE = {
  DOCUMENT: 'DOCUMENT',
  LOCATION: 'LOCATION',
  VENUE: 'VENUE',
  EVENT: 'EVENT',
}
export default {
  layout: 'admin',
  components: {
    BasicInformation,
    StatusSelect,
    Documents,
    SimpleConfirm,
    Locations,
    Events,
  },
  data () {
    return {
      deletable: {
        resource: '', // DOCUMENT | LOCATION | VENUE
        dialog: false,
        processing: false,
        progress: 0,
        status: '',
        temp: [],
        dialogTitle: (items) => items.length < 2
          ? 'Are you sure you want to delete this document?'
          : 'Are you sure you want to delete the following documents?'
      },
      documentLoading: false,
      locationLoading: false,
    }
  },
  computed: {
    ...mapGetters('page', {
      isLoading: 'isLoading'
    }),
    ...mapGetters('documents', {
      documents: 'items',
      documentPagination: 'pagination',
      documentSorting: 'sorting',
      documentSortBy: 'sortBy'
    }),
    ...mapGetters('locations', {
      locations: 'items',
      locationPagination: 'pagination',
      locationSorting: 'sorting',
      locationSortBy: 'sortBy'
    }),
    ...mapGetters('documentStatuses', { documentStatuses: 'items' }),
    ...mapGetters('venues', { venue_: 'item' }),
    ...mapGetters('venueStatuses', { statuses: 'items' }),
    ...mapGetters('eventStatuses', { eventStatuses: 'items' }),
    ...mapGetters('events', {
      events: 'items',
      eventPagination: 'pagination',
      eventSorting: 'sorting',
      eventSortBy: 'sortBy'
    }),
    venue () {
      return this.isNew ? DEFAULT_VENUE : this.venue_
    },
    pageTitle () {
      return this.isNew ? 'New Venue' : 'Venue Details'
    },
    isNew () {
      return get(this.$route, 'params.id', 'new') === 'new'
    }
  },
  mixins: [deletables],
  filters: {
    formatDeleteTitles (value, prop = 'name') {
      return value.map(item => item[prop]).join(', ')
    }
  },
  methods: {
    get,
    ...mapActions('page', {
      setPageLoading: 'setLoading'
    }),
    returnToList () {
      this.$router.push({ path: '/admin/venues' })
    },
    filterEvents (params) {
      this.$store.dispatch('events/setSort', params.sort)
      this.$store.dispatch('events/patchFilters', {
        'filter[name]': params['filter[name]']
      })
      this.$store.dispatch('events/getItems', { params: { include: EVENT_INCLUDES }})
    },
    changeStatus (statusId) {
      return this.onSave({ status_id: statusId, uuid: this.$route.params.id })
    },
    async onSave (data) {
      try {
        this.setPageLoading(true)
        if (this.isNew) {
          await this.$store.dispatch('venues/createItem', { data })
          await this.$store.dispatch('generalMessage/setMessage', 'Saved.')
          this.returnToList()
        } else {
          await this.$store.dispatch('venues/updateItem', { data, params: { id: data.uuid } })
          await this.$store.dispatch('generalMessage/setMessage', 'Modified.')
        }
      } catch (error) {
        const message = get(error, 'response.data.message', error.message)
        this.$store.dispatch('generalErrorMessages/setErrors', message)
      } finally {
        this.setPageLoading(false)
      }
    },
    onCancel () {
      this.returnToList()
    },
    async onDelete (item) {
      try {
        this.setPageLoading(true)
        await this.$store.dispatch('venues/deleteItem', { getItems: false, params: { id: this.$route.params.id } })
        this.setPageLoading(false)
        await this.$store.dispatch('generalMessage/setMessage', 'Deleted.')
        this.returnToList()
      } catch (error) {
        const message = get(error, 'response.data.message', error.message)
        this.$store.dispatch('generalErrorMessages/setErrors', message)
      }
    },
    deleteResource (resource, type) {
      this.deletable.resource = type
      this.deletable.temp = [resource]
      this.deletable.dialog = true
    },
    deleteResources (documents, type) {
      this.deletable.resource = type
      this.deleteTemp = documents
      this.deletable.dialog = true
    },

    // Documents
    onDocumentPaginate (value) {
      this.$store.dispatch('documents/setPagination', value)
      this.$store.dispatch('documents/getItems')
    },
    viewDocument (document) {
      this.$router.push({ path: `/admin/documents/${document.uuid}` })
    },
    editDocument (document) {
      this.$router.push({ path: `/admin/documents/${document.uuid}/edit` })
    },
    changeDocumentStatus (statusId, document) {
      this.documentLoading = true
      this.$store.dispatch('documents/patchItem', {
        data: { status: statusId },
        params: { id: document.uuid }
      })
        .then()
        .catch(error => {
          const message = get(error, 'response.data.message', error.message)
          this.$store.dispatch('generalErrorMessages/setErrors', message)
        })
        .then(() => {
          this.documentLoading = false
        })
    },
    changeDocumentStatuses (statusId, documents) {
      documents.forEach((document) => {
        this.changeStatusSingle(statusId, document)
      })
    },
    async onSubmitDelete () {
      this.deletable.processing = true
      this.deletable.progress = 0
      this.deletable.status = ''
      const dispatcheables = []

      this.deletable.temp.forEach((item) => {
        dispatcheables.push(this.$store.dispatch(`${this.deletable.resource.toLowerCase()}s/deleteItem`, {
          getItems: false,
          params: { id: item.uuid } // assomption that all models (location, venue, document) has uuid
        }))
      })

      let chunks = this.chunk(dispatcheables, this.deleteTempParrallelRequest)
      let doneCount = 0

      for (let i in chunks) {
        await Promise.all(chunks[i])
        doneCount += chunks[i].length
        this.deleteTempStatus = doneCount + ' / ' + this.deleteTemp.length + ' Done'
        this.deleteTempProgress = doneCount / this.deleteTemp.length * 100
        await this.sleep(this.deletablesSleepTime)
      }

      // this.filterEvents(this.lastFilterParams)
      await this.sleep(500)
      this.deletable.processing = false
      this.deletable.dialog = false
    },
    onCancelDelete () {
      this.deleteDialog = false
      this.deleteTemp = []
    },

    // Locations
    onLocationPaginate (value) {
      this.$store.dispatch('locations/setPagination', value)
      this.$store.dispatch('locations/getItems', {
        params: { include: LOCATION_INCLUDES }
      })
    },

    // Events
    onEventPaginate (value) {
      this.$store.dispatch('events/setPagination', value)
      this.$store.dispatch('events/getItems', { params: { include: EVENT_INCLUDES }})
    },
    changeEventStatus (statusId, event) {
      this.documentLoading = true
      this.$store.dispatch('events/patchItem', {
        data: { status_id: statusId },
        params: { id: event.uuid }
      })
        .then()
        .catch(error => {
          const message = get(error, 'response.data.message', error.message)
          this.$store.dispatch('generalErrorMessages/setErrors', message)
        })
        .then(() => {
          this.documentLoading = false
        })
    },
    changeEventStatuses (statusId, events) {
      events.forEach((event) => {
        this.changeStatusSingle(statusId, event)
      })
    },
    viewEvent (event) {
      this.$router.push({ path: `/admin/events/${event.uuid}/edit` })
    }
  },
  beforeRouteEnterOrUpdate (vm, to, from, next) {
    const id = to.params.id || 'new'
    const promises = []

    if (id !== 'new') {
      vm.setPageLoading(true)
      vm.$store.dispatch('venues/getItem', { params: { id, include: INCLUDE } })
        .then()
        .catch(error => console.error(error))
        .then(() => {
          vm.setPageLoading(false)
        })
      vm.$store.dispatch('documents/setFilters', {
        'filter[assigned_uuid]': id
      })
      promises.push(vm.$store.dispatch('documents/getItems'))
      promises.push(vm.$store.dispatch('documentStatuses/getItems'))
      vm.$store.dispatch('events/setFilters', {
        'filter[venue_uuid]': id
      })
      promises.push(vm.$store.dispatch('events/getItems', { params: { include: EVENT_INCLUDES }}))
      vm.$store.dispatch('locations/setFilters', {
        'filter[venue_uuid]': id
      })
      promises.push(vm.$store.dispatch('locations/getItems', { params: { include: LOCATION_INCLUDES } }))
    }
    promises.push(vm.$store.dispatch('venueStatuses/getItems'))
    promises.push(vm.$store.dispatch('eventStatuses/getItems'))

    Promise.all(promises)
      .then(() => {})
      .catch((error) => {
        console.error(error)
      })
      .then(() => {
        if (next) next()
      })
  }
}
</script>
