import { shallowMount, mount } from '@vue/test-utils'
import { createLocalVue } from 'fresh-bus/tests/utils'
import { createStore } from 'fresh-bus/store'
import Component from '~/pages/admin/contractor/index.vue'

describe('Contractor page', () => {
  let localVue
  describe('Mount', () => {
    test('snapshot', async () => {
      const vue = createLocalVue({ validation: true })
      localVue = vue.localVue
      const store = createStore({})
      const wrapper = shallowMount(Component, {
        localVue: localVue,
        store
      })
      // Action: change State Machine's state
      await wrapper.vm.$store.dispatch('page/setLoading', false)
      await wrapper.vm.$nextTick()
      expect(wrapper.element).toMatchSnapshot()
    })
  })
  describe('Computed', () => {
    test('url', async () => {
      const vue = createLocalVue({ validation: true })
      localVue = vue.localVue
      const store = createStore({})
      const wrapper = mount(Component, {
        localVue: localVue,
        store
      })

      // Action: change State Machine's state
      await wrapper.vm.$store.dispatch('page/setLoading', false)
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.url).toBe('https://connect.squareupsandbox.com/oauth2/authorize?client_id=sandbox-sq0idb-XpOEfx_tSDS3oIDPuf9Tqw&scope=PAYMENTS_READ CUSTOMERS_READ EMPLOYEES_READ INVENTORY_READ ITEMS_READ MERCHANT_PROFILE_READ ORDERS_READ')
    })
  })
})
