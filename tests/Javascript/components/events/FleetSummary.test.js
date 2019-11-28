import { createLocalVue, mount } from '@vue/test-utils'
import Component from '~/components/events/FleetSummary.vue'
import { FIXTURE_EVENT_FLEET_MEMBER_SUMMARY } from 'tests/__data__/fleetSummary'

describe('Event FleetSummary component', () => {
  // Component instance "under test"
  let localVue
  describe('Snapshots', () => {
    test('default', () => {
      localVue = createLocalVue()
      const wrapper = mount(Component, {
        localVue: localVue
      })
      expect(wrapper.element).toMatchSnapshot()
    })
    test('data set', () => {
      localVue = createLocalVue()
      const wrapper = mount(Component, {
        localVue: localVue,
        propsData: {
          ...FIXTURE_EVENT_FLEET_MEMBER_SUMMARY
        }
      })
      expect(wrapper.element).toMatchSnapshot()
    })
  })
})
