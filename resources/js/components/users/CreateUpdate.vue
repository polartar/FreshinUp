<template>
  <div class="px-4">
    <v-flex>
      <v-btn
        flat
        small
        class="mx-0"
        @click="backToList"
      >
        <div class="d-flex align-content-center white--text">
          <v-icon>fas fa-arrow-left</v-icon>
          <span
            class="mx-3"
            style="line-height: 1.60rem;"
          >Return to User list</span>
        </div>
      </v-btn>
    </v-flex>

    <v-flex class="my-3">
      <div class="white--text headline">
        {{ pageTitle }}
      </div>
    </v-flex>

    <v-flex class="mt-5">
      <basic-information
        :value="user"
        :is-loading="userLoading"
        :levels="levels"
        :types="types"
        @input="createOrUpdate"
      />
    </v-flex>

    <v-flex
      v-if="!isNew"
      class="mt-4"
    >
      <company-overview
        :value="company"
        :types="companyTypes"
        :statuses="companyStatuses"
        @manage-view="viewCompany"
      />
    </v-flex>
  </div>
</template>

<script>
import get from 'lodash/get'

import BasicInformation from './BasicInformation.vue'
import CompanyOverview from '~/components/companies/CompanyOverview.vue'
import { mapGetters } from 'vuex'

const USER_INCLUDES = [
  'company'
  // TODO company api should allow retrieval of company_type and company_status
  // 'company_type',
  // 'company_status'
].join(',')
export default {
  components: {
    BasicInformation,
    CompanyOverview
  },
  layout: 'admin',
  data () {
    return {}
  },
  computed: {
    ...mapGetters(['currentUser']),
    ...mapGetters('users', {
      user: 'item',
      userLoading: 'itemLoading'
    }),
    ...mapGetters('userLevels', {
      levels: 'items'
    }),
    ...mapGetters('userTypes', {
      types: 'items'
    }),
    ...mapGetters('companyTypes', {
      companyTypes: 'items'
    }),
    ...mapGetters('companyStatuses', {
      companyStatuses: 'items'
    }),
    company () {
      // TODO: company should include:
      //  - company.company_type.name because type is already taken
      //  - company.company_status.name because status is already taken
      // Make API request that will do that
      return get(this.user, 'company')
    },
    pageTitle () {
      return this.isNew ? 'New User' : 'User Details'
    },
    isNew () {
      return get(this.$route, 'params.id', 'new') === 'new'
    }
  },
  methods: {
    backToList () {
      this.$router.push({ path: '/admin/users' })
    },
    viewCompany (company) {
      this.$router.push({ path: `/admin/companies/${company.uuid}` })
    },
    createOrUpdate (payload) {
      // TODO: exclude level and type for now. At some point we will need to add them back
      const { level, type, ...data } = payload
      const id = this.$route.params.id
      const action = this.isNew
        ? this.$store.dispatch('users/createItem', { data })
        : this.$store.dispatch('users/updateItem', {
          params: { id },
          data
        })
      action
        .then(() => {
          this.$store.dispatch('generalMessage/setMessage', 'Saved.')
          this.$store.dispatch('users/getItem', {
            params: {
              id,
              include: USER_INCLUDES
            }
          })
        })
        .catch(error => {
          const message = get(error, 'response.data.message', error.message)
          this.$store.dispatch('generalErrorMessages/setErrors', message)
        })
    }
  },
  beforeRouteEnterOrUpdate (vm, to, from, next) {
    const id = vm.$route.params.id
    const promises = []
    promises.push(vm.$store.dispatch('userLevels/getItems'))
    promises.push(vm.$store.dispatch('userTypes/getItems'))
    promises.push(vm.$store.dispatch('companyTypes/getItems'))
    promises.push(vm.$store.dispatch('companyStatuses/getItems'))
    if (id !== 'new') {
      promises.push(vm.$store.dispatch('users/getItem', {
        params: {
          id,
          include: USER_INCLUDES
        }
      }))
    }
    Promise.all(promises)
      .then()
      .catch()
      .then(() => {
        vm.$store.dispatch('page/setLoading', false)
        next && next()
      })
  }
}
</script>

<style scoped>
  .back-btn-inner {
    color: #fff;
    display: flex;
    align-items: center;
    font-size: 13px;
  }

  .back-btn-inner span {
    margin-left: 10px;
    font-weight: bold;
    text-transform: initial;
  }

  .back-btn-inner .v-icon {
    font-size: 16px;
  }
</style>
