<template>
  <div>
    <v-layout
      row
      align-center
      pt-3
    >
      <v-btn
        flat
        small
        @click="backToEventDetails"
      >
        <div class="back-btn-inner">
          <v-icon>fas fa-arrow-left</v-icon>
          <span>Return to Events Details</span>
        </div>
      </v-btn>
    </v-layout>
    <v-layout
      row
      justify-space-between
      ma-2
    >
      <h2 class="white--text">
        {{ pageTitle }}
      </h2>
      <v-flex
        v-if="statuses.length"
        text-xs-right
        sm2
        xs12
      >
        <status-select
          :value="status"
          :options="statuses"
        />
      </v-flex>
    </v-layout>
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
        md9
        sm8
      >
        <v-tabs
          v-model="active"
          slider-color="primary"
        >
          <v-tab
            key="0"
            ripple
          >
            Event Document
          </v-tab>
          <v-tab
            key="1"
            ripple
          >
            Event Activity
          </v-tab>
          <v-tab-item
            key="0"
          >
            <document-section
              :statuses="documentStatuses"
              :documents="[]"
            />
          </v-tab-item>
          <v-tab-item
            key="1"
          >
            <message-list />
          </v-tab-item>
        </v-tabs>
      </v-flex>
      <v-flex
        md3
        sm4
        pl-3
      >
        <div
          class="mb-4"
        >
          <customer-summary
            :customer="customer"
            @onButtonClick="viewCustomerProfile"
          />
        </div>
        <div>
          <financial-summary
            :financial="financial"
            @onButtonClick="viewContact"
          />
        </div>
      </v-flex>
    </v-layout>
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'
import StatusSelect from '~/components/events/StatusSelect'
import CustomerSummary from '~/components/events/CustomerSummary'
import FinancialSummary from '~/components/events/FinancialSummary'
import DocumentSection from '~/components/events/DocumentSection'
import MessageList from '~/components/events/MessageList'

export default {
  layout: 'admin',
  components: {
    StatusSelect,
    CustomerSummary,
    FinancialSummary,
    DocumentSection,
    MessageList
  },
  data () {
    return {
      financial: {},
      active: 0
    }
  },
  computed: {
    ...mapGetters('page', ['isLoading']),
    ...mapGetters('events', { event: 'item' }),
    ...mapGetters('eventStatuses', { 'statuses': 'items' }),
    ...mapGetters('documentStatuses', { 'documentStatuses': 'items' }),
    pageTitle () {
      return this.event && this.event.name
    },
    status () {
      return this.event && this.event.host_status
    },
    customer () {
      return this.event ? {
        uuid: this.event.uuid,
        status: this.event.host_status,
        updated_at: this.event.updated_at,
        created_at: this.event.created_at
      } : {}
    }
  },
  methods: {
    ...mapActions('page', {
      setPageLoading: 'setLoading'
    }),
    backToEventDetails () {
      this.$router.push({ path: '/admin/events/' + this.event.uuid + '/edit' })
    },
    viewCustomerProfile () {
      if (this.event && this.event.host) {
        this.$router.push({ path: '/admin/companies/' + this.event.host.id })
      }
    },
    viewContact () {
    }
  },
  beforeRouteEnterOrUpdate (vm, to, from, next) {
    vm.setPageLoading(true)
    const eventUuid = to.path.split('/')[3]

    let eventParams = { id: eventUuid }

    Promise.all([
      vm.$store.dispatch('events/getItem', { params: eventParams }),
      vm.$store.dispatch('eventStatuses/getItems'),
      vm.$store.dispatch('documentStatuses/getItems')
    ]).then(() => {
      vm.$store.dispatch('page/setLoading', false)
      if (next) next()
    })
  }
}
</script>

<style scoped>
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
.service-summary{
  margin-top: 15px;
}
</style>
