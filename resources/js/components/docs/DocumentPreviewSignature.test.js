import * as Stories from './DocumentPreviewSignature.stories'
import Component from './DocumentPreviewSignature'
import { mount, shallowMount } from '@vue/test-utils'

describe('component/doc/DocumentPreviewSignature', () => {
  describe('Snapshots', () => {
    test('Default', async () => {
      const wrapper = mount(Stories.Default())
      await wrapper.vm.$nextTick()
      await wrapper.vm.$nextTick()
      expect(wrapper.element).toMatchSnapshot()
    })
  })

  describe('Props & Computed', () => {
    test('signeeName', async () => {
      const signeeName = 'moc signeeName'
      const wrapper = shallowMount(Component, {
        propsData: {
          signeeName
        }
      })
      expect(wrapper.vm.signeeName).toMatch(signeeName)
    })
  })
})
