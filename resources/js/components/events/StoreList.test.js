import { shallowMount, mount } from '@vue/test-utils'
import createLocalVue from 'vue-cli-plugin-freshinup-ui/utils/testing/createLocalVue'
import Component from '~/components/events/StoreList.vue'
import { FIXTURE_ASSIGNED_STORES } from 'tests/__data__/assignedStores'
import { FIXTURE_STORE_STATUSES } from 'tests/__data__/storeStatuses'

describe('Store List component', () => {
  // Component instance "under test"
  let localVue
  describe('Snapshots', () => {
    beforeEach(() => {
      localVue = createLocalVue().vue
    })
    test('stores assigned', () => {
      const wrapper = mount(Component, {
        localVue,
        propsData: {
          statuses: FIXTURE_STORE_STATUSES,
          stores: FIXTURE_ASSIGNED_STORES
        }
      })
      expect(wrapper.element).toMatchSnapshot()
    })
    test('stores empty', () => {
      const wrapper = mount(Component, {
        localVue,
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
      localVue = createLocalVue().vue
    })

    test('manage function emitted single action', () => {
      const wrapper = shallowMount(Component, {
        localVue
      })

      const itemActions = [
        { action: 'view-details', text: 'View details' },
        { action: 'unassign', text: 'Unassign' }
      ]

      wrapper.vm.manage(itemActions[0], FIXTURE_ASSIGNED_STORES[0])
      wrapper.vm.manage(itemActions[1], FIXTURE_ASSIGNED_STORES[1])

      expect(wrapper.emitted()['manage-view-details']).toBeTruthy()
      expect(wrapper.emitted()['manage-unassign']).toBeTruthy()

      expect(wrapper.emitted()['manage-view-details'][0][0]).toEqual(FIXTURE_ASSIGNED_STORES[0])
      expect(wrapper.emitted()['manage-unassign'][0][0]).toEqual(FIXTURE_ASSIGNED_STORES[1])
    })

    test('manageMultiple function emitted multiple manage action', () => {
      const wrapper = shallowMount(Component, {
        localVue
      })

      wrapper.vm.manageMultiple('unassign')

      expect(wrapper.emitted()['manage-multiple-unassign']).toBeTruthy()
    })
  })

  describe('Computed', () => {
    beforeEach(() => {
      localVue = createLocalVue().vue
    })
    test('selectedActions', () => {
      const wrapper = shallowMount(Component)
      wrapper.setData({ selected: [] })
      expect(wrapper.vm.selectedActions).toEqual([])
      wrapper.setData({ selected: [ 1 ] })
      expect(wrapper.vm.selectedActions[0].action).toBe('unassign')
      expect(wrapper.vm.selectedActions[0].text).toBe('Unassign')
    })
  })
})
