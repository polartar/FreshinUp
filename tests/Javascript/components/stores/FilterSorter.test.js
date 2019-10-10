import { createLocalVue, shallowMount, mount } from '@vue/test-utils'
import Component from '~/components/stores/FilterSorter.vue'

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
  })

  describe('Methods', () => {
    beforeEach(() => {
      localVue = createLocalVue()
    })

    test('selectLocation function change filters', () => {
      const wrapper = shallowMount(Component)
      wrapper.vm.selectLocation({ uuid: 1 }, () => {})
      expect(wrapper.vm.location_uuid).toBe(1)
      wrapper.vm.selectLocation(null, () => {})
      expect(wrapper.vm.location_uuid).toBe('')
    })

    test('selectTag function change filters', () => {
      const wrapper = shallowMount(Component)
      wrapper.vm.selectTag({ uuid: 1 }, () => {})
      expect(wrapper.vm.tag).toBe(1)
      wrapper.vm.selectTag(null, () => {})
      expect(wrapper.vm.tag).toBe('')
    })

    test('clearFilters function clear filters', () => {
      const wrapper = shallowMount(Component)
      wrapper.vm.clearFilters({})
      expect(wrapper.vm.status).toBeNull()
      expect(wrapper.vm.tag).toBeNull()
      expect(wrapper.vm.location_uuid).toBeNull()
    })

    test('run function emitted runFilter', () => {
      const wrapper = shallowMount(Component)
      wrapper.setData({
        status: 2,
        tag: '2',
        location_uuid: '3'
      })
      wrapper.vm.run({})
      expect(wrapper.emitted().runFilter).toBeTruthy()
      const runParams = wrapper.emitted().runFilter[0][0]
      expect(runParams['status']).toEqual(2)
      expect(runParams['tag']).toEqual('2')
      expect(runParams['location_uuid']).toEqual('3')
    })
  })

  describe('Computed', () => {
    beforeEach(() => {
      localVue = createLocalVue()
    })
    test('filters', () => {
      const wrapper = shallowMount(Component)
      wrapper.setData({
        status: 2,
        tag: '2',
        location_uuid: '3'
      })
      expect(wrapper.vm.filters.status).toBe(2)
      expect(wrapper.vm.filters.tag).toBe('2')
      expect(wrapper.vm.filters.location_uuid).toBe('3')
    })
  })
})
