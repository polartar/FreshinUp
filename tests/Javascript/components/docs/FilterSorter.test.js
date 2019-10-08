import { createLocalVue, shallowMount, mount } from '@vue/test-utils'
import { FIXTURE_DOCUMENT_TYPES } from 'tests/__data__/documentTypes'
import { FIXTURE_DOCUMENT_STATUSES } from 'tests/__data__/documentStatuses'
import Component from '~/components/docs/FilterSorter.vue'

describe('FilterSorter', () => {
  // Component instance "under test"
  let localVue
  describe('Snapshots', () => {
    test('defaults', () => {
      localVue = createLocalVue()
      const wrapper = mount(Component, {
        localVue: localVue,
        propsData: {
          types: FIXTURE_DOCUMENT_TYPES,
          statuses: FIXTURE_DOCUMENT_STATUSES
        }
      })
      expect(wrapper.element).toMatchSnapshot()
    })
  })

  describe('Methods', () => {
    beforeEach(() => {
      localVue = createLocalVue()
    })

    test('selectAssigned function change filters', () => {
      const wrapper = shallowMount(Component)
      wrapper.vm.selectAssigned(1, () => {})
      expect(wrapper.vm.assigned_uuid).toBe(1)
      wrapper.vm.selectAssigned('', () => {})
      expect(wrapper.vm.assigned_uuid).toBe('')
    })

    test('clearFilters function clear filters', () => {
      const wrapper = shallowMount(Component)
      wrapper.vm.clearFilters({})
      expect(wrapper.vm.type).toBeNull()
      expect(wrapper.vm.status).toBeNull()
      expect(wrapper.vm.assigned_uuid).toBeNull()
    })

    test('run function emitted runFilter', () => {
      const wrapper = shallowMount(Component)
      wrapper.setData({
        type: 1,
        status: 2,
        assigned_uuid: '3',
        expireDate: {
          start: '2020-09-18',
          end: '2020-09-30'
        }
      })
      wrapper.vm.run({})
      expect(wrapper.emitted().runFilter).toBeTruthy()
      const runParams = wrapper.emitted().runFilter[0][0]
      expect(runParams['type']).toEqual(1)
      expect(runParams['status']).toEqual(2)
      expect(runParams['assigned_uuid']).toEqual('3')
      expect(runParams['expiration_from']).toEqual('2020-09-18')
      expect(runParams['expiration_to']).toEqual('2020-09-30')
    })
  })

  describe('Computed', () => {
    beforeEach(() => {
      localVue = createLocalVue()
    })
    test('filters', () => {
      const wrapper = shallowMount(Component)
      wrapper.setData({
        type: 1,
        status: 2,
        assigned_uuid: '3',
        expireDate: {
          start: '2020-09-18',
          end: '2020-09-30'
        }
      })
      expect(wrapper.vm.filters.type).toBe(1)
      expect(wrapper.vm.filters.status).toBe(2)
      expect(wrapper.vm.filters.assigned_uuid).toBe('3')
      expect(wrapper.vm.filters.expiration_from).toBe('2020-09-18')
      expect(wrapper.vm.filters.expiration_to).toBe('2020-09-30')
    })
  })
})
