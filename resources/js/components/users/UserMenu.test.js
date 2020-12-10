import { mount } from '@vue/test-utils'
import createLocalVue from 'vue-cli-plugin-freshinup-ui/utils/testing/createLocalVue'
import { FIXTURE_USER } from '../../../../tests/Javascript/__data__/user'
import Component from './UserMenu'
import * as Stories from './UserMenu.stories'

describe('UserMenu', () => {
  let localVue
  beforeEach(() => {
    const vue = createLocalVue({ validation: true })
    localVue = vue.localVue
  })
  describe('Snapshots', () => {
    test('Default', async () => {
      const wrapper = mount(Stories.Default())
      await wrapper.vm.$nextTick()
      expect(wrapper.element).toMatchSnapshot()
    })
    test('Set', async () => {
      const wrapper = mount(Stories.Set())
      await wrapper.vm.$nextTick()
      expect(wrapper.element).toMatchSnapshot()
    })
    it('isCurrentUser', async () => {
      const wrapper = mount(Component, {
        localVue: localVue,
        propsData: {
          user: FIXTURE_USER,
          adminUser: FIXTURE_USER,
          isCurrentUser: true
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
          isCurrentUserAdmin: true
        }
      })
      expect(wrapper.element).toMatchSnapshot()
    })
  })
})
