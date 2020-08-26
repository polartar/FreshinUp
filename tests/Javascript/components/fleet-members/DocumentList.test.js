import { createLocalVue, shallowMount, mount } from '@vue/test-utils'
import Component from '~/components/fleet-members/DocumentList.vue'
import { FIXTURE_DOCUMENTS } from 'tests/__data__/documents'
import { FIXTURE_DOCUMENT_STATUSES } from 'tests/__data__/documentStatuses'

describe('DocumentList component', () => {
  // Component instance "under test"
  let localVue
  describe('Snapshots', () => {
    test('docss set', () => {
      localVue = createLocalVue()
      const wrapper = mount(Component, {
        localVue: localVue,
        propsData: {
          statuses: FIXTURE_DOCUMENT_STATUSES,
          docss: FIXTURE_DOCUMENTS
        }
      })
      expect(wrapper.element).toMatchSnapshot()
    })
    test('docss empty', () => {
      localVue = createLocalVue()
      const wrapper = mount(Component, {
        localVue: localVue,
        propsData: {
          statuses: FIXTURE_DOCUMENT_STATUSES,
          docss: []
        }
      })
      expect(wrapper.element).toMatchSnapshot()
    })
  })
  describe('Methods Extended', () => {
    beforeEach(() => {
      localVue = createLocalVue()
    })

    test('sort/search actions', () => {
      const wrapper = shallowMount(Component, {
        localVue
      })

      wrapper.vm.sort({ id: 'name', label: 'Name' })
      expect(wrapper.emitted()['sort']).toBeTruthy()

      wrapper.vm.searchInput('test')
      expect(wrapper.emitted()['searchInput']).toBeTruthy()
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
      expect(wrapper.emitted()['manage-view'][0][0].id).toEqual(1)
      expect(wrapper.emitted()['manage-view'][0][0].title).toEqual(
        'mock title'
      )
      expect(wrapper.emitted()['manage-view'][0][0].status).toEqual(1)
      expect(wrapper.emitted()['manage-delete']).toBeTruthy()
      expect(wrapper.emitted()['manage-delete'][0][0].id).toEqual(1)
      expect(wrapper.emitted()['manage-delete'][0][0].title).toEqual(
        'mock title'
      )
      expect(wrapper.emitted()['manage-delete'][0][0].status).toEqual(1)
    })

    test('manageMultiple function emitted multiple manage action', () => {
      const wrapper = shallowMount(Component, {
        localVue
      })

      wrapper.vm.manageMultiple('delete')

      expect(wrapper.emitted()['manage-multiple-delete']).toBeTruthy()
      expect(wrapper.emitted()['manage-multiple-delete'][0][0]).toEqual([])
    })

    test('changeStatus function emitted change-status action', () => {
      const wrapper = shallowMount(Component, {
        localVue
      })

      const mockDoc = { id: 1, title: 'mock title', status: 1 }

      wrapper.vm.changeStatus(2, mockDoc)

      expect(wrapper.emitted()['change-status']).toBeTruthy()
      expect(wrapper.emitted()['change-status'][0][0]).toEqual(2)
      expect(wrapper.emitted()['change-status'][0][1].id).toEqual(1)
      expect(wrapper.emitted()['change-status'][0][1].title).toEqual(
        'mock title'
      )
      expect(wrapper.emitted()['change-status'][0][1].status).toEqual(1)
    })

    test('changeStatusMultiple function emitted change-status-multiple action', () => {
      const wrapper = shallowMount(Component, {
        localVue
      })

      wrapper.setData({ selected: [1] })
      wrapper.vm.changeStatusMultiple(2)

      expect(wrapper.emitted()['change-status-multiple']).toBeTruthy()
      expect(wrapper.emitted()['change-status-multiple'][0][0]).toEqual(2)
      expect(wrapper.emitted()['change-status-multiple'][0][1]).toEqual([1])
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
      wrapper.setData({ selected: [1] })
      expect(wrapper.vm.selectedDocActions[0].action).toBe('delete')
      expect(wrapper.vm.selectedDocActions[0].text).toBe('Delete')
    })
  })
})
