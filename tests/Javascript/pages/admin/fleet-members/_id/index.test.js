import { shallowMount } from '@vue/test-utils'
import { createStore } from 'fresh-bus/store'
import { createLocalVue } from 'fresh-bus/tests/utils'
import { FIXTURE_STORE } from 'tests/__data__/stores'
import { FIXTURE_STORE_STATUSES } from 'tests/__data__/storeStatuses'
import { FIXTURE_EVENTS } from 'tests/__data__/events'
import { FIXTURE_EVENT_STATUSES } from 'tests/__data__/eventStatuses'
import Component from '~/pages/admin/fleet-members/_id/index.vue'
import events from '~/store/modules/events'
import eventStatuses from '~/store/modules/eventStatuses'
import stores from '~/store/modules/stores'
import storeStatuses from '~/store/modules/storeStatuses'

describe('Fleet Members Page', () => {
  let localVue, mock, store, actions
  describe('Mount', () => {
    beforeEach(() => {
      const vue = createLocalVue({ validation: true })
      localVue = vue.localVue
      mock = vue.mock
    })

    afterEach(() => {
      mock.restore()
    })

    test('snapshot', async () => {
      const vue = createLocalVue({ validation: true })
      localVue = vue.localVue
      mock = vue.mock
        .onGet('api/foodfleet/stores/' + FIXTURE_STORE.uuid).reply(200, { data: FIXTURE_STORE })
        .onGet('api/foodfleet/event-statuses').reply(200, { data: FIXTURE_EVENT_STATUSES })
        .onGet('api/foodfleet/store-statuses').reply(200, { data: FIXTURE_STORE_STATUSES })
        .onGet('api/foodfleet/events').reply(200, { data: FIXTURE_EVENTS })
        .onAny()
        .reply(config => {
          console.warn('No mock match for ' + config.url, config)
          return [404, {}]
        })
      const store = createStore({}, {
        modules: {
          events: events({}),
          eventStatuses: eventStatuses({}),
          stores: stores({}),
          storeStatuses: storeStatuses({})
        }
      })
      const wrapper = shallowMount(Component, {
        localVue: localVue,
        store
      })
      // Action: change State Machine's state
      await wrapper.vm.$store.dispatch('page/setLoading', true)
      await wrapper.vm.$nextTick()
      await wrapper.vm.$store.dispatch('stores/getItem', { params: { id: FIXTURE_STORE.uuid } })
      await wrapper.vm.$nextTick()
      await wrapper.vm.$store.dispatch('events/getItems')
      await wrapper.vm.$store.dispatch('storeStatuses/getItems')
      await wrapper.vm.$store.dispatch('eventStatuses/getItems')
      await wrapper.vm.$nextTick()
      await wrapper.vm.$store.dispatch('page/setLoading', false)
      await wrapper.vm.$nextTick()
      expect(wrapper.element).toMatchSnapshot()
    })
  })

  describe('Methods', () => {
    beforeEach(() => {
      const vue = createLocalVue({ validation: true })
      const eventModule = events({})
      localVue = vue.localVue
      actions = {
        getItems: jest.fn(),
        setFilters: jest.fn()
      }
      store = createStore({
        stores: {
          item: FIXTURE_STORE
        },
        storeStatuses: {
          items: FIXTURE_STORE_STATUSES
        },
        events: {
          items: FIXTURE_EVENTS
        },
        eventStatuses: {
          items: FIXTURE_EVENT_STATUSES
        }
      }, {
        modules: {
          events: { ...eventModule, actions: { ...eventModule.actions, ...actions } }
        }
      })
    })

    afterEach(() => {
      mock.restore()
    })

    test('filterEvents function filter events with filterParams ', async () => {
      const wrapper = shallowMount(Component, {
        localVue: localVue,
        store,
        mocks: {
          $route: {
            params: {
              id: 1
            }
          }
        }
      })

      const params = { name: 'mock filter name' }
      wrapper.vm.filterEvents(params)
      expect(actions.setFilters).toHaveBeenCalled()
      expect(actions.setFilters.mock.calls).toHaveLength(1)
      expect(actions.setFilters.mock.calls[0][1]).toEqual(params)

      expect(actions.getItems).toHaveBeenCalled()
      expect(actions.getItems.mock.calls).toHaveLength(1)
    })

    test('onPaginate function change paginate', () => {
      const wrapper = shallowMount(Component, {
        localVue: localVue,
        store,
        mocks: {
          $route: {
            params: {
              id: 1
            }
          }
        }
      })

      wrapper.vm.onPaginate({
        rowsPerPage: 2,
        totalItems: 5,
        page: 2
      })
      expect(wrapper.vm.pagination.rowsPerPage).toBe(2)
    })
  })
})
