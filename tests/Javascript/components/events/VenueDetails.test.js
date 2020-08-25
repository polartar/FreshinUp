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

    test('With data', async () => {
      const wrapper = mount(Stories.WithData())
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

    test('locations', async () => {
      const wrapper = shallowMount(Component)
      wrapper.setProps({
        venues: []
      })
      expect(wrapper.vm.locations).toHaveLength(0)

      wrapper.setProps({
        venues: FIXTURE_VENUES
      })
      wrapper.setData({
        venueUuid: FIXTURE_VENUES[1].uuid
      })
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.locations).toHaveLength(FIXTURE_VENUES[1].locations.length)
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
          uuid: 'abc123'
        }
      })
      wrapper.vm.onLocationCleared()
      expect(wrapper.vm.location).toMatchObject(DEFAULT_LOCATION)
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
      expect(wrapper.vm.location.venue_uuid).toBeNull()
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

    test('onLocationChanged(value)', async () => {
      const wrapper = shallowMount(Component)
      wrapper.setData({
        location: FIXTURE_VENUES[0].locations[0],
        venues: FIXTURE_VENUES
      })
      wrapper.vm.onLocationChanged(FIXTURE_VENUES[0].locations[1].uuid)
      await wrapper.vm.$nextTick()
      await wrapper.vm.$nextTick()
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
})
