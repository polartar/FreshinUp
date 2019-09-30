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
      wrapper.vm.selectAssigned({ uuid: 'mock' })
      wrapper.vm.selectAssigned()
      expect(wrapper.emitted()['assign-change']).toBeTruthy()
      expect(wrapper.emitted()['assign-change'][1][0]).toEqual('')
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

    test('typeValue change different', () => {
      const wrapper = shallowMount(Component, {
        propsData: {
          type: 1
        }
      })

      wrapper.vm.type = 2
      expect(wrapper.vm.typeValue).toBe(2)
      wrapper.vm.typeValue = 3
      expect(wrapper.emitted()['type-change']).toBeTruthy()
      expect(wrapper.emitted()['type-change'][0][0]).toEqual(3)
    })

    test('typeValue change same', () => {
      const wrapper = shallowMount(Component, {
        propsData: {
          type: 1
        }
      })

      wrapper.vm.typeValue = 1
      expect(wrapper.emitted()['type-change']).toBeFalsy()
    })
  })
})
