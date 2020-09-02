import { shallowMount } from '@vue/test-utils'
import MapValueKeysToData from '../../../resources/js/mixins/MapValueKeysToData'
import pick from 'lodash/pick'

const FIXTURES = {
  FINANCIAL: {
    hold_back: 8483,
    bonus_cash: 325.51,
    dealer_fee: 574.69,
    dealer_trade_fee: 438.23,
    misc_cost: 151.74,
    payment: 2815,
    reconciled: true,
    notes: `mbled it to make a type specimen book. It has survived not only five centuries,
      but also the leap into electronic typesetting,
      remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset
      sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like
      Aldus PageMaker including versions of Lore
    `
  }
}

describe('Mixin MapValueKeysToData', () => {
  describe('Props', () => {
    test('prop value changes data matching keys', async () => {
      const MockComponent = {
        render () {},
        data () {
          return {
            name: null,
            is_secured: false
          }
        },
        mixins: [MapValueKeysToData]
      }
      const wrapper = shallowMount(MockComponent, {
        propsData: {
          value: {
            name: null
          }
        }
      })
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.name).toBeNull()
      wrapper.setProps({
        value: {
          name: 'Jim Kirk',
          is_secured: true
        }
      })
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.name).toEqual('Jim Kirk')
      expect(wrapper.vm.is_secured).toEqual(true)
    })
  })
  describe('Methods', () => {
    const MockComponent = {
      render () {},
      data () {
        return {
          dealer_trade_fee: 0
        }
      },
      mixins: [MapValueKeysToData]
    }
    test('mapValueKeysToData(data)', async () => {
      const wrapper = shallowMount(MockComponent)
      wrapper.vm.mapValueKeysToData({})
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.dealer_trade_fee).toEqual(0)
      expect(wrapper.vm.hold_back).not.toBeDefined()
      expect(wrapper.vm.bonus_cash).not.toBeDefined()
      expect(wrapper.vm.dealer_fee).not.toBeDefined()
      expect(wrapper.vm.misc_cost).not.toBeDefined()
      expect(wrapper.vm.desk_payment).not.toBeDefined()
      expect(wrapper.vm.fi_declined).not.toBeDefined()

      wrapper.vm.mapValueKeysToData(FIXTURES.FINANCIAL)
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.hold_back).toEqual(FIXTURES.FINANCIAL.hold_back)
      expect(wrapper.vm.bonus_cash).toEqual(FIXTURES.FINANCIAL.bonus_cash)
      expect(wrapper.vm.dealer_fee).toEqual(FIXTURES.FINANCIAL.dealer_fee)
      expect(wrapper.vm.dealer_trade_fee).toEqual(FIXTURES.FINANCIAL.dealer_trade_fee)
      expect(wrapper.vm.misc_cost).toEqual(FIXTURES.FINANCIAL.misc_cost)
      expect(wrapper.vm.desk_payment).toEqual(FIXTURES.FINANCIAL.desk_payment)
      expect(wrapper.vm.fi_declined).toEqual(FIXTURES.FINANCIAL.fi_declined)
    })
    test('save()', async () => {
      const wrapper = shallowMount(MockComponent, {
        propsData: {
          value: FIXTURES.FINANCIAL
        }
      })
      await wrapper.vm.$nextTick()
      wrapper.vm.save()
      const payload = pick(wrapper.vm, [
        'hold_back',
        'bonus_cash',
        'dealer_fee',
        'dealer_trade_fee',
        'misc_cost',
        'desk_payment',
        'fi_declined'
      ])
      const [emittedPayload] = wrapper.emitted('input')
      expect(emittedPayload[0]).toMatchObject(payload)
    })
  })
})
