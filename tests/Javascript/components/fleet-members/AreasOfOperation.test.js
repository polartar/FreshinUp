import { mount } from '@vue/test-utils'
import * as Stories from '~/components/fleet-members/AreasOfOperation.stories'

describe('fleet-members/AreasOfOperation', () => {
  describe('Snapshots', () => {
    test('Default', async () => {
      const wrapper = mount(Stories.Default())
      await wrapper.vm.$nextTick()
      expect(wrapper.element).toMatchSnapshot()
    })
  })
})
