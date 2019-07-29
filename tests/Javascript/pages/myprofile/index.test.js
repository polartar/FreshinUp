import { mount } from '@vue/test-utils'
import { createLocalVue } from 'tests/utils'
import { createStore } from 'fresh-bus/store'
import { FIXTURE_CURRENT_USER } from 'tests/__data__/user'
import Component from '~/pages/myprofile/index.vue'

describe('My profile page', () => {
  let localVue, mock, store
  beforeEach(() => {
    const vue = createLocalVue({ validation: true })
    localVue = vue.localVue
    mock = vue.mock
    mock
      .onGet('api/currentUser').reply(200, FIXTURE_CURRENT_USER)
      .onGet('api/users/1').reply(200, { data: FIXTURE_CURRENT_USER })
      .onAny().reply(config => {
        console.warn('No mock match for ' + config.url, config)
        return [404, {}]
      })
    store = createStore({
      currentUser: FIXTURE_CURRENT_USER
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
