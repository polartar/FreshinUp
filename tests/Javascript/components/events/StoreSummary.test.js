import { shallowMount } from '@vue/test-utils'
import { createLocalVue } from 'fresh-bus/tests/utils'
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
    test('onButtonClick() emits onButtonClick', () => {
      const wrapper = shallowMount(Component, {
        localVue: localVue,
        propsData: {
          store: FIXTURE_EVENT_STORE_SUMMARY
        }
      })
      wrapper.vm.onButtonClick()
      expect(wrapper.emitted()['onButtonClick']).toBeTruthy()
    })
    test('onRemoveClick() emits onRemoveClick', () => {
      const wrapper = shallowMount(Component, {
        localVue: localVue,
        propsData: {
          store: FIXTURE_EVENT_STORE_SUMMARY
        }
      })
      wrapper.vm.onRemoveClick()
      expect(wrapper.emitted()['onRemoveClick']).toBeTruthy()
    })
  })
})
