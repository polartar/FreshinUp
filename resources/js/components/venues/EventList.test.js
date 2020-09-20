import { mount, shallowMount } from '@vue/test-utils'
import Component from '~/components/venues/EventList.vue'
import * as Stories from '~/components/venues/EventList.stories'
import { FIXTURE_EVENTS } from '../../../../tests/Javascript/__data__/events'
import { FIXTURE_EVENT_STATUSES } from '../../../../tests/Javascript/__data__/eventStatuses'

describe('components/venues/EventList', () => {
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
      const wrapper = shallowMount(Component)
      expect(wrapper.vm.items).toHaveLength(0)
      wrapper.setProps({
        items: FIXTURE_EVENTS
      })
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.items).toMatchObject(FIXTURE_EVENTS)
    })
    test('isLoading', async () => {
      const wrapper = shallowMount(Component)
      expect(wrapper.vm.isLoading).toBe(false)

      wrapper.setProps({
        isLoading: true
      })
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.isLoading).toBe(true)
    })
    test('statuses', async () => {
      const wrapper = shallowMount(Component)
      expect(wrapper.vm.statuses).toHaveLength(0)
      wrapper.setProps({
        statuses: FIXTURE_EVENT_STATUSES
      })
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.statuses).toMatchObject(FIXTURE_EVENT_STATUSES)
    })
  })

  describe('Methods', () => {
    test('changeStatus', async () => {
      const wrapper = shallowMount(Component)
      const value = 2
      const event = FIXTURE_EVENTS[0]
      wrapper.vm.changeStatus(value, event)
      await wrapper.vm.$nextTick()
      const emitted = wrapper.emitted()['change-status']
      expect(emitted).toBeTruthy()
      expect(emitted[0][0]).toEqual(value)
      expect(emitted[0][1]).toMatchObject(event)
    })
  })
})
