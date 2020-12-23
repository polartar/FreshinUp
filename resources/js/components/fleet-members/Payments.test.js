import { mount, shallowMount } from '@vue/test-utils'
import * as Stories from './Payments.stories'

import Component from './Payments.vue'
import { FIXTURE_PAYMENTS } from 'tests/__data__/payments'
import { FIXTURE_PAYMENT_STATUSES } from 'tests/__data__/paymentStatuses'
import { PAYMENT_STATUS } from '../../store/modules/paymentStatuses'

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
        expect(wrapper.vm.processable({ ...item, status_id: 1 })).toBe(true)
        expect(wrapper.vm.processable({ ...item, status_id: 4 })).toBe(true)
      })

      test('when not processable', () => {
        expect(wrapper.vm.processable({ ...item, status_id: 2 })).toBe(false)
        expect(wrapper.vm.processable({ ...item, status_id: 3 })).toBe(false)
        expect(wrapper.vm.processable({ ...item, status_id: 5 })).toBe(false)
      })
    })
    describe('process(item)', () => {
      const item = FIXTURE_PAYMENTS[0]
      test('when processable', () => {
        const processables = [PAYMENT_STATUS.PENDING, PAYMENT_STATUS.FAILED]
        processables.forEach(status => {
          const wrapper = shallowMount(Component)
          wrapper.vm.process({ ...item, status })
          const emitted = wrapper.emitted()['manage-pay']
          expect(emitted).toBeTruthy()
          expect(emitted[0][0]).toMatchObject({ ...item, status })
        })
      })

      test('when not processable', () => {
        const unprocessables = [
          PAYMENT_STATUS.OVERDUE,
          PAYMENT_STATUS.PAID,
          PAYMENT_STATUS.REFUNDED
        ]
        unprocessables.forEach(status => {
          const wrapper = shallowMount(Component)
          wrapper.vm.process({ ...item, status_id: status })
          const emitted = wrapper.emitted()['manage-pay']
          expect(emitted).toBeFalsy()
        })
      })
    })
    describe('manageLabel(item)', () => {
      const wrapper = shallowMount(Component)
      const item = FIXTURE_PAYMENTS[0]
      expect(wrapper.vm.manageLabel({ ...item, status_id: 1 })).toEqual('Pay now')
      expect(wrapper.vm.manageLabel({ ...item, status_id: 2 })).toEqual('')
      expect(wrapper.vm.manageLabel({ ...item, status_id: 3 })).toEqual('')
      expect(wrapper.vm.manageLabel({ ...item, status_id: 4 })).toEqual('Retry')
      expect(wrapper.vm.manageLabel({ ...item, status_id: 5 })).toEqual('')
    })
  })
})
