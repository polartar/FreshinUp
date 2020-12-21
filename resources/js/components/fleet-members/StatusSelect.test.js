import { shallowMount, mount } from '@vue/test-utils'
import Component from './StatusSelect.vue'
import * as Stories from './StatusSelect.stories'
import { FIXTURE_STORE_STATUSES } from 'tests/__data__/storeStatuses'

describe('components/fleet-members/StatusSelect', () => {
  describe('Visuals', () => {
    test('ReadOnly', async () => {
      const wrapper = mount(Stories.ReadOnly())
      await wrapper.vm.$nextTick()
      expect(wrapper.element).toMatchSnapshot()
    })
    test('Selected', async () => {
      const wrapper = mount(Stories.Selected())
      await wrapper.vm.$nextTick()
      expect(wrapper.element).toMatchSnapshot()
    })
  })

  describe('Props & Computed', () => {
    test('value', () => {
      const wrapper = shallowMount(Component)
      expect(wrapper.vm.value).toEqual(1)

      wrapper.setProps({
        value: 2
      })
      expect(wrapper.vm.value).toEqual(2)
    })
    test('options', () => {
      const wrapper = shallowMount(Component)
      expect(wrapper.vm.options).toHaveLength(0)

      wrapper.setProps({
        options: FIXTURE_STORE_STATUSES
      })
      expect(wrapper.vm.options).toEqual(FIXTURE_STORE_STATUSES)
    })
    test('items', () => {
      const wrapper = shallowMount(Component)
      expect(wrapper.vm.items).toHaveLength(0)

      wrapper.setProps({
        options: FIXTURE_STORE_STATUSES
      })
      const expectations = FIXTURE_STORE_STATUSES.map(option => ({
        label: option.name,
        id: option.id,
        color: option.color
      }))
      expect(wrapper.vm.items).toEqual(expectations)
    })
  })
})
