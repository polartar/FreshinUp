import { createLocalVue, shallowMount, mount } from '@vue/test-utils'
import Component from '~/components/events/StatusSelect.vue'
import { FIXTURE_EVENT_STATUSES } from '../../__data__/eventStatuses'

describe('Event StatusSelect component', () => {
  let localVue
  describe('Visuals', () => {
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
    describe('items', () => {
      test('returns expected order', () => {
        const wrapper = shallowMount(Component, {
          localVue: localVue,
          propsData: {
            value: 2,
            options: FIXTURE_EVENT_STATUSES
          }
        })
        expect(wrapper.vm.items).toHaveLength(9)
        const expectedLabels = [
          'Draft',
          'FF Initial Review',
          'Customer Agreement',
          'Fleet Member Selection',
          'Customer Review',
          'Fleet Member Contracts',
          'Confirmed',
          'Cancelled',
          'Past'
        ]

        expectedLabels.forEach((value, index) => {
          expect(wrapper.vm.items[index]).toHaveProperty('label', value)
        })
      })
    })
    describe('activeItem', () => {
      test('returns the matching value', () => {
        const wrapper = shallowMount(Component, {
          localVue: localVue,
          propsData: {
            value: 2,
            options: FIXTURE_EVENT_STATUSES
          }
        })
        expect(wrapper.vm.activeItem).toHaveProperty('label', 'FF Initial Review')
        expect(wrapper.vm.activeItem).toHaveProperty('id', 2)
        expect(wrapper.vm.activeItem).toHaveProperty('color', 'warning')
      })
      test('returns null ', () => {
        const wrapper = shallowMount(Component, {
          localVue: localVue,
          propsData: {
            value: 7,
            options: FIXTURE_EVENT_STATUSES
          }
        })
        expect(wrapper.vm.activeItem).toHaveProperty('label', 'Confirmed')
        expect(wrapper.vm.activeItem).toHaveProperty('id', 7)
        expect(wrapper.vm.activeItem).toHaveProperty('color', 'success')
      })
    })
  })
})
