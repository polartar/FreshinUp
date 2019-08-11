import { createLocalVue, shallowMount, mount } from '@vue/test-utils'
import Component from '~/components/financials/AdvancedFilter.vue'
import { FIXTURE_PAYMENT_TYPES } from 'tests/__data__/paymentTypes'
import { FIXTURE_DEVICES } from 'tests/__data__/devices'

describe('BasicFilter', () => {
  // Component instance "under test"
  let localVue
  describe('Snapshots', () => {
    test('defaults', () => {
      localVue = createLocalVue()
      const wrapper = mount(Component, {
        localVue: localVue,
        propsData: {
          paymentTypes: FIXTURE_PAYMENT_TYPES,
          devices: FIXTURE_DEVICES
        }
      })
      expect(wrapper.element).toMatchSnapshot()
    })
  })
  describe('Methods', () => {
    beforeEach(() => {
      localVue = createLocalVue()
    })
    test('select functions change filters', () => {
      const wrapper = shallowMount(Component, {
        propsData: {
          advancedFilters: {
            event_tag_uuid: null,
            location_uuid: null,
            customer_uuid: null,
            staff_uuid: null,
            device_uuid: null,
            category_uuid: null,
            item_uuid: null,
            min_price: null,
            max_price: null,
            payment_type_uuid: null,
            transaction_uuid: null,
            payment_uuid: null
          },
          paymentTypes: FIXTURE_PAYMENT_TYPES,
          devices: FIXTURE_DEVICES
        }
      })
      wrapper.vm.setValue({ uuid: 1 }, 'event_tag_uuid')
      expect(wrapper.vm.filters.event_tag_uuid).toBe(1)
    })
    test('close() emits close', () => {
      const wrapper = shallowMount(Component, {
        propsData: {
          paymentTypes: FIXTURE_PAYMENT_TYPES,
          devices: FIXTURE_DEVICES
        }
      })
      wrapper.vm.close()
      expect(wrapper.emitted().close).toBeTruthy()
    })
    test('createSelectables() return selectable list', () => {
      const wrapper = shallowMount(Component, {
        propsData: {
          paymentTypes: FIXTURE_PAYMENT_TYPES,
          devices: FIXTURE_DEVICES
        }
      })
      expect(wrapper.vm.createSelectables(FIXTURE_PAYMENT_TYPES)).toStrictEqual([
        { value: null, text: 'All' },
        { value: 1, text: 'Credit Card' },
        { value: 2, text: 'Money Transfer' },
        { value: 3, text: 'Google Pay' },
        { value: 4, text: 'Apple Pay' }
      ])
    })
  })
})
