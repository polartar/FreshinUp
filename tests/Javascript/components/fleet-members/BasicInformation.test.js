import { mount } from '@vue/test-utils'
import createLocalVue from 'vue-cli-plugin-freshinup-ui/utils/testing/createLocalVue'
import Component from '~/components/fleet-members/BasicInformation.vue'
import { FIXTURE_FLEET_MEMBER } from 'tests/__data__/fleet-members'

describe('flee-members/BasicInformation', () => {
  let localVue

  describe('Snapshots', () => {
    beforeEach(() => {
      const vue = createLocalVue({ validation: true })
      localVue = vue.localVue
    })
    test('default', () => {
      const wrapper = mount(Component, {
        localVue: localVue
      })
      expect(wrapper.element).toMatchSnapshot()
    })
    test('member set', () => {
      const wrapper = mount(Component, {
        localVue: localVue,
        propsData: {
          member: FIXTURE_FLEET_MEMBER
        }
      })
      expect(wrapper.element).toMatchSnapshot()
    })
  })
})
