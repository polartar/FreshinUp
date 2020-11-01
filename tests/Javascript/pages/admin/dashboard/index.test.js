import { shallowMount } from '@vue/test-utils'
import createLocalVue from 'vue-cli-plugin-freshinup-ui/utils/testing/createLocalVue'
import { FIXTURE_USER } from 'tests/__data__/user'
import Component from '~/pages/admin/dashboard/index.vue'
import createStore from 'tests/createStore'

describe('Admin Dashboard Steps Page', () => {
  let localVue, mock, store
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
        .onGet('api/currentUser').reply(200, FIXTURE_USER)
        .onAny()
        .reply(config => {
          console.warn('No mock match for ' + config.url, config)
          return [404, {}]
        })
      const store = createStore()
      const wrapper = shallowMount(Component, {
        localVue: localVue,
        store
      })

      await wrapper.vm.$store.dispatch('page/setLoading', false)
      await wrapper.vm.$nextTick()

      expect(wrapper.element).toMatchSnapshot()
    })
  })

  describe('Computed', () => {
    describe('editUserRoute', () => {
      beforeEach(() => {
        const vue = createLocalVue({ validation: true })
        localVue = vue.localVue
        store = createStore({ currentUser: FIXTURE_USER })
        mock = vue.mock
      })

      afterEach(() => {
        mock.restore()
      })

      it('should return route of editing user', async () => {
        const vue = createLocalVue({ validation: true })
        const wrapper = shallowMount(Component, {
          localVue: localVue,
          store
        })

        mock = vue.mock
          .onGet('api/currentUser').reply(200, FIXTURE_USER)
          .onAny()
          .reply(config => {
            console.warn('No mock match for ' + config.url, config)
            return [404, {}]
          })

        await wrapper.vm.$store.dispatch('page/setLoading', true)
        await wrapper.vm.$nextTick()
        expect(wrapper.vm.editUserRoute).toEqual('/admin/users/1/edit')
      })

      it('should return default route if no user id', async () => {
        const vue = createLocalVue({ validation: true })
        const wrapper = shallowMount(Component, {
          localVue: localVue,
          store: createStore({ currentUser: {} })
        })

        mock = vue.mock
          .onGet('api/currentUser').reply(200, {})
          .onAny()
          .reply(config => {
            console.warn('No mock match for ' + config.url, config)
            return [404, {}]
          })

        await wrapper.vm.$store.dispatch('page/setLoading', true)
        await wrapper.vm.$nextTick()
        expect(wrapper.vm.editUserRoute).toEqual('/admin/dashboard')
      })
    })

    describe('editCompanyRoute', () => {
      beforeEach(() => {
        const vue = createLocalVue({ validation: true })
        localVue = vue.localVue
        store = createStore({ currentUser: FIXTURE_USER })
        mock = vue.mock
      })

      afterEach(() => {
        mock.restore()
      })

      it('should return route of editing company', async () => {
        const vue = createLocalVue({ validation: true })
        const wrapper = shallowMount(Component, {
          localVue: localVue,
          store
        })

        mock = vue.mock
          .onGet('api/currentUser').reply(200, FIXTURE_USER)
          .onAny()
          .reply(config => {
            console.warn('No mock match for ' + config.url, config)
            return [404, {}]
          })

        await wrapper.vm.$store.dispatch('page/setLoading', true)
        await wrapper.vm.$nextTick()
        expect(wrapper.vm.editCompanyRoute).toEqual('/admin/companies/1/edit')
      })

      it('should return default route if no company id', async () => {
        const vue = createLocalVue({ validation: true })
        const wrapper = shallowMount(Component, {
          localVue: localVue,
          store: createStore({ currentUser: {} })
        })

        mock = vue.mock
          .onGet('api/currentUser').reply(200, {})
          .onAny()
          .reply(config => {
            console.warn('No mock match for ' + config.url, config)
            return [404, {}]
          })

        await wrapper.vm.$store.dispatch('page/setLoading', true)
        await wrapper.vm.$nextTick()
        expect(wrapper.vm.editUserRoute).toEqual('/admin/dashboard')
      })
    })
  })
})
