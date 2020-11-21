import { mount, shallowMount } from '@vue/test-utils'
import * as Stories from './FMapMarker.stories'
import Component from './FMapMarker'

// TODO: tests skipped for now since we can't test/use FMapMarker only with FMap providing mapbox ie <f-map> <f-map-marker/> </f-map>
describe.skip('components/FMapMarker', () => {
  describe('Snapshots', () => {
    test('Default', async () => {
      const wrapper = mount(Stories.Default())
      expect(wrapper.element).toMatchSnapshot()
    })
    test('Populated', async () => {
      const wrapper = mount(Stories.Populated())
      expect(wrapper.element).toMatchSnapshot()
    })
  })

  describe('Props & Computed', () => {
    test('color', () => {
      const wrapper = shallowMount(Component)
      expect(wrapper.vm.color).toBeUndefined()

      wrapper.setProps({
        color: 'tomato'
      })
      expect(wrapper.vm.color).toEqual('tomato')
    })
    test('coordinates', () => {
      const wrapper = shallowMount(Component)
      expect(wrapper.vm.coordinates).toBeUndefined()

      const coordinates = [ -118.406829, 33.942912 ]
      wrapper.setProps({
        coordinates
      })
      expect(wrapper.vm.coordinates).toMatchObject(coordinates)
    })
  })
})
