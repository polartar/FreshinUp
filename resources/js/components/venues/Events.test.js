import { mount, shallowMount } from '@vue/test-utils'
import Component from '~/components/venues/Events.vue'
import * as Stories from '~/components/venues/Events.stories'
import { FIXTURE_EVENT_STATUSES } from '../../../../tests/Javascript/__data__/eventStatuses'
import { FIXTURE_EVENTS } from '../../../../tests/Javascript/__data__/events'

describe('components/venues/Events', () => {
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
    test('runFilter', async () => {
      const wrapper = shallowMount(Component)
      const payload = {
        term: 'abc',
        sortBy: 'created_at'
      }
      wrapper.vm.onFilter(payload)
      await wrapper.vm.$nextTick()
      const emitted = wrapper.emitted()['runFilter']
      expect(emitted).toBeTruthy()
      expect(emitted[0][0]).toMatchObject(payload)
    })
  })
})
