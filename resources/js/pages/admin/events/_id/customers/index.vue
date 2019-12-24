<template>
  <div
    v-if="!isLoading"
  >
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
          v-model="activeTab"
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
              :documents="documents"
            />
          </v-tab-item>
          <v-tab-item
            key="1"
          >
            <messages
              :messages="messages"
              :activists="activists"
              @send-message="postMessage"
            />
          </v-tab-item>
        </v-tabs>
      </v-flex>
      <v-flex
        md3
        sm4
        pl-3
      >
        <div
          v-if="summary"
          class="mb-4"
        >
          <customer-summary
            :customer="summary.customer"
            @onButtonClick="viewCustomerProfile"
          />
        </div>
        <div>
          <financial-summary
            :financial="summary.financial"
            @onButtonClick="viewContact"
          />
        </div>
      </v-flex>
    </v-layout>
  </div>
</template>

<script>
import { get } from 'lodash'
import { mapGetters, mapActions } from 'vuex'
import StatusSelect from '~/components/events/StatusSelect'
import CustomerSummary from '~/components/events/CustomerSummary'
import FinancialSummary from '~/components/events/FinancialSummary'
import DocumentSection from '~/components/events/DocumentSection'
import Messages from '~/components/events/Messages'

export default {
  layout: 'admin',
  components: {
    StatusSelect,
    CustomerSummary,
    FinancialSummary,
    DocumentSection,
    Messages
  },
  data () {
    return {
      activeTab: 0,
      eventUuid: ''
    }
  },
  computed: {
    ...mapGetters('page', ['isLoading']),
    ...mapGetters('events', { event: 'item' }),
    ...mapGetters('eventSummary', { 'summary': 'item' }),
    ...mapGetters('eventStatuses', { 'statuses': 'items' }),
    ...mapGetters('documentStatuses', { 'documentStatuses': 'items' }),
    ...mapGetters('documents', { 'documents': 'items' }),
    ...mapGetters('messages', { 'messages': 'items' }),
    pageTitle () {
      return get(this.summary, 'customer.owner')
    },
    status () {
      return get(this.event, 'host_status')
    },
    activists () {
      if (this.pageTitle) {
        return 'Messages between FoodFleet and ' + this.pageTitle + ' will be displayed here.'
      } else {
        return ''
      }
    }
  },
  methods: {
    ...mapActions('page', {
      setPageLoading: 'setLoading'
    }),
    backToEventDetails () {
      this.$router.push({ path: '/admin/events/' + get(this.event, 'uuid') + '/edit' })
    },
    viewCustomerProfile () {
      this.$router.push({ path: '/admin/companies/' + get(this.event, 'host.id') })
    },
    viewContact () {
    },
    async postMessage (message) {
      const data = {
        content: message,
        event_uuid: get(this.event, 'uuid')
      }

      await this.$store.dispatch('messages/createItem', { data })
      await this.$store.dispatch('messages/getItems')
      await this.$store.dispatch('generalMessage/setMessage', 'Saved')
    }
  },
  beforeRouteEnterOrUpdate (vm, to, from, next) {
    vm.setPageLoading(true)
    this.eventUuid = to.path.split('/')[3]

    const eventParams = { id: this.eventUuid }
    const documentFilters = { assigned_uuid: this.eventUuid }
    const messageFilters = { event_uuid: this.eventUuid }

    vm.$store.dispatch('documents/setFilters', documentFilters)
    vm.$store.dispatch('messages/setFilters', messageFilters)

    Promise.all([
      vm.$store.dispatch('events/getItem', { params: eventParams }),
      vm.$store.dispatch('eventSummary/getItem', { params: eventParams }),
      vm.$store.dispatch('eventStatuses/getItems'),
      vm.$store.dispatch('documentStatuses/getItems'),
      vm.$store.dispatch('documents/getItems'),
      vm.$store.dispatch('messages/getItems')
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
