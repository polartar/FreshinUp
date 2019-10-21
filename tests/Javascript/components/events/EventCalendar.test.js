import { createLocalVue, shallowMount, mount } from '@vue/test-utils'
import Component from '~/components/events/EventCalendar.vue'

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
  })

  describe('Methods', () => {
    beforeEach(() => {
      localVue = createLocalVue()
    })

    test('moveDate function to move date', () => {
      const wrapper = shallowMount(Component, {
        propsData: {
          year: 2002,
          month: 1,
          day: 10
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
    })

    test('moveToToday function to go back today', () => {
      const wrapper = shallowMount(Component, {
        propsData: {
          year: 2002,
          month: 1,
          day: 10
        }
      })

      expect(wrapper.vm.currentDate).toBe('2002-1-10')

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
          year: 2004,
          month: 5,
          day: 1
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
          events: [
            {
              name: 'Meeting A',
              start: '2019-12-27',
              end: '2019-12-28',
              status: 'draft'
            }
          ]
        }
      })

      expect(wrapper.vm.eventsMap).toEqual({
        '2019-12-27': [{
          name: 'Meeting A',
          start: '2019-12-27',
          end: '2019-12-28',
          status: 'draft',
          periods: 1
        }]
      })
    })
  })
})
