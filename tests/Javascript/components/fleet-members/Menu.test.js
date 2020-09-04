import { mount } from '@vue/test-utils'
import * as Stories from '~/components/fleet-members/Menu.stories'

describe('fleet-members/Menu', () => {
  describe('Snapshots', () => {
    test('Default', async () => {
      const wrapper = mount(Stories.Default())
      await wrapper.vm.$nextTick()
      expect(wrapper.element).toMatchSnapshot()
    })
  })
})
