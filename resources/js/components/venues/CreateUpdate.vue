<template>
  <div class="pa-4">
    <div>
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
    </div>
    <div class="d-flex justify-space-between align-content-center my-3">
      <div class="white--text headline">
        {{ pageTitle }}
      </div>
      <v-flex
        text-xs-right
        sm2
        xs12
      >
        <status-select
          v-model="venue.status_id"
          :options="statuses"
        />
      </v-flex>
    </div>
    <div class="mt-5">
      <BasicInformation
        :value="venue"
        @input="onSave"
        @cancel="onCancel"
        @delete="onDelete"
      />
    </div>
  </div>
</template>
<script>
import { mapGetters } from 'vuex'
import BasicInformation, { DEFAULT_VENUE } from './BasicInformation'
import get from 'lodash/get'
import StatusSelect from '../events/StatusSelect'

const INCLUDE = [
  'owner'
]

export default {
  layout: 'admin',
  components: {
    BasicInformation,
    StatusSelect
  },
  data () {
    return {
      venueLoading: false
    }
  },
  computed: {
    ...mapGetters('venues', { venue_: 'item' }),
    ...mapGetters('venueStatuses', { statuses: 'items' }),
    venue () {
      return this.isNew ? DEFAULT_VENUE : this.venue_
    },
    isLoading () {
      return this.$store.getters['page/isLoading'] || this.venueLoading
    },
    pageTitle () {
      return this.isNew ? 'New Venue' : 'Venue Details'
    },
    isNew () {
      return get(this.$route, 'params.id', 'new') === 'new'
    }
  },
  methods: {
    returnToList () {
      this.$router.push({ path: '/admin/venues' })
    },
    changeStatus (statusId) {
      return this.onSave({ status_id: statusId, uuid: this.$route.params.id })
    },
    async onSave (data) {
      try {
        this.venueLoading = true
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
        this.venueLoading = false
      }
    },
    onCancel () {
      this.returnToList()
    },
    async onDelete (item) {
      try {
        this.venueLoading = true
        await this.$store.dispatch('venues/deleteItem', { getItems: false, params: { id: this.$route.params.id } })
        this.venueLoading = false
        await this.$store.dispatch('generalMessage/setMessage', 'Deleted.')
        this.returnToList()
      } catch (error) {
        const message = get(error, 'response.data.message', error.message)
        this.$store.dispatch('generalErrorMessages/setErrors', message)
      }
    }
  },
  beforeRouteEnterOrUpdate (vm, to, from, next) {
    const id = to.params.id || 'new'
    const promises = []

    if (id !== 'new') {
      promises.push(vm.$store.dispatch('venues/getItem', { params: { id, include: INCLUDE } }))
    }
    promises.push(vm.$store.dispatch('venueStatuses/getItems'))

    vm.$store.dispatch('page/setLoading', true)
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
