import { mount } from '@vue/test-utils'
import createLocalVue from 'vue-cli-plugin-freshinup-ui/utils/testing/createLocalVue'
import { FIXTURE_EVENT } from 'tests/__data__/event'
import { FIXTURE_STORE } from 'tests/__data__/stores'
import { FIXTURE_STORE_STATUSES } from 'tests/__data__/storeStatuses'
import { FIXTURE_DOCUMENT_STATUSES } from 'tests/__data__/documentStatuses'
import { FIXTURE_MENUS } from 'tests/__data__/menus'
import { FIXTURE_MESSAGES } from 'tests/__data__/messages'
import { FIXTURE_EVENT_STORE_SUMMARY } from 'tests/__data__/storeSummary'
import { FIXTURE_STORE_SERVICES } from 'tests/__data__/storeServiceSummary'
import Component from '~/pages/admin/events/_id/stores/_id.vue'
import createStore from 'tests/createStore'

describe('page/admin/events/store', () => {
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
        .onGet('api/foodfleet/event-menu-items').reply(200, { data: FIXTURE_MENUS })
        .onGet('api/foodfleet/messages').reply(200, { data: FIXTURE_MESSAGES })
        .onAny().reply(config => {
          console.warn('No mock match for ' + config.url, config)
          return [404, {}]
        })
      store = createStore()

      afterEach(() => {
        mock.restore()
      })

      it('mount store detail page', async () => {
        const wrapper = mount(Component, {
          localVue,
          store
        })
        await wrapper.vm.$store.dispatch('events/getItem', { params: { id: 2 } })
        await wrapper.vm.$store.dispatch('stores/getItem', { params: { id: 2 } })
        await wrapper.vm.$store.dispatch('stores/summary/getItem', { params: { id: 2 } })
        await wrapper.vm.$store.dispatch('stores/serviceSummary/getItem', { params: { id: 2 } })
        await wrapper.vm.$store.dispatch('eventMenuItems/getItems')
        await wrapper.vm.$store.dispatch('messages/getItems')
        await wrapper.vm.$store.dispatch('storeStatuses/getItems')
        await wrapper.vm.$store.dispatch('documentStatuses/getItems')

        await wrapper.vm.$store.dispatch('page/setLoading', false)
        await wrapper.vm.$nextTick()
        expect(wrapper.vm.store.name).toContain(FIXTURE_STORE.name)
        expect(wrapper.vm.event.uuid).toBe(FIXTURE_EVENT.uuid)
        expect(wrapper.element).toMatchSnapshot()
      })
    })
  })
  describe('Methods', () => {
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
        .onGet('api/foodfleet/event-menu-items').reply(200, { data: FIXTURE_MENUS })
        .onGet('api/foodfleet/messages').reply(200, { data: FIXTURE_MESSAGES })
        .onPost('api/foodfleet/messages').reply(201, FIXTURE_MESSAGES[0])
        .onAny().reply(config => {
          console.warn('No mock match for ' + config.url, config)
          return [404, {}]
        })
      store = createStore()
    })

    afterEach(() => {
      mock.restore()
    })

    test.skip('messageSave function test', async () => {
      const wrapper = mount(Component, {
        localVue: localVue,
        store
      })
      await wrapper.vm.$store.dispatch('events/getItem', { params: { id: 2 } })
      await wrapper.vm.$store.dispatch('stores/getItem', { params: { id: 2 } })

      await wrapper.vm.$store.dispatch('page/setLoading', false)
      await wrapper.vm.$nextTick()

      await wrapper.vm.messageSave('send message test')
      expect(wrapper.vm.messages[0]).toEqual(FIXTURE_MESSAGES[0])
    })
  })
})
