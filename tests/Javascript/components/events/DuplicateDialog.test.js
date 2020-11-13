import { createLocalVue, shallowMount, mount } from '@vue/test-utils'
import Component from '~/components/events/DuplicateDialog.vue'
import * as Stories from '~/components/events/DuplicateDialog.stories'

describe('Duplicate Dialog component', () => {
  // Component instance "under test"
  let localVue
  describe('Snapshots', () => {
    test('Default', async () => {
      const wrapper = mount(Stories.Default())
      await wrapper.vm.$nextTick()
      expect(wrapper.element).toMatchSnapshot()
    })
    test('WithData', async () => {
      const wrapper = mount(Stories.WithData())
      await wrapper.vm.$nextTick()
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
      expect(wrapper.emitted()['Duplicate']).toBeTruthy()
    })
  })
})
