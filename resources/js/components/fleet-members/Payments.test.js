import { mount, shallowMount } from '@vue/test-utils'
import * as Stories from './Payments.stories'

import Component from './Payments.vue'
import { FIXTURE_PAYMENTS } from 'tests/__data__/payments'
import { FIXTURE_PAYMENT_STATUSES } from 'tests/__data__/paymentStatuses'

describe('fleet-members/Payments', () => {
  describe('Snapshots', () => {
    test('Default', async () => {
      const wrapper = mount(Stories.Default())
      await wrapper.vm.$nextTick()
      expect(wrapper.element).toMatchSnapshot()
    })
    test('Loading', async () => {
      const wrapper = mount(Stories.Loading())
      await wrapper.vm.$nextTick()
      expect(wrapper.element).toMatchSnapshot()
    })
    test('Populated', async () => {
      const wrapper = mount(Stories.Populated())
      await wrapper.vm.$nextTick()
      expect(wrapper.element).toMatchSnapshot()
    })
  })

  describe('Props & computed', () => {
    test('isLoading', async () => {
      const wrapper = shallowMount(Component)
      expect(wrapper.vm.isLoading).toBe(false)

      wrapper.setProps({
        isLoading: true
      })
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.isLoading).toBe(true)
    })
    test('items', async () => {
      const wrapper = shallowMount(Component)
      expect(wrapper.vm.items).toHaveLength(0)

      wrapper.setProps({
        items: FIXTURE_PAYMENTS
      })
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.items).toMatchObject(FIXTURE_PAYMENTS)
    })
    test('statuses', async () => {
      const wrapper = shallowMount(Component)
      expect(wrapper.vm.statuses).toHaveLength(0)

      wrapper.setProps({
        statuses: FIXTURE_PAYMENT_STATUSES
      })
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.statuses).toMatchObject(FIXTURE_PAYMENT_STATUSES)
    })
    test('dialog', async () => {
      const wrapper = shallowMount(Component)
      expect(wrapper.vm.dialog).toBe(false)

      wrapper.setProps({
        dialog: true
      })
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.dialog).toBe(true)
    })
  })

  describe('Methods', () => {
    describe('processable(item)', () => {
      const wrapper = shallowMount(Component)
      const item = FIXTURE_PAYMENTS[0]
      test('when processable', () => {
        expect(wrapper.vm.processable({ ...item, status: 1 })).toBe(true)
        expect(wrapper.vm.processable({ ...item, status: 3 })).toBe(true)
      })

      test('when not processable', () => {
        expect(wrapper.vm.processable({ ...item, status: 2 })).toBe(false)
        expect(wrapper.vm.processable({ ...item, status: 4 })).toBe(false)
        expect(wrapper.vm.processable({ ...item, status: 5 })).toBe(false)
      })
    })
    describe('manageLabel(item)', () => {
      const wrapper = shallowMount(Component)
      const item = FIXTURE_PAYMENTS[0]
      expect(wrapper.vm.manageLabel({ ...item, status: 1 })).toEqual('Pay now')
      expect(wrapper.vm.manageLabel({ ...item, status: 2 })).toEqual('')
      expect(wrapper.vm.manageLabel({ ...item, status: 3 })).toEqual('Retry')
      expect(wrapper.vm.manageLabel({ ...item, status: 4 })).toEqual('')
      expect(wrapper.vm.manageLabel({ ...item, status: 5 })).toEqual('')
    })
  })
})
