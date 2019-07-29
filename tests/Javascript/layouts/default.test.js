import { mount } from '@vue/test-utils'
import { createStore } from 'fresh-bus/store'
import { createLocalVue } from 'fresh-bus/tests/utils'
import { FIXTURE_CURRENT_USER } from 'tests/__data__/users'
import Component from '~/layouts/admin.vue'

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
        currentUser: FIXTURE_CURRENT_USER,
        page: {
          title: 'Default template'
        }
      })
      const vue = createLocalVue({ validation: true })
      localVue = vue.localVue
      mock = vue.mock
        .onGet('api/currentUser').reply(200, FIXTURE_CURRENT_USER)
        .onGet('api/users/1').reply(200, { data: FIXTURE_CURRENT_USER })
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
      expect(wrapper.find('.page-title').text()).toStrictEqual('Default template')
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
