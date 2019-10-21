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

    test('moveToToday function go back today', () => {
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
