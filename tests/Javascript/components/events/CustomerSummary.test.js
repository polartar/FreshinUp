import { shallowMount } from '@vue/test-utils'
import createLocalVue from 'vue-cli-plugin-freshinup-ui/utils/testing/createLocalVue'
import Component from '~/components/events/CustomerSummary.vue'
import { FIXTURE_EVENT_CUSTOMER_SUMMARY } from 'tests/__data__/customerSummary'

describe('Event CustomerSummary component', () => {
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
          customer: FIXTURE_EVENT_CUSTOMER_SUMMARY
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
    test('onButtonClick() emits onButtonClick', () => {
      const wrapper = shallowMount(Component, {
        localVue: localVue,
        propsData: {
          customer: FIXTURE_EVENT_CUSTOMER_SUMMARY
        }
      })
      wrapper.vm.onButtonClick()
      expect(wrapper.emitted()['onButtonClick']).toBeTruthy()
    })
  })
})
