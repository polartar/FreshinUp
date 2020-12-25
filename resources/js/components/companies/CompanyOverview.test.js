import { mount, shallowMount } from '@vue/test-utils'
import * as Stories from './CompanyOverview.stories'
import Component, { DEFAULT_COMPANY, DEFAULT_IMAGE } from './CompanyOverview'
import { FIXTURE_COMPANY_TYPES } from 'tests/__data__/companyTypes'
import { FIXTURE_COMPANY } from 'tests/__data__/companies'
import { FIXTURE_COMPANY_STATUSES } from '../../../../tests/Javascript/__data__/companyStatuses'
import omit from 'lodash/omit'

describe('components/companies/CompanyOverview', () => {
  describe('Snapshots', () => {
    test('Empty', () => {
      const wrapper = mount(Stories.Empty())
      expect(wrapper.element).toMatchSnapshot()
    })
    test('Populated', () => {
      const wrapper = mount(Stories.Populated())
      expect(wrapper.element).toMatchSnapshot()
    })
    test('Loading', () => {
      const wrapper = mount(Stories.Loading())
      expect(wrapper.element).toMatchSnapshot()
    })
  })

  describe('Props & Computed', () => {
    test('value', () => {
      const wrapper = shallowMount(Component)
      expect(wrapper.vm.value).toMatchObject(DEFAULT_COMPANY)

      const value = FIXTURE_COMPANY
      wrapper.setProps({
        value
      })
      expect(wrapper.vm.value).toMatchObject(value)
    })
    test('isLoading', () => {
      const wrapper = shallowMount(Component)
      expect(wrapper.vm.isLoading).toEqual(false)

      wrapper.setProps({
        isLoading: true
      })
      expect(wrapper.vm.isLoading).toEqual(true)
    })
    test('types', () => {
      const wrapper = shallowMount(Component)
      expect(wrapper.vm.types).toHaveLength(0)

      const types = FIXTURE_COMPANY_TYPES
      wrapper.setProps({
        types
      })
      expect(wrapper.vm.types).toMatchObject(types)
    })
    test('statuses', () => {
      const wrapper = shallowMount(Component)
      expect(wrapper.vm.statuses).toHaveLength(0)

      const statuses = FIXTURE_COMPANY_TYPES
      wrapper.setProps({
        statuses
      })
      expect(wrapper.vm.statuses).toMatchObject(statuses)
    })
    test('companyLogo', async () => {
      const wrapper = shallowMount(Component)
      expect(wrapper.vm.companyLogo).toEqual(DEFAULT_IMAGE)

      const logo = 'https://avatars3.githubusercontent.com/u/17571380?s=60&v=4'
      wrapper.setProps({
        value: {
          logo
        }
      })
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.companyLogo).toEqual(logo)
    })
    test('typesById', async () => {
      const wrapper = shallowMount(Component)
      expect(wrapper.vm.typesById).toMatchObject({})

      wrapper.setProps({
        types: [
          { 'id': 1, 'key_id': 'supplier', 'name': 'Supplier' },
          { 'id': 2, 'key_id': 'customer', 'name': 'Customer' }
        ]
      })
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.typesById).toMatchObject({
        1: { 'id': 1, 'key_id': 'supplier', 'name': 'Supplier' },
        2: { 'id': 2, 'key_id': 'customer', 'name': 'Customer' }
      })
    })
    test('typeName', async () => {
      const wrapper = shallowMount(Component)
      expect(wrapper.vm.typeName).toBeUndefined()

      const types = FIXTURE_COMPANY_TYPES
      const type = FIXTURE_COMPANY_TYPES[0]
      wrapper.setProps({
        types,
        value: {
          type_id: type.id
        }
      })
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.typeName).toEqual(wrapper.vm.typesById[type.id].name)
    })
    test('statusesById', async () => {
      const wrapper = shallowMount(Component)
      expect(wrapper.vm.statusesById).toMatchObject({})

      const statuses = FIXTURE_COMPANY_STATUSES
      wrapper.setProps({
        statuses
      })
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.statusesById).toMatchObject(statuses.reduce((map, status) => {
        map[status.id] = status
        return map
      }, {}))
    })
    test('statusName', async () => {
      const wrapper = shallowMount(Component)
      expect(wrapper.vm.statusName).toBeUndefined()

      const statuses = FIXTURE_COMPANY_STATUSES
      const status = FIXTURE_COMPANY_STATUSES[0]
      wrapper.setProps({
        statuses,
        value: {
          status: status.id
        }
      })
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.statusName).toEqual(wrapper.vm.statusesById[status.id].name)
    })
    test('isEmptyCompany', async () => {
      const wrapper = shallowMount(Component)
      expect(wrapper.vm.isEmptyCompany).toBe(true)

      wrapper.setProps({
        value: {
          name: 'Some random name'
        }
      })
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.isEmptyCompany).toBe(false)
    })
  })

  describe('Methods', () => {
    test('viewDetails()', async () => {
      const wrapper = shallowMount(Component)
      const item = omit(FIXTURE_COMPANY, ['admin', 'members'])
      wrapper.setProps({
        value: item
      })
      await wrapper.vm.$nextTick()
      wrapper.vm.viewDetails()
      await wrapper.vm.$nextTick()
      const manage = wrapper.emitted().manage
      expect(manage).toBeTruthy()
      expect(manage[0][0]).toEqual('view')
      expect(manage[0][1]).toMatchObject(item)
      const manageView = wrapper.emitted()['manage-view']
      expect(manageView).toBeTruthy()
      expect(manageView[0][0]).toMatchObject(item)
    })
  })
})
