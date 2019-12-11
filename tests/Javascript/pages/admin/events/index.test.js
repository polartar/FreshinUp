import { shallowMount } from '@vue/test-utils'
import { createStore } from 'fresh-bus/store'
import createLocalVue from 'vue-cli-plugin-freshinup-ui/utils/testing/createLocalVue'
import { FIXTURE_EVENTS } from 'tests/__data__/events'
import Component from '~/pages/admin/events/index.vue'
import events from '~/store/modules/events'
import eventStatuses from '~/store/modules/eventStatuses'

describe('Admin Events Page', () => {
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
        .onGet('api/foodfleet/events').reply(200, FIXTURE_EVENTS)
        .onAny()
        .reply(config => {
          console.warn('No mock match for ' + config.url, config)
          return [404, {}]
        })
      const store = createStore({}, {
        modules: {
          events: events({}),
          eventStatuses: eventStatuses({})
        }
      })
      const wrapper = shallowMount(Component, {
        localVue: localVue,
        store
      })
      // Action: change State Machine's state
      await wrapper.vm.$store.dispatch('page/setLoading', false)
      await wrapper.vm.$nextTick()
      await wrapper.vm.$store.dispatch('events/getItems')
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
        patchItem: jest.fn(),
        deleteItem: jest.fn(),
        getItems: jest.fn(),
        setFilters: jest.fn()
      }
      store = createStore({
        events: {
          items: FIXTURE_EVENTS
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

    test('changeStatusSingle function change event status', async () => {
      const wrapper = shallowMount(Component, {
        localVue: localVue,
        store
      })

      wrapper.vm.changeStatusSingle(2, { uuid: 'mock uuid' })
      const data = { data: { status_id: 2 }, params: { id: 'mock uuid' } }
      expect(actions.patchItem).toHaveBeenCalled()
      expect(actions.patchItem.mock.calls).toHaveLength(1)
      expect(actions.patchItem.mock.calls[0][1]).toEqual(data)
    })

    test('changeStatusMultiple function change event status for each', async () => {
      const wrapper = shallowMount(Component, {
        localVue: localVue,
        store
      })

      wrapper.vm.changeStatusMultiple(3, [{ uuid: 'mock uuid 1' }, { uuid: 'mock uuid 2' }])

      expect(actions.patchItem).toHaveBeenCalled()
      expect(actions.patchItem.mock.calls).toHaveLength(2)

      const firstData = { data: { status_id: 3 }, params: { id: 'mock uuid 1' } }
      const secondData = { data: { status_id: 3 }, params: { id: 'mock uuid 2' } }

      expect(actions.patchItem.mock.calls[0][1]).toEqual(firstData)
      expect(actions.patchItem.mock.calls[1][1]).toEqual(secondData)
    })

    test('onSubmitDelete function delete events in deleteTemp ', async () => {
      const wrapper = shallowMount(Component, {
        localVue: localVue,
        store
      })

      wrapper.setData({ deleteTemp: [{ uuid: 'mock uuid 1' }, { uuid: 'mock uuid 2' }] })
      wrapper.vm.onSubmitDelete()

      expect(actions.deleteItem).toHaveBeenCalled()
      expect(actions.deleteItem.mock.calls).toHaveLength(2)

      const firstData = { getItems: false, params: { id: 'mock uuid 1' } }
      const secondData = { getItems: false, params: { id: 'mock uuid 2' } }

      expect(actions.deleteItem.mock.calls[0][1]).toEqual(firstData)
      expect(actions.deleteItem.mock.calls[1][1]).toEqual(secondData)
    })

    test('filterEvents function filter events with filterParams ', async () => {
      const $route = {
        query: {}
      }
      const wrapper = shallowMount(Component, {
        localVue: localVue,
        store,
        mocks: {
          $route
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
        store
      })

      wrapper.vm.onPaginate({
        rowsPerPage: 2,
        totalItems: 5,
        page: 2
      })
      expect(wrapper.vm.pagination.rowsPerPage).toBe(2)
    })

    test('deleteSingle function change deleteTemp', () => {
      const wrapper = shallowMount(Component, {
        localVue: localVue,
        store
      })

      wrapper.vm.deleteSingle({ id: 1 })
      expect(wrapper.vm.deleteTemp[0].id).toBe(1)
      expect(wrapper.vm.deleteDialog).toBeTruthy()
    })

    test('multipleDelete function change deleteTemp', () => {
      const wrapper = shallowMount(Component, {
        localVue: localVue,
        store
      })

      wrapper.vm.multipleDelete([{ id: 1 }])
      expect(wrapper.vm.deleteTemp[0].id).toBe(1)
      expect(wrapper.vm.deleteDialog).toBeTruthy()
    })

    test('onCancelDelete function change deleteTemp', () => {
      const wrapper = shallowMount(Component, {
        localVue: localVue,
        store
      })

      wrapper.vm.onCancelDelete()
      expect(wrapper.vm.deleteTemp).toHaveLength(0)
    })
  })
})
