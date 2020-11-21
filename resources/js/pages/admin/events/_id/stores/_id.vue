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
        {{ get(store, 'name') }}
      </h2>
      <v-flex
        text-xs-right
        sm2
        xs12
      >
        <f-btn-status
          label-prop="name"
          :value="status_id"
          :items="storeStatuses"
        />
      </v-flex>
    </v-layout>
    <v-divider />
    <br>
    <v-layout
      row
      pa-2
    >
      <v-flex
        md8
        sm8
        xs12
      >
        <v-card class="mx-2">
          <v-tabs
            v-model="tab"
            background-color="transparent"
            color="basil"
            grow
          >
            <v-tab
              v-for="item in tabItems"
              :key="item"
            >
              {{ item }}
            </v-tab>
          </v-tabs>

          <v-tabs-items
            v-model="tab"
            color="basil"
          >
            <v-tab-item>
              <v-card
                flat
                color="basil"
              >
                <menus
                  :menus="menuItems"
                  :menu-title="store && store.name"
                  @save="menuItemSave"
                  @manage-delete="menuItemDelete"
                />
              </v-card>
            </v-tab-item>
            <v-tab-item>
              <v-card
                flat
                color="basil"
              >
                <document-section
                  :statuses="documentStatuses"
                  :documents="documents"
                />
              </v-card>
            </v-tab-item>
            <v-tab-item>
              <v-card
                flat
                color="basil"
              >
                <messages
                  :activists="activists"
                  :messages="messages"
                  @send-message="messageSave"
                />
              </v-card>
            </v-tab-item>
          </v-tabs-items>
        </v-card>
      </v-flex>
      <v-flex
        md4
        sm4
        xs12
      >
        <v-layout
          row
        >
          <v-flex>
            <v-card class="mx-2">
              <v-divider />
              <store-summary
                :store="summary"
              />
            </v-card>
          </v-flex>
        </v-layout>

        <v-layout
          row
          class="service-summary"
        >
          <v-flex>
            <v-card class="mx-2">
              <v-divider />
              <store-service-summary
                :service="service"
                @save="commissionSave"
              />
            </v-card>
          </v-flex>
        </v-layout>
      </v-flex>
    </v-layout>
  </div>
</template>

<script>
import { omitBy, isNull, get } from 'lodash'
import { mapGetters, mapActions } from 'vuex'
import Menus from '~/components/events/Menus.vue'
import DocumentSection from '~/components/events/DocumentSection.vue'
import Messages from '~/components/events/Messages.vue'
import StoreSummary from '~/components/events/StoreSummary.vue'
import StoreServiceSummary from '~/components/events/StoreServiceSummary.vue'
import FBtnStatus from 'fresh-bus/components/ui/FBtnStatus'

export default {
  layout: 'admin',
  components: {
    FBtnStatus,
    Menus,
    DocumentSection,
    Messages,
    StoreSummary,
    StoreServiceSummary
  },
  data () {
    return {
      tab: null,
      tabItems: [
        'Event Menu', 'Event Documents', 'Event Activity'
      ],
      activists: 'William D and John Smith',
    }
  },
  computed: {
    ...mapGetters('page', ['isLoading']),
    ...mapGetters('events', { event: 'item' }),
    ...mapGetters('stores', { store: 'item' }),
    ...mapGetters('stores/summary', { storeSummary: 'item' }),
    ...mapGetters('stores/serviceSummary', { serviceSummary: 'item' }),
    ...mapGetters('messages', { messages: 'items' }),
    ...mapGetters('eventMenuItems', { menuItems: 'items' }),
    ...mapGetters('storeStatuses', { storeStatuses: 'items' }),
    ...mapGetters('documentStatuses', { documentStatuses: 'items' }),
    status_id () {
      return get(this.store, 'status', 1)
    },
    documents () {
      return get(this.store, 'documents') || []
    },
    summary () {
      return {
        status: get(this.store, 'status'),
        owner: get(this.storeSummary, 'owner.first_name') + get(this.storeSummary, 'owner.last_name'),
        lisence_due: 'Dec, 30 2020',
        phone: get(this.storeSummary, 'owner.mobile_phone'),
        email: get(this.storeSummary, 'owner.email'),
        tags: get(this.storeSummary, 'tags')
      }
    },
    service () {
      let commissionRate = get(this.event, 'commission_rate') || 0
      let commissionType = get(this.event, 'commission_type') || 1
      const eventStores = get(this.serviceSummary, 'event_stores')
      if (eventStores && eventStores.length) {
        const eventStore = eventStores.filter(ele => ele.event_uuid === get(this.event, 'uuid'))
        if (eventStore.length) {
          commissionRate = get(eventStore[0], 'commission_rate', this.event.commission_rate) || 0
          commissionType = get(eventStore[0], 'commission_type', this.event.commission_type) || 1
        }
      }
      return {
        ...this.serviceSummary,
        commission_rate: commissionRate,
        commission_type: commissionType
      }
    }
  },
  methods: {
    get,
    ...mapActions('page', {
      setPageLoading: 'setLoading'
    }),
    async menuItemSave (params) {
      let data = {
        ...params,
        event_uuid: get(this.event, 'uuid'),
        store_uuid: get(this.store, 'uuid')
      }
      data = omitBy(data, (value, key) => {
        const extra = ['created_at', 'updated_at']
        return extra.includes(key) || isNull(value)
      })
      if (data.uuid) {
        await this.$store.dispatch('eventMenuItems/updateItem', { data, params: { id: data.uuid } })
        await this.$store.dispatch('generalMessage/setMessage', 'Modified')
      } else {
        await this.$store.dispatch('eventMenuItems/createItem', { data })
        await this.$store.dispatch('generalMessage/setMessage', 'Saved')
      }
      await this.$store.dispatch('eventMenuItems/getItems')
      await this.$store.dispatch('stores/serviceSummary/getItem', { params: { id: this.store.uuid } })
    },
    async menuItemDelete (params) {
      await this.$store.dispatch('eventMenuItems/deleteItem', { getItems: false, params: { id: params.uuid } })
      await this.$store.dispatch('eventMenuItems/getItems')
      await this.$store.dispatch('stores/serviceSummary/getItem', { params: { id: this.store.uuid } })
      await this.$store.dispatch('generalMessage/setMessage', 'Deleted')
    },
    async messageSave (message) {
      let data = {
        content: message,
        event_uuid: get(this.event, 'uuid'),
        store_uuid: get(this.store, 'uuid')
      }
      data = omitBy(data, (value, key) => {
        const extra = ['created_at', 'updated_at']
        return extra.includes(key) || isNull(value)
      })
      await this.$store.dispatch('messages/createItem', { data })
      await this.$store.dispatch('messages/getItems')
      await this.$store.dispatch('generalMessage/setMessage', 'Saved')
    },
    backToEventDetails () {
      this.$router.push({ path: '/admin/events/' + this.event.uuid + '/edit' })
    },
    async commissionSave (params) {
      let data = {
        ...params,
        event_uuid: get(this.event, 'uuid')
      }
      data = omitBy(data, (value, key) => {
        const extra = ['event_stores', 'total_cost', 'total_services']
        return extra.includes(key) || isNull(value)
      })
      await this.$store.dispatch('stores/updateItem', { data, params: { id: this.store.uuid } })
      await this.$store.dispatch('stores/serviceSummary/getItem', { params: { id: this.store.uuid } })
    }
  },
  beforeRouteEnterOrUpdate (vm, to, from, next) {
    vm.setPageLoading(true)
    const eventUuid = to.path.split('/')[3]
    const storeUuid = to.params.id

    let eventParams = { id: eventUuid }
    let params = {
      id: storeUuid,
      include: 'documents'
    }
    let filter = { store_uuid: storeUuid }

    Promise.all([
      vm.$store.dispatch('events/getItem', { params: eventParams }),
      vm.$store.dispatch('stores/getItem', { params }),
      vm.$store.dispatch('stores/summary/getItem', { params: { id: storeUuid } }),
      vm.$store.dispatch('stores/serviceSummary/getItem', { params: { id: storeUuid } }),
      vm.$store.dispatch('eventMenuItems/setFilters', filter),
      vm.$store.dispatch('eventMenuItems/getItems'),
      vm.$store.dispatch('messages/setFilters', filter),
      vm.$store.dispatch('messages/getItems'),
      vm.$store.dispatch('storeStatuses/getItems'),
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
