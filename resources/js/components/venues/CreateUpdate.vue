<template>
  <div>
    <v-layout
      pt4
      class="px-4"
      column
    >
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
      <v-flex
        v-if="!isNew"
        class="mt-4"
      >
        <locations
          :is-loading="locationLoading"
          :form-is-loading="locationFormIsLoading"
          :items="locations"
          :categories="locationCategories"
          :rows-per-page="locationPagination.rowsPerPage"
          :page="locationPagination.page"
          :total-items="locationPagination.totalItems"
          :sort-by="locationSorting.sortBy"
          :descending="locationSorting.descending"
          @new-location="createLocation"
          @paginate="onLocationPaginate"
          @manage-delete="item => deleteResource(DELETABLE_RESOURCE.LOCATION, item)"
          @manage-multiple-delete="items => deleteResources(DELETABLE_RESOURCE.LOCATION, items)"
        />
      </v-flex>
      <v-flex
        v-if="!isNew"
        class="mt-4"
      >
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
      <v-flex
        v-if="!isNew"
        class="mt-4"
      >
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
              Deletable(s) : {{ deletable.temp | formatDeleteTitles(deletable.resource) }}
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

const VENUE_INCLUDES = [
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
  EVENT: 'EVENT'
}

const TITLE_RESOURCE = {
  DOCUMENT: 'title',
  LOCATION: 'name',
  VENUE: 'name',
  EVENT: 'name'
}

export default {
  layout: 'admin',
  components: {
    BasicInformation,
    StatusSelect,
    Documents,
    SimpleConfirm,
    Locations,
    Events
  },
  filters: {
    formatDeleteTitles (value, resource) {
      const prop = TITLE_RESOURCE[resource] || 'name'
      return value.map(item => item[prop]).join(', ')
    }
  },
  mixins: [deletables],
  data () {
    return {
      locationFormIsLoading: false,
      DELETABLE_RESOURCE,
      deletable: {
        resource: '', // DOCUMENT | LOCATION | VENUE | EVENT
        dialog: false,
        processing: false,
        progress: 0,
        status: '',
        temp: [],
        dialogTitle: (items) => items.length < 2
          ? 'Are you sure you want to this item ?'
          : 'Are you sure you want to delete the following items?'
      },
      documentLoading: false,
      locationLoading: false
    }
  },
  computed: {
    ...mapGetters('documents', {
      documents: 'items',
      documentPagination: 'pagination',
      documentSorting: 'sorting'
    }),
    ...mapGetters('locations', {
      locations_: 'items',
      locationPagination: 'pagination',
      locationSorting: 'sorting'
    }),
    ...mapGetters('locationCategories', { locationCategories: 'items' }),
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
    locations () {
      // For some reasons, after creating a location, locations/items result to an object
      return Array.isArray(this.locations_) ? this.locations_ : []
    },
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
      this.$store.dispatch('events/getItems')
    },
    changeStatus (statusId) {
      if (!this.isNew) {
        return this.onSave({ status_id: statusId, uuid: this.$route.params.id })
      } else {
        this.status_id = statusId
      }
    },
    async onSave (data) {
      try {
        this.setPageLoading(true)
        if (this.isNew) {
          data.status_id = this.status_id
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
    deleteResource (type, resource) {
      this.deletable.resource = type
      this.deletable.temp = [resource]
      this.deletable.dialog = true
    },
    deleteResources (type, resources) {
      this.deletable.resource = type
      this.deletable.temp = resources
      this.deletable.dialog = true
    },

    // Documents
    onDocumentPaginate (value) {
      this.$store.dispatch('documents/setPagination', value)
      this.$store.dispatch('documents/getItems')
    },
    viewDocument (document) {
      this.$router.push({ path: `/admin/docs/${document.uuid}` })
    },
    editDocument (document) {
      this.$router.push({ path: `/admin/docs/${document.uuid}/edit` })
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
          getItems: true,
          params: { id: item.uuid } // assomption that all models (location, venue, document) has uuid
        }))
      })

      let chunks = this.chunk(dispatcheables, this.deleteTempParrallelRequest)
      let doneCount = 0

      for (let i in chunks) {
        await Promise.all(chunks[i])
        doneCount += chunks[i].length
        this.deletable.status = doneCount + ' / ' + this.deletable.temp.length + ' Done'
        this.deletable.progress = doneCount / this.deletable.temp.length * 100
        await this.sleep(this.deletablesSleepTime)
      }

      await this.sleep(500)
      this.deletable.processing = false
      this.deletable.dialog = false
    },
    onCancelDelete () {
      this.deletable.dialog = false
      this.deletable.temp = []
    },

    // Locations
    onLocationPaginate (value) {
      this.$store.dispatch('locations/setPagination', value)
      this.$store.dispatch('locations/getItems')
    },
    createLocation (data, locationFormComponent) {
      // TODO: We should avoid mutating props but in this case we don't care
      // TODO: use slot, move form component to here just like we did
      // in fleet-members/CreateUpdate.vue for AreaForm
      this.locationFormIsLoading = true
      this.$store.dispatch('locations/createItem', {
        data: { ...data, venue_uuid: this.$route.params.id }
      })
        .then(response => {
          // get(response, 'data', data)
          this.$store.dispatch('generalMessage/setMessage', 'Location added.')
          locationFormComponent.newLocationDialog = false
          this.$store.dispatch('locations/getItems')
        })
        .catch(error => {
          const message = get(error, 'response.data.message', error.message)
          this.$store.dispatch('generalErrorMessages/setErrors', message)
        })
        .then(() => {
          this.locationFormIsLoading = false
        })
    },

    // Events
    onEventPaginate (value) {
      this.$store.dispatch('events/setPagination', value)
      this.$store.dispatch('events/getItems')
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
      vm.$store.dispatch('venues/getItem', { params: { id, include: VENUE_INCLUDES } })
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
        'filter[venue_uuid]': id,
        include: EVENT_INCLUDES
      })
      promises.push(vm.$store.dispatch('events/getItems'))
      vm.$store.dispatch('locations/setFilters', {
        venue_uuid: id,
        include: LOCATION_INCLUDES.join(',')
      })
      promises.push(vm.$store.dispatch('locations/getItems'))
      promises.push(vm.$store.dispatch('locationCategories/getItems'))
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
