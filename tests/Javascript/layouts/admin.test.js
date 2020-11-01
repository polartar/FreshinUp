import { mount } from '@vue/test-utils'
import createLocalVue from 'vue-cli-plugin-freshinup-ui/utils/testing/createLocalVue'
import { FIXTURE_USER } from 'tests/__data__/users'
import Component from '~/layouts/admin.vue'
import createStore from 'tests/createStore'

describe('Admin layout', () => {
  let localVue, mock, store
  describe('mount', () => {
    beforeEach(async () => {
      store = createStore({
        currentUser: FIXTURE_USER,
        page: {
          title: 'Admin template'
        }
      })
      const vue = createLocalVue({ validation: true })
      localVue = vue.localVue
      mock = vue.mock
        .onGet('api/currentUser').reply(200, FIXTURE_USER)
        .onGet('api/users/1').reply(200, { data: FIXTURE_USER })
        .onAny().reply(config => {
          console.error('No mock match for ' + config.url, config)
          return [404, {}]
        })
    })
    afterEach(() => {
      mock.restore()
    })
    it('snapshot of layout', async () => {
      const wrapper = mount(Component, {
        localVue,
        store
      })
      await wrapper.vm.$store.dispatch('users/getItem', { params: { id: 1 } })
      await wrapper.vm.$store.dispatch('page/setLoading', false)
      await wrapper.vm.$nextTick()
      expect(wrapper.element).toMatchSnapshot()
    })
  })
})
