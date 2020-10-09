import * as Stories from './DocumentPreviewSignatureItem.stories'
import Component from './DocumentPreviewSignatureItem'
import { mount, shallowMount } from '@vue/test-utils'

describe('component/doc/DocumentPreviewSignatureItem', () => {
  describe('Snapshots', () => {
    test('Default', async () => {
      const wrapper = mount(Stories.Default())
      await wrapper.vm.$nextTick()
      expect(wrapper.element).toMatchSnapshot()
    })
  })

  describe('Props & Computed', () => {
    test('signeeName', async () => {
      const item = 'moc item'
      const wrapper = shallowMount(Component, {
        propsData: {
          item
        }
      })
      expect(wrapper.vm.item).toMatch(item)
    })
  })
})
