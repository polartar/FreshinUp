import { createLocalVue, shallowMount, mount } from '@vue/test-utils'
import { FIXTURE_DOCUMENT_STATUSES } from 'tests/__data__/documentStatuses'
import { FIXTURE_DOCUMENTS } from 'tests/__data__/documents'
import Component from '~/components/docs/DoctableList.vue'

describe('Document List component', () => {
  // Component instance "under test"
  let localVue
  describe('Snapshots', () => {
    test('defaults', () => {
      localVue = createLocalVue()
      const wrapper = mount(Component, {
        localVue: localVue,
        propsData: {
          docs: FIXTURE_DOCUMENTS,
          statuses: FIXTURE_DOCUMENT_STATUSES
        }
      })
      expect(wrapper.element).toMatchSnapshot()
    })
  })

  describe('Methods', () => {
    beforeEach(() => {
      localVue = createLocalVue()
    })

    test('occurs when onRowsPerPageChange is invoked', () => {
      const wrapper = shallowMount(Component, {
        localVue
      })
      wrapper.vm.onRowsPerPageChange(2)
      wrapper.vm.onRowsPerPageChange(1)

      // assert event has been emitted
      expect(wrapper.emitted().paginate).toBeTruthy()

      // assert event count
      expect(wrapper.emitted().paginate).toHaveLength(2)

      // assert event payload
      expect(wrapper.emitted().paginate[0][0].rowsPerPage).toEqual(2)
      expect(wrapper.emitted().paginate[1][0].rowsPerPage).toEqual(1)
    })

    test('occurs when onPageChange is invoked', () => {
      const wrapper = shallowMount(Component, {
        localVue
      })
      wrapper.vm.onPageChange(17)
      wrapper.vm.onPageChange(4)

      // assert event has been emitted
      expect(wrapper.emitted().paginate).toBeTruthy()

      // assert event count
      expect(wrapper.emitted().paginate).toHaveLength(2)

      // assert event payload
      expect(wrapper.emitted().paginate[0][0].page).toEqual(17)
      expect(wrapper.emitted().paginate[1][0].page).toEqual(4)
    })

    test('manage function emitted single manage action', () => {
      const wrapper = shallowMount(Component, {
        localVue
      })

      const itemActions = [
        { action: 'view', text: 'View / Edit' },
        { action: 'delete', text: 'Delete' }
      ]
      const mockDoc = { id: 1, title: 'mock title', status: 1 }

      wrapper.vm.manage(itemActions[0], mockDoc)
      wrapper.vm.manage(itemActions[1], mockDoc)

      expect(wrapper.emitted()['manage-view']).toBeTruthy()
      expect(wrapper.emitted()['manage-delete']).toBeTruthy()
    })

    test('manageMultiple function emitted multiple manage action', () => {
      const wrapper = shallowMount(Component, {
        localVue
      })

      wrapper.vm.manageMultiple('delete')

      expect(wrapper.emitted()['manage-multiple-delete']).toBeTruthy()
    })

    test('changeStatus function emitted change-status action', () => {
      const wrapper = shallowMount(Component, {
        localVue
      })

      const mockDoc = { id: 1, title: 'mock title', status: 1 }

      wrapper.vm.changeStatus(2, mockDoc)

      expect(wrapper.emitted()['change-status']).toBeTruthy()
    })
  })

  describe('Computed', () => {
    beforeEach(() => {
      localVue = createLocalVue()
    })
    test('selectedDocActions', () => {
      const wrapper = shallowMount(Component)
      wrapper.setData({ selected: [] })
      expect(wrapper.vm.selectedDocActions).toEqual([])
      wrapper.setData({ selected: [ 1 ] })
      expect(wrapper.vm.selectedDocActions[0].action).toBe('delete')
      expect(wrapper.vm.selectedDocActions[0].text).toBe('Delete')
    })
  })

  describe('page property', () => {
    test('changes the v-pagination components selected item', async () => {
      const wrapper = mount(Component, {
        localVue: createLocalVue.localVue,
        propsData: {
          page: 1,
          isLoading: false,
          rowsPerPage: 5,
          totalItems: 10
        }
      })
      expect(wrapper.find('.v-pagination .v-pagination__item--active').text()).toEqual('1')
      wrapper.setProps({
        page: 2,
        isLoading: false,
        rowsPerPage: 5,
        totalItems: 10
      })
      await wrapper.vm.$nextTick()
      expect(wrapper.find('.v-pagination .v-pagination__item--active').text()).toEqual('2')
    })
  })
})
