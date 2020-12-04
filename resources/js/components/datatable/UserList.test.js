import { createLocalVue, shallowMount, mount } from '@vue/test-utils'
import Component from './UserList'
import { FIXTURE_USER_STATUSES } from '../../../../tests/Javascript/__data__/userStatuses'
import { FIXTURE_USERS } from '../../../../tests/Javascript/__data__/users'
import { FIXTURE_USER_LEVELS } from '../../../../tests/Javascript/__data__/userLevels'

describe('User List component', () => {
  // Component instance "under test"
  let localVue
  describe('Snapshots', () => {
    test('Set', () => {
      localVue = createLocalVue()
      const wrapper = mount(Component, {
        localVue: localVue,
        propsData: {
          statuses: FIXTURE_USER_STATUSES,
          users: FIXTURE_USERS,
          levels: FIXTURE_USER_LEVELS
        }
      })
      expect(wrapper.element).toMatchSnapshot()
    })
    test('Empty', () => {
      localVue = createLocalVue()
      const wrapper = mount(Component, {
        localVue: localVue,
        propsData: {
          statuses: FIXTURE_USER_STATUSES,
          levels: FIXTURE_USER_LEVELS,
          users: []
        }
      })
      expect(wrapper.element).toMatchSnapshot()
    })
  })
  describe('Methods', () => {
    beforeEach(() => {
      localVue = createLocalVue()
    })

    test('manage function emitted single manage action', () => {
      const wrapper = shallowMount(Component, {
        localVue
      })

      const itemActions = [
        { action: 'edit', text: 'Edit' },
        { action: 'delete', text: 'Delete' },
        { action: 'view', text: 'View' }
      ]
      const mockEvent = { id: 1, title: 'mock title', status: 1 }

      wrapper.vm.manage(itemActions[0], mockEvent)
      wrapper.vm.manage(itemActions[1], mockEvent)
      wrapper.vm.manage(itemActions[2], mockEvent)

      expect(wrapper.emitted()['manage-edit']).toBeTruthy()
      expect(wrapper.emitted()['manage-delete']).toBeTruthy()
      expect(wrapper.emitted()['manage-view']).toBeTruthy()
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

      const mockEvent = { id: 1, title: 'mock title', status: 1 }

      wrapper.vm.changeStatus(2, mockEvent)

      expect(wrapper.emitted()['change-status']).toBeTruthy()
      expect(wrapper.emitted()['change-status'][0][0]).toEqual(2)
    })

    test('changeStatusMultiple function emitted change-status-multiple action', () => {
      const wrapper = shallowMount(Component, {
        localVue
      })

      wrapper.vm.changeStatusMultiple(3)

      expect(wrapper.emitted()['change-status-multiple']).toBeTruthy()
      expect(wrapper.emitted()['change-status-multiple'][0][0]).toEqual(3)
    })
  })
})
