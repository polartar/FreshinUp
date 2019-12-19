import { mount } from '@vue/test-utils'
import { createStore } from 'fresh-bus/store'
import createLocalVue from 'vue-cli-plugin-freshinup-ui/utils/testing/createLocalVue'
import Component from '~/components/dashboard/StepsCard.vue'

describe('Dashboard StepsCard Component', () => {
  describe('Snapshots', () => {
    let localVue, store

    beforeEach(() => {
      const vue = createLocalVue({ validation: true })
      localVue = vue.localVue
      store = createStore({})
    })

    it('should match snapshots for common', async () => {
      const wrapper = mount(Component, {
        localVue,
        store,
        propsData: {
          icon: {
            size: 100,
            name: 'icon-users'
          },
          content: {
            title: 'a title',
            description: 'a description',
            button: 'a button'
          },
          navTo: 'myprofile'
        }
      })

      expect(wrapper.element).toMatchSnapshot()
    })

    it('should match snapshots for disabled', async () => {
      const wrapper = mount(Component, {
        localVue,
        store,
        propsData: {
          disabled: true,
          icon: {
            size: 100,
            name: 'icon-users'
          },
          content: {
            title: 'a title',
            description: 'a description',
            button: 'a button'
          },
          navTo: 'myprofile'
        }
      })

      expect(wrapper.element).toMatchSnapshot()
    })
  })
  describe('Computed', () => {
    describe('iconColor', () => {
      let localVue, store

      beforeEach(() => {
        const vue = createLocalVue({ validation: true })
        localVue = vue.localVue
        store = createStore({})
      })

      it('should be primary color', async () => {
        const wrapper = mount(Component, {
          localVue,
          store,
          propsData: {
            icon: {
              size: 100,
              name: 'icon-users'
            },
            content: {
              title: 'a title',
              description: 'a description',
              button: 'a button'
            },
            navTo: 'myprofile'
          }
        })

        expect(wrapper.vm.iconColor).toEqual('primary')
      })

      it('should be grey color same as disabled button', async () => {
        const wrapper = mount(Component, {
          localVue,
          store,
          propsData: {
            disabled: true,
            icon: {
              size: 100,
              name: 'icon-users'
            },
            content: {
              title: 'a title',
              description: 'a description',
              button: 'a button'
            },
            navTo: 'myprofile'
          }
        })

        expect(wrapper.vm.iconColor).toEqual('rgba(0, 0, 0, 0.12)')
      })
    })
  })
})
