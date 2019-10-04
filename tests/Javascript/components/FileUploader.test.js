import { createLocalVue, shallowMount, mount } from '@vue/test-utils'
import Component from '~/components/FileUploader.vue'

function MockFile (name, size, mimeType) {
  name = name || 'mock.txt'
  size = size || 1024
  mimeType = mimeType || 'application/pdf'

  function range (count) {
    let output = ''
    for (let i = 0; i < count; i++) {
      output += 'a'
    }
    return output
  }

  let blob = new Blob([range(size)], { type: mimeType })
  blob.lastModifiedDate = new Date()
  blob.name = name

  return blob
}

describe('FileUploader', () => {
  // Component instance "under test"
  let localVue
  describe('Snapshots', () => {
    test('defaults', () => {
      localVue = createLocalVue()
      const wrapper = mount(Component, {
        localVue: localVue
      })
      expect(wrapper.element).toMatchSnapshot()
    })
  })
  describe('Methods', () => {
    beforeEach(() => {
      localVue = createLocalVue()
    })

    test('remove() clear file name and src', () => {
      global.URL.createObjectURL = jest.fn()
      const wrapper = shallowMount(Component, {
        propsData: {
          value: { name: 'test_name', src: '' }
        }
      })
      wrapper.vm.remove()
      expect(wrapper.emitted().onValueChange).toBeTruthy()
      const changeValue = wrapper.emitted().onValueChange[0]
      expect(changeValue[0].name).toEqual('')
      expect(changeValue[0].src).toEqual('')
    })

    test('onFileChange() change file name and src', () => {
      global.URL.createObjectURL = jest.fn()
      const wrapper = shallowMount(Component, {
        propsData: {
          value: { name: '', src: '' }
        }
      })
      const file = new MockFile()
      wrapper.vm.onFileChange([ file ])
      expect(wrapper.emitted().onValueChange).toBeTruthy()
    })

    test('more than max file size display error', () => {
      global.URL.createObjectURL = jest.fn()
      const wrapper = shallowMount(Component, {
        propsData: {
          value: { name: '', src: '' },
          maxFileSize: 1
        }
      })
      const file = new MockFile(null, 10 * 1024 * 1024, null)
      wrapper.vm.onFileChange([ file ])
      expect(wrapper.vm.errorDialog).toBeTruthy()
    })
  })
})
