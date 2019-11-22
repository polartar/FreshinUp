import { createLocalVue, shallowMount, mount } from '@vue/test-utils'
import Component from '~/components/events/EventCalendar.vue'
import { FIXTURE_EVENTS } from 'tests/__data__/events'

describe('EventCalendar', () => {
  // Component instance "under test"
  let localVue
  describe('Snapshots', () => {
    test('defaults', () => {
      localVue = createLocalVue()
      const wrapper = mount(Component, {
        localVue: localVue
      })
      expect(wrapper.element).toMatchSnapshot()
    })

    test('with events data', () => {
      localVue = createLocalVue()
      const wrapper = mount(Component, {
        localVue: localVue,
        propsData: {
          events: FIXTURE_EVENTS
        }
      })
      expect(wrapper.element).toMatchSnapshot()
    })
  })

  describe('Methods', () => {
    beforeEach(() => {
      localVue = createLocalVue()
    })

    test('moveDate function to move date', () => {
      const wrapper = shallowMount(Component, {
        propsData: {
          date: '2002-01-10'
        }
      })

      wrapper.vm.$refs.calendar = {
        move: jest.fn()
      }
      wrapper.vm.moveDate({
        year: 2003,
        month: 11
      })

      expect(wrapper.vm.$refs.calendar.move).toHaveBeenCalled()
      expect(wrapper.vm.$refs.calendar.move).toHaveBeenCalledWith(22)

      expect(wrapper.emitted()['change-date']).toBeTruthy()
      expect(wrapper.emitted()['change-date'][0][0].year).toBe(2003)
      expect(wrapper.emitted()['change-date'][0][0].month).toBe(11)
    })

    test('moveToToday function to go back today', () => {
      const wrapper = shallowMount(Component, {
        propsData: {
          date: '2002-01-10'
        }
      })

      expect(wrapper.vm.currentDate).toBe('2002-01-10')

      wrapper.vm.$refs.calendar = {
        times: {
          today: {
            year: 2000,
            month: 12
          }
        },
        move: jest.fn()
      }
      wrapper.vm.moveToToday()

      expect(wrapper.vm.currentYear).toBe(2000)
      expect(wrapper.vm.currentMonth).toBe(12)
    })

    test('clickEvent function to emit', () => {
      const wrapper = shallowMount(Component, {
        propsData: {
          date: '2004-05-01'
        }
      })

      wrapper.vm.clickEvent('event')

      expect(wrapper.emitted()['click-event']).toBeTruthy()
      expect(wrapper.emitted()['click-event'][0][0]).toBe('event')
    })
  })

  describe('Computed', () => {
    beforeEach(() => {
      localVue = createLocalVue()
    })

    test('eventsMap computed for mapping events', () => {
      const wrapper = shallowMount(Component, {
        propsData: {
          events: FIXTURE_EVENTS
        }
      })

      expect(wrapper.vm.eventsMap['2019-10-10'][0].uuid).toEqual('a7936425-485a-4419-9acd-13cdccaed346')
    })

    test('eventsMap computed for multiple days event', () => {
      const wrapper = shallowMount(Component, {
        propsData: {
          events: FIXTURE_EVENTS
        }
      })

      expect(wrapper.vm.eventsMap['2019-10-10'][0].uuid).toEqual('a7936425-485a-4419-9acd-13cdccaed346')
      expect(wrapper.vm.eventsMap['2019-10-11'][0].uuid).toEqual('a7936425-485a-4419-9acd-13cdccaed346')
      expect(wrapper.vm.eventsMap['2019-10-12'][0].uuid).toEqual('a7936425-485a-4419-9acd-13cdccaed346')
    })
  })
})
