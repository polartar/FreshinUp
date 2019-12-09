import { createLocalVue, shallowMount, mount } from '@vue/test-utils'
import Component from '~/components/events/AssignedEvents.vue'
import { FIXTURE_EVENTS } from 'tests/__data__/events'
import { FIXTURE_EVENT_STATUSES } from 'tests/__data__/eventStatuses'

describe('Assigned Events component', () => {
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

    test('emit actions', () => {
      const wrapper = shallowMount(Component, {
        localVue
      })

      const mockEvent = { id: 1, title: 'mock title', status: 1 }
      wrapper.vm.viewEvent(mockEvent)
      expect(wrapper.emitted()['viewEvent']).toBeTruthy()

      wrapper.vm.sort({ id: 'name', label: 'Name' })
      expect(wrapper.emitted()['sort']).toBeTruthy()

      wrapper.vm.searchInput('test')
      expect(wrapper.emitted()['searchInput']).toBeTruthy()
    })
  })
})
