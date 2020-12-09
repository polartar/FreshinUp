import { shallowMount, mount } from '@vue/test-utils'
import Component from './DuplicateEventDialog.vue'
import * as Stories from './DuplicateEventDialog.stories'

describe('DuplicateEventDialog', () => {
  describe('Snapshots', () => {
    test('Default', async () => {
      const wrapper = mount(Stories.Default())
      await wrapper.vm.$nextTick()
      expect(wrapper.element).toMatchSnapshot()
    })
    test('IsLoading', async () => {
      const wrapper = mount(Stories.IsLoading())
      await wrapper.vm.$nextTick()
      expect(wrapper.element).toMatchSnapshot()
    })
    test('Populated', async () => {
      const wrapper = mount(Stories.Populated())
      await wrapper.vm.$nextTick()
      expect(wrapper.element).toMatchSnapshot()
    })
  })

  describe('Methods', () => {
    test('close()', () => {
      const wrapper = shallowMount(Component)
      wrapper.vm.close()
      const emitted = wrapper.emitted().close
      expect(emitted).toBeTruthy()
    })
  })
})
