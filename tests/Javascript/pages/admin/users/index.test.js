import { shallowMount } from '@vue/test-utils'
import { createStore } from 'fresh-bus/store'
import { createLocalVue } from 'fresh-bus/tests/utils'
import { FIXTURE_USERS_RESPONSE, FIXTURE_USERS_SORTED_BY_FIRSTNAME } from 'tests/__data__/users'
import { FIXTURE_CURRENT_USER } from 'tests/__data__/user'
import Component from '~/pages/admin/users/index.vue'

describe('Admin Users Page', () => {
  let localVue, mock
  describe('Mount', () => {
    beforeEach(() => {
      const vue = createLocalVue({ validation: true })
      localVue = vue.localVue
      mock = vue.mock
    })
    afterEach(() => {
      mock.restore()
    })
    test('snapshot', async () => {
      const vue = createLocalVue({ validation: true })
      localVue = vue.localVue
      mock = vue.mock
        .onGet('api/currentUser').reply(200, FIXTURE_CURRENT_USER)
        .onGet('api/users', { params: { 'page[size]': 10, 'page[number]': 1, sort: 'first_name' } })
        .reply(200, { data: FIXTURE_USERS_SORTED_BY_FIRSTNAME })
        .onGet('api/users').reply(200, FIXTURE_USERS_RESPONSE)
        .onAny()
        .reply(config => {
          console.warn('No mock match for ' + config.url, config)
          return [404, {}]
        })
      const store = createStore({
        users: {
          items: FIXTURE_USERS_RESPONSE // Start with listed sorted by id
        }
      })
      const wrapper = shallowMount(Component, {
        localVue: localVue,
        store
      })
      // Action: change State Machine's state
      await wrapper.vm.$store.dispatch('page/setLoading', false)
      await wrapper.vm.$nextTick()
      await wrapper.vm.$store.dispatch('users/getItems')
      await wrapper.vm.$nextTick()
      expect(wrapper.element).toMatchSnapshot()
    })
  })
})
