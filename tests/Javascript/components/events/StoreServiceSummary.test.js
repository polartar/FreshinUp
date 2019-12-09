import { shallowMount } from '@vue/test-utils'
import { createLocalVue } from 'fresh-bus/tests/utils'
import Component from '~/components/events/StoreServiceSummary.vue'
import { FIXTURE_STORE_SERVICES } from 'tests/__data__/storeServiceSummary'

describe('Event StoreServiceSummary component', () => {
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
          service: FIXTURE_STORE_SERVICES[0]
        }
      })
      expect(wrapper.element).toMatchSnapshot()
    })
    test('edit state ', () => {
      const wrapper = shallowMount(Component, {
        localVue: localVue,
        propsData: {
          service: FIXTURE_STORE_SERVICES[0]
        }
      })
      wrapper.vm.edit()
      expect(wrapper.element).toMatchSnapshot()
    })
    test('cancel state ', () => {
      const wrapper = shallowMount(Component, {
        localVue: localVue,
        propsData: {
          service: FIXTURE_STORE_SERVICES[0]
        }
      })
      wrapper.vm.cancel()
      expect(wrapper.element).toMatchSnapshot()
    })
  })

  describe('Methods', () => {
    beforeEach(() => {
      const vue = createLocalVue({ validation: true })
      localVue = vue.localVue
    })
    test('viewContract() emits viewContract', () => {
      const wrapper = shallowMount(Component, {
        localVue: localVue,
        propsData: {
          service: FIXTURE_STORE_SERVICES[0]
        }
      })
      wrapper.vm.viewContract()
      expect(wrapper.emitted()['viewContract']).toBeTruthy()
    })

    test('save() emits saved data', () => {
      const wrapper = shallowMount(Component, {
        localVue: localVue,
        propsData: {
          service: FIXTURE_STORE_SERVICES[0]
        }
      })
      wrapper.vm.save()
      expect(wrapper.emitted().save).toBeTruthy()
      expect(wrapper.emitted().save).toHaveLength(1)
      expect(wrapper.emitted().save[0]).toEqual([FIXTURE_STORE_SERVICES[0]])
    })

    test('getCommissionValue return correctly format percentage', () => {
      const wrapper = shallowMount(Component, {
        localVue: localVue,
        propsData: {
          service: FIXTURE_STORE_SERVICES[0]
        }
      })
      expect(wrapper.vm.getCommissionValue()).toEqual('30 %')
    })

    test('getCommissionValue return correctly format dollar', () => {
      const wrapper = shallowMount(Component, {
        localVue: localVue,
        propsData: {
          service: FIXTURE_STORE_SERVICES[1]
        }
      })
      expect(wrapper.vm.getCommissionValue()).toEqual('$ 40')
    })
  })
})
