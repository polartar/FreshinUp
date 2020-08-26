<template>
  <div>
    <div>
      <basic-information
        @save="saveMember"
        @delete="deleteMember"
        @cancel="onCancel"
      />
    </div>
    <v-layout
      row
      px-2
      py-4
    >
      <v-flex>
        <DocumentList
          :docs="docs"
          :statuses="statuses"
          :types="types"
          :sortables="sortables"
          :rows-per-page="pagination.rowsPerPage"
          :page="pagination.page"
          :total-items="pagination.totalItems"
          :sort-by="sorting.sortBy"
          :descending="sorting.descending"
        />
      </v-flex>
    </v-layout>
    <v-layout
      row
      px-2
      py-4
    >
      <v-flex>
        <Events
          :events="events"
        />
      </v-flex>
    </v-layout>
  </div>
</template>
<script>
import BasicInformation from './BasicInformation'
import DocumentList from './DocumentList'
import { mapGetters } from 'vuex'
import Events from './Events'

export default {
  layout: 'admin',
  components: {
    BasicInformation,
    DocumentList,
    Events
  },
  data () {
    return {
      pagination: {
        page: 1,
        rowsPerPage: 10,
        totalItems: 5
      },
      sorting: {
        descending: false,
        sortBy: ''
      },
      sortables: [
        { value: '-created_at', text: 'Newest' },
        { value: 'created_at', text: 'Oldest' },
        { value: 'title', text: 'Title (A - Z)' },
        { value: '-title', text: 'Title (Z - A)' }
      ],
      events: ['Event will populate once your restaurant is assigned.']
    }
  },
  computed: {
    ...mapGetters('documents', { docs: 'items' }),
    ...mapGetters('documentTypes', { types: 'items' }),
    ...mapGetters('documentStatuses', { statuses: 'items' })
  },
  methods: {
    saveMember (item) {},

    deleteMember (item) {},

    onCancel () {
      this.$router.push('/admin/fleet-members')
    }

  },

  beforeRouteEnterOrUpdate (vm, to, from, next) {
    const id = to.params.id || 'new'
    const promises = []
    if (id !== 'new') {
    }
    vm.$store.dispatch('page/setLoading', true)
    promises.push(vm.$store.dispatch('documents/getItem', { params: { id } }))
    promises.push(vm.$store.dispatch('documentStatuses/getItems'))
    promises.push(vm.$store.dispatch('documentTypes/getItems'))
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
