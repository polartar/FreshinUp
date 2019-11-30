import { shallowMount } from '@vue/test-utils'
import { createLocalVue } from 'fresh-bus/tests/utils'
import Component from '~/components/events/FinancialSummary.vue'
import { FIXTURE_EVENT_FINANCIAL_SUMMARY } from 'tests/__data__/eventFinancialSummary'

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
          financial: FIXTURE_EVENT_FINANCIAL_SUMMARY
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
          financial: FIXTURE_EVENT_FINANCIAL_SUMMARY
        }
      })
      wrapper.vm.onButtonClick()
      expect(wrapper.emitted()['onButtonClick']).toBeTruthy()
    })
  })
})
