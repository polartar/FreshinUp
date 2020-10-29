import { mount } from '@vue/test-utils'
import createLocalVue from 'vue-cli-plugin-freshinup-ui/utils/testing/createLocalVue'
import { FIXTURE_USER } from 'tests/__data__/users'
import Component from '~/layouts/default.vue'
import createStore from 'tests/createStore'

const mockAuth = {
  logout: () => {
    window.location.assign('/auth')
  }
}

describe('Default layout', () => {
  let localVue, mock, store
  describe('mount', () => {
    beforeEach(async () => {
      store = createStore({
        currentUser: FIXTURE_USER,
        page: {
          title: 'Default template'
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
    it('signout redirects', async () => {
      const wrapper = mount(Component, {
        localVue,
        store,
        mocks: { $auth: mockAuth }
      })
      window.location.assign = jest.fn() // Create a spy
      await wrapper.vm.signout()
      expect(window.location.assign).toHaveBeenCalledWith('/auth')
    })
  })
})
