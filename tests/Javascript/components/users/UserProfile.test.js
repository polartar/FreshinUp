import { mount } from '@vue/test-utils'
import { createLocalVue } from 'tests/utils'
import { FIXTURE_USER } from 'tests/__data__/user'
import { FIXTURE_COMPANY } from 'tests/__data__/companies'
import Component from '~/components/users/UserProfile.vue'

describe('UserProfile', () => {
  let localVue
  beforeEach(() => {
    const vue = createLocalVue({ validation: true })
    localVue = vue.localVue
  })
  describe('Methods', () => {
    it('snapshot', async () => {
      const wrapper = mount(Component, {
        localVue: localVue,
        propsData: {
          user: FIXTURE_USER,
          isCurrentUser: true,
          company: FIXTURE_COMPANY
        }
      })
      expect(wrapper.element).toMatchSnapshot()
    })
  })
})
