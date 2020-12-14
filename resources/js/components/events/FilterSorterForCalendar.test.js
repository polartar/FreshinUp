import { createLocalVue, mount } from '@vue/test-utils'
import { FIXTURE_EVENT_STATUSES } from 'tests/__data__/eventStatuses'
import Component from '~/components/events/FilterSorterForCalendar.vue'

const allSelected = FIXTURE_EVENT_STATUSES.map(item => item.id)

describe('FilterSorterForCalendar', () => {
  // Component instance "under test"
  let localVue
  describe('Snapshots', () => {
    test('defaults', () => {
      localVue = createLocalVue()
      const wrapper = mount(Component, {
        localVue: localVue,
        propsData: {
          statuses: FIXTURE_EVENT_STATUSES,
          filters: {
            name: '',
            status_id: allSelected,
            host_uuid: null,
            manager_uuid: null
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
          statuses: FIXTURE_EVENT_STATUSES
        }
      })
      wrapper.vm.clearFilters({})
      expect(wrapper.vm.filters.host_uuid).toBeNull()
      expect(wrapper.vm.filters.manager_uuid).toBeNull()
      expect(wrapper.vm.filters.status_id).toBeNull()
      expect(wrapper.vm.filters.name).toEqual('')
    })

    test('run function emitted runFilter', () => {
      const wrapper = mount(Component, {
        localVue: localVue,
        propsData: {
          statuses: FIXTURE_EVENT_STATUSES,
          filters: {
            name: 'event names',
            status_id: [ 1, 2 ],
            host_uuid: [ { uuid: 1 } ],
            manager_uuid: [ { uuid: 2 } ]
          }
        }
      })
      wrapper.vm.run({})
      expect(wrapper.emitted().runFilter).toBeTruthy()
      const runParams = wrapper.emitted().runFilter[0][0]
      expect(runParams['name']).toEqual('event names')
      expect(runParams['status_id']).toEqual([ 1, 2 ])
      expect(runParams['host_uuid']).toEqual([ 1 ])
      expect(runParams['manager_uuid']).toEqual([ 2 ])
    })
  })
})
