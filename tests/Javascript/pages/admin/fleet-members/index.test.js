import { shallowMount } from '@vue/test-utils'
import { createStore } from 'fresh-bus/store'
import { createLocalVue } from 'fresh-bus/tests/utils'
import { FIXTURE_STORES_RESPONSE, FIXTURE_STORES_SORTED_BY_FIRSTNAME } from 'tests/__data__/stores'
import Component from '~/pages/admin/fleet-members/index.vue'
import stores from '~/store/modules/stores'
import storeStatuses from '~/store/modules/storeStatuses'

describe('Admin Docs Page', () => {
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
        .onGet('api/foodfleet/stores', { params: { 'page[size]': 10, 'page[number]': 1, sort: 'first_name' } })
        .reply(200, { data: FIXTURE_STORES_SORTED_BY_FIRSTNAME })
        .onGet('api/foodfleet/stores').reply(200, FIXTURE_STORES_RESPONSE)
        .onAny()
        .reply(config => {
          console.warn('No mock match for ' + config.url, config)
          return [404, {}]
        })
      const store = createStore({}, {
        modules: {
          stores: stores({}),
          storeStatuses: storeStatuses({})
        }
      })
      const wrapper = shallowMount(Component, {
        localVue: localVue,
        store
      })
      // Action: change State Machine's state
      await wrapper.vm.$store.dispatch('page/setLoading', false)
      await wrapper.vm.$nextTick()
      await wrapper.vm.$store.dispatch('stores/getItems')
      await wrapper.vm.$nextTick()
      expect(wrapper.element).toMatchSnapshot()
    })
  })

  describe('Methods', () => {
    beforeEach(() => {
      const vue = createLocalVue({ validation: true })
      const docModule = stores({})
      localVue = vue.localVue
      actions = {
        patchItem: jest.fn()
      }
      store = createStore({
        docs: {
          items: FIXTURE_STORES_RESPONSE
        }
      }, {
        modules: {
          stores: { ...docModule, actions: { ...docModule.actions, ...actions } }
        }
      })
    })

    afterEach(() => {
      mock.restore()
    })

    describe('storeViewOrEdit(store)', () => {
      const pushMock = jest.fn()
      const wrapper = shallowMount(Component, {
        localVue,
        store,
        mocks: {
          $router: {
            push: pushMock
          }
        }
      })
      wrapper.vm.storeViewOrEdit({ uuid: 'abc123' })
      expect(pushMock).toHaveBeenCalledWith({ path: `/admin/fleet-members/abc123/edit` })
    })

    test('changeStatus function change doc status', async () => {
      const wrapper = shallowMount(Component, {
        localVue: localVue,
        store
      })

      wrapper.vm.changeStatus(2, { uuid: 'mock uuid' })
      const data = { data: { status: 2 }, params: { id: 'mock uuid' } }
      expect(actions.patchItem).toHaveBeenCalled()
      expect(actions.patchItem.mock.calls).toHaveLength(1)
      expect(actions.patchItem.mock.calls[0][1]).toEqual(data)
    })

    test('changeStatusMultiple function change doc status for each', async () => {
      const wrapper = shallowMount(Component, {
        localVue: localVue,
        store
      })

      wrapper.vm.changeStatusMultiple(3, [{ uuid: 'mock uuid 1' }, { uuid: 'mock uuid 2' }])

      expect(actions.patchItem).toHaveBeenCalled()
      expect(actions.patchItem.mock.calls).toHaveLength(2)

      const firstData = { data: { status: 3 }, params: { id: 'mock uuid 1' } }
      const secondData = { data: { status: 3 }, params: { id: 'mock uuid 2' } }

      expect(actions.patchItem.mock.calls[0][1]).toEqual(firstData)
      expect(actions.patchItem.mock.calls[1][1]).toEqual(secondData)
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

    test('deleteStore function change deleteTemp', () => {
      const wrapper = shallowMount(Component, {
        localVue: localVue,
        store
      })

      wrapper.vm.deleteStore({ id: 1 })
      expect(wrapper.vm.deleteTemp[0].id).toBe(1)
      expect(wrapper.vm.deleteDialog).toBeTruthy()

      wrapper.vm.deleteStore([{ id: 1 }])
      expect(wrapper.vm.deleteTemp[0].id).toBe(1)
      expect(wrapper.vm.deleteDialog).toBeTruthy()
    })
  })
})
