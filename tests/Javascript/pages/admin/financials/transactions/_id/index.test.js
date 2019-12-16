import { mount } from '@vue/test-utils'
import { createStore } from 'fresh-bus/store'
import createLocalVue from 'vue-cli-plugin-freshinup-ui/utils/testing/createLocalVue'
import Component from '~/pages/admin/financials/transactions/_id/index.vue'
import transactions from '~/store/modules/transactions'
import { FIXTURE_TRANSACTIONS } from 'tests/__data__/transactions'

describe('Admin Transaction Details Page', () => {
  describe('Mount', () => {
    let localVue, mock, store
    beforeEach(() => {
      const vue = createLocalVue({ validation: true })
      localVue = vue.localVue
      mock = vue.mock
      mock.onGet('api/foodfleet/transactions/' + FIXTURE_TRANSACTIONS[0].uuid)
        .reply(200, { data: FIXTURE_TRANSACTIONS[0] })
        .onAny().reply(config => {
          console.warn('No mock match for ' + config.url, config)
          return [404, {}]
        })
      store = createStore({}, {
        modules: {
          transactions: transactions({})
        }
      })
    })
    afterEach(() => {
      mock.restore()
    })
    it('default', async () => {
      const wrapper = mount(Component, {
        localVue,
        store
      })
      await wrapper.vm.$store.dispatch('transactions/getItem', { params: { id: FIXTURE_TRANSACTIONS[0].uuid } })
      await wrapper.vm.$store.dispatch('page/setLoading', false)
      await wrapper.vm.$nextTick()
      await wrapper.vm.$nextTick()
      expect(wrapper.element).toMatchSnapshot()
    })
  })
})
