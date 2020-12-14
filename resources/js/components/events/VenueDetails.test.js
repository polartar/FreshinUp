import { mount, shallowMount } from '@vue/test-utils'
import * as Stories from '~/components/events/VenueDetails.stories'
import Component, { DEFAULT_LOCATION } from '~/components/events/VenueDetails.vue'
import createLocalVue from 'vue-cli-plugin-freshinup-ui/utils/testing/createLocalVue'
import { FIXTURE_VENUES } from '../../__data__/venues'

describe('events/VenueDetails', () => {
  let localVue

  describe('Snapshots', () => {
    test('Default', async () => {
      const wrapper = mount(Stories.Default())
      await wrapper.vm.$nextTick()
      expect(wrapper.element).toMatchSnapshot()
    })

    test('NotSelected', async () => {
      const wrapper = mount(Stories.NotSelected())
      await wrapper.vm.$nextTick()
      expect(wrapper.element).toMatchSnapshot()
    })

    test('SelectedVenue', async () => {
      const wrapper = mount(Stories.SelectedVenue())
      await wrapper.vm.$nextTick()
      expect(wrapper.element).toMatchSnapshot()
    })
    test('SelectedVenueAndLocation', async () => {
      const wrapper = mount(Stories.SelectedVenueAndLocation())
      await wrapper.vm.$nextTick()
      expect(wrapper.element).toMatchSnapshot()
    })
  })

  describe('Props & Computed', () => {
    beforeEach(() => {
      const vue = createLocalVue({ validation: true })
      localVue = vue.localVue
    })

    test('venues', () => {
      const wrapper = shallowMount(Component)
      expect(wrapper.vm.venues).toHaveLength(0)

      wrapper.setProps({
        venues: FIXTURE_VENUES
      })
      expect(wrapper.vm.venues).toEqual(FIXTURE_VENUES)
    })

    test('locationUuid', () => {
      const wrapper = shallowMount(Component)
      expect(wrapper.vm.locationUuid).toEqual('')

      const locationUuid = FIXTURE_VENUES[0].locations[0].uuid
      wrapper.setProps({
        locationUuid
      })
      expect(wrapper.vm.locationUuid).toEqual(locationUuid)
    })

    test('hasLongText', () => {
      const wrapper = shallowMount(Component)
      wrapper.setData({
        location: {
          details: FIXTURE_VENUES[0].locations[0].details
        },
        locationDetailMaxChar: 300
      })
      expect(wrapper.vm.hasLongText).toBe(false)
      wrapper.setData({
        locationDetailMaxChar: 50
      })
      expect(wrapper.vm.hasLongText).toBe(true)
    })

    test('minLocationDetail', () => {
      const wrapper = shallowMount(Component)
      wrapper.setData({
        location: {
          details: FIXTURE_VENUES[0].locations[0].details
        },
        locationDetailMaxChar: 50
      })
      expect(wrapper.vm.minLocationDetail).toHaveLength(50)
    })

    test('selectedVenueLocations', async () => {
      const wrapper = shallowMount(Component)
      wrapper.setProps({
        venues: []
      })
      expect(wrapper.vm.selectedVenueLocations).toHaveLength(0)

      wrapper.setProps({
        venues: FIXTURE_VENUES
      })
      wrapper.setData({
        venueUuid: FIXTURE_VENUES[0].uuid
      })
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.selectedVenueLocations).toHaveLength(FIXTURE_VENUES[0].locations.length)
    })

    describe('venuesByUuid', () => {
      test('with empty venues', async () => {
        const wrapper = shallowMount(Component)
        wrapper.setProps({
          venues: []
        })
        await wrapper.vm.$nextTick()
        expect(wrapper.vm.venuesByUuid).toMatchObject({})
      })
      test('otherwise', async () => {
        const wrapper = shallowMount(Component)
        wrapper.setProps({
          venues: FIXTURE_VENUES
        })
        await wrapper.vm.$nextTick()
        const expectations = FIXTURE_VENUES.reduce((map, venue) => {
          map[venue.uuid] = venue
          return map
        }, {})
        expect(wrapper.vm.venuesByUuid).toMatchObject(expectations)
      })
    })

    describe('selectedVenue', () => {
      test('with empty venues', async () => {
        const wrapper = shallowMount(Component)
        wrapper.setProps({
          venues: []
        })
        expect(wrapper.vm.selectedVenue).toMatchObject({})
      })
      test('otherwise', async () => {
        const wrapper = shallowMount(Component)
        wrapper.setProps({
          venues: FIXTURE_VENUES
        })
        wrapper.setData({
          venueUuid: FIXTURE_VENUES[1].uuid
        })
        await wrapper.vm.$nextTick()
        expect(wrapper.vm.selectedVenue).toMatchObject(FIXTURE_VENUES[1])
      })
    })

    describe('locationsByUuid', () => {
      test('with empty venues', async () => {
        const wrapper = shallowMount(Component)
        wrapper.setProps({
          venues: []
        })
        expect(wrapper.vm.locationsByUuid).toMatchObject({})
      })
      test('otherwise', async () => {
        const wrapper = shallowMount(Component)
        wrapper.setProps({
          venues: FIXTURE_VENUES
        })
        wrapper.setData({
          venueUuid: FIXTURE_VENUES[1].uuid
        })
        await wrapper.vm.$nextTick()
        const expectations = wrapper.vm.selectedVenueLocations.reduce((map, location) => {
          map[location.uuid] = location
          return map
        }, {})
        expect(wrapper.vm.locationsByUuid).toMatchObject(expectations)
      })
    })
  })

  describe('Methods', () => {
    beforeEach(() => {
      const vue = createLocalVue({ validation: true })
      localVue = vue.localVue
    })

    test('onLocationCleared()', () => {
      const wrapper = shallowMount(Component)
      wrapper.setData({
        location: {
          uuid: 'location123',
          venue_uuid: 'venue123'
        }
      })
      wrapper.vm.onLocationCleared()
      expect(wrapper.vm.location).toMatchObject({ ...DEFAULT_LOCATION, venue_uuid: 'venue123' })
    })

    test('onVenueCleared()', () => {
      const wrapper = shallowMount(Component)
      wrapper.setData({
        location: {
          uuid: 'abc123',
          venue_uuid: 'venue_abc123'
        }
      })
      wrapper.vm.onVenueCleared()
      expect(wrapper.vm.location).toMatchObject(DEFAULT_LOCATION)
    })

    test('onVenueChanged(value)', () => {
      const wrapper = shallowMount(Component)
      wrapper.setData({
        location: {
          uuid: 'abc123',
          venue_uuid: 'venue_abc111'
        }
      })
      wrapper.vm.onVenueChanged('venue_abc222')
      expect(wrapper.vm.location).toMatchObject({ ...DEFAULT_LOCATION, venue_uuid: 'venue_abc222' })
    })

    // TODO somehow this is not working anymore
    test.skip('onLocationChanged(value)', async () => {
      const wrapper = shallowMount(Component)
      wrapper.setData({
        location: FIXTURE_VENUES[0].locations[0]
      })
      wrapper.setProps({
        venues: FIXTURE_VENUES
      })
      wrapper.vm.onLocationChanged(FIXTURE_VENUES[0].locations[1].uuid)
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.location).toMatchObject(FIXTURE_VENUES[0].locations[1])
    })

    test('toggleShowMore()', () => {
      const wrapper = shallowMount(Component, {
        localVue: localVue
      })

      wrapper.setData({
        showMoreActivated: false
      })

      wrapper.vm.toggleShowMore()
      expect(wrapper.vm.showMoreActivated).toBe(true)
      wrapper.vm.toggleShowMore()
      expect(wrapper.vm.showMoreActivated).toBe(false)
    })
    describe('getDirections()', () => {
      test('when empty venue data', async () => {
        const wrapper = shallowMount(Component)
        wrapper.vm.getDirections()
        await wrapper.vm.$nextTick()
        const emitted = wrapper.emitted()
        expect(emitted['get-directions']).toHaveLength(1)
        expect(emitted['get-directions'][0][0]).toEqual(DEFAULT_LOCATION)
      })
      test('when venue data is set', async () => {
        const wrapper = shallowMount(Component)
        const location = FIXTURE_VENUES[0].locations[0]
        wrapper.setData({
          location
        })
        await wrapper.vm.$nextTick()
        wrapper.vm.getDirections()
        await wrapper.vm.$nextTick()
        const emitted = wrapper.emitted()
        expect(emitted['get-directions']).toHaveLength(1)
        expect(emitted['get-directions'][0][0]).toEqual(location)
      })
    })
  })

  describe('watch', () => {
    test('location', async () => {
      const wrapper = shallowMount(Component)
      wrapper.setData({
        location: FIXTURE_VENUES[0].locations[0]
      })
      const newLocation = FIXTURE_VENUES[0].locations[1]
      wrapper.setData({
        location: newLocation
      })
      await wrapper.vm.$nextTick()
      const emitted = wrapper.emitted()
      expect(emitted.input).toBeTruthy()
      expect(emitted.input[0][0]).toMatchObject({
        uuid: newLocation.uuid,
        venue_uuid: newLocation.venue_uuid
      })
    })
  })
})
