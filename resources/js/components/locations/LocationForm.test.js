import { mount, shallowMount } from '@vue/test-utils'

import * as Stories from './LocationForm.stories'
import Component from './LocationForm.vue'

describe('components/locations/LocationForm', () => {
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

  describe('methods', () => {
    test('onCancel()', async () => {
      const wrapper = shallowMount(Component)
      wrapper.vm.onCancel()
      await wrapper.vm.$nextTick()
      expect(wrapper.emitted().cancel).toBeTruthy()
    })
  })
})
