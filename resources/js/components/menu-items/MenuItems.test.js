import { mount, shallowMount } from '@vue/test-utils'

import * as Stories from './MenuItems.stories'
import Component from './MenuItems'

describe('components/menu-items/MenuItems', () => {
  describe('Snapshots', () => {
    test('Empty', async () => {
      const wrapper = mount(Stories.Empty())
      await wrapper.vm.$nextTick()
      expect(wrapper.element).toMatchSnapshot()
    })
    test('Populated', async () => {
      const wrapper = mount(Stories.Populated())
      await wrapper.vm.$nextTick()
      expect(wrapper.element).toMatchSnapshot()
    })
    test('Loading', async () => {
      const wrapper = mount(Stories.Loading())
      await wrapper.vm.$nextTick()
      expect(wrapper.element).toMatchSnapshot()
    })
  })
  describe('Props & Computed', () => {
    test('dialog', async () => {
      const wrapper = shallowMount(Component)
      expect(wrapper.vm.dialog).toBe(false)

      wrapper.setProps({
        dialog: true
      })
      expect(wrapper.vm.dialog).toBe(true)
    })
  })
})
