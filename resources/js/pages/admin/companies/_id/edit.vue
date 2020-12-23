<template>
  <div>
    <div :class="`fresh-company__edit fresh-company__edit--${company ? company.status : ''}`">
      <create-update :is-admin="isCurrentUserAdmin" />
    </div>

    <v-container
      v-if="companyId && company"
      fluid
    >
      <v-card class="mb-5">
        <v-card-title>
          <h3>Company Members</h3>
        </v-card-title>

        <v-divider />

        <v-alert
          :value="true"
          color="warning"
          icon="warning"
        >
          Coming soon
        </v-alert>
        <!-- Commented out for now
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
        -->
      </v-card>

      <v-card
        v-if="isCustomer"
        class="mb-5"
      >
        <v-card-title>
          <h3>Company Venues</h3>
        </v-card-title>

        <v-divider />

        <v-alert
          :value="true"
          color="warning"
          icon="warning"
        >
          Coming soon
        </v-alert>
      </v-card>

      <v-card
        v-if="isSupplier"
        class="mb-5"
      >
        <v-card-title>
          <h3>Company Fleet</h3>
        </v-card-title>

        <v-divider />

        <v-alert
          :value="true"
          color="warning"
          icon="warning"
        >
          Coming soon
        </v-alert>
      </v-card>

      <v-card>
        <v-card-title>
          <h3>Company Events</h3>
        </v-card-title>

        <v-divider />

        <v-alert
          :value="true"
          color="warning"
          icon="warning"
        >
          Coming soon
        </v-alert>
      </v-card>
    </v-container>
  </div>
</template>

<script>
import CreateUpdate from '~/components/companies/CreateUpdate.vue'
import BusCreateUpdate from 'fresh-bus/components/pages/admin/companies/CreateUpdate.vue'
import UsersPage from 'fresh-bus/pages/admin/users/index.vue'
import { mapActions, mapGetters } from 'vuex'

export default {
  components: {
    CreateUpdate
  },
  mixins: [UsersPage],
  layout: BusCreateUpdate.layout,

  data () {
    return {
      companyId: null
    }
  },

  beforeRouteEnterOrUpdate (vm, to, from, next) {
    vm.companyId = to.params.id

    BusCreateUpdate.beforeRouteEnterOrUpdate(vm, to, from, () => {
      if (vm.companyId) {
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
    ...mapGetters('currentUser', {
      isCurrentUserAdmin: 'isAdmin'
    }),
    ...mapGetters('companies', {
      company: 'item'
    }),
    ...mapGetters('companyDetails/users', {
      users: 'items',
      pagination: 'pagination',
      sorting: 'sorting',
      sortBy: 'sortBy'
    }),
    isSupplier () {
      return this.company.company_types.reduce((result, type) => {
        return result || (type.key_id === 'supplier')
      }, false)
    },
    isCustomer () {
      return this.company.company_types.reduce((result, type) => {
        return result || (type.key_id === 'host')
      }, false)
    }
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

<style lang="scss">
.fresh-company__edit--1 .v-select__selections {
  color: #71b179 !important;
}
.fresh-company__edit--4 .v-select__selections {
  color: #f9ad36 !important;
}
.fresh-company__edit--3 .v-select__selections {
  color: #888888 !important;
}
</style>
