import * as Stories from './DocumentPreviewContent.stories'
import Component from './DocumentPreviewContent'
import { mount, shallowMount } from '@vue/test-utils'
import { FIXTURE_DOCUMENT } from '../../../../tests/Javascript/__data__/documents'

describe('component/doc/DocumentPreviewContent', () => {
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
})
