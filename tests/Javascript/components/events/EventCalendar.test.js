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
})
