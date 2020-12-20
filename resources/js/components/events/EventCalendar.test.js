import { createLocalVue, shallowMount, mount } from '@vue/test-utils'
import Component, { lastThreeYears } from '~/components/events/EventCalendar.vue'
import { FIXTURE_EVENTS } from 'tests/__data__/events'
import { FIXTURE_EVENT_STATUSES } from '../../../../tests/Javascript/__data__/eventStatuses'

describe('components/events/EventCalendar', () => {
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

  describe('Props & Computed', () => {
    test('isLoading', () => {
      const wrapper = shallowMount(Component)
      expect(wrapper.vm.isLoading).toEqual(false)

      wrapper.setProps({
        isLoading: true
      })
      expect(wrapper.vm.isLoading).toEqual(true)
    })
    test('events', () => {
      const wrapper = shallowMount(Component)
      expect(wrapper.vm.events).toHaveLength(0)

      wrapper.setProps({
        events: FIXTURE_EVENTS
      })
      expect(wrapper.vm.events).toMatchObject(FIXTURE_EVENTS)
    })
    test('type', () => {
      const wrapper = shallowMount(Component)
      expect(wrapper.vm.type).toEqual('month')

      wrapper.setProps({
        type: 'week'
      })
      expect(wrapper.vm.type).toEqual('week')
    })
    test('yearRange', () => {
      const wrapper = shallowMount(Component)
      expect(wrapper.vm.yearRange).toMatchObject(lastThreeYears())

      const range = [2017, 2018, 2019, 2020]
      wrapper.setProps({
        yearRange: range
      })
      expect(wrapper.vm.yearRange).toMatchObject(range)
    })
    test('date', () => {
      const wrapper = shallowMount(Component)
      expect(wrapper.vm.date).toEqual('2019-1-1')

      wrapper.setProps({
        date: '2020-12-14'
      })
      expect(wrapper.vm.date).toEqual('2020-12-14')
    })

    // Computed
    describe('eventsMap', () => {
      test('for mapping events', () => {
        const wrapper = shallowMount(Component, {
          propsData: {
            events: FIXTURE_EVENTS
          }
        })

        expect(wrapper.vm.eventsMap['2019-10-10'][0].uuid).toEqual('c48fb5d3-37e0-4cb5-bb44-d2d1b5fd97d5')
      })

      test('for multiple days event', () => {
        const wrapper = shallowMount(Component, {
          propsData: {
            events: FIXTURE_EVENTS
          }
        })

        expect(wrapper.vm.eventsMap['2019-10-10'][0].uuid).toEqual('c48fb5d3-37e0-4cb5-bb44-d2d1b5fd97d5')
        expect(wrapper.vm.eventsMap['2019-10-11'][0].uuid).toEqual('c48fb5d3-37e0-4cb5-bb44-d2d1b5fd97d5')
        expect(wrapper.vm.eventsMap['2019-10-12'][0].uuid).toEqual('c48fb5d3-37e0-4cb5-bb44-d2d1b5fd97d5')
      })
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
        9: 'grey'
      })
    })
  })

  describe('Methods', () => {
    test('moveToToday()', () => {
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

    test('moveDate(date)', () => {
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

      const emitted = wrapper.emitted()['change-date']
      expect(emitted).toBeTruthy()
      expect(emitted[0][0].year).toBe(2003)
      expect(emitted[0][0].month).toBe(11)
    })

    test('clickEvent(event)', () => {
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
})
