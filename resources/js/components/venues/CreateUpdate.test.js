import { mount } from '@vue/test-utils'
import createLocalVue from 'vue-cli-plugin-freshinup-ui/utils/testing/createLocalVue'
import BaseComponent from '~/components/venues/CreateUpdate.vue'
import { FIXTURE_VENUE } from '../../../../tests/Javascript/__data__/venues'
import { FIXTURE_VENUE_STATUSES } from '../../../../tests/Javascript/__data__/venueStatuses'
import createStore from 'tests/createStore'

describe.skip('components/venues/CreateUpdate', () => {
  let localVue, mock, store
  describe('Visuals', () => {
    const venue = FIXTURE_VENUE
    beforeEach(() => {
      const vue = createLocalVue({ validation: true })
      localVue = vue.localVue
      mock = vue.mock
      mock
        .onGet('/api/foodfleet/venues/abc111').reply(200, { data: venue })
        .onGet('api/foodfleet/venues/statuses').reply(200, { data: FIXTURE_VENUE_STATUSES })
        .onAny().reply(config => {
          console.warn('No mock match for ' + config.url, config)
          return [404, {}]
        })
      store = createStore()
    })

    afterEach(() => {
      mock.restore()
    })

    it('mount create page', async () => {
      const Component = {
        extends: BaseComponent,
        layout: BaseComponent.layout,
        beforeRouteEnterOrUpdate: BaseComponent.beforeRouteEnterOrUpdate,
        computed: {
          isNew () { return true }
        }
      }
      const wrapper = mount(Component, {
        localVue,
        store
      })
      await wrapper.vm.$store.dispatch('page/setLoading', false)
      await wrapper.vm.$store.dispatch('venueStatuses/getItems')
      await wrapper.vm.$nextTick()
      expect(wrapper.element).toMatchSnapshot()
    })

    it('mount edit page', async () => {
      const Component = {
        extends: BaseComponent,
        layout: BaseComponent.layout,
        beforeRouteEnterOrUpdate: BaseComponent.beforeRouteEnterOrUpdate
      }
      const wrapper = mount(Component, {
        localVue,
        store
      })
      await wrapper.vm.$store.dispatch('venues/getItem', { params: { id: FIXTURE_VENUE.uuid } })
      await wrapper.vm.$store.dispatch('venueStatuses/getItems')
      await wrapper.vm.$store.dispatch('page/setLoading', false)
      await wrapper.vm.$nextTick()
      expect(wrapper.html()).toContain('Venue Details')
      expect(wrapper.vm.venue.uuid).toBe(FIXTURE_VENUE.uuid)
      expect(wrapper.element).toMatchSnapshot()
    })
  })

  describe('Methods', () => {
    beforeEach(() => {
      const vue = createLocalVue({ validation: true })
      localVue = vue.localVue
      mock = vue.mock
      mock
        .onGet('api/foodfleet/venues/'.FIXTURE_VENUE.uuid).reply(200, { data: FIXTURE_VENUE })
        .onGet('api/foodfleet/venue/statuses').reply(200, { data: FIXTURE_VENUE_STATUSES })
        .onAny().reply(config => {
          console.warn('No mock match for ' + config.url, config)
          return [404, {}]
        })
      store = createStore()
    })

    afterEach(() => {
      mock.restore()
    })
  })
})
