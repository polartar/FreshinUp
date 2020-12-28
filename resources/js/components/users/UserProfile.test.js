import Component from './UserProfile'
import { shallowMount } from '@vue/test-utils'
import { FIXTURE_USERS } from '../../../../tests/Javascript/__data__/users'

describe('components/users/UserProfile', () => {
  describe('Props & Computed', () => {
    test('totalCompanyMembers', () => {
      const wrapper = shallowMount(Component)
      expect(wrapper.vm.totalCompanyMembers).toEqual(0)

      wrapper.setProps({
        company: null
      })
      expect(wrapper.vm.totalCompanyMembers).toEqual(0)

      wrapper.setProps({
        company: {
          members: []
        }
      })
      expect(wrapper.vm.totalCompanyMembers).toEqual(0)

      wrapper.setProps({
        company: {
          members: FIXTURE_USERS
        }
      })
      expect(wrapper.vm.totalCompanyMembers).toEqual(FIXTURE_USERS.length)
    })
  })
})
