import { createLocalVue, mount, shallowMount } from '@vue/test-utils'
import * as Stories from './AreaForm.stories'
import Component from './AreaForm.vue'

describe('components/fleet-members/AreaForm', () => {
  describe('Snapshots', () => {
    test('Default', async () => {
      const wrapper = mount(Stories.Default())
      await wrapper.vm.$nextTick()
      expect(wrapper.element).toMatchSnapshot()
    })
    test('Loading', async () => {
      const wrapper = mount(Stories.Loading())
      await wrapper.vm.$nextTick()
      expect(wrapper.element).toMatchSnapshot()
    })
    test('WithData', async () => {
      const wrapper = mount(Stories.WithData())
      await wrapper.vm.$nextTick()
      expect(wrapper.element).toMatchSnapshot()
    })
  })

  describe('Props & Data', () => {
    test('isLoading', async () => {
      const wrapper = shallowMount(Component)
      expect(wrapper.vm.isLoading).toBe(false)

      wrapper.setProps({
        isLoading: true
      })
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.isLoading).toBe(true)
    })
  })

  describe('methods', () => {
    let localVue

    beforeEach(() => {
      localVue = createLocalVue()
    })

    test('onCancel()', async () => {
      const wrapper = shallowMount(Component, {
        localVue: localVue
      })
      wrapper.vm.onCancel()
      await wrapper.vm.$nextTick()
      expect(wrapper.emitted().cancel).toBeTruthy()
    })
  })
})
