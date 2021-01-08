import { mount } from '@vue/test-utils'
import createStore from 'tests/createStore'
import { FIXTURE_COMPANY, FIXTURE_COMPANY_RESPONSE } from 'tests/__data__/companies'
import { FIXTURE_USERS_RESPONSE } from 'tests/__data__/users'
import createLocalVue from 'vue-cli-plugin-freshinup-ui/utils/testing/createLocalVue'
import Page from '~/pages/admin/companies/_id/edit.vue'

describe('Edit Company Page', () => {
  let localVue, mock, store

  beforeEach(() => {
    const vue = createLocalVue({ validation: true })
    localVue = vue.localVue
    mock = vue.mock
    mock
      .onGet('api/companies/1').reply(200, FIXTURE_COMPANY_RESPONSE)
      .onGet('api/foodfleet/companies/1/members').reply(200, FIXTURE_USERS_RESPONSE)
      .onGet('api/userlevels').reply(200, { data: [] })
      .onGet('api/userstatuses').reply(200, { data: [] })
      .onAny().reply(config => {
        console.warn('No mock match for ' + config.url, config)
        return [404, {}]
      })

    store = createStore({})
  })
  afterEach(() => {
    mock.restore()
  })

  describe('Snapshots', () => {
    it('mocked', (done) => {
      const wrapper = mount(Page, {
        localVue,
        store
      })
      Page.beforeRouteEnterOrUpdate(wrapper.vm, { params: { id: 1 } }, null, async () => {
        await wrapper.vm.$nextTick()
        expect(wrapper.find('.fresh-company__edit').classes()).toContain(`fresh-company__edit--${FIXTURE_COMPANY.status}`)
        expect(wrapper.element).toMatchSnapshot()
        done()
      })
    })
  })

  describe('Computed', () => {
    it('isSupplier and isCustomer', (done) => {
      const wrapper = mount(Page, {
        localVue,
        store
      })

      Page.beforeRouteEnterOrUpdate(wrapper.vm, { params: { id: 1 } }, null, async () => {
        await wrapper.vm.$nextTick()
        expect(wrapper.vm.isSupplier).toEqual(false)
        expect(wrapper.vm.isCustomer).toEqual(false)

        wrapper.vm.$store.state.companies.item.data.company_types = [{ key_id: 'host' }]
        await wrapper.vm.$nextTick()
        expect(wrapper.vm.isSupplier).toEqual(false)
        expect(wrapper.vm.isCustomer).toEqual(true)

        wrapper.vm.$store.state.companies.item.data.company_types = [{ key_id: 'supplier' }]
        await wrapper.vm.$nextTick()
        expect(wrapper.vm.isSupplier).toEqual(true)
        expect(wrapper.vm.isCustomer).toEqual(false)

        wrapper.vm.$store.state.companies.item.data.company_types = [{ key_id: 'host' }, { key_id: 'supplier' }]
        await wrapper.vm.$nextTick()
        expect(wrapper.vm.isSupplier).toEqual(true)
        expect(wrapper.vm.isCustomer).toEqual(true)

        done()
      })
    })
  })

  describe('Methods', () => {
    it('onUsersPaginate', (done) => {
      const wrapper = mount(Page, {
        localVue,
        store
      })

      Page.beforeRouteEnterOrUpdate(wrapper.vm, { params: { id: 1 } }, null, async () => {
        wrapper.vm.onUsersPaginate({ sortBy: 'first_name', descending: false })
        await wrapper.vm.$nextTick()
        const lastIndex = mock.history.get.length - 1
        expect(mock.history.get[lastIndex].params['sort']).toEqual('first_name')
        done()
      })
    })

    it('filterUsers', (done) => {
      const wrapper = mount(Page, {
        localVue,
        store,
        mocks: {
          $route: {}
        }
      })

      Page.beforeRouteEnterOrUpdate(wrapper.vm, { params: { id: 1 } }, null, async () => {
        wrapper.vm.filterUsers({ term: 'test', sort: 'created_at' })
        await wrapper.vm.$nextTick()
        const lastIndex = mock.history.get.length - 1
        expect(mock.history.get[lastIndex].params['term']).toEqual('test')
        done()
      })
    })
  })
})
