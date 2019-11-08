import { createLocalVue, mount } from '@vue/test-utils'
import Component from '~/components/events/StoreFilter.vue'
import { STORE_TYPES } from 'tests/__data__/storeTypes'

describe('event Store Filter Sorter component', () => {
  let localVue
  describe('Snapshots', () => {
    test('types set', () => {
      localVue = createLocalVue()
      const wrapper = mount(Component, {
        localVue: localVue,
        propsData: {
          filters: {},
          types: STORE_TYPES
        }
      })

      expect(wrapper.element).toMatchSnapshot()
    })

    test('types empty', () => {
      localVue = createLocalVue()
      const wrapper = mount(Component, {
        localVue: localVue,
        propsData: {
          filters: {},
          types: STORE_TYPES
        }
      })

      expect(wrapper.element).toMatchSnapshot()
    })
  })

  describe('Action', () => {
    test('clearFilters function clear filters', () => {
      const localVue = createLocalVue()

      const wrapper = mount(Component, {
        localVue: localVue,
        propsData: {
          filters: {},
          types: STORE_TYPES
        }
      })
      wrapper.vm.clearFilters({})
      expect(wrapper.vm.filters.location_uuid).toBeNull()
      expect(wrapper.vm.filters.type).toBeNull()
      expect(wrapper.vm.filters.tags_uuid).toBeNull()
    })

    test('run function emitted runFilter', () => {
      const wrapper = mount(Component, {
        localVue: localVue,
        propsData: {
          filters: {
            location_uuid: 1,
            type: 1,
            tags_uuid: [ { uuid: 3 } ]
          },
          types: STORE_TYPES
        }
      })
      wrapper.vm.run({})
      expect(wrapper.emitted().runFilter).toBeTruthy()
      const runParams = wrapper.emitted().runFilter[0][0]
      expect(runParams['location_uuid']).toEqual(1)
      expect(runParams['type']).toEqual(1)
      expect(runParams['tags_uuid']).toEqual([{ uuid: 3 }])
    })
  })
})
