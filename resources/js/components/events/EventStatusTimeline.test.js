import { shallowMount, mount } from '@vue/test-utils'
import Component from '~/components/events/EventStatusTimeline.vue'
import * as Stories from '~/components/events/EventStatusTimeline.stories'
import { FIXTURE_EVENT_HISTORIES } from 'tests/__data__/eventHistory'
import { FIXTURE_EVENT_STATUSES } from 'tests/__data__/eventStatuses'

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
  })

  describe('Props & Computed', () => {
    test('statusesById', async () => {
      const wrapper = shallowMount(Component)
      wrapper.setProps({
        statuses: []
      })
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.statusesById).toMatchObject({})

      wrapper.setProps({
        statuses: FIXTURE_EVENT_STATUSES
      })
      const expectation = FIXTURE_EVENT_STATUSES.reduce((map, status) => {
        map[status.id] = status
        return map
      }, {})
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.statusesById).toMatchObject(expectation)
    })
    test('options', async () => {
      const wrapper = shallowMount(Component)
      wrapper.setProps({
        histories: []
      })
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.options).toMatchObject([])

      wrapper.setProps({
        histories: FIXTURE_EVENT_HISTORIES,
        statuses: FIXTURE_EVENT_STATUSES
      })
      const expectation = FIXTURE_EVENT_HISTORIES.map(history => {
        const status = wrapper.vm.statusesById[history.id] || {}
        return { ...history, name: status.name }
      })
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.options).toMatchObject(expectation)
    })
  })

  describe('Methods', () => {
    describe('getColorFor()', () => {
      test('when selected', () => {
        const wrapper = shallowMount(Component)
        wrapper.setProps({
          status: 1
        })
        expect(wrapper.vm.getColorFor({ status_id: 1 })).toEqual('warning lighten-2')
      })
      test('default', () => {
        const wrapper = shallowMount(Component)
        expect(wrapper.vm.getColorFor({ completed: false })).toEqual('success')
      })
    })
    describe('getIconFor()', () => {
      test('when selected', () => {
        const wrapper = shallowMount(Component)
        wrapper.setProps({
          status: 1
        })
        expect(wrapper.vm.getIconFor({ status_id: 1 })).toEqual('')
      })
      test('otherwise', () => {
        const wrapper = shallowMount(Component)
        wrapper.setProps({
          status: 1
        })
        expect(wrapper.vm.getIconFor({ status_id: 2 })).toEqual('check_circle_outline')
      })
    })
  })
})
