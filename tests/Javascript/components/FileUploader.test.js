import { shallowMount, mount } from '@vue/test-utils'
import createLocalVue from 'vue-cli-plugin-freshinup-ui/utils/testing/createLocalVue'
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
  let localVue, mock
  describe('Snapshots', () => {
    test('defaults', () => {
      const vue = createLocalVue()
      localVue = vue.localVue
      const wrapper = mount(Component, {
        localVue: localVue
      })
      expect(wrapper.element).toMatchSnapshot()
    })
  })
  describe('Methods', () => {
    beforeEach(() => {
      const vue = createLocalVue()
      localVue = vue.localVue
      mock = vue.mock
      mock
        .onPost('foodfleet/tmp-media').reply(200, 'mock url')
        .onAny().reply(config => {
          console.warn('No mock match for ' + config.url, config)
          return [404, {}]
        })
    })

    test('remove() clear file name and src', () => {
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

    test('submitFile() return file src', async () => {
      const wrapper = shallowMount(Component, {
        propsData: {
          value: { name: '', src: '' }
        }
      })
      const file = new MockFile()
      const src = await wrapper.vm.submitFile([ file ])
      expect(src).toEqual('mock url')
    })

    test('onFileChange() change file name and src', async () => {
      const wrapper = shallowMount(Component, {
        propsData: {
          value: { name: '', src: '' }
        }
      })
      const file = new MockFile()
      await wrapper.vm.onFileChange([ file ])
      expect(wrapper.emitted().onValueChange).toBeTruthy()
      expect(wrapper.emitted().onValueChange[0][0].name).toEqual('mock.txt')
      expect(wrapper.emitted().onValueChange[0][0].src).toEqual('mock url')
    })

    test('more than max file size display error', () => {
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
