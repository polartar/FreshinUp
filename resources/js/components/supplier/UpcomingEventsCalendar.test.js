import { mount, shallowMount } from '@vue/test-utils'
import * as Stories from './UpcomingEventsCalendar.stories'
import Component from './UpcomingEventsCalendar.vue'
import { FIXTURE_EVENTS } from '../../../../tests/Javascript/__data__/events'
import { FIXTURE_EVENT_STATUSES } from '../../../../tests/Javascript/__data__/eventStatuses'

describe('components/supplier/UpcomingEventsCalendar', () => {
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
    test('colorByStatusId', () => {
      const wrapper = shallowMount(Component)
      expect(wrapper.vm.colorsByStatusId).toMatchObject({})

      wrapper.setProps({
        statuses: FIXTURE_EVENT_STATUSES
      })
      expect(wrapper.vm.colorsByStatusId).toMatchObject({
        1: 'grey',
        2: 'warning',
        3: 'warning',
        4: 'warning',
        5: 'warning',
        6: 'warning',
        7: 'success',
        8: 'error',
        9: 'grey',
      })
    })
  })

  describe('Methods', () => {
    test('viewAll()', () => {
      const wrapper = shallowMount(Component)
      wrapper.vm.viewAll()
      const emitted = wrapper.emitted()['manage-multiple-view']
      expect(emitted).toBeTruthy()
    })
    test('previous()', () => {
      const wrapper = shallowMount(Component)
      const prevMock = jest.fn()
      wrapper.vm.$refs = {
        calendar: {
          prev: prevMock
        }
      }
      wrapper.vm.previous()
      expect(prevMock).toHaveBeenCalled()
    })
    test('next()', () => {
      const wrapper = shallowMount(Component)
      const nextMock = jest.fn()
      wrapper.vm.$refs = {
        calendar: {
          next: nextMock
        }
      }
      wrapper.vm.next()
      expect(nextMock).toHaveBeenCalled()
    })
  })
})
