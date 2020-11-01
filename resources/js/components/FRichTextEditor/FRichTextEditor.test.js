import * as Stories from './FRichTextEditor.stories'
import Component from './FRichTextEditor'
import { mount, shallowMount } from '@vue/test-utils'

describe('component/FRichTextEditor', () => {
  describe('Snapshots', () => {
    test('Default', async () => {
      const wrapper = mount(Stories.Default())
      await wrapper.vm.$nextTick()
      expect(wrapper.element).toMatchSnapshot()
    })
    test('Populated', async () => {
      const wrapper = mount(Stories.Populated())
      await wrapper.vm.$nextTick()
      expect(wrapper.element).toMatchSnapshot()
    })
  })

  describe('Props', () => {
    test('value', async () => {
      const wrapper = shallowMount(Component)
      expect(wrapper.vm.value).toBe('')

      const template = Stories.SAMPLE_TEXT
      wrapper.setProps({
        value: template
      })
      expect(wrapper.vm.value).toBe(template)
    })
  })
})
