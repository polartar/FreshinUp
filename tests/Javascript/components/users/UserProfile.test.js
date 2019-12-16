import { mount, shallowMount } from '@vue/test-utils'
import createLocalVue from 'vue-cli-plugin-freshinup-ui/utils/testing/createLocalVue'
import { FIXTURE_USER } from 'tests/__data__/user'
import { FIXTURE_COMPANY } from 'tests/__data__/companies'
import Component from '~/components/users/UserProfile.vue'

describe('UserProfile', () => {
  let localVue
  beforeEach(() => {
    const vue = createLocalVue({ validation: true })
    localVue = vue.localVue
  })
  describe('Snapshots', () => {
    it('without user and company', async () => {
      const wrapper = mount(Component, {
        localVue: localVue,
        propsData: {
          adminUser: FIXTURE_USER,
          isCurrentUser: true
        }
      })
      expect(wrapper.element).toMatchSnapshot()
    })
    it('isCurrentUser', async () => {
      const wrapper = mount(Component, {
        localVue: localVue,
        propsData: {
          user: FIXTURE_USER,
          adminUser: FIXTURE_USER,
          isCurrentUser: true,
          company: FIXTURE_COMPANY.data
        }
      })
      expect(wrapper.element).toMatchSnapshot()
    })
    it('isCurrentUserAdmin', async () => {
      const wrapper = mount(Component, {
        localVue: localVue,
        propsData: {
          user: FIXTURE_USER,
          adminUser: FIXTURE_USER,
          isCurrentUserAdmin: true,
          company: FIXTURE_COMPANY.data
        }
      })
      expect(wrapper.element).toMatchSnapshot()
    })
  })
  describe('Computed', () => {
    test('totalCompanyMembers', () => {
      const wrapper = shallowMount(Component, {
        localVue: localVue,
        propsData: {
          user: FIXTURE_USER,
          isCurrentUser: true,
          company: FIXTURE_COMPANY.data
        }
      })
      expect(wrapper.vm.totalCompanyMembers).toBe(16)
    })
  })
})
