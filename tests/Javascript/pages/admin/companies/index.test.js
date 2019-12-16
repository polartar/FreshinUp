import { mount } from '@vue/test-utils'
import { createStore } from 'fresh-bus/store'
import createLocalVue from 'vue-cli-plugin-freshinup-ui/utils/testing/createLocalVue'
import { FIXTURE_COMPANIES_RESPONSE } from 'tests/__data__/companies'
import Page from '~/pages/admin/companies/index.vue'

describe('Admin Companies Page', () => {
  let localVue, mock, store

  describe('Mount', () => {
    beforeEach(() => {
      const vue = createLocalVue({ validation: true })
      localVue = vue.localVue
      mock = vue.mock
      store = createStore({})

      mock = vue.mock
        .onGet('api/companies').reply(200, FIXTURE_COMPANIES_RESPONSE)
        .onAny()
        .reply(config => {
          console.warn('No mock match for ' + config.url, config)
          return [404, {}]
        })
    })
    afterEach(() => {
      mock.restore()
    })

    test('snapshot', done => {
      const wrapper = mount(Page, {
        localVue,
        store,
        mocks: {
          $route: {}
        }
      })

      Page.beforeRouteEnterOrUpdate(wrapper.vm, null, null, async () => {
        await wrapper.vm.$nextTick()
        expect(wrapper.element).toMatchSnapshot()
        done()
      })
    })
  })
})
