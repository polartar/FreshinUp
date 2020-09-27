import { mount, shallowMount } from '@vue/test-utils'

import Component from './LocationForm.vue'
import { Default, Indoor, Outdoor, WithDocuments } from './LocationForm.stories'

describe('components/locations/LocationForm', () => {
  describe('Snapshots', () => {
    test('Default', async () => {
      const wrapper = mount(Default())
      await wrapper.vm.$nextTick()
      expect(wrapper.element).toMatchSnapshot()
    })
    test('Indoor', async () => {
      const wrapper = mount(Indoor())
      await wrapper.vm.$nextTick()
      expect(wrapper.element).toMatchSnapshot()
    })
    test('Outdoor', async () => {
      const wrapper = mount(Outdoor())
      await wrapper.vm.$nextTick()
      expect(wrapper.element).toMatchSnapshot()
    })
    test('WithDocuments', async () => {
      const wrapper = mount(WithDocuments())
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
    describe('triggerFilePicker()', () => {
      test('when image is not found', async () => {
        const wrapper = shallowMount(Component)
        const querySelectorMock = jest.fn(() => null)
        wrapper.vm.$el = {
          querySelector: querySelectorMock
        }
        const result = wrapper.vm.triggerFilePicker()
        expect(querySelectorMock).toHaveBeenCalledWith('.ff-add-location__file_input')
        expect(result).toBe(false)
      })
      test('when image is found', async () => {
        const wrapper = shallowMount(Component)
        const clickMock = jest.fn()
        const querySelectorMock = jest.fn(() => ({
          click: clickMock
        }))
        wrapper.vm.$el = {
          querySelector: querySelectorMock
        }
        const result = wrapper.vm.triggerFilePicker()
        expect(querySelectorMock).toHaveBeenCalledWith('.ff-add-location__file_input')
        expect(clickMock).toHaveBeenCalled()
        expect(result).toBe(true)
      })
    })
  })
})
