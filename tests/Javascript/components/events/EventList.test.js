import { createLocalVue, shallowMount, mount } from '@vue/test-utils'
import Component from '~/components/events/EventList.vue'
import { FIXTURE_EVENTS } from 'tests/__data__/events'
import { FIXTURE_EVENT_STATUSES } from 'tests/__data__/eventStatuses'

describe('Event List component', () => {
  // Component instance "under test"
  let localVue
  describe('Snapshots', () => {
    test('events set', () => {
      localVue = createLocalVue()
      const wrapper = mount(Component, {
        localVue: localVue,
        propsData: {
          statuses: FIXTURE_EVENT_STATUSES,
          events: FIXTURE_EVENTS
        }
      })
      expect(wrapper.element).toMatchSnapshot()
    })
    test('events empty', () => {
      localVue = createLocalVue()
      const wrapper = mount(Component, {
        localVue: localVue,
        propsData: {
          statuses: FIXTURE_EVENT_STATUSES,
          events: []
        }
      })
      expect(wrapper.element).toMatchSnapshot()
    })
  })
  describe('Methods', () => {
    beforeEach(() => {
      localVue = createLocalVue()
    })

    test('manage function emitted single manage action', () => {
      const wrapper = shallowMount(Component, {
        localVue
      })

      const itemActions = [
        { action: 'edit', text: 'Edit' },
        { action: 'duplicate', text: 'Duplicate' },
        { action: 'delete', text: 'Delete' },
        { action: 'cancel', text: 'Cancel' },
        { action: 'leave', text: 'Leave Event' }
      ]
      const mockEvent = { id: 1, title: 'mock title', status: 1 }

      wrapper.vm.manage(itemActions[0], mockEvent)
      wrapper.vm.manage(itemActions[1], mockEvent)
      wrapper.vm.manage(itemActions[2], mockEvent)
      wrapper.vm.manage(itemActions[3], mockEvent)
      wrapper.vm.manage(itemActions[4], mockEvent)

      expect(wrapper.emitted()['manage-edit']).toBeTruthy()
      expect(wrapper.emitted()['manage-duplicate']).toBeTruthy()
      expect(wrapper.emitted()['manage-delete']).toBeTruthy()
      expect(wrapper.emitted()['manage-cancel']).toBeTruthy()
      expect(wrapper.emitted()['manage-leave']).toBeTruthy()
    })

    test('manageMultiple function emitted multiple manage action', () => {
      const wrapper = shallowMount(Component, {
        localVue
      })

      wrapper.vm.manageMultiple('delete')

      expect(wrapper.emitted()['manage-multiple-delete']).toBeTruthy()
    })

    test('changeStatus function emitted change-status action', () => {
      const wrapper = shallowMount(Component, {
        localVue
      })

      const mockEvent = { id: 1, title: 'mock title', status: 1 }

      wrapper.vm.changeStatus(2, mockEvent)

      expect(wrapper.emitted()['change-status']).toBeTruthy()
      expect(wrapper.emitted()['change-status'][0][0]).toEqual(2)
    })

    test('changeStatusMultiple function emitted change-status-multiple action', () => {
      const wrapper = shallowMount(Component, {
        localVue
      })

      wrapper.vm.changeStatusMultiple(3)

      expect(wrapper.emitted()['change-status-multiple']).toBeTruthy()
      expect(wrapper.emitted()['change-status-multiple'][0][0]).toEqual(3)
    })
  })

  describe('Computed', () => {
    beforeEach(() => {
      localVue = createLocalVue()
    })
    test('selectedActions', () => {
      const wrapper = shallowMount(Component)
      wrapper.setData({ selected: [] })
      expect(wrapper.vm.selectedActions).toEqual([])
      wrapper.setData({ selected: [ 1 ] })
      expect(wrapper.vm.selectedActions[0].action).toBe('delete')
      expect(wrapper.vm.selectedActions[0].text).toBe('Delete')
    })
  })
})
