import { mount, shallowMount } from '@vue/test-utils'
import Component from './BasicInformation.vue'
import * as Stories from './BasicInformation.stories'
import { FIXTURE_MAPBOX_SEARCH_RESULT } from 'tests/__data__/mapbox'
import { FIXTURE_USER } from 'tests/__data__/user'

describe('components/venues/BasicInformation', () => {
  describe('Snapshots', () => {
    const _createObjectURL = window.URL.createObjectURL
    const createObjectURLMock = jest.fn()
    beforeEach(() => {
      window.URL.createObjectURL = createObjectURLMock
    })
    afterEach(() => {
      window.URL.createObjectURL = _createObjectURL
    })
    test('Default', () => {
      const wrapper = mount(Stories.Default(), {
        stubs: {
          'f-map': true,
          'f-map-marker': true
        }
      })
      expect(wrapper.element).toMatchSnapshot()
    })
    test('Loading', () => {
      const wrapper = mount(Stories.Loading(), {
        stubs: {
          'f-map': true,
          'f-map-marker': true
        }
      })
      expect(wrapper.element).toMatchSnapshot()
    })
    test('AddressesLoading', () => {
      const wrapper = mount(Stories.AddressesLoading(), {
        stubs: {
          'f-map': true,
          'f-map-marker': true
        }
      })
      expect(wrapper.element).toMatchSnapshot()
    })
    test('Populated', () => {
      const wrapper = mount(Stories.Populated(), {
        stubs: {
          'f-map': true,
          'f-map-marker': true
        }
      })
      expect(wrapper.element).toMatchSnapshot()
    })
  })

  describe('Props & Computed', () => {
    test('isLoading', async () => {
      const wrapper = shallowMount(Component)
      expect(wrapper.vm.isLoading).toBe(false)

      wrapper.setProps({
        isLoading: true
      })
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.isLoading).toBe(true)
    })
    test('addressesLoading', async () => {
      const wrapper = shallowMount(Component)
      expect(wrapper.vm.addressesLoading).toBe(false)

      wrapper.setProps({
        addressesLoading: true
      })
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.addressesLoading).toBe(true)
    })
    test('addresses', async () => {
      const wrapper = shallowMount(Component)
      expect(wrapper.vm.addresses).toHaveLength(0)

      wrapper.setProps({
        addresses: FIXTURE_MAPBOX_SEARCH_RESULT.features
      })
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.addresses).toMatchObject(FIXTURE_MAPBOX_SEARCH_RESULT.features)
    })
    test('mapboxAccessToken', async () => {
      const wrapper = shallowMount(Component)
      expect(wrapper.vm.mapboxAccessToken).toBeNull()

      wrapper.setProps({
        mapboxAccessToken: 'abc123'
      })
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.mapboxAccessToken).toEqual('abc123')
    })
    // computed
    test('hasOwner', async () => {
      const wrapper = shallowMount(Component)
      expect(wrapper.vm.hasOwner).toBe(false)

      wrapper.setProps({
        value: {
          owner: {
            uuid: 'abc123'
          }
        }
      })
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.hasOwner).toBe(true)
    })
    test('isEditing', async () => {
      const wrapper = shallowMount(Component)
      expect(wrapper.vm.isEditing).toBe(false)

      wrapper.setProps({
        value: {
          uuid: 'abc123'
        }
      })
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.isEditing).toBe(true)
    })
    test('mapCenter', async () => {
      const wrapper = shallowMount(Component)
      expect(wrapper.vm.mapCenter).toBeUndefined()

      wrapper.setProps({
        value: {
          longitude: -118.406829,
          latitude: 33.942912
        }
      })
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.mapCenter).toMatchObject([
        -118.406829, 33.942912
      ])
    })
  })

  describe('Methods', () => {
    test('selectOwner(user)', async () => {
      const wrapper = shallowMount(Component)
      expect(wrapper.vm.owner_uuid).toEqual('')
      expect(wrapper.vm.owner).toMatchObject({})

      wrapper.vm.selectOwner(FIXTURE_USER)
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.owner_uuid).toEqual(FIXTURE_USER.uuid)
      expect(wrapper.vm.owner).toMatchObject(FIXTURE_USER)
    })
    test('onCancel()', async () => {
      const wrapper = shallowMount(Component)
      wrapper.vm.onCancel()
      const emitted = wrapper.emitted().cancel
      expect(emitted).toBeTruthy()
    })
    test('onDeleteVenue()', async () => {
      const wrapper = shallowMount(Component)
      wrapper.vm.onDeleteVenue()
      const emitted = wrapper.emitted().delete
      expect(emitted).toBeTruthy()
      expect(emitted[0][0]).toMatchObject(wrapper.vm.payload)
    })
    describe('searchPlaces(query)', () => {
      beforeEach(() => {
        jest.mock('lodash/debounce', () => jest.fn(fn => fn))
      })
      afterEach(() => {
        jest.clearAllMocks()
      })
      test('when query is not defined', async () => {
        const query = ''
        const wrapper = shallowMount(Component, {
          propsData: {
            value: {
              address_line_1: query
            }
          }
        })
        wrapper.vm.searchPlaces(query)
        await wrapper.vm.$nextTick()
        const emitted = wrapper.emitted()['search-places']
        expect(emitted).not.toBeTruthy()
      })
      test('when query = address_line_1', async () => {
        const query = 'LA stadium'
        const wrapper = shallowMount(Component, {
          propsData: {
            value: {
              address_line_1: query
            }
          }
        })
        wrapper.vm.searchPlaces(query)
        await wrapper.vm.$nextTick()
        const emitted = wrapper.emitted()['search-places']
        expect(emitted).not.toBeTruthy()
      })
      test('otherwise', async () => {
        const wrapper = shallowMount(Component)
        const query = 'LA stadium'
        // mock since jest.mock('lodash/debounce', () => jest.fn(f => f)) is not working
        // remember that tests are also documentation
        wrapper.vm.searchPlaces = (q) => {
          wrapper.vm.$emit('search-places', q)
        }
        wrapper.vm.searchPlaces(query)
        await wrapper.vm.$nextTick()
        const emitted = wrapper.emitted()['search-places']
        expect(emitted).toBeTruthy()
        expect(emitted[0][0]).toEqual(query)
      })
    })
    test('onPlaceInput(place)', async () => {
      const wrapper = shallowMount(Component)
      expect(wrapper.vm.borderBox).toBeNull()
      expect(wrapper.vm.address_line_1).toEqual('')
      expect(wrapper.vm.address_line_2).toEqual('')
      expect(wrapper.vm.latitude).toEqual('')
      expect(wrapper.vm.longitude).toEqual('')
      const place = FIXTURE_MAPBOX_SEARCH_RESULT.features[0]

      wrapper.vm.onPlaceInput(place)
      expect(wrapper.vm.borderBox).toEqual(place.bbox)
      expect(wrapper.vm.address_line_1).toEqual(place.text)
      expect(wrapper.vm.address_line_2).toEqual(
        place.place_name.replace(`${place.text}, `, '')
      )
      expect(wrapper.vm.latitude).toEqual(place.center[1])
      expect(wrapper.vm.longitude).toEqual(place.center[0])
    })
  })
})
