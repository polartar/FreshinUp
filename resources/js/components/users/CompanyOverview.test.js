import { mount } from '@vue/test-utils'
import * as Stories from './CompanyOverview.stories'

describe('components/venues/DocumentList', () => {
  describe('Snapshots', () => {
    test('Empty', () => {
      const wrapper = mount(Stories.Empty())
      expect(wrapper.element).toMatchSnapshot()
    })
    test('Populated', () => {
      const wrapper = mount(Stories.Populated())
      expect(wrapper.element).toMatchSnapshot()
    })
  })
})
