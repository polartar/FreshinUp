import { createLocalVue, shallowMount, mount } from '@vue/test-utils'
import Component from '~/components/stores/StoreList.vue'
import { FIXTURE_STORES } from 'tests/__data__/stores'
import { FIXTURE_STORE_STATUSES } from 'tests/__data__/storeStatuses'

describe('Store List component', () => {
  // Component instance "under test"
  let localVue
  describe('Snapshots', () => {
    test('stores set', () => {
      localVue = createLocalVue()
      const wrapper = mount(Component, {
        localVue: localVue,
        propsData: {
          statuses: FIXTURE_STORE_STATUSES,
          stores: FIXTURE_STORES
        }
      })
      expect(wrapper.element).toMatchSnapshot()
    })
    test('stores empty', () => {
      localVue = createLocalVue()
      const wrapper = mount(Component, {
        localVue: localVue,
        propsData: {
          statuses: FIXTURE_STORE_STATUSES,
          stores: []
        }
      })
      expect(wrapper.element).toMatchSnapshot()
    })
  })
  describe('Methods', () => {
    beforeEach(() => {
      localVue = createLocalVue()
    })

    test('manage function emitted single manage action', () => {
      const wrapper = shallowMount(Component, {
        localVue
      })

      const itemActions = [
        { action: 'edit', text: 'Edit' },
        { action: 'delete', text: 'Delete' },
        { action: 'cancel', text: 'Cancel' },
        { action: 'leave', text: 'Leave Store' }
      ]
      const mockStore = { id: 1, title: 'mock title', status: 1 }

      wrapper.vm.manage(itemActions[0], mockStore)
      wrapper.vm.manage(itemActions[1], mockStore)
      wrapper.vm.manage(itemActions[2], mockStore)
      wrapper.vm.manage(itemActions[3], mockStore)

      expect(wrapper.emitted()['manage-edit']).toBeTruthy()
      expect(wrapper.emitted()['manage-delete']).toBeTruthy()
      expect(wrapper.emitted()['manage-cancel']).toBeTruthy()
      expect(wrapper.emitted()['manage-leave']).toBeTruthy()
    })

    test('manageMultiple function emitted multiple manage action', () => {
      const wrapper = shallowMount(Component, {
        localVue
      })

      wrapper.vm.manageMultiple('delete')

      expect(wrapper.emitted()['manage-multiple-delete']).toBeTruthy()
    })

    test('changeStatus function emitted change-status action', () => {
      const wrapper = shallowMount(Component, {
        localVue
      })

      const mockStore = { id: 1, title: 'mock title', status: 1 }

      wrapper.vm.changeStatus(2, mockStore)

      expect(wrapper.emitted()['change-status']).toBeTruthy()
      expect(wrapper.emitted()['change-status'][0][0]).toEqual(2)
    })

    test('changeStatusMultiple function emitted change-status-multiple action', () => {
      const wrapper = shallowMount(Component, {
        localVue
      })

      wrapper.vm.changeStatusMultiple(3)

      expect(wrapper.emitted()['change-status-multiple']).toBeTruthy()
      expect(wrapper.emitted()['change-status-multiple'][0][0]).toEqual(3)
    })
  })

  describe('Computed', () => {
    beforeEach(() => {
      localVue = createLocalVue()
    })
    test('selectedStoreActions', () => {
      const wrapper = shallowMount(Component)
      wrapper.setData({ selected: [] })
      expect(wrapper.vm.selectedStoreActions).toEqual([])
      wrapper.setData({ selected: [ 1 ] })
      expect(wrapper.vm.selectedStoreActions[0].action).toBe('delete')
      expect(wrapper.vm.selectedStoreActions[0].text).toBe('Delete')
    })
  })
})
