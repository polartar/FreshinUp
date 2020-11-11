import Component from './FMap'
import { shallowMount } from '@vue/test-utils'

describe('components/FMap', () => {
  // no snapshots tests since it will break on CI

  describe('Props & Computed', () => {
    test('accessToken', async () => {
      const wrapper = shallowMount(Component, {
        propsData: {
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
