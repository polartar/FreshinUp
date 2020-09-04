import { shallowMount, mount } from '@vue/test-utils'
import Component from '~/components/events/EventStatusTimeline.vue'
import * as Stories from '~/components/events/EventStatusTimeline.stories'
import { FIXTURE_EVENT_HISTORIES } from '../../__data__/eventHistory'

describe('EventStatusTimeline', () => {
  describe('Snapshots', () => {
    test('Default', async () => {
      const wrapper = mount(Stories.Default())
      await wrapper.vm.$nextTick()
      expect(wrapper.element).toMatchSnapshot()
    })
    test('Populated', async () => {
      const wrapper = mount(Stories.Populated())
      await wrapper.vm.$nextTick()
      expect(wrapper.element).toMatchSnapshot()
    })
    test('AllChecked', async () => {
      const wrapper = mount(Stories.AllChecked())
      await wrapper.vm.$nextTick()
      expect(wrapper.element).toMatchSnapshot()
    })
    test('Cancelled', async () => {
      const wrapper = mount(Stories.Cancelled())
      await wrapper.vm.$nextTick()
      expect(wrapper.element).toMatchSnapshot()
    })
  })

  describe('Props & Computed', () => {
    test('historiesByStatus', async () => {
      const wrapper = shallowMount(Component)
      wrapper.setProps({
        histories: []
      })
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.historiesByStatus).toMatchObject({})

      wrapper.setProps({
        histories: FIXTURE_EVENT_HISTORIES
      })
      const expectation = FIXTURE_EVENT_HISTORIES.reduce((map, history) => {
        map[history.status_id] = history
        return map
      }, {})
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.historiesByStatus).toMatchObject(expectation)
    })
    describe('options', () => {
      test('when', async () => {
        const wrapper = shallowMount(Component)
        wrapper.setProps({
          histories: []
        })
        await wrapper.vm.$nextTick()
        expect(wrapper.vm.options).toMatchObject([])

        wrapper.setProps({
          histories: FIXTURE_EVENT_HISTORIES
        })
        const expectation = FIXTURE_EVENT_HISTORIES.reduce((map, history) => {
          map[history.status_id] = history
          return map
        }, {})
        await wrapper.vm.$nextTick()
        expect(wrapper.vm.historiesByStatus).toMatchObject(expectation)
      })
    })
  })

  describe('Methods', () => {
    describe('getColorFor()', () => {
      test('when completed', () => {
        const wrapper = shallowMount(Component)
        expect(wrapper.vm.getColorFor({ completed: true })).toEqual('success')
      })
      test('when selected', () => {
        const wrapper = shallowMount(Component)
        wrapper.setProps({
          status: 1
        })
        expect(wrapper.vm.getColorFor({ status_id: 1 })).toEqual('warning lighten-2')
      })
      test('default', () => {
        const wrapper = shallowMount(Component)
        expect(wrapper.vm.getColorFor({ completed: false })).toEqual('grey lighten-2')
      })
    })
    describe('getIconFor()', () => {
      test('when completed', () => {
        const wrapper = shallowMount(Component)
        expect(wrapper.vm.getIconFor({ completed: true })).toEqual('check_circle_outline')
      })
      test('otherwise', () => {
        const wrapper = shallowMount(Component)
        expect(wrapper.vm.getIconFor({ completed: false })).toEqual('')
      })
    })
  })
})
