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
      <basic-information />
    </v-flex>

    <v-flex
      v-if="!isNew"
      class="mt-4"
    >
      <company-overview
        :value="currentUser.company"
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
    }
  },
  beforeRouteEnterOrUpdate (vm, to, from, next) {
    next && next()
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
