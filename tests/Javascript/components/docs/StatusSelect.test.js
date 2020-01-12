import { createLocalVue, shallowMount, mount } from '@vue/test-utils'
import Component from '~/components/docs/StatusSelect.vue'
import { FIXTURE_DOCUMENT_STATUSES } from 'tests/__data__/documentStatuses'

describe('Event StatusSelect component', () => {
  // Component instance "under test"
  let localVue
  describe('Visuals', () => {
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
    describe('items', () => {
      test('returns expected order', () => {
        const wrapper = shallowMount(Component, {
          localVue: localVue,
          propsData: {
            value: 2,
            options: FIXTURE_DOCUMENT_STATUSES
          }
        })
        expect(wrapper.vm.items).toHaveLength(5)
        const expectedLabels = [
          'Pending',
          'Approved',
          'Rejected',
          'Expiring',
          'Expired'
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
            options: FIXTURE_DOCUMENT_STATUSES
          }
        })
        expect(wrapper.vm.activeItem).toHaveProperty('label', 'Approved')
        expect(wrapper.vm.activeItem).toHaveProperty('id', 2)
        expect(wrapper.vm.activeItem).toHaveProperty('color', 'success')
      })
      test('returns null ', () => {
        const wrapper = shallowMount(Component, {
          localVue: localVue,
          propsData: {
            value: 2,
            options: FIXTURE_DOCUMENT_STATUSES
          }
        })
        expect(wrapper.vm.activeItem).toHaveProperty('label', 'Approved')
        expect(wrapper.vm.activeItem).toHaveProperty('id', 2)
        expect(wrapper.vm.activeItem).toHaveProperty('color', 'success')
      })
    })
  })
})
