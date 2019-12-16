import { shallowMount } from '@vue/test-utils'
import createLocalVue from 'vue-cli-plugin-freshinup-ui/utils/testing/createLocalVue'
import Component from '~/components/events/StoreSummary.vue'
import { FIXTURE_EVENT_STORE_SUMMARY } from 'tests/__data__/storeSummary'

describe('Event StoreSummary component', () => {
  // Component instance "under test"
  let localVue
  describe('Snapshots', () => {
    beforeEach(() => {
      const vue = createLocalVue({ validation: true })
      localVue = vue.localVue
    })
    test('required set', () => {
      const wrapper = shallowMount(Component, {
        localVue: localVue,
        propsData: {
          store: FIXTURE_EVENT_STORE_SUMMARY
        }
      })
      expect(wrapper.element).toMatchSnapshot()
    })
  })

  describe('Methods', () => {
    beforeEach(() => {
      const vue = createLocalVue({ validation: true })
      localVue = vue.localVue
    })
    test('viewMemberProfile() emits viewMemberProfile', () => {
      const wrapper = shallowMount(Component, {
        localVue: localVue,
        propsData: {
          store: FIXTURE_EVENT_STORE_SUMMARY
        }
      })
      wrapper.vm.viewMemberProfile()
      expect(wrapper.emitted()['viewMemberProfile']).toBeTruthy()
    })

    test('removeMemberProfile() emits removeMemberProfile', () => {
      const wrapper = shallowMount(Component, {
        localVue: localVue,
        propsData: {
          store: FIXTURE_EVENT_STORE_SUMMARY
        }
      })
      wrapper.vm.removeMemberProfile()
      expect(wrapper.emitted()['removeMemberProfile']).toBeTruthy()
    })
  })
})
