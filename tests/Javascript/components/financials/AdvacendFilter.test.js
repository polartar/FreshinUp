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
    test('clearAllEvents() set to null all event related filters', () => {
      const wrapper = shallowMount(Component, {
        propsData: {
          advancedFilters: {
            event_tag_uuid: 1,
            location_uuid: 1,
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
      expect(wrapper.vm.filters.event_tag_uuid).toBe(1)
      expect(wrapper.vm.filters.location_uuid).toBe(1)
      wrapper.vm.clearAllEvents()
      expect(wrapper.vm.filters.event_tag_uuid).toBeNull()
      expect(wrapper.vm.filters.location_uuid).toBeNull()
    })
    test('clearAllCustomers() set to null all customer related filters', () => {
      const wrapper = shallowMount(Component, {
        propsData: {
          advancedFilters: {
            event_tag_uuid: null,
            location_uuid: null,
            customer_uuid: 1,
            staff_uuid: 1,
            device_uuid: 1,
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
      expect(wrapper.vm.filters.customer_uuid).toBe(1)
      expect(wrapper.vm.filters.staff_uuid).toBe(1)
      expect(wrapper.vm.filters.device_uuid).toBe(1)
      wrapper.vm.clearAllCustomers()
      expect(wrapper.vm.filters.customer_uuid).toBeNull()
      expect(wrapper.vm.filters.staff_uuid).toBeNull()
      expect(wrapper.vm.filters.device_uuid).toBeNull()
    })
    test('clearAllItems() set to null all item related filters', () => {
      const wrapper = shallowMount(Component, {
        propsData: {
          advancedFilters: {
            event_tag_uuid: null,
            location_uuid: null,
            customer_uuid: null,
            staff_uuid: null,
            device_uuid: null,
            category_uuid: 1,
            item_uuid: 1,
            min_price: 1,
            max_price: 10,
            payment_type_uuid: null,
            transaction_uuid: null,
            payment_uuid: null
          },
          paymentTypes: FIXTURE_PAYMENT_TYPES,
          devices: FIXTURE_DEVICES
        }
      })
      expect(wrapper.vm.filters.category_uuid).toBe(1)
      expect(wrapper.vm.filters.item_uuid).toBe(1)
      expect(wrapper.vm.filters.min_price).toBe(1)
      expect(wrapper.vm.filters.max_price).toBe(10)
      wrapper.vm.clearAllItems()
      expect(wrapper.vm.filters.category_uuid).toBeNull()
      expect(wrapper.vm.filters.item_uuid).toBeNull()
      expect(wrapper.vm.filters.min_price).toBeNull()
      expect(wrapper.vm.filters.max_price).toBeNull()
    })
    test('clearAllPayments() set to null all payment related filters', () => {
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
            payment_type_uuid: 1,
            transaction_uuid: 1,
            payment_uuid: 1
          },
          paymentTypes: FIXTURE_PAYMENT_TYPES,
          devices: FIXTURE_DEVICES
        }
      })
      expect(wrapper.vm.filters.payment_type_uuid).toBe(1)
      expect(wrapper.vm.filters.transaction_uuid).toBe(1)
      expect(wrapper.vm.filters.payment_uuid).toBe(1)
      wrapper.vm.clearAllPayments()
      expect(wrapper.vm.filters.payment_type_uuid).toBeNull()
      expect(wrapper.vm.filters.transaction_uuid).toBeNull()
      expect(wrapper.vm.filters.payment_uuid).toBeNull()
    })
    test('clearAll() set to null all filters', () => {
      const wrapper = shallowMount(Component, {
        propsData: {
          advancedFilters: {
            event_tag_uuid: 1,
            location_uuid: 1,
            customer_uuid: 1,
            staff_uuid: 1,
            device_uuid: 1,
            category_uuid: 1,
            item_uuid: 1,
            min_price: 1,
            max_price: 10,
            payment_type_uuid: 1,
            transaction_uuid: 1,
            payment_uuid: 1
          },
          paymentTypes: FIXTURE_PAYMENT_TYPES,
          devices: FIXTURE_DEVICES
        }
      })
      expect(wrapper.vm.filters).toEqual({
        event_tag_uuid: 1,
        location_uuid: 1,
        customer_uuid: 1,
        staff_uuid: 1,
        device_uuid: 1,
        category_uuid: 1,
        item_uuid: 1,
        min_price: 1,
        max_price: 10,
        payment_type_uuid: 1,
        transaction_uuid: 1,
        payment_uuid: 1
      })
      wrapper.vm.clearAll()
      expect(wrapper.vm.filters).toEqual({
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
      })
    })
  })
})
