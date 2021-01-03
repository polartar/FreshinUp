import { shallowMount, mount } from '@vue/test-utils'
import Component from './Stores.vue'
import * as Stories from './Stores.stories'
import { FIXTURE_STORE_STATUSES } from 'tests/__data__/storeStatuses'

describe('components/events/Stores', () => {
  // Component instance "under test"
  describe('Snapshots', () => {
    test('Default', () => {
      const wrapper = mount(Stories.Default())
      expect(wrapper.element).toMatchSnapshot()
    })
    test('Populated', () => {
      const wrapper = mount(Stories.Populated())
      expect(wrapper.element).toMatchSnapshot()
    })
  })

  describe('Methods', () => {
    test('filterStores(status)', () => {
      const wrapper = shallowMount(Component)

      wrapper.vm.filterStores(FIXTURE_STORE_STATUSES[0])
      expect(wrapper.emitted()['filter-stores']).toBeTruthy()
      expect(wrapper.emitted()['filter-stores'][0][0]).toEqual(FIXTURE_STORE_STATUSES[0])
    })
  })
})
