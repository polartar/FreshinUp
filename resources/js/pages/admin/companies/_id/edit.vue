<template>
  <div>
    <create-update ref="createUpdate" />

    <v-container fluid>
      <v-card>
        <v-card-title>
          <h3>Company Members</h3>
        </v-card-title>

        <v-divider/>

        <user-filter
          v-if="!isLoading"
          :sortables="sortables"
          @runFilter="filterUsers"
        />
        <user-list
          v-if="!isLoading"
          :users="users"
          :levels="userlevels"
          :statuses="userstatuses"
          :is-loading="isLoading || isLoadingList"
          :rows-per-page="pagination.rowsPerPage"
          :page="pagination.page"
          :total-items="pagination.totalItems"
          :sort-by="sorting.sortBy"
          :descending="sorting.descending"
          :headers="headers"
          :item-actions="itemActions"
          class="users-list"
          must-sort
          @paginate="onUsersPaginate"
          @change-status="changeStatus"
          @change-level="changeLevel"
          @manage-teams="showUserTeamsAssigner"
          @manage-view="userView"
          @manage-edit="userEdit"
          @manage-delete="deleteUser"
          @manage-multiple-delete="deleteMultiple"
        />
      </v-card>
    </v-container>
  </div>
</template>

<script>
import CreateUpdate from 'fresh-bus/components/pages/admin/companies/CreateUpdate.vue'
import UsersPage from 'fresh-bus/pages/admin/users/index.vue'
import { mapActions, mapGetters } from 'vuex'

export default {
  components: {
    CreateUpdate
  },
  mixins: [UsersPage],
  layout: CreateUpdate.layout,

  data() {
    return {
      companyId: null
    }
  },

  beforeRouteEnterOrUpdate (vm, to, from, next) {
    vm.companyId = to.params.id

    CreateUpdate.beforeRouteEnterOrUpdate(vm, to, from, () => {
      if(vm.companyId) {
        vm.$store.dispatch('companyDetails/users/getItems', { params: { companyId: vm.companyId } }).then(() => {
          Promise.all([
            vm.$store.dispatch('userLevels/getUserlevels'),
            vm.$store.dispatch('userStatuses/getUserstatuses')
          ]).then(() => {
            vm.$store.dispatch('page/setLoading', false)
            if (next) next()
          })
        })
      } else {
        next()
      }
    })
  },

  computed: {
    ...mapGetters('companyDetails/users', {
      users: 'items',
      pagination: 'pagination',
      sorting: 'sorting',
      sortBy: 'sortBy'
    }),
  },

  methods: {
    ...mapActions('page', {
      setPageLoading: 'setLoading'
    }),
    onUsersPaginate (value) {
      this.$store.dispatch('companyDetails/users/setPagination', value)
      this.$store.dispatch('companyDetails/users/getItems', { params: { companyId: this.companyId } })
    },
    filterUsers (params) {
      this.lastFilterParams = params
      this.$store.dispatch('companyDetails/users/setSort', params.sort)
      this.$store.dispatch('companyDetails/users/setFilters', {
        ...this.$route.query,
        ...this.lastFilterParams
      })
      this.$store.dispatch('companyDetails/users/getItems', { params: { companyId: this.companyId } })
    }
  }
}
</script>
