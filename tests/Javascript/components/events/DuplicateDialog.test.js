import { createLocalVue, shallowMount, mount } from '@vue/test-utils'
import Component from '~/components/events/DuplicateDialog.vue'

describe('Duplicate Dialog component', () => {
  // Component instance "under test"
  let localVue
  describe('Snapshots', () => {
    test('dialog open', () => {
      localVue = createLocalVue()
      const wrapper = mount(Component, {
        localVue: localVue,
        propsData: {
          duplicateDialog: true,
          duplicating: true
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

      const mockValue = true
      wrapper.vm.changeDuplicateDialogue(mockValue)
      expect(wrapper.emitted()['manage-duplicate-dialog']).toBeTruthy()

      wrapper.vm.onDuplicate()
      expect(wrapper.emitted()['manage-duplicate']).toBeTruthy()
    })
  })
})
