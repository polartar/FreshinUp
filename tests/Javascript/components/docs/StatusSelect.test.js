import { createLocalVue, shallowMount, mount } from '@vue/test-utils'
import Component from '~/components/docs/StatusSelect.vue'
import { FIXTURE_DOCUMENT_STATUSES } from 'tests/__data__/documentStatuses'

describe('Event StatusSelect component', () => {
  // Component instance "under test"
  let localVue
  describe('Snapshots', () => {
    test('options set', () => {
      localVue = createLocalVue()
      const wrapper = mount(Component, {
        localVue: localVue,
        propsData: {
          options: FIXTURE_DOCUMENT_STATUSES
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
          options: FIXTURE_DOCUMENT_STATUSES
        }
      })
      expect(wrapper.vm.activeItem.id).toEqual(2)
    })
  })
})
