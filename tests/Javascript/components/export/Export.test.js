import { createLocalVue, shallowMount, mount } from '@vue/test-utils'
import Component from '~/components/export/Export.vue'
import { FIXTURE_TRANSACTIONS } from 'tests/__data__/transactions'

const dataVisibilityPartial = [
  'event_location',
  'square_created_at',
  'total_money',
  'total_tax_money',
  'total_discount_money'
]

const dataVisibility = [
  'event_location',
  'square_created_at',
  'square_updated_at',
  'total_money',
  'total_tax_money',
  'total_discount_money',
  'total_service_charge_money',
  'items',
  'event_tags',
  'square_id',
  'store',
  'store_square_id',
  'host',
  'supplier',
  'customer',
  'customer_square_id',
  'customer_reference_id'
]

describe('TransactionList', () => {
  // Component instance "under test"
  let localVue
  describe('Snapshots', () => {
    test('transactions and data visibility set', () => {
      localVue = createLocalVue()
      const wrapper = mount(Component, {
        localVue: localVue,
        propsData: {
          transactions: FIXTURE_TRANSACTIONS,
          dataVisibility: dataVisibility
        }
      })
      expect(wrapper.element).toMatchSnapshot()
    })
  })
  describe('Methods', () => {
    beforeEach(() => {
      localVue = createLocalVue()
    })
    test('close() emits close', () => {
      const wrapper = shallowMount(Component, {
        propsData: {
          transactions: FIXTURE_TRANSACTIONS,
          dataVisibility: dataVisibility
        }
      })
      wrapper.vm.close()
      expect(wrapper.emitted().close).toBeTruthy()
    })
    test('formatTransactionDate()', () => {
      const wrapper = shallowMount(Component, {
        propsData: {
          transactions: FIXTURE_TRANSACTIONS,
          dataVisibility: dataVisibility

        }
      })
      expect(wrapper.vm.formatTransactionDate(FIXTURE_TRANSACTIONS[0].square_created_at)).toBe('Jan 22, 2018 - 07:22pm')
    })
    test('formatItems()', () => {
      const wrapper = shallowMount(Component, {
        propsData: {
          transactions: FIXTURE_TRANSACTIONS,
          dataVisibility: dataVisibility

        }
      })
      expect(wrapper.vm.formatItems(FIXTURE_TRANSACTIONS[0].items)).toBe('3 Hamburger, 2 Coke, 2 Beer')
    })
    test('formatEventTags()', () => {
      const wrapper = shallowMount(Component, {
        propsData: {
          transactions: FIXTURE_TRANSACTIONS,
          dataVisibility: dataVisibility

        }
      })
      expect(wrapper.vm.formatEventTags(FIXTURE_TRANSACTIONS[0].event.event_tags)).toBe('Tag 1, Tag 2')
    })
    test('attributes()', () => {
      const wrapper = shallowMount(Component, {
        propsData: {
          transactions: FIXTURE_TRANSACTIONS,
          dataVisibility: dataVisibilityPartial

        }
      })
      expect(wrapper.vm.attributes(FIXTURE_TRANSACTIONS)).toEqual([['Burger fest / New York', 'Jan 22, 2018 - 07:22pm', '$100.00', '$20.00', '$0.00'], ['Burger fest / New York', 'Jan 22, 2018 - 07:22pm', '$100.00', '$20.00', '$0.00'], ['Song festival / New York', 'Jan 22, 2018 - 07:22pm', '$100.00', '$20.00', '$0.00'], ['Burger fest / New York', 'Jan 22, 2018 - 07:22pm', '$100.00', '$20.00', '$0.00'], ['Burger fest / New York', 'Jan 22, 2018 - 07:22pm', '$100.00', '$20.00', '$0.00'], ['Burger fest / New York', 'Jan 22, 2018 - 07:22pm', '$100.00', '$20.00', '$0.00'], ['Art Basel / New York', 'Jan 22, 2018 - 07:22pm', '$100.00', '$20.00', '$0.00'], ['Art Basel / New York', 'Jan 22, 2018 - 07:22pm', '$100.00', '$20.00', '$0.00']])
    })
  })
  describe('Computed', () => {
    beforeEach(() => {
      localVue = createLocalVue()
    })
    test('headerArray()', () => {
      const wrapper = shallowMount(Component, {
        propsData: {
          transactions: FIXTURE_TRANSACTIONS,
          dataVisibility: dataVisibility

        }
      })
      expect(wrapper.vm.headerArray).toEqual(['Event / Venue / Location', 'Creation Date', 'Update Date', 'Total', 'Tax Total', 'Total Discount', 'Total Service Charge', 'Items', 'Event Tags', 'Square ID', 'Fleet member', 'Fleet Member Square ID', 'Customer Company', 'Supplier', 'Customer name', 'Customer Square ID', 'Customer Reference ID'])
    })
    test('dataVisibilityComputed()', () => {
      const wrapper = shallowMount(Component, {
        propsData: {
          transactions: FIXTURE_TRANSACTIONS,
          dataVisibility: dataVisibility
        }
      })
      expect(wrapper.vm.dataVisibilityComputed).toEqual(['event_location', 'square_created_at', 'square_updated_at', 'total_money', 'total_tax_money', 'total_discount_money', 'total_service_charge_money', 'items', 'event_tags', 'square_id', 'store', 'store_square_id', 'host', 'supplier', 'customer', 'customer_square_id', 'customer_reference_id'])
    })
    test('downloadAttribute() default is export.csv', () => {
      const wrapper = shallowMount(Component, {
        propsData: {
          transactions: FIXTURE_TRANSACTIONS,
          dataVisibility: dataVisibility

        }
      })
      expect(wrapper.vm.downloadAttribute).toBe('export.csv')
    })
    test('downloadAttribute() is export.pdf if selected_type is pdf', () => {
      const wrapper = shallowMount(Component, {
        propsData: {
          transactions: FIXTURE_TRANSACTIONS,
          dataVisibility: dataVisibility
        }
      })
      wrapper.vm.selected_type = 'pdf'
      expect(wrapper.vm.downloadAttribute).toBe('export.pdf')
    })
  })
})
