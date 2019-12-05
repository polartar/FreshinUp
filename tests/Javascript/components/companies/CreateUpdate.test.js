import { mount } from '@vue/test-utils'
import { createStore } from 'fresh-bus/store'
import { FIXTURE_COMPANY } from 'tests/__data__/companies'
import { createLocalVue } from 'fresh-bus/tests/utils'
import Component from '~/components/companies/CreateUpdate.vue'

describe('Companies CreateUpdate Component', () => {
  let localVue, mock, store

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

      store = createStore({})
    })
    afterEach(() => {
      mock.restore()
    })

    it('mocked company admin', async () => {
      const wrapper = mount(Component, {
        localVue,
        store,
        propsData: {
          isAdmin: true
        }
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
        store,
        propsData: {
          isAdmin: false
        }
      })

      await wrapper.vm.$store.dispatch('companies/getItem', { params: { id: 1 } })
      await wrapper.vm.$store.dispatch('page/setLoading', false)
      await wrapper.vm.$nextTick()

      expect(wrapper.element).toMatchSnapshot()
      expect(wrapper.find('input[aria-label="Status"]').attributes('disabled')).toBe('disabled')
    })
  })
})
