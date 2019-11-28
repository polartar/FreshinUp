import { createLocalVue, mount } from '@vue/test-utils'
import Component from '~/components/events/FleetMemberSummary.vue'
import { FIXTURE_EVENT_FLEET_MEMBER_SUMMARY } from 'tests/__data__/fleetMemberSummary'

describe('Event FleetSummary component', () => {
  // Component instance "under test"
  let localVue
  describe('Snapshots', () => {
    test('required set', () => {
      localVue = createLocalVue()
      const wrapper = mount(Component, {
        localVue: localVue,
        propsData: {
          member: FIXTURE_EVENT_FLEET_MEMBER_SUMMARY
        }
      })
      expect(wrapper.element).toMatchSnapshot()
    })
  })
})
