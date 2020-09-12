import { createLocalVue, mount } from '@vue/test-utils'
import Component from '~/components/stores/StoreAreaList.vue'
import { FIXTURE_STORE_AREAS } from 'tests/__data__/storeAreas'

describe('Store List component', () => {
  // Component instance "under test"
  let localVue
  describe('Snapshots', () => {
    test('stores set', () => {
      localVue = createLocalVue()
      const wrapper = mount(Component, {
        localVue: localVue,
        propsData: {
          storeAreas: FIXTURE_STORE_AREAS
        }
      })
      expect(wrapper.element).toMatchSnapshot()
    })
    test('stores empty', () => {
      localVue = createLocalVue()
      const wrapper = mount(Component, {
        localVue: localVue,
        propsData: {
          stores: []
        }
      })
      expect(wrapper.element).toMatchSnapshot()
    })
  })

})
