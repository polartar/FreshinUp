<template>
  <div>
    <v-flex
      d-flex
      align-center
      justify-space-between
      ma-2
    >
      <h2 class="white--text">
        {{ pageTitle }}
      </h2>
      <v-flex text-xs-right>
        <v-btn
          slot="activator"
          color="primary"
          dark
          @click="docNew"
        >
          Add New Content
        </v-btn>
      </v-flex>
    </v-flex>
    <v-divider />

    <br>
    <doc-filter
      v-if="!isLoading"
      :statuses="statuses"
      :infoList="infoList"
      @runFilter="filterUsers"
    />
    <doctable-list
      v-if="!isLoading"
      :docs="docs"
      :statuses="statuses"
      @manage-view="docView"
      @manage-edit="docEdit"
    />
  </div>
</template>

<script>
import { mapGetters, mapActions, mapState } from 'vuex'
import docFilter from '~/components/docs/FilterSorter.vue'
import simpleConfirm from 'fresh-bus/components/SimpleConfirm.vue'
import { deletables } from 'fresh-bus/components/mixins/Deletables'
import DoctableList from '~/components/docs/DoctableList.vue'
import get from 'lodash/get'

export default {
  layout: 'admin',
  components: {
    docFilter,
    DoctableList,
    simpleConfirm
  },
  mixins: [deletables],
  data () {
    return {
      pageTitle: 'Document List',
      deleteUserDialog: false,
      docs: [
        { id: 1, status: 1, type: 1, title: 'James Lee ID', owner: '3 Brothers Kitchen', created_at: '2019-09-16 06:26:03', expiration_date: '2019-09-16 06:26:03' },
        { id: 2, status: 2, type: 2, title: 'James Lee ID', owner: '3 Brothers Kitchen', created_at: '2019-09-16 06:26:03', expiration_date: '2019-09-16 06:26:03' }
      ],
      infoList: [],
      statuses: [
        { value: 1, text: 'Pending' },
        { value: 2, text: 'Approved' },
        { value: 3, text: 'Rejected' },
        { value: 4, text: 'Expiring' },
        { value: 5, text: 'Expired' }
      ],
      lastFilterParams: {}
    }
  },
  computed: {
    isLoadingList () {
      return get(this.$store, 'state.users.pending.items', true)
    },
    ...mapGetters('page', ['isLoading']),
    ...mapState('users', ['sortables'])
  },
  methods: {
    ...mapActions('page', {
      setPageLoading: 'setLoading'
    }),
    docNew () {
      this.$router.push({ path: '/admin/docs/new' })
    },
    docView (doc) {
      this.$router.push({ path: '/admin/docs/' + doc.id })
    },
    docEdit (doc) {
      this.$router.push({ path: '/admin/docs/' + doc.id })
    },
    filterUsers (params) {
      this.lastFilterParams = params
      this.$store.dispatch('users/setSort', params.sort)
      this.$store.dispatch('users/setFilters', {
        ...this.$route.query,
        ...this.lastFilterParams
      })
      this.$store.dispatch('users/getItems')
    }
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
