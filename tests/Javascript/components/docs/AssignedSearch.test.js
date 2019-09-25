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

  describe('Methods', () => {
    beforeEach(() => {
      localVue = createLocalVue()
    })

    test('selectAssigned function emitted assign-change', () => {
      const wrapper = shallowMount(Component)
      wrapper.vm.selectAssigned({})
      expect(wrapper.emitted()['assign-change']).toBeTruthy()
    })
  })

  describe('Computed', () => {
    beforeEach(() => {
      localVue = createLocalVue()
    })
    test('currentOption', () => {
      const wrapper = shallowMount(Component, {
        propsData: {
          type: 2
        }
      })
      expect(wrapper.vm.currentOption.value).toBe(2)
    })

    test('typeValue', () => {
      const wrapper = shallowMount(Component, {
        propsData: {
          type: 1
        }
      })

      wrapper.vm.type = 2
      expect(wrapper.vm.typeValue).toBe(2)
    })
  })
})
