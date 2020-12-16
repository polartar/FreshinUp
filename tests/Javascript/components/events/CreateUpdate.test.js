import { mount, shallowMount } from '@vue/test-utils'
import createLocalVue from 'vue-cli-plugin-freshinup-ui/utils/testing/createLocalVue'
import { FIXTURE_EVENT, EMPTY_EVENT } from 'tests/__data__/event'
import { FIXTURE_EVENT_STATUSES } from 'tests/__data__/eventStatuses'
import BaseComponent from '~/components/events/CreateUpdate.vue'
import { getFileNameCopy } from '~/utils'
import { FIXTURE_EVENT_HISTORIES } from 'tests/__data__/eventHistory'
import createStore from 'tests/createStore'

describe('components/events/CreateUpdate', () => {
  let localVue, mock, store
  describe('Visuals', () => {
    beforeEach(() => {
      const vue = createLocalVue({ validation: true })
      localVue = vue.localVue
      mock = vue.mock
      mock
        .onGet(/(companies|users)(.*)/).reply(200, { data: [] })
        .onGet('api/foodfleet/events/1').reply(200, { data: FIXTURE_EVENT })
        .onGet('api/foodfleet/events/new').reply(200, { data: EMPTY_EVENT })
        .onGet('api/foodfleet/event-statuses').reply(200, { data: FIXTURE_EVENT_STATUSES })
        .onGet('api/foodfleet/event/status/histories').reply(200, { data: FIXTURE_EVENT_HISTORIES })
        .onAny().reply(config => {
          console.warn('No mock match for ' + config.url, config)
          return [404, {}]
        })
      store = createStore()
    })

    afterEach(() => {
      mock.restore()
    })

    it.skip('mount create page', async () => {
      const Component = {
        extends: BaseComponent,
        layout: BaseComponent.layout,
        beforeRouteEnterOrUpdate: BaseComponent.beforeRouteEnterOrUpdate,
        data () {
          return {
            isNew: true
          }
        }
      }
      const wrapper = mount(Component, {
        localVue,
        store
      })
      await wrapper.vm.$store.dispatch('events/getItem', { params: { id: 'new' } })
      await wrapper.vm.$store.dispatch('eventStatuses/getItems')
      await wrapper.vm.$store.dispatch('page/setLoading', false)
      await wrapper.vm.$nextTick()
      expect(wrapper.html()).toContain('New Event')
      expect(wrapper.html()).not.toContain(FIXTURE_EVENT.name)
      expect(wrapper.element).toMatchSnapshot()
    })

    it.skip('mount edit page', async () => {
      const Component = {
        extends: BaseComponent,
        layout: BaseComponent.layout,
        beforeRouteEnterOrUpdate: BaseComponent.beforeRouteEnterOrUpdate
      }
      const wrapper = mount(Component, {
        localVue,
        store
      })
      await wrapper.vm.$store.dispatch('events/getItem', { params: { id: 1 } })
      await wrapper.vm.$store.dispatch('eventStatuses/getItems')
      await wrapper.vm.$store.dispatch('eventHistories/getItems', { params: { events_uuid: wrapper.vm.event.uuid } })
      await wrapper.vm.$store.dispatch('page/setLoading', false)
      await wrapper.vm.$nextTick()
      expect(wrapper.html()).toContain('Event Details')
      expect(wrapper.vm.event.uuid).toBe(FIXTURE_EVENT.uuid)
      expect(wrapper.element).toMatchSnapshot()
    })
  })

  describe('Methods', () => {
    beforeEach(() => {
      const vue = createLocalVue({ validation: true })
      localVue = vue.localVue
      mock = vue.mock
      mock
        .onGet('api/foodfleet/events/1').reply(200, { data: FIXTURE_EVENT })
        .onGet('api/foodfleet/events/new').reply(200, { data: EMPTY_EVENT })
        .onGet('api/foodfleet/event-statuses').reply(200, { data: FIXTURE_EVENT_STATUSES })
        .onGet('api/foodfleet/event/status/histories').reply(200, { data: FIXTURE_EVENT_HISTORIES })
        .onAny().reply(config => {
          console.warn('No mock match for ' + config.url, config)
          return [404, {}]
        })
      store = createStore()
    })

    afterEach(() => {
      mock.restore()
    })

    test('getFileNameCopy ', async () => {
      expect(getFileNameCopy('aliquam')).toEqual('Copy of aliquam (1)')
      expect(getFileNameCopy('aliquam (1)')).toEqual('Copy of aliquam (2)')
      expect(getFileNameCopy('aliquam (2)')).toEqual('Copy of aliquam (3)')
    })

    test.skip('changeBasicInfo function change event', async () => {
      const wrapper = shallowMount(BaseComponent, {
        localVue: localVue,
        store
      })
      await wrapper.vm.$store.dispatch('events/getItem', { params: { id: 1 } })
      wrapper.vm.changeBasicInfo({
        name: 'mock event name'
      })
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.event.name).toBe('mock event name')
    })
  })
})
