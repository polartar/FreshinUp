import { shallowMount, mount } from '@vue/test-utils'
import Component from '~/components/fleet-members/StatusSelect.vue'
import * as Stories from '~/components/fleet-members/StatusSelect.stories'
import { FIXTURE_FLEET_MEMBERS_STATUSES } from 'tests/__data__/fleet-members'

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
        options: FIXTURE_FLEET_MEMBERS_STATUSES
      })
      expect(wrapper.vm.options).toEqual(FIXTURE_FLEET_MEMBERS_STATUSES)
    })
    test('items', () => {
      const wrapper = shallowMount(Component)
      expect(wrapper.vm.items).toHaveLength(0)

      wrapper.setProps({
        options: FIXTURE_FLEET_MEMBERS_STATUSES
      })
      const expectations = FIXTURE_FLEET_MEMBERS_STATUSES.map(option => ({
        label: option.name,
        id: option.id,
        color: 'warning'
      }))
      expect(wrapper.vm.items).toEqual(expectations)
    })
  })
})
