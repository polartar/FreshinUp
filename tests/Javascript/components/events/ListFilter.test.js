import { createLocalVue, shallowMount, mount } from '@vue/test-utils'
import { FIXTURE_EVENT_STATUSES } from 'tests/__data__/eventStatuses'
import Component from '~/components/events/ListFilter.vue'

const allSelected = FIXTURE_EVENT_STATUSES.map(item => item.id)

describe('ListFilter', () => {
  // Component instance "under test"
  let localVue, mock
  describe('Snapshots', () => {
    test('defaults', () => {
      localVue = createLocalVue()
      const wrapper = mount(Component, {
        localVue: localVue,
        propsData: {
          statuses: FIXTURE_EVENT_STATUSES,
          filters: {
            status: allSelected,
            host_uuid: null,
            manager_uuid: null,
            event_tag_uuid: null,
            start_at: null,
            end_at: null
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

    test('selectHost function change filters', () => {
      const wrapper = mount(Component, {
        localVue: localVue,
        propsData: {
          statuses: FIXTURE_EVENT_STATUSES
        }
      })
      wrapper.vm.selectHost({ uuid: 1 })
      expect(wrapper.vm.filters.host_uuid).toBe(1)
      wrapper.vm.selectHost(null)
      expect(wrapper.vm.filters.host_uuid).toBeNull()
    })

    test('selectManager function change filters', () => {
      const wrapper = mount(Component, {
        localVue: localVue,
        propsData: {
          statuses: FIXTURE_EVENT_STATUSES
        }
      })
      wrapper.vm.selectManager({ uuid: 1 })
      expect(wrapper.vm.filters.manager_uuid).toBe(1)
      wrapper.vm.selectManager(null)
      expect(wrapper.vm.filters.manager_uuid).toBeNull()
    })

    test('selectTag function change filters', () => {
      const wrapper = mount(Component, {
        localVue: localVue,
        propsData: {
          statuses: FIXTURE_EVENT_STATUSES
        }
      })
      wrapper.vm.selectTag({ uuid: 1 })
      expect(wrapper.vm.filters.event_tag_uuid).toBe(1)
      wrapper.vm.selectTag(null)
      expect(wrapper.vm.filters.event_tag_uuid).toBeNull()
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
      expect(wrapper.vm.filters.event_tag_uuid).toBeNull()
      expect(wrapper.vm.filters.start_at).toBeNull()
      expect(wrapper.vm.filters.end_at).toBeNull()
      expect(wrapper.vm.filters.status).toEqual([])
    })

    test('run function emitted runFilter', () => {
      const wrapper = mount(Component, {
        localVue: localVue,
        propsData: {
          statuses: FIXTURE_EVENT_STATUSES,
          filters: {
            status: [ 1, 2 ],
            host_uuid: 1,
            manager_uuid: 2,
            event_tag_uuid: 3,
            start_at: '2020-09-18',
            end_at: '2020-09-20'
          }
        }
      })
      wrapper.vm.run({})
      expect(wrapper.emitted().runFilter).toBeTruthy()
      const runParams = wrapper.emitted().runFilter[0][0]
      expect(runParams['status']).toEqual([ 1, 2 ])
      expect(runParams['host_uuid']).toEqual(1)
      expect(runParams['manager_uuid']).toEqual(2)
      expect(runParams['event_tag_uuid']).toEqual(3)
      expect(runParams['start_at']).toEqual('2020-09-18')
      expect(runParams['end_at']).toEqual('2020-09-20')
    })
  })
})
