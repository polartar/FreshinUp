import { shallowMount, mount } from '@vue/test-utils'
import Component from './MenuItemList'
import * as Stories from './MenuItemList.stories'
import { FIXTURE_MENUS } from '../../../../tests/Javascript/__data__/menus'

describe('components/menu-items/MenuItemList', () => {
  describe('Snapshots', () => {
    test('Empty', async () => {
      const wrapper = mount(Stories.Empty())
      await wrapper.vm.$nextTick()
      expect(wrapper.element).toMatchSnapshot()
    })
    test('IsLoading', async () => {
      const wrapper = mount(Stories.IsLoading())
      await wrapper.vm.$nextTick()
      expect(wrapper.element).toMatchSnapshot()
    })
    test('Populated', async () => {
      const wrapper = mount(Stories.Populated())
      await wrapper.vm.$nextTick()
      expect(wrapper.element).toMatchSnapshot()
    })
  })

  describe('Props & Computed', () => {
    test('isLoading', async () => {
      const wrapper = shallowMount(Component)
      expect(wrapper.vm.isLoading).toBe(false)

      wrapper.setProps({
        isLoading: true
      })
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.isLoading).toBe(true)
    })
    test('items', async () => {
      const wrapper = shallowMount(Component)
      expect(wrapper.vm.items).toHaveLength(0)

      wrapper.setProps({
        items: FIXTURE_MENUS
      })
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.items).toMatchObject(FIXTURE_MENUS)
    })
  })
})
