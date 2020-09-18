import { mount } from '@vue/test-utils'
import Component from '~/components/venues/VenueList.vue'
import * as Stories from '~/components/venues/VenueList.stories'
import { FIXTURE_VENUE_STATUSES, FIXTURE_VENUES } from '../../__data__/venues'

describe('components/venues/VenueList', () => {
  describe('Snapshots', () => {
    test('Empty', async () => {
      const wrapper = mount(Stories.Empty())
      await wrapper.vm.$nextTick()
      expect(wrapper.element).toMatchSnapshot()
    })
    test('IsLoading', async () => {
      const wrapper = mount(Stories.IsLoading())
      await wrapper.vm.$nextTick()
      expect(wrapper.element).toMatchSnapshot()
    })
    test('Populated', async () => {
      const wrapper = mount(Stories.Populated())
      await wrapper.vm.$nextTick()
      expect(wrapper.element).toMatchSnapshot()
    })
  })

  describe('Props & Computed', () => {
    test('isLoading', async () => {
      const wrapper = mount(Component)
      expect(wrapper.vm.isLoading).toBe(false)

      wrapper.setProps({
        isLoading: true
      })
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.isLoading).toBe(true)
    })
    test('items', async () => {
      const wrapper = mount(Component)
      expect(wrapper.vm.items).toHaveLength(0)

      wrapper.setProps({
        items: FIXTURE_VENUES
      })
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.items).toMatchObject(FIXTURE_VENUES)
    })
    test('statuses', async () => {
      const wrapper = mount(Component)
      expect(wrapper.vm.statuses).toHaveLength(0)

      wrapper.setProps({
        statuses: FIXTURE_VENUE_STATUSES
      })
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.statuses).toMatchObject(FIXTURE_VENUE_STATUSES)
    })
  })
})
