import { shallowMount } from '@vue/test-utils'
import { createStore } from 'fresh-bus/store'
import { createLocalVue } from 'fresh-bus/tests/utils'
import { FIXTURE_DOCUMENTS_RESPONSE, FIXTURE_DOCUMENTS_SORTED_BY_FIRSTNAME } from 'tests/__data__/documents'
import Component from '~/pages/admin/docs/index.vue'
import documents from '~/store/modules/documents'
import documentStatuses from '~/store/modules/documentStatuses'
import documentTypes from '~/store/modules/documentTypes'

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
        .onGet('api/foodfleet/documents', { params: { 'page[size]': 10, 'page[number]': 1, sort: 'first_name' } })
        .reply(200, { data: FIXTURE_DOCUMENTS_SORTED_BY_FIRSTNAME })
        .onGet('api/foodfleet/documents').reply(200, FIXTURE_DOCUMENTS_RESPONSE)
        .onAny()
        .reply(config => {
          console.warn('No mock match for ' + config.url, config)
          return [404, {}]
        })
      const store = createStore({}, {
        modules: {
          documents: documents({}),
          documentStatuses: documentStatuses({}),
          documentTypes: documentTypes({})
        }
      })
      const wrapper = shallowMount(Component, {
        localVue: localVue,
        store
      })
      // Action: change State Machine's state
      await wrapper.vm.$store.dispatch('page/setLoading', false)
      await wrapper.vm.$nextTick()
      await wrapper.vm.$store.dispatch('documents/getItems')
      await wrapper.vm.$nextTick()
      expect(wrapper.element).toMatchSnapshot()
    })
  })

  describe('Methods', () => {
    beforeEach(() => {
      const vue = createLocalVue({ validation: true })
      const docModule = documents({})
      localVue = vue.localVue
      actions = {
        patchItem: jest.fn()
      }
      store = createStore({
        docs: {
          items: FIXTURE_DOCUMENTS_RESPONSE
        }
      }, {
        modules: {
          documents: { ...docModule, actions: { ...docModule.actions, ...actions } }
        }
      })
    })

    afterEach(() => {
      mock.restore()
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

    test('deleteDoc function change deleteTemp', () => {
      const wrapper = shallowMount(Component, {
        localVue: localVue,
        store
      })

      wrapper.vm.deleteDoc({ id: 1 })
      expect(wrapper.vm.deleteTemp[0].id).toBe(1)
      expect(wrapper.vm.deleteDialog).toBeTruthy()

      wrapper.vm.deleteDoc([{ id: 1 }])
      expect(wrapper.vm.deleteTemp[0].id).toBe(1)
      expect(wrapper.vm.deleteDialog).toBeTruthy()
    })
  })
})
