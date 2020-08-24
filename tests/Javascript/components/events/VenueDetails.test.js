import { mount, shallowMount } from '@vue/test-utils'
import * as Stories from '~/components/events/VenueDetails.stories'
import Component from '~/components/events/VenueDetails.vue'
import createLocalVue from 'vue-cli-plugin-freshinup-ui/utils/testing/createLocalVue'
import { FIXTURE_VENUE_ADDITIONAL_DATA, FIXTURE_VENUES } from '../../__data__/venues'

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

  describe('Computed', () => {
    beforeEach(() => {
      const vue = createLocalVue({ validation: true })
      localVue = vue.localVue
    })

    test('has long text', () => {
      const wrapper = shallowMount(Component, {
        propsData: {
          venue: FIXTURE_VENUE_ADDITIONAL_DATA
        }
      })

      wrapper.setData({
        locationDetailMaxChar: 300
      })

      expect(wrapper.vm.hasLongText).toBeFalsy()

      wrapper.setData({
        locationDetailMaxChar: 50
      })

      expect(wrapper.vm.hasLongText).toBeTruthy()
    })

    test('minimum location detail length', () => {
      const wrapper = shallowMount(Component, {
        propsData: {
          venue: FIXTURE_VENUE_ADDITIONAL_DATA
        }
      })

      wrapper.setData({
        locationDetailMaxChar: 50
      })

      expect(wrapper.vm.minLocationDetail).toHaveLength(50)
    })

    test('locations', () => {
      const wrapper = shallowMount(Component, {
        propsData: {
          venue: FIXTURE_VENUE_ADDITIONAL_DATA,
          venues: FIXTURE_VENUES
        }
      })

      wrapper.setData({
        venueData: {
          venue: 'acb123'
        }
      })

      expect(wrapper.vm.locations).toHaveLength(1)
    })
  })

  describe('Methods', () => {
    beforeEach(() => {
      const vue = createLocalVue({ validation: true })
      localVue = vue.localVue
    })

    test('toggle show more', () => {
      const wrapper = shallowMount(Component, {
        localVue: localVue
      })

      wrapper.setData({
        showMoreActivated: false
      })

      wrapper.vm.toggleShowMore()
      expect(wrapper.vm.showMoreActivated).toBeTruthy()
      wrapper.vm.toggleShowMore()
      expect(wrapper.vm.showMoreActivated).toBeFalsy()
    })
  })
})
