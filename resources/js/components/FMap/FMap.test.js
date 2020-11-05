import Component from './FMap'
import { mount, shallowMount } from '@vue/test-utils'

describe('components/FMap', () => {
  describe('Snapshots', () => {
    const _createObjectURL = window.URL.createObjectURL
    const createObjectURLMock = jest.fn()
    beforeEach(() => {
      window.URL.createObjectURL = createObjectURLMock
    })
    afterEach(() => {
      window.URL.createObjectURL = _createObjectURL
    })
    test('Basic', async () => {
      const wrapper = mount(Component)
      await wrapper.vm.$nextTick()
      expect(wrapper.element).toMatchSnapshot()
    })
  })

  describe('Props & Computed', () => {
    test('accessToken', async () => {
      const wrapper = shallowMount(Component, {
        props: {
          accessToken: 'aaa111'
        }
      })
      expect(wrapper.vm.accessToken).toEqual('aaa111')

      wrapper.setProps({
        accessToken: 'bbb222'
      })
      expect(wrapper.vm.accessToken).toEqual('bbb222')
    })
    test('mStyle', async () => {
      const wrapper = shallowMount(Component)
      expect(wrapper.vm.mStyle).toEqual('mapbox://styles/mapbox/streets-v11')

      const mStyle = 'mapbox://styles/mapbox/streets-v12'
      wrapper.setProps({
        mStyle
      })
      expect(wrapper.vm.mStyle).toEqual(mStyle)
    })
  })
})
