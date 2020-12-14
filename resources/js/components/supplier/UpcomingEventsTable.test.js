import { mount, shallowMount } from '@vue/test-utils'
import * as Stories from './UpcomingEventsTable.stories'
import Component from './UpcomingEventsTable.vue'
import { FIXTURE_EVENTS } from '../../../../tests/Javascript/__data__/events'
import { FIXTURE_EVENT_STATUSES } from '../../../../tests/Javascript/__data__/eventStatuses'

describe('components/supplier/UpcomingEventsTable', () => {
  describe('Snapshots', () => {
    test('Default', async () => {
      const wrapper = mount(Stories.Default())
      await wrapper.vm.$nextTick()
      expect(wrapper.element).toMatchSnapshot()
    })
    test('Loading', async () => {
      const wrapper = mount(Stories.Loading())
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
    test('events', async () => {
      const wrapper = shallowMount(Component)
      expect(wrapper.vm.events).toHaveLength(0)

      wrapper.setProps({
        events: FIXTURE_EVENTS
      })
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.events).toMatchObject(FIXTURE_EVENTS)
    })
    test('eventStatuses', async () => {
      const wrapper = shallowMount(Component)
      expect(wrapper.vm.eventStatuses).toHaveLength(0)

      wrapper.setProps({
        eventStatuses: FIXTURE_EVENT_STATUSES
      })
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.eventStatuses).toMatchObject(FIXTURE_EVENT_STATUSES)
    })
  })

  describe('Methods', () => {
    test('viewAll()', () => {
      const wrapper = shallowMount(Component)
      wrapper.vm.viewAll()
      const emitted = wrapper.emitted()['manage-multiple-view']
      expect(emitted).toBeTruthy()
    })
    test('viewItem(item)', () => {
      const wrapper = shallowMount(Component)
      const item = FIXTURE_EVENTS[0]
      wrapper.vm.viewItem(item)
      const emitted = wrapper.emitted()['manage-view']
      expect(emitted).toBeTruthy()
      expect(emitted[0][0]).toMatchObject(item)
    })
    test('changeStatus(value, item)', () => {
      const wrapper = shallowMount(Component)
      const value = 1
      const item = FIXTURE_EVENT_STATUSES[0]
      wrapper.vm.changeStatus(value, item)
      const emitted = wrapper.emitted()['change-status']
      expect(emitted).toBeTruthy()
      expect(emitted[0][0]).toEqual(value)
      expect(emitted[0][1]).toMatchObject(item)
    })
    describe('manageMultiple(action, items, value)', () => {
      const items = FIXTURE_EVENTS
      test('when action = view', () => {
        const wrapper = shallowMount(Component)
        wrapper.vm.manageMultiple('view', items)
        const emitted = wrapper.emitted()['manage-multiple']
        expect(emitted).toBeTruthy()
        expect(emitted[0][0]).toEqual('view')
        expect(emitted[0][1]).toMatchObject(items)
      })

      test('when action = status', () => {
        const wrapper = shallowMount(Component)
        wrapper.vm.manageMultiple('status', items, 2)
        const emitted = wrapper.emitted()['manage-multiple']
        expect(emitted).toBeTruthy()
        expect(emitted[0][0]).toEqual('status')
        expect(emitted[0][1]).toMatchObject(items)
        expect(emitted[0][2]).toEqual(2)
      })
    })
  })
})
