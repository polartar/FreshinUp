import { shallowMount, mount } from '@vue/test-utils'
import createLocalVue from 'vue-cli-plugin-freshinup-ui/utils/testing/createLocalVue'
import Component from './FileUploader.vue'
import * as Stories from './FileUploader.stories'

function MockFile (name, size, mimeType) {
  name = name || 'mock.txt'
  size = size || 1024
  mimeType = mimeType || 'application/pdf'

  function range (count) {
    return Array(count).fill(0).join('')
  }

  let blob = new Blob([range(size)], { type: mimeType })
  blob.lastModifiedDate = new Date()
  blob.name = name

  return blob
}

describe('components/FileUploader', () => {
  // Component instance "under test"
  let mock
  describe('Snapshots', () => {
    test('Default', () => {
      const wrapper = mount(Stories.Default())
      expect(wrapper.element).toMatchSnapshot()
    })
    test('Downloadable', () => {
      const wrapper = mount(Stories.Downloadable())
      expect(wrapper.element).toMatchSnapshot()
    })
  })

  describe('Props & Computed', () => {
    test('value', async () => {
      const wrapper = shallowMount(Component)
      expect(wrapper.vm.value).toMatchObject({
        name: '',
        src: ''
      })

      const value = {
        name: 'my file',
        src: 'https://example.domain/my-file.csv'
      }
      wrapper.setProps({
        value
      })
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.value).toMatchObject(value)
      expect(wrapper.vm.name).toEqual(value.name)
      expect(wrapper.vm.src).toEqual(value.src)
    })

    // computed
    test('maxInBytes', () => {
      const wrapper = shallowMount(Component)
      const MB = 1024 * 1024

      wrapper.setProps({
        maxFileSize: 5
      })
      expect(wrapper.vm.maxInBytes).toEqual(5 * MB)

      wrapper.setProps({
        maxFileSize: 10
      })
      expect(wrapper.vm.maxInBytes).toEqual(10 * MB)
    })

    test('isDownloadable', async () => {
      const wrapper = shallowMount(Component)
      expect(wrapper.vm.isDownloadable).toEqual(false)

      wrapper.setProps({
        value: { name: 'mock name', src: 'https://downloadable.net/mockfile' }
      })
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.isDownloadable).toEqual(true)
    })
  })

  describe('Methods', () => {
    beforeEach(() => {
      const vue = createLocalVue()
      mock = vue.mock
      mock
        .onPost('foodfleet/tmp-media').reply(200, 'mock url')
        .onAny().reply(config => {
          console.warn('No mock match for ' + config.url, config)
          return [404, {}]
        })
    })

    test('launchFilePicker()', () => {
      const wrapper = shallowMount(Component)
      const clickMock = jest.fn()
      wrapper.vm.$refs.file = {
        click: clickMock
      }
      wrapper.vm.launchFilePicker()
      expect(clickMock).toHaveBeenCalled()
    })

    describe('onFileChange()', () => {
      test('default', async () => {
        const wrapper = shallowMount(Component)
        const file = new MockFile()
        await wrapper.vm.onFileChange([file])
        const emitted = wrapper.emitted().input
        expect(emitted).toBeTruthy()
        expect(emitted[0][0].name).toEqual('mock.txt')
        expect(emitted[0][0].src).toEqual('mock url')
      })
      test('when more than max file size display error', () => {
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

    test('submitFile()', async () => {
      const wrapper = shallowMount(Component)
      const file = new MockFile()
      const src = await wrapper.vm.submitFile([ file ])
      expect(src).toEqual('mock url')
    })

    test('remove()', () => {
      const wrapper = shallowMount(Component, {
        propsData: {
          value: { name: 'test_name', src: '' }
        }
      })
      wrapper.vm.remove()
      const emitted = wrapper.emitted().input
      expect(emitted).toBeTruthy()
      expect(emitted[0][0].name).toEqual('')
      expect(emitted[0][0].src).toEqual('')
    })

    test('showError(message)', () => {
      const wrapper = shallowMount(Component)
      const errorText = 'error text'
      wrapper.vm.showError(errorText)
      expect(wrapper.vm.errorText).toEqual(errorText)
      expect(wrapper.vm.errorDialog).toEqual(true)
    })
  })
})
