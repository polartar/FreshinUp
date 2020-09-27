import { mount, shallowMount } from '@vue/test-utils'

import Component from './LocationForm.vue'
import { Default, Indoor, Outdoor, WithDocuments } from './LocationForm.stories'
import { FIXTURE_LOCATION } from '../../../../tests/Javascript/__data__/locations'

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

  describe('Props & computed', () => {
    test('allowedFormats', async () => {
      const wrapper = shallowMount(Component)
      const allowedFormats = ['PDF', 'CSV']
      wrapper.setProps({
        allowedFormats
      })
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.allowedFormats).toMatchObject(allowedFormats)
    })
    test('isIndoor', async () => {
      const wrapper = shallowMount(Component)

      wrapper.setProps({
        value: {
          category_id: 1
        }
      })
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.isIndoor).toBe(true)

      wrapper.setProps({
        value: {
          category_id: 2
        }
      })
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.isIndoor).toBe(false)
    })
    test('acceptedFormats', async () => {
      const wrapper = shallowMount(Component)
      wrapper.setProps({
        allowedFormats: ['GIF', 'PNG']
      })
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.acceptedFormats).toEqual('.gif,.png')
    })
  })

  describe('methods', () => {
    test('save()', async () => {
      const wrapper = shallowMount(Component)
      wrapper.setProps({
        value: FIXTURE_LOCATION
      })
      wrapper.vm.save()
      await wrapper.vm.$nextTick()
      const emitted = wrapper.emitted().input
      expect(emitted).toBeTruthy()
      expect(Object.keys(emitted[0][0])).toContain('files')
    })
    test('removeFile(file)', async () => {
      const wrapper = shallowMount(Component)
      const a = { name: 'a' }; const b = { name: 'b' }; const c = { name: 'c' }
      wrapper.setData({
        files: [a, b, c]
      })
      wrapper.vm.removeFile(a)
      expect(wrapper.vm.files).not.toContain(a)
      expect(wrapper.vm.files).toContain(b)
      expect(wrapper.vm.files).toContain(c)
    })
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
    // TODO: onFileChange
    // TODO: submitFile
  })
})
