import { mount } from '@vue/test-utils'
import { createLocalVue } from 'tests/utils'
import { createStore } from 'fresh-bus/store'
import { FIXTURE_USERS } from 'tests/__data__/users'
import Component from '~/pages/admin/users/index.vue'

describe('Users index page', () => {
  let localVue, mock, store
  beforeEach(() => {
    const vue = createLocalVue({ validation: true })
    localVue = vue.localVue
    mock = vue.mock
    mock
      .onGet('api/users').reply(200, { data: FIXTURE_USERS })
      .onAny().reply(config => {
        console.warn('No mock match for ' + config.url, config)
        return [404, {}]
      })
    store = createStore({
      currentUser: FIXTURE_USERS
    })
  })
  afterEach(() => {
    mock.restore()
  })
  it('snapshot of page', async () => {
    const wrapper = mount(Component, {
      localVue: localVue,
      store
    })
    expect(wrapper.element).toMatchSnapshot()
  })
})
