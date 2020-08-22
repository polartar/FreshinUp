import { mount } from '@vue/test-utils'
import Component from '~/components/fleet-members/BasicInformation.vue'
import * as Stories from '~/components/fleet-members/BasicInformation.stories'

describe('flee-members/BasicInformation', () => {
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
    // TODO
  })
})
