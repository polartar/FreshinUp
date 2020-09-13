import { mount } from '@vue/test-utils'
import Component from './StoreAreaList.vue'
import * as Stories from './StoreAreaList.stories'
import { FIXTURE_STORE_AREAS } from 'tests/__data__/storeAreas'

describe('components/fleet-members/StoreAreaList', () => {
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
    test('isLoading', async () => {
      const wrapper = mount(Component)
      expect(wrapper.vm.isLoading).toBe(false)

      wrapper.setProps({
        isLoading: true
      })
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.isLoading).toBe(true)
    })
    test('items', async () => {
      const wrapper = mount(Component)
      expect(wrapper.vm.items).toHaveLength(0)

      wrapper.setProps({
        items: FIXTURE_STORE_AREAS
      })
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.items).toMatchObject(FIXTURE_STORE_AREAS)
    })
  })
})
