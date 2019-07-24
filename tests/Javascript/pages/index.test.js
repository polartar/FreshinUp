import { mount } from '@vue/test-utils'
import Component from '~/pages/index.vue'

describe('Index page', () => {
  it('snapshot of page', async () => {
    const wrapper = mount(Component)
    expect(wrapper.element).toMatchSnapshot()
  })
})
