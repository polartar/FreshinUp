import { mount } from '@vue/test-utils'
import Component from '~/components/venues/VenueList.vue'
import * as Stories from '~/components/venues/VenueList.stories'
import { FIXTURE_VENUE_STATUSES, FIXTURE_VENUES } from '../../__data__/venues'

describe('components/venues/VenueList', () => {
  describe('Snapshots', () => {
    test('Empty', () => {
      const wrapper = mount(Stories.Empty())
      expect(wrapper.element).toMatchSnapshot()
    })
    test('Populated', () => {
      const wrapper = mount(Stories.Populated())
      expect(wrapper.element).toMatchSnapshot()
    })
  })

  describe('Props & Computed', () => {
    test('statuses', async () => {
      const wrapper = mount(Component)
      expect(wrapper.vm.statuses).toHaveLength(0)

      wrapper.setProps({
        statuses: FIXTURE_VENUE_STATUSES
      })
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.statuses).toMatchObject(FIXTURE_VENUE_STATUSES)
    })
    test('venues', async () => {
      const wrapper = mount(Component)
      expect(wrapper.vm.venues).toHaveLength(0)

      wrapper.setProps({
        venues: FIXTURE_VENUES
      })
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.venues).toMatchObject(FIXTURE_VENUES)
    })
  })
})
