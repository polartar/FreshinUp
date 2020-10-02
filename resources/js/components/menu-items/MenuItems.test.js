import { mount } from '@vue/test-utils'

import * as Stories from './MenuItems.stories'

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
})
