import { mount } from '@vue/test-utils'
import { createStore } from 'fresh-bus/store'
import createLocalVue from 'vue-cli-plugin-freshinup-ui/utils/testing/createLocalVue'
import { FIXTURE_EVENT } from 'tests/__data__/event'
import { FIXTURE_STORES } from 'tests/__data__/stores'
import { FIXTURE_EVENT_STATUSES } from 'tests/__data__/eventStatuses'
import { FIXTURE_DOCUMENT_STATUSES } from 'tests/__data__/documentStatuses'
import { FIXTURE_DOCUMENTS } from 'tests/__data__/documents'
import { FIXTURE_MESSAGES } from 'tests/__data__/messages'
import { FIXTURE_EVENT_SUMMARY } from 'tests/__data__/eventSummary'
import Component from '~/pages/admin/events/_id/customers/index.vue'
import events from '~/store/modules/events.js'
import eventStatuses from '~/store/modules/eventStatuses.js'
import eventSummary from '~/store/modules/eventSummary.js'
import documents from '~/store/modules/documents.js'
import documentStatuses from '~/store/modules/documentStatuses.js'
import stores from '~/store/modules/stores.js'
import messages from '~/store/modules/messages.js'

describe('Event Customers page', () => {
  let localVue, mock, store
  describe('Mount', () => {
    beforeEach(() => {
      const vue = createLocalVue({ validation: true })
      localVue = vue.localVue
      mock = vue.mock
      mock
        .onGet('api/foodfleet/events/2').reply(200, { data: FIXTURE_EVENT })
        .onGet('api/foodfleet/event-summary/2').reply(200, { data: FIXTURE_EVENT_SUMMARY })
        .onGet('api/foodfleet/event-statuses').reply(200, { data: FIXTURE_EVENT_STATUSES })
        .onGet('api/foodfleet/document-statuses').reply(200, { data: FIXTURE_DOCUMENT_STATUSES })
        .onGet('api/foodfleet/documents').reply(200, { data: FIXTURE_DOCUMENTS })
        .onGet('api/foodfleet/messages').reply(200, { data: FIXTURE_MESSAGES })
        .onGet('api/foodfleet/stores').reply(200, { data: FIXTURE_STORES })
        .onAny().reply(config => {
          console.warn('No mock match for ' + config.url, config)
          return [404, {}]
        })
      store = createStore({}, {
        modules: {
          events: events({}),
          eventStatuses: eventStatuses({}),
          eventSummary: eventSummary({}),
          documents: documents({}),
          documentStatuses: documentStatuses({}),
          stores: stores({}),
          messages: messages({})
        }
      })
    })

    afterEach(() => {
      mock.restore()
    })

    it('mount customer detail page', async () => {
      const wrapper = mount(Component, {
        localVue,
        store
      })
      await wrapper.vm.$store.dispatch('events/getItem', { params: { id: 2 } })
      await wrapper.vm.$store.dispatch('eventSummary/getItem', { params: { id: 2 } })
      await wrapper.vm.$store.dispatch('eventStatuses/getItems')
      await wrapper.vm.$store.dispatch('documentStatuses/getItems')
      await wrapper.vm.$store.dispatch('documents/getItems')
      await wrapper.vm.$store.dispatch('stores/getItems')
      await wrapper.vm.$store.dispatch('messages/getItems')

      await wrapper.vm.$store.dispatch('page/setLoading', false)
      await wrapper.vm.$nextTick()

      expect(wrapper.vm.pageTitle).toContain(FIXTURE_EVENT_SUMMARY.customer.owner)
      expect(wrapper.vm.status).toBe(FIXTURE_EVENT.host_status)
      expect(wrapper.element).toMatchSnapshot()
    })
  })

  describe('Methods', () => {
    beforeEach(() => {
      const vue = createLocalVue({ validation: true })
      localVue = vue.localVue
      mock = vue.mock
      mock
        .onGet('api/foodfleet/events/2').reply(200, { data: FIXTURE_EVENT })
        .onGet('api/foodfleet/event-summary/2').reply(200, { data: FIXTURE_EVENT_SUMMARY })
        .onGet('api/foodfleet/event-statuses').reply(200, { data: FIXTURE_EVENT_STATUSES })
        .onGet('api/foodfleet/document-statuses').reply(200, { data: FIXTURE_DOCUMENT_STATUSES })
        .onGet('api/foodfleet/documents').reply(200, { data: FIXTURE_DOCUMENTS })
        .onGet('api/foodfleet/messages').reply(200, { data: FIXTURE_MESSAGES })
        .onGet('api/foodfleet/stores').reply(200, { data: FIXTURE_STORES })
        .onPost('api/foodfleet/messages').reply(200)
        .onAny().reply(config => {
          console.warn('No mock match for ' + config.url, config)
          return [404, {}]
        })
      store = createStore({}, {
        modules: {
          events: events({}),
          eventStatuses: eventStatuses({}),
          eventSummary: eventSummary({}),
          documents: documents({}),
          documentStatuses: documentStatuses({}),
          stores: stores({}),
          messages: messages({})
        }
      })
    })

    afterEach(() => {
      mock.restore()
    })

    test('postMessage function test', async () => {
      store.dispatch = jest.fn()
      const wrapper = mount(Component, {
        localVue: localVue,
        store
      })
      await wrapper.vm.$store.dispatch('events/getItem', { params: { id: 2 } })
      await wrapper.vm.$store.dispatch('eventSummary/getItem', { params: { id: 2 } })
      await wrapper.vm.$store.dispatch('eventStatuses/getItems')
      await wrapper.vm.$store.dispatch('documentStatuses/getItems')
      await wrapper.vm.$store.dispatch('documents/getItems')
      await wrapper.vm.$store.dispatch('stores/getItems')
      await wrapper.vm.$store.dispatch('messages/getItems')

      await wrapper.vm.$store.dispatch('page/setLoading', false)
      await wrapper.vm.$nextTick()

      await wrapper.vm.postMessage('send message test')
      expect(store.dispatch.mock.calls[8][0]).toEqual('messages/createItem')
      expect(store.dispatch.mock.calls[8][1].data.content).toEqual('send message test')
    })
  })
})
