import { createLocalVue, shallowMount, mount } from '@vue/test-utils'
import Component from '~/components/events/StoreFilter.vue'
import { FIXTURE_STORE_STATUSES } from 'tests/__data__/storeStatuses'
import { STORE_CATEGORY } from 'tests/__data__/storeCategories'

describe('event Store Filter Sorter component', () => {
  let localVue
  describe('Snapshots', () => {
    test('default', () => {
      localVue = createLocalVue()
      const wrapper = mount(Component, {
        localVue: localVue,
        propsData: {
          filters: {
            status: null,
            store_type: null,
            tag_uuid: null,
            owner_id: null,
            location_uuid: null
          },
          statuses: FIXTURE_STORE_STATUSES,
          types: STORE_CATEGORY
        }
      })

      expect(wrapper.element).toMatchSnapshot()
    })
  })

  describe('Methods', () => {
    test('select functions change filters', () => {
      const localVue = createLocalVue()
      const wrapper = shallowMount(Component, {
        localVue: localVue,
        propsData: {
          filters: {
            status: null,
            store_type: null,
            tag_uuid: null,
            owner_id: null,
            location_uuid: null
          },
          statuses: FIXTURE_STORE_STATUSES,
          types: STORE_CATEGORY
        }
      })

      wrapper.vm.selectOwner({ id: 1, name: 'Level1 User' })
      expect(wrapper.props().filters.owner_id).toBe(1)
      wrapper.vm.selectLocation({ uuid: 3, name: 'Fredrickstad' })
      expect(wrapper.props().filters.location_uuid).toBe(3)
    })

    test('clearFilters function clear filters', () => {
      const wrapper = mount(Component, {
        localVue: localVue,
        propsData: {
          filters: {},
          statuses: FIXTURE_STORE_STATUSES,
          types: STORE_CATEGORY
        }
      })
      wrapper.vm.clearFilters({})
      expect(wrapper.vm.filters.status).toBeNull()
      expect(wrapper.vm.filters.store_type).toBeNull()
      expect(wrapper.vm.filters.tag_uuid).toBeNull()
      expect(wrapper.vm.filters.owner_id).toBeNull()
      expect(wrapper.vm.filters.location_uuid).toBeNull()
    })

    test('run function emitted runFilter', () => {
      const wrapper = mount(Component, {
        localVue: localVue,
        propsData: {
          filters: {
            status: 4,
            store_type: 4,
            tag_uuid: [ { uuid: 3 } ],
            owner_id: 2,
            location_uuid: 1
          },
          statuses: FIXTURE_STORE_STATUSES,
          types: STORE_CATEGORY
        }
      })
      wrapper.vm.run({})
      expect(wrapper.emitted().runFilter).toBeTruthy()
      const runParams = wrapper.emitted().runFilter[0][0]

      expect(runParams['status']).toEqual(4)
      expect(runParams['store_type']).toEqual(4)
      expect(runParams['tag_uuid']).toEqual([3])
      expect(runParams['owner_id']).toEqual(2)
      expect(runParams['location_uuid']).toEqual(1)
    })
  })
})
