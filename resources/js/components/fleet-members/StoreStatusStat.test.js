import { mount } from '@vue/test-utils'
import Component from './StoreStatusStat.vue'
import * as Stories from './StoreStatusStat.stories'
import { FIXTURE_STORE_STATUS_STATS } from '../../../../tests/Javascript/__data__/storeStatusStats'

const item = FIXTURE_STORE_STATUS_STATS[0]
describe('components/fleet-members/StoreStatusStat', () => {
  describe('Snapshots', () => {
    test('Default', () => {
      const wrapper = mount(Stories.Default())
      expect(wrapper.element).toMatchSnapshot()
    })
    test('Populated', () => {
      const wrapper = mount(Stories.Populated())
      expect(wrapper.element).toMatchSnapshot()
    })
  })

  describe('Props & Computed', () => {
    test('label', async () => {
      const wrapper = mount(Component)
      expect(wrapper.vm.label).toEqual('')

      wrapper.setProps({
        label: item.label
      })
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.label).toBe(item.label)
    })
    test('color', async () => {
      const wrapper = mount(Component)
      expect(wrapper.vm.color).toEqual('grey')

      wrapper.setProps({
        color: item.color
      })
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.color).toBe(item.color)
    })
    test('value', async () => {
      const wrapper = mount(Component)
      expect(wrapper.vm.value).toEqual(0)

      wrapper.setProps({
        value: item.value
      })
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.value).toBe(item.value)
    })
  })
})
