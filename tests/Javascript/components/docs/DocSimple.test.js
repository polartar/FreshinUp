import { mount } from '@vue/test-utils'
import createLocalVue from 'vue-cli-plugin-freshinup-ui/utils/testing/createLocalVue'
import Component from '~/components/docs/DocSimple.vue'

const FIXTURE_ITEMS = [
  { id: 1, name: 'John Smith', uuid: 1, other_field: 'John Smith' },
  { id: 2, name: 'Bob Loblaw', uuid: 2, other_field: 'Bob Loblaw' },
  { id: 3, name: 'Mario Brother', uuid: 3, other_field: 'Mario Brother' }
]

describe('DocSimple', () => {
  // Component instance "under test"
  let mock, localVue
  describe('Snapshots', () => {
    beforeEach(() => {
      const vue = createLocalVue({ validation: true })
      localVue = vue.localVue
    })
    test('defaults', () => {
      const wrapper = mount(Component, {
        localVue: localVue,
        propsData: {
          url: 'user',
          termParam: 'filter[name]',
          resultsIdKey: 'uuid',
          resultsTextKey: 'name'
        }
      })
      expect(wrapper.element).toMatchSnapshot()
    })
  })

  describe('Props', () => {
    beforeEach(() => {
      const vue = createLocalVue({ validation: true })
      localVue = vue.localVue
      mock = vue.mock
      mock.onGet('user').reply(200, {
        data: FIXTURE_ITEMS
      })
    })

    afterEach(() => {
      mock.restore()
    })

    test('formatItems function format items', (done) => {
      const wrapper = mount(Component, {
        localVue: localVue,
        propsData: {
          url: 'user',
          termParam: 'filter[name]',
          resultsIdKey: 'uuid',
          resultsTextKey: 'name',
          formatItems: list => {
            return list.map(item => {
              item.name = 'mock name'
              return item
            })
          }
        }
      })
      let field = wrapper.find('input')
      field.element.value = 'Bob'
      field.trigger('input')
      setTimeout(() => {
        expect(wrapper.vm.results[0].name).toEqual('mock name')
        expect(wrapper.vm.items[0].name).toEqual('mock name')
        done()
      }, 301)
    })
  })
})
