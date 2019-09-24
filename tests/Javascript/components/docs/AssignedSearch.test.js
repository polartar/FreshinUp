import { createLocalVue, shallowMount, mount } from '@vue/test-utils'
import Component from '~/components/docs/AssignedSearch.vue'

describe('AssignedSearch', () => {
  // Component instance "under test"
  let localVue
  describe('Snapshots', () => {
    test('defaults', () => {
      localVue = createLocalVue()
      const wrapper = mount(Component, {
        localVue: localVue,
        propsData: {
          type: 2,
          onAssignChange: () => {},
          onTypeChange: () => {}
        }
      })
      expect(wrapper.element).toMatchSnapshot()
    })
  })

  describe('Computed', () => {
    beforeEach(() => {
      localVue = createLocalVue()
    })
    test('currentOption', () => {
      const wrapper = shallowMount(Component, {
        propsData: {
          type: 2,
          onAssignChange: () => {},
          onTypeChange: () => {}
        }
      })
      expect(wrapper.vm.currentOption.value).toBe(2)
    })
  })
})
