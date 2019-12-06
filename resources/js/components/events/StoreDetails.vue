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
        {{ store.name }}
      </h2>
      <v-flex
        text-xs-right
        sm2
        xs12
      >
        <status-select
          :value="status"
          :options="storeStatuses"
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

          <v-tabs-items v-model="tab">
            <v-tab-item>
              <v-card
                flat
                color="basil"
              >
                <menus
                  :menus="menuItems"
                  :menu-title="store.name"
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
                  :documents="store.documents"
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
      >
        <v-card class="mx-2">
          <v-divider />
          <store-summary
            :store="store1"
          />
        </v-card>
      </v-flex>
    </v-layout>
  </div>
</template>

<script>
import { omitBy, isNull } from 'lodash'
import { mapGetters, mapActions } from 'vuex'
import { createHelpers } from 'vuex-map-fields'
import StatusSelect from '~/components/events/StatusSelect'
import Menus from '~/components/events/Menus.vue'
import DocumentSection from '~/components/events/DocumentSection.vue'
import Messages from '~/components/events/Messages.vue'
import StoreSummary from '~/components/events/StoreSummary.vue'

const { mapFields } = createHelpers({
  getterType: 'getField',
  mutationType: 'updateField'
})

export default {
  name: 'StoreDetails',
  layout: 'admin',
  components: {
    StatusSelect,
    Menus,
    DocumentSection,
    Messages,
    StoreSummary
  },
  data () {
    return {
      tab: null,
      tabItems: [
        'Event Menu', 'Event Documents', 'Event Activity'
      ],
      activists: 'William D and John Smith',
      store1: {
        status: 1,
        owner: 'Dan Smith',
        lisence_due: 'Dec, 30 2020',
        phone: '938 374822',
        email: 'dan.simth@gmail.com',
        tags: ['SEAFOOD', 'SMOKED', 'DESSERT', 'BAY AREA', 'VEGAN OPTIONS', 'SMOKED', 'DESSERT', 'SEAFOOD', 'SMOKED']
      }
    }
  },
  computed: {
    ...mapGetters('page', ['isLoading']),
    ...mapGetters('events', { event: 'item' }),
    ...mapGetters('stores', { store: 'item' }),
    ...mapGetters('messages', { messages: 'items' }),
    ...mapGetters('eventMenuItems', { menuItems: 'items' }),
    ...mapGetters('storeStatuses', { storeStatuses: 'items' }),
    ...mapGetters('documentStatuses', { documentStatuses: 'items' }),
    ...mapFields('stores', [
      'status'
    ])
  },
  methods: {
    ...mapActions('page', {
      setPageLoading: 'setLoading'
    }),
    async menuItemSave (params) {
      let data = {
        ...params,
        event_uuid: this.event.uuid,
        store_uuid: this.store.uuid
      }
      data = omitBy(data, (value, key) => {
        const extra = ['created_at', 'updated_at']
        return extra.includes(key) || isNull(value)
      })
      if (data.uuid) {
        await this.$store.dispatch('eventMenuItems/updateItem', { data, params: { id: data.uuid } })
        await this.$store.dispatch('eventMenuItems/getItems')
        await this.$store.dispatch('generalMessage/setMessage', 'Modified')
      } else {
        await this.$store.dispatch('eventMenuItems/createItem', { data })
        await this.$store.dispatch('eventMenuItems/getItems')
        await this.$store.dispatch('generalMessage/setMessage', 'Saved')
      }
    },
    async menuItemDelete (params) {
      await this.$store.dispatch('eventMenuItems/deleteItem', { getItems: false, params: { id: params.uuid } })
      await this.$store.dispatch('eventMenuItems/getItems')
      await this.$store.dispatch('generalMessage/setMessage', 'Deleted')
    },
    async messageSave (message) {
      let data = {
        content: message,
        event_uuid: this.event.uuid,
        store_uuid: this.store.uuid
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
</style>
