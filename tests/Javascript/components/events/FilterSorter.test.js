import { createLocalVue, mount } from '@vue/test-utils'
import { FIXTURE_EVENT_STATUSES } from 'tests/__data__/eventStatuses'
import Component from '~/components/events/FilterSorter.vue'

const allSelected = FIXTURE_EVENT_STATUSES.map(item => item.id)
const defaultFilters = {
  status_id: allSelected,
  host_uuid: null,
  manager_uuid: null,
  event_tag_uuid: null,
  start_at: null,
  end_at: null
}

describe('FilterSorter', () => {
  // Component instance "under test"
  let localVue
  describe('Snapshots', () => {
    test('defaults', () => {
      localVue = createLocalVue()
      const wrapper = mount(Component, {
        localVue: localVue
      })
      expect(wrapper.element).toMatchSnapshot()
    })
    test('with props', () => {
      localVue = createLocalVue()
      const wrapper = mount(Component, {
        localVue: localVue,
        propsData: {
          statuses: FIXTURE_EVENT_STATUSES,
          withoutExpansion: true,
          filters: defaultFilters
        }
      })
      expect(wrapper.element).toMatchSnapshot()
    })
    test('without-expansion', () => {
      localVue = createLocalVue()
      const wrapper = mount(Component, {
        localVue: localVue,
        propsData: {
          statuses: FIXTURE_EVENT_STATUSES,
          withoutExpansion: true,
          filters: defaultFilters
        }
      })
      expect(wrapper.element).toMatchSnapshot()
    })
  })

  describe('Methods', () => {
    beforeEach(() => {
      localVue = createLocalVue()
    })

    test('changeDate function change filters', () => {
      const wrapper = mount(Component, {
        localVue: localVue,
        propsData: {
          statuses: FIXTURE_EVENT_STATUSES
        }
      })
      wrapper.setData({
        rangeDate: {
          start: '2019-03-20 20:00:00',
          end: '2019-03-22 20:00:00'
        }
      })
      wrapper.vm.changeDate()
      expect(wrapper.vm.filters.start_at).toBe('2019-03-20 20:00:00')
      expect(wrapper.vm.filters.end_at).toBe('2019-03-22 20:00:00')
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
      expect(wrapper.vm.filters.type_id).toBeNull()
      expect(wrapper.vm.filters.start_at).toBeNull()
      expect(wrapper.vm.filters.end_at).toBeNull()
      expect(wrapper.vm.filters.status_id).toBeNull()
    })

    test('run function emitted runFilter', () => {
      const wrapper = mount(Component, {
        localVue: localVue,
        propsData: {
          statuses: FIXTURE_EVENT_STATUSES,
          filters: {
            status_id: [ 1, 2 ],
            host_uuid: [ { uuid: 1 } ],
            manager_uuid: [ { uuid: 2 } ],
            type_id: [ 2 ],
            start_at: '2020-09-18',
            end_at: '2020-09-20'
          }
        }
      })
      wrapper.vm.run({})
      expect(wrapper.emitted().runFilter).toBeTruthy()
      const runParams = wrapper.emitted().runFilter[0][0]
      expect(runParams['status_id']).toEqual([ 1, 2 ])
      expect(runParams['host_uuid']).toEqual([ 1 ])
      expect(runParams['manager_uuid']).toEqual([ 2 ])
      expect(runParams['type_id']).toEqual([ 2 ])
      expect(runParams['start_at']).toEqual('2020-09-18')
      expect(runParams['end_at']).toEqual('2020-09-20')
    })
  })
})
