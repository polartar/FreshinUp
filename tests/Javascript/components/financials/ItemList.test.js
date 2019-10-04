import { createLocalVue, shallowMount, mount } from '@vue/test-utils'
import Component from '~/components/financials/ItemList.vue'
import { FIXTURE_ITEMS } from 'tests/__data__/items'

describe('ItemList', () => {
  // Component instance "under test"
  let localVue
  describe('Snapshots', () => {
    test('items set', () => {
      localVue = createLocalVue()
      const wrapper = mount(Component, {
        localVue: localVue,
        propsData: {
          items: FIXTURE_ITEMS
        }
      })
      expect(wrapper.element).toMatchSnapshot()
    })
    test('item is empty', () => {
      localVue = createLocalVue()
      const wrapper = mount(Component, {
        localVue: localVue,
        propsData: {
          items: []
        }
      })
      expect(wrapper.element).toMatchSnapshot()
    })
  })
  describe('Methods', () => {
    beforeEach(() => {
      localVue = createLocalVue()
    })
    test('sum()', () => {
      const wrapper = shallowMount(Component, {
        propsData: {
          items: FIXTURE_ITEMS
        }
      })
      expect(wrapper.vm.sum('total_money')).toBe(5000)
      expect(wrapper.vm.sum('total_discount_money')).toBe(200)
      expect(wrapper.vm.sum('total_tax_money')).toBe(1000)
    })
  })
  describe('Computed', () => {
    beforeEach(() => {
      localVue = createLocalVue()
    })
    test('sumGrossSales()', () => {
      const wrapper = shallowMount(Component, {
        propsData: {
          items: FIXTURE_ITEMS
        }
      })
      expect(wrapper.vm.sumGrossSales).toBe(5000)
    })
    test('sumDiscount()', () => {
      const wrapper = shallowMount(Component, {
        propsData: {
          items: FIXTURE_ITEMS
        }
      })
      expect(wrapper.vm.sumDiscount).toBe(200)
    })
    test('sumTaxes()', () => {
      const wrapper = shallowMount(Component, {
        propsData: {
          items: FIXTURE_ITEMS
        }
      })
      expect(wrapper.vm.sumTaxes).toBe(1000)
    })
    test('sumNet()', () => {
      const wrapper = shallowMount(Component, {
        propsData: {
          items: FIXTURE_ITEMS
        }
      })
      expect(wrapper.vm.sumNet).toBe(4000)
    })
  })
})
