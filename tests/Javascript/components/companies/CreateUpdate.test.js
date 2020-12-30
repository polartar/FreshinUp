import { mount } from '@vue/test-utils'
import { FIXTURE_COMPANY } from 'tests/__data__/companies'
import createLocalVue from 'vue-cli-plugin-freshinup-ui/utils/testing/createLocalVue'
import Component from '~/components/companies/CreateUpdate.vue'
import createStore from 'tests/createStore'
import { FIXTURE_USER } from '../../__data__/user'

describe('Companies CreateUpdate Component', () => {
  let localVue, mock, store, storeWithAdmin

  describe('Snapshots', () => {
    beforeEach(() => {
      const vue = createLocalVue({ validation: true })
      localVue = vue.localVue
      mock = vue.mock
      mock
        .onGet('api/companies/1').reply(200, { data: FIXTURE_COMPANY })
        .onAny().reply(config => {
          console.warn('No mock match for ' + config.url, config)
          return [404, {}]
        })

      store = createStore({
        currentUser: {
          ...FIXTURE_USER, has_admin_access: false
        }
      })
      storeWithAdmin = createStore({
        currentUser: {
          ...FIXTURE_USER, has_admin_access: true
        }
      })
    })
    afterEach(() => {
      mock.restore()
    })

    it('mocked company admin', async () => {
      const wrapper = mount(Component, {
        localVue,
        store: storeWithAdmin
      })

      await wrapper.vm.$store.dispatch('companies/getItem', { params: { id: 1 } })
      await wrapper.vm.$store.dispatch('page/setLoading', false)
      await wrapper.vm.$nextTick()

      expect(wrapper.element).toMatchSnapshot()
      expect(wrapper.find('input[aria-label="Status"]').attributes('disabled')).toBeFalsy()
    })

    it('mocked company non admin', async () => {
      const wrapper = mount(Component, {
        localVue,
        store
      })

      await wrapper.vm.$store.dispatch('companies/getItem', { params: { id: 1 } })
      await wrapper.vm.$store.dispatch('page/setLoading', false)
      await wrapper.vm.$nextTick()
      expect(wrapper.element).toMatchSnapshot()
      expect(wrapper.find('input[aria-label="Status"]').attributes('disabled')).toBe('disabled')
    })
  })
})
