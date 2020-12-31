import { createLocalVue, shallowMount, mount } from '@vue/test-utils'
import Component from '~/components/events/Stores.vue'
import { FIXTURE_STORES } from 'tests/__data__/stores'
import { STORE_TYPES } from 'tests/__data__/storeTypes'
import { FIXTURE_STORE_STATUSES } from 'tests/__data__/storeStatuses'
import { FIXTURE_EVENT } from '../../../../tests/Javascript/__data__/event'

describe('Store List component', () => {
  // Component instance "under test"
  let localVue
  describe('Snapshots', () => {
    test('', () => {

    })
    test('stores assigned', () => {
      localVue = createLocalVue()
      const wrapper = mount(Component, {
        localVue: localVue,
        propsData: {
          types: STORE_TYPES,
          statuses: FIXTURE_STORE_STATUSES,
          stores: FIXTURE_STORES,
          event: FIXTURE_EVENT
        }
      })
      expect(wrapper.element).toMatchSnapshot()
    })
    test('stores empty', () => {
      localVue = createLocalVue()
      const wrapper = mount(Component, {
        localVue: localVue,
        propsData: {
          types: STORE_TYPES,
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

    test('filter stores', () => {
      const wrapper = shallowMount(Component, {
        localVue
      })

      wrapper.vm.filterStores(FIXTURE_STORE_STATUSES[0])

      expect(wrapper.emitted()['filter-stores']).toBeTruthy()

      expect(wrapper.emitted()['filter-stores'][0][0]).toEqual(FIXTURE_STORE_STATUSES[0])
    })

    test('view details', () => {
      const wrapper = shallowMount(Component, {
        localVue
      })

      wrapper.vm.viewDetails(FIXTURE_STORE_STATUSES[0])

      expect(wrapper.emitted()['manage-view-details']).toBeTruthy()

      expect(wrapper.emitted()['manage-view-details'][0][0]).toEqual(FIXTURE_STORE_STATUSES[0])
    })

    test('unassign', () => {
      const wrapper = shallowMount(Component, {
        localVue
      })

      wrapper.vm.unassign(FIXTURE_STORE_STATUSES[1])

      expect(wrapper.emitted()['manage-unassign']).toBeTruthy()

      expect(wrapper.emitted()['manage-unassign'][0][0]).toEqual(FIXTURE_STORE_STATUSES[1])
    })

    test('manage multiple unassign', () => {
      const wrapper = shallowMount(Component, {
        localVue
      })

      wrapper.vm.multipleUnassign(FIXTURE_STORE_STATUSES[2])

      expect(wrapper.emitted()['manage-multiple-unassign']).toBeTruthy()

      expect(wrapper.emitted()['manage-multiple-unassign'][0][0]).toEqual(FIXTURE_STORE_STATUSES[2])
    })
  })
})
