import { mount } from '@vue/test-utils'
import Component from './LocationList.vue'
import * as Stories from './LocationList.stories'
import { FIXTURE_LOCATIONS } from '../../../../tests/Javascript/__data__/locations'

describe('components/locations/LocationList', () => {
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
    test('options', async () => {
      const wrapper = mount(Component)
      expect(wrapper.vm.options).toHaveLength(0)

      wrapper.setProps({
        items: FIXTURE_LOCATIONS
      })
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.options).toMatchObject(FIXTURE_LOCATIONS.map(location => ({
        ...location,
        eventNames: (location.events || []).map(event => event.name).join(', ')
      })))
    })
  })
})
