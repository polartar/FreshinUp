import { mount, shallowMount } from '@vue/test-utils'
import Component, { DEFAULT_IMAGE } from './BasicInformation.vue'
import * as Stories from './BasicInformation.stories'
import { FIXTURE_USER_LEVELS } from '../../../../tests/Javascript/__data__/userLevels'
import { FIXTURE_USER_TYPES } from '../../../../tests/Javascript/__data__/userTypes'
import { FIXTURE_USER_STATUSES } from '../../../../tests/Javascript/__data__/userStatuses'
import { FIXTURE_USER } from '../../../../tests/Javascript/__data__/user'
import { FIXTURE_COMPANIES } from '../../../../tests/Javascript/__data__/companies'

describe('components/users/BasicInformation', () => {
  describe('Snapshots', () => {
    test('Default', () => {
      const wrapper = mount(Stories.Default())
      expect(wrapper.element).toMatchSnapshot()
    })
    test('IsLoading', () => {
      const wrapper = mount(Stories.IsLoading())
      expect(wrapper.element).toMatchSnapshot()
    })
    test('BasicView', () => {
      const wrapper = mount(Stories.BasicView())
      expect(wrapper.element).toMatchSnapshot()
    })
    test('AdminView', () => {
      const wrapper = mount(Stories.AdminView())
      expect(wrapper.element).toMatchSnapshot()
    })
  })

  describe('Props & Computed', () => {
    test('isLoading', async () => {
      const wrapper = shallowMount(Component)
      expect(wrapper.vm.isLoading).toBe(false)

      wrapper.setProps({
        isLoading: true
      })
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.isLoading).toBe(true)
    })
    test('isAdmin', async () => {
      const wrapper = shallowMount(Component)
      expect(wrapper.vm.isAdmin).toBe(false)

      wrapper.setProps({
        isAdmin: true
      })
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.isAdmin).toBe(true)
    })
    test('levels', async () => {
      const wrapper = shallowMount(Component)
      expect(wrapper.vm.levels).toHaveLength(0)

      wrapper.setProps({
        levels: FIXTURE_USER_LEVELS
      })
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.levels).toMatchObject(FIXTURE_USER_LEVELS)
    })
    test('types', async () => {
      const wrapper = shallowMount(Component)
      expect(wrapper.vm.types).toHaveLength(0)

      wrapper.setProps({
        types: FIXTURE_USER_TYPES
      })
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.types).toMatchObject(FIXTURE_USER_TYPES)
    })
    test('statuses', async () => {
      const wrapper = shallowMount(Component)
      expect(wrapper.vm.statuses).toHaveLength(0)

      wrapper.setProps({
        statuses: FIXTURE_USER_STATUSES
      })
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.statuses).toMatchObject(FIXTURE_USER_STATUSES)
    })
    // computed
    test('isEditing', async () => {
      const wrapper = shallowMount(Component)
      expect(wrapper.vm.isEditing).toBe(false)

      wrapper.setProps({
        value: {
          uuid: 'abc123'
        }
      })
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.isEditing).toBe(true)
    })
    test('hasImage & storeImage', async () => {
      const wrapper = shallowMount(Component)
      expect(wrapper.vm.hasImage).toBe(false)
      expect(wrapper.vm.storeImage).toBe(DEFAULT_IMAGE)

      wrapper.setProps({
        value: {
          avatar: DEFAULT_IMAGE
        }
      })
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.hasImage).toBe(false)
      expect(wrapper.vm.storeImage).toBe(DEFAULT_IMAGE)

      const avatar = 'https://zube.io/avatars/32b9f73e8371329f72775f60adf6c597.jpg'
      wrapper.setProps({
        value: {
          avatar
        }
      })
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.hasImage).toBe(true)
      expect(wrapper.vm.storeImage).toBe(avatar)
    })
  })

  describe('Methods', () => {
    test('selectManager(user)', async () => {
      const wrapper = shallowMount(Component)
      expect(wrapper.vm.manager_uuid).toEqual('')

      const user = FIXTURE_USER
      wrapper.vm.selectManager(user)
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.manager_uuid).toEqual(user.uuid)
    })
    test('selectCompany(company)', async () => {
      const wrapper = shallowMount(Component)
      expect(wrapper.vm.company_id).toBeNull()

      const company = FIXTURE_COMPANIES[0]
      wrapper.vm.selectCompany(company)
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.company_id).toEqual(company.id)
    })
    test('onCancel()', async () => {
      const wrapper = shallowMount(Component)
      wrapper.vm.onCancel()
      const emitted = wrapper.emitted().cancel
      expect(emitted).toBeTruthy()
    })
    test('onChangePassword()', async () => {
      const wrapper = shallowMount(Component)
      wrapper.vm.onChangePassword()
      const emitted = wrapper.emitted()['change-password']
      expect(emitted).toBeTruthy()
    })
  })
})
