import { createLocalVue, shallowMount, mount } from '@vue/test-utils'
import Component from '~/components/financials/TransactionList.vue'
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
    test('transactions set', () => {
      localVue = createLocalVue()
      const wrapper = mount(Component, {
        localVue: localVue,
        propsData: {
          transactions: FIXTURE_TRANSACTIONS
        }
      })
      expect(wrapper.element).toMatchSnapshot()
    })
    test('transaction empty', () => {
      localVue = createLocalVue()
      const wrapper = mount(Component, {
        localVue: localVue,
        propsData: {
          transactions: []
        }
      })
      expect(wrapper.element).toMatchSnapshot()
    })
    test('partial visibility selected', () => {
      localVue = createLocalVue()
      const wrapper = mount(Component, {
        localVue: localVue,
        propsData: {
          transactions: FIXTURE_TRANSACTIONS,
          dataVisibility: dataVisibilityPartial
        }
      })
      expect(wrapper.element).toMatchSnapshot()
    })
    test('all visibility selected', () => {
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
    test('formatTransactionDate()', () => {
      const wrapper = shallowMount(Component, {
        propsData: {
          transactions: FIXTURE_TRANSACTIONS
        }
      })
      expect(wrapper.vm.formatTransactionDate(FIXTURE_TRANSACTIONS[0].square_created_at)).toBe('Jan 22, 2018 - 07:22pm')
    })
    test('viewDetails()', () => {
      const wrapper = shallowMount(Component, {
        propsData: {
          transactions: FIXTURE_TRANSACTIONS
        }
      })
      expect(wrapper.vm.viewDetails(FIXTURE_TRANSACTIONS[0].uuid)).toBe('/admin/financials/transactions/' + FIXTURE_TRANSACTIONS[0].uuid)
    })
    test('formatItems()', () => {
      const wrapper = shallowMount(Component, {
        propsData: {
          transactions: FIXTURE_TRANSACTIONS
        }
      })
      expect(wrapper.vm.formatItems(FIXTURE_TRANSACTIONS[0].items)).toBe('3 Hamburger, 2 Coke, 2 Beer')
    })
    test('formatEventTags()', () => {
      const wrapper = shallowMount(Component, {
        propsData: {
          transactions: FIXTURE_TRANSACTIONS
        }
      })
      expect(wrapper.vm.formatEventTags(FIXTURE_TRANSACTIONS[0].event.event_tags)).toBe('Tag 1, Tag 2')
    })
  })
  describe('Computed', () => {
    beforeEach(() => {
      localVue = createLocalVue()
    })
    test('headers()', () => {
      const wrapper = shallowMount(Component, {
        propsData: {
          transactions: FIXTURE_TRANSACTIONS
        }
      })
      expect(wrapper.vm.headers).toEqual([{ 'align': 'left', 'class': 'font-weight-bold', 'sortable': false, 'text': 'Event / Location', 'value': 'event_location' }, { 'align': 'left', 'class': 'font-weight-bold', 'sortable': false, 'text': 'Creation Date', 'value': 'square_created_at' }, { 'align': 'left', 'class': 'font-weight-bold', 'sortable': false, 'text': 'Items', 'value': 'items' }, { 'align': 'left', 'class': 'font-weight-bold', 'sortable': false, 'text': 'Total', 'value': 'total_money' }, { 'align': 'left', 'class': 'font-weight-bold', 'sortable': false, 'text': 'Tax Total', 'value': 'total_tax_money' }, { 'align': 'left', 'class': 'font-weight-bold', 'sortable': false, 'text': 'Total Discount', 'value': 'total_discount_money' }, { 'sortable': false, 'value': 'action' }])
    })
    test('dataVisibilityComputed()', () => {
      const wrapper = shallowMount(Component, {
        propsData: {
          transactions: FIXTURE_TRANSACTIONS
        }
      })
      expect(wrapper.vm.dataVisibilityComputed).toEqual(['event_location', 'square_created_at', 'items', 'total_money', 'total_tax_money', 'total_discount_money'])
    })
  })
})
