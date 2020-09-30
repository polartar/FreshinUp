import { mount, shallowMount } from '@vue/test-utils'
import Component from './Locations.vue'
import * as Stories from './Locations.stories'
import { FIXTURE_LOCATIONS } from '../../../../tests/Javascript/__data__/locations'
import { FIXTURE_LOCATION_CATEGORIES } from '../../../../tests/Javascript/__data__/locationCategories'

describe('components/locations/Locations', () => {
  describe('Snapshots', () => {
    test('Empty', () => {
      const wrapper = mount(Stories.Empty())
      expect(wrapper.element).toMatchSnapshot()
    })
    test('Loading', () => {
      const wrapper = mount(Stories.Loading())
      expect(wrapper.element).toMatchSnapshot()
    })
    test('Populated', () => {
      const wrapper = mount(Stories.Populated())
      expect(wrapper.element).toMatchSnapshot()
    })
  })

  describe('Props & Computed', () => {
    test('items', async () => {
      const wrapper = mount(Component)
      expect(wrapper.vm.items).toHaveLength(0)
      wrapper.setProps({
        items: FIXTURE_LOCATIONS
      })
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.items).toMatchObject(FIXTURE_LOCATIONS)
    })
    test('categories', async () => {
      const wrapper = mount(Component)
      expect(wrapper.vm.categories).toHaveLength(0)
      wrapper.setProps({
        categories: FIXTURE_LOCATION_CATEGORIES
      })
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.categories).toMatchObject(FIXTURE_LOCATION_CATEGORIES)
    })
    test('isLoading', async () => {
      const wrapper = mount(Component)
      expect(wrapper.vm.isLoading).toBe(false)
      wrapper.setProps({
        isLoading: true
      })
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.isLoading).toBe(true)
    })
  })

  describe('Methods', () => {
    test('onSubmit(payload)', async () => {
      const wrapper = shallowMount(Component)
      const location = FIXTURE_LOCATIONS[0]
      wrapper.vm.onSubmit(location)
      await wrapper.vm.$nextTick()
      const emitted = wrapper.emitted()['new-location']
      expect(emitted).toBeTruthy()
      expect(emitted[0][0]).toMatchObject(location)
      expect(wrapper.vm.location).toMatchObject(Object.assign({}, wrapper.vm.location, location))
    })
  })
})
