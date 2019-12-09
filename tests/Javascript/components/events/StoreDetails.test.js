import { mount, shallowMount } from '@vue/test-utils'
import { createStore } from 'fresh-bus/store'
import createLocalVue from 'vue-cli-plugin-freshinup-ui/utils/testing/createLocalVue'
import { FIXTURE_EVENT } from 'tests/__data__/event'
import { FIXTURE_STORE } from 'tests/__data__/store'
import { FIXTURE_STORE_STATUSES } from 'tests/__data__/storeStatuses'
import { FIXTURE_DOCUMENT_STATUSES } from 'tests/__data__/documentStatuses'
import { FIXTURE_MENUS } from 'tests/__data__/menus'
import { FIXTURE_MESSAGES } from 'tests/__data__/messages'
import { FIXTURE_EVENT_STORE_SUMMARY } from 'tests/__data__/storeSummary'
import { FIXTURE_STORE_SERVICES } from 'tests/__data__/storeServiceSummary'
import BaseComponent from '~/components/events/StoreDetails.vue'
import events from '~/store/modules/events.js'
import stores from '~/store/modules/stores.js'
import storeStatuses from '~/store/modules/storeStatuses.js'
import documentStatuses from '~/store/modules/documentStatuses.js'
import eventMenuItems from '~/store/modules/eventMenuItems.js'
import messages from '~/store/modules/messages.js'

describe('Event StoreDetails Component', () => {
  let localVue, mock, store
  describe('Mount', () => {
    beforeEach(() => {
      const vue = createLocalVue({ validation: true })
      localVue = vue.localVue
      mock = vue.mock
      mock
        .onGet('api/foodfleet/events/2').reply(200, { data: FIXTURE_EVENT })
        .onGet('api/foodfleet/stores/2').reply(200, { data: FIXTURE_STORE })
        .onGet('api/foodfleet/store-summary/2').reply(200, { data: FIXTURE_EVENT_STORE_SUMMARY })
        .onGet('api/foodfleet/store-service-summary/2').reply(200, { data: FIXTURE_STORE_SERVICES })
        .onGet('api/foodfleet/store-statuses').reply(200, { data: FIXTURE_STORE_STATUSES })
        .onGet('api/foodfleet/document-statuses').reply(200, { data: FIXTURE_DOCUMENT_STATUSES })
        .onGet('api/foodfleet/event-menu-items?store_uuid=2').reply(200, { data: FIXTURE_MENUS })
        .onGet('api/foodfleet/messages?store_uuid=2').reply(200, { data: FIXTURE_MESSAGES })
        .onAny().reply(config => {
          console.warn('No mock match for ' + config.url, config)
          return [404, {}]
        })
      store = createStore({}, {
        modules: {
          events: events({}),
          stores: stores({}),
          storeStatuses: storeStatuses({}),
          documentStatuses: documentStatuses({}),
          eventMenuItems: eventMenuItems({}),
          messages: messages({})
        }
      })
    })

    afterEach(() => {
      mock.restore()
    })

    it('mount store detail page', async () => {
      const Component = {
        extends: BaseComponent,
        layout: BaseComponent.layout,
        beforeRouteEnterOrUpdate: BaseComponent.beforeRouteEnterOrUpdate
      }
      const wrapper = mount(Component, {
        localVue,
        store
      })
      let filter = { store_uuid: 2 }
      await wrapper.vm.$store.dispatch('events/getItem', { params: { id: 1 } })
      await wrapper.vm.$store.dispatch('stores/getItem', { params: { id: 1 } })
      await wrapper.vm.$store.dispatch('eventMenuItems/setFilters', filter),
      await wrapper.vm.$store.dispatch('eventMenuItems/getItems')
      await wrapper.vm.$store.dispatch('messages/setFilters', filter)
      await wrapper.vm.$store.dispatch('messages/getItems')
      await wrapper.vm.$store.dispatch('storeStatuses/getItems')
      await wrapper.vm.$store.dispatch('documentStatuses/getItems')
      
      await wrapper.vm.$store.dispatch('page/setLoading', false)
      await wrapper.vm.$nextTick()
      // expect(wrapper.vm.store.name).toContain(FIXTURE_STORE.name)
      // expect(wrapper.vm.event.uuid).toBe(FIXTURE_STORE.uuid)
      expect(wrapper.element).toMatchSnapshot()
    })
  })

  describe('Methods', () => {
    beforeEach(() => {
      const vue = createLocalVue({ validation: true })
      localVue = vue.localVue
      mock = vue.mock
      mock
        .onGet('api/foodfleet/events/1').reply(200, { data: FIXTURE_EVENT })
        .onGet('api/foodfleet/stores/1').reply(200, { data: FIXTURE_STORE })
        .onGet('api/foodfleet/store-summary/2').reply(200, { data: FIXTURE_EVENT_STORE_SUMMARY })
        .onGet('api/foodfleet/store-service-summary/2').reply(200, { data: FIXTURE_STORE_SERVICES })
        .onGet('api/foodfleet/store-statuses').reply(200, { data: FIXTURE_STORE_STATUSES })
        .onGet('api/foodfleet/document-statuses').reply(200, { data: FIXTURE_DOCUMENT_STATUSES })
        .onGet('api/foodfleet/event-menu-items?store_uuid=2').reply(200, { data: FIXTURE_MENUS })
        .onGet('api/foodfleet/messages?store_uuid=2').reply(200, { data: FIXTURE_MESSAGES })
        .onAny().reply(config => {
          console.warn('No mock match for ' + config.url, config)
          return [404, {}]
        })
      store = createStore({}, {
        modules: {
          events: events({}),
          stores: stores({}),
          storeStatuses: storeStatuses({}),
          documentStatuses: documentStatuses({}),
          eventMenuItems: eventMenuItems({}),
          messages: messages({})
        }
      })
    })

    afterEach(() => {
      mock.restore()
    })

    test('messageSave function test', async () => {
      const wrapper = shallowMount(BaseComponent, {
        localVue: localVue,
        store
      })

      await wrapper.vm.messageSave('send message test')

      await wrapper.vm.$nextTick()
      expect(wrapper.vm.messages[2].content).toBe('send message test')
    })
  })
})
