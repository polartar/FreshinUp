import { createLocalVue, mount, shallowMount } from '@vue/test-utils'
import * as Stories from '~/components/fleet-members/Payments.stories'

import Component from '~/components/fleet-members/Payments.vue'
import { FIXTURE_PAYMENT } from '../../__data__/Payments'

describe('fleet-members/Payments', () => {
  describe('Snapshots', () => {
    test('Default', async () => {
      const wrapper = mount(Stories.Default())
      await wrapper.vm.$nextTick()
      expect(wrapper.element).toMatchSnapshot()
    })
    test('WithData', async () => {
      const wrapper = mount(Stories.WithData())
      await wrapper.vm.$nextTick()
      expect(wrapper.element).toMatchSnapshot()
    })
  })

  describe('methods', () => {
    let localVue

    beforeEach(() => {
      localVue = createLocalVue()
    })

    test('get status', () => {
      const wrapper = shallowMount(Component, {
        localVue: localVue
      })

      wrapper.setData({
        statuses: [
          {
            id: 1,
            text: 'Pending'
          },
          {
            id: 2,
            text: 'Paid'
          },
          {
            id: 3,
            text: 'Failed'
          },
          {
            id: 4,
            text: 'Refunded'
          }
        ]
      })

      const expectedValue = {
        id: 1,
        text: 'Pending',
        class: 'grey'
      }

      expect(wrapper.vm.getStatus(FIXTURE_PAYMENT)).toEqual(expectedValue)
    })

    test('processable', () => {
      const wrapper = shallowMount(Component, {
        localVue: localVue
      })

      expect(wrapper.vm.processable(FIXTURE_PAYMENT)).toBeTruthy()
    })

    test('manage label', () => {
      const wrapper = shallowMount(Component, {
        localVue: localVue
      })

      expect(wrapper.vm.manageLabel(FIXTURE_PAYMENT)).toEqual('Pay now')
    })
  })
})
