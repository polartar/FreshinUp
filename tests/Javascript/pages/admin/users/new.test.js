import { mount } from '@vue/test-utils'
import createLocalVue from 'vue-cli-plugin-freshinup-ui/utils/testing/createLocalVue'
import { createStore } from 'fresh-bus/store'
import { FIXTURE_USER } from 'tests/__data__/user'
import Component from '~/pages/admin/users/new.vue'

describe('Admin New User Page', () => {
  let localVue, mock, store
  describe('mount', () => {
    beforeEach(() => {
      store = createStore({
        currentUser: FIXTURE_USER
      })
      const vue = createLocalVue({ validation: true })
      localVue = vue.localVue
      mock = vue.mock
        .onGet('api/currentUser').reply(200, FIXTURE_USER)
        .onGet('api/users/new').reply(200, FIXTURE_USER)
        .onGet('api/users/1').reply(200, { data: FIXTURE_USER })
        .onGet(/companies(.*)/).reply(200, { data: [] })
        .onAny().reply(config => {
          console.error('No mock match for ' + config.url, config)
          return [404, {}]
        })
    })
    afterEach(() => {
      mock.restore()
    })
    it('enabledFields for foodfleet', async () => {
      const wrapper = mount(Component, {
        localVue: localVue,
        store
      })
      await wrapper.vm.$store.dispatch('users/getItem', { params: { id: 1 } })
      await wrapper.vm.$store.dispatch('page/setLoading', false)
      await wrapper.vm.$nextTick()
      expect(wrapper.html()).toContain('Status')
      expect(wrapper.html()).toContain('First Name')
      expect(wrapper.html()).toContain('Last Name')
      expect(wrapper.html()).toContain('Company')
      expect(wrapper.html()).toContain('Title')
      expect(wrapper.html()).toContain('Email')
      expect(wrapper.html()).toContain('Level')
      expect(wrapper.html()).toContain('Type')
      expect(wrapper.html()).toContain('Office Phone')
      expect(wrapper.html()).toContain('Mobile Phone')
      expect(wrapper.html()).toContain('Street address')
      expect(wrapper.html()).toContain('Street address 2')
      expect(wrapper.html()).toContain('City')
      expect(wrapper.html()).toContain('Country')
      expect(wrapper.html()).toContain('Post Code')
      expect(wrapper.html()).toContain('Notes')
      expect(wrapper.html()).not.toContain('Roles')
      expect(wrapper.element).toMatchSnapshot()
    })
  })
})
