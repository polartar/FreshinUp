import * as Stories from './DocumentPreview.stories'
import Component from './DocumentPreview'
import { mount, shallowMount } from '@vue/test-utils'
import { FIXTURE_DOCUMENT } from '../../../../tests/Javascript/__data__/documents'

describe('component/doc/DocumentPreview', () => {
  describe('Snapshots', () => {
    test('Default', async () => {
      const wrapper = mount(Stories.Default())
      await wrapper.vm.$nextTick()
      await wrapper.vm.$nextTick()
      expect(wrapper.element).toMatchSnapshot()
    })
  })

  describe('Props & Computed', () => {
    test('doc', async () => {
      const template = FIXTURE_DOCUMENT
      const wrapper = shallowMount(Component, {
        propsData: {
          doc: template
        }
      })
      expect(wrapper.vm.doc).toMatchObject(template)
    })
  })

  describe('Methods', () => {
    test('onClose()', async () => {
      const template = FIXTURE_DOCUMENT
      const wrapper = shallowMount(Component, {
        propsData: {
          doc: template
        }
      })
      wrapper.vm.onClose()
      const event = wrapper.emitted().close
      expect(event).toBeTruthy()
    })
  })
})
