import { mount } from '@vue/test-utils'
import createLocalVue from 'vue-cli-plugin-freshinup-ui/utils/testing/createLocalVue'
import Component from '~/components/companies/FilterSorter.vue'

describe('Companies FilterSorter Component', () => {
  let localVue

  beforeEach(() => {
    const vue = createLocalVue({ validation: true })
    localVue = vue.localVue
  })

  describe('Mount', () => {
    test('snapshot', () => {
      const wrapper = mount(Component, {
        localVue
      })

      expect(wrapper.element).toMatchSnapshot()
    })
  })

  describe('Methods', () => {
    test('run() emits runFilter', () => {
      const wrapper = mount(Component, {
        localVue
      })
      wrapper.vm.run({ term: 'test' })
      expect(wrapper.emitted().runFilter).toBeTruthy()
      expect(wrapper.emitted().runFilter).toHaveLength(1)
      expect(wrapper.emitted().runFilter[0]).toEqual([{ 'filter[name]': 'test', 'filter[status]': null, 'filter[users_id]': null, 'sort': undefined }])
    })

    test('selectOwner() emits runFilter and change value to company', () => {
      const wrapper = mount(Component, {
        localVue
      })
      wrapper.vm.selectOwner({ id: 1 })
      expect(wrapper.vm.owner).toBe(1)
      expect(wrapper.emitted().runFilter).toBeTruthy()
      expect(wrapper.emitted().runFilter).toHaveLength(1)
      expect(wrapper.emitted().runFilter[0]).toEqual([{ 'filter[name]': '', 'filter[status]': null, 'filter[users_id]': 1, 'sort': 'created_at' }])
    })
  })
})
