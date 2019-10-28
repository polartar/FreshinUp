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

    test('with events data', () => {
      localVue = createLocalVue()
      const wrapper = mount(Component, {
        localVue: localVue,
        propsData: {
          events: [{
            name: 'Meeting A',
            start: '2019-12-27',
            end: '2019-12-27',
            status: 'draft'
          }, {
            name: 'Meeting B',
            start: '2019-12-23',
            end: '2019-12-24',
            status: 'pending'
          }, {
            name: 'Meeting C',
            start: '2019-12-24',
            end: '2019-12-24',
            status: 'confirmed'
          }, {
            name: 'Meeting D',
            start: '2019-12-12',
            end: '2019-12-15',
            status: 'past'
          }, {
            name: 'Meeting E',
            start: '2019-12-23',
            end: '2019-12-23',
            status: 'cancelled'
          }]
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
    })

    test('moveToToday function to go back today', () => {
      const wrapper = shallowMount(Component, {
        propsData: {
          date: '2002-01-10'
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
