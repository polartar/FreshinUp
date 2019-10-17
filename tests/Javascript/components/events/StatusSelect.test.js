import { createLocalVue, shallowMount, mount } from '@vue/test-utils'
import Component from '~/components/events/StatusSelect.vue'
import { FIXTURE_EVENT_STATUSES } from 'tests/__data__/eventStatuses'

describe('Event StatusSelect component', () => {
  // Component instance "under test"
  let localVue
  describe('Snapshots', () => {
    test('options set', () => {
      localVue = createLocalVue()
      const wrapper = mount(Component, {
        localVue: localVue,
        propsData: {
          options: FIXTURE_EVENT_STATUSES
        }
      })
      expect(wrapper.element).toMatchSnapshot()
    })
  })

  describe('Computed', () => {
    beforeEach(() => {
      localVue = createLocalVue()
    })
    test('activeItem', () => {
      const wrapper = shallowMount(Component, {
        localVue: localVue,
        propsData: {
          value: 2,
          options: FIXTURE_EVENT_STATUSES
        }
      })
      expect(wrapper.vm.activeItem.id).toEqual(2)
    })
  })
})
