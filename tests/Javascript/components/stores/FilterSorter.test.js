import { createLocalVue, mount } from '@vue/test-utils'
import { FIXTURE_STORE_STATUSES } from 'tests/__data__/storeStatuses'
import Component from '~/components/stores/FilterSorter.vue'

const allSelected = FIXTURE_STORE_STATUSES.map(item => item.id)

describe('FilterSorter', () => {
  // Component instance "under test"
  let localVue
  describe('Snapshots', () => {
    test('defaults', () => {
      localVue = createLocalVue()
      const wrapper = mount(Component, {
        localVue: localVue,
        propsData: {
          filters: {
            status_id: allSelected,
            tag: null,
            location_uuid: null,
            supplier_uuid: null
          }
        }
      })
      expect(wrapper.element).toMatchSnapshot()
    })
  })

  describe('Methods', () => {
    beforeEach(() => {
      localVue = createLocalVue()
    })

    test('clearFilters function clear filters', () => {
      const wrapper = mount(Component, {
        localVue: localVue,
        propsData: {
          statuses: FIXTURE_STORE_STATUSES
        }
      })
      wrapper.vm.clearFilters({})
      expect(wrapper.vm.filters.status_id).toBeNull()
      expect(wrapper.vm.filters.tag).toBeNull()
      expect(wrapper.vm.filters.location_uuid).toBeNull()
      expect(wrapper.vm.filters.supplier_uuid).toBeNull()
    })

    test('run function emitted runFilter', () => {
      const wrapper = mount(Component, {
        localVue: localVue,
        propsData: {
          statuses: FIXTURE_STORE_STATUSES,
          filters: {
            status_id: [ 1, 2 ],
            tag: [ { uuid: 1 } ],
            location_uuid: 2,
            supplier_uuid: [ { uuid: 3 } ]
          }
        }
      })
      wrapper.vm.run({})
      expect(wrapper.emitted().runFilter).toBeTruthy()
      const runParams = wrapper.emitted().runFilter[0][0]
      expect(runParams['status_id']).toEqual([ 1, 2 ])
      expect(runParams['tag']).toEqual([ 1 ])
      expect(runParams['location_uuid']).toEqual(2)
      expect(runParams['supplier_uuid']).toEqual([ 3 ])
    })

    test('selectLocation function change filters.location_uuid', () => {
      const wrapper = mount(Component, {
        localVue: localVue,
        propsData: {
          statuses: FIXTURE_STORE_STATUSES
        }
      })
      wrapper.vm.selectLocation({ uuid: 1 })
      expect(wrapper.vm.filters.location_uuid).toEqual(1)
      wrapper.vm.selectLocation()
      expect(wrapper.vm.filters.location_uuid).toBeNull()
    })
  })
})
