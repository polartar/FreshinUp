import Component from './CreateUpdate'
import { shallowMount } from '@vue/test-utils'
import createLocalVue from 'vue-cli-plugin-freshinup-ui/utils/testing/createLocalVue'
import createStore from 'tests/createStore'
import { mount } from 'vue-cli-plugin-freshinup-ui/utils/testing'
import { FIXTURE_DOCUMENT_TEMPLATES } from '../../../../tests/Javascript/__data__/documentTemplates'

describe('components/doc-templates/CreateUpdate', () => {
  describe('Snapshots', () => {
    let store, mock, localVue
    beforeEach(() => {
      store = createStore()
      let v = createLocalVue()
      localVue = v.localVue
      mock = v.mock
    })
    test('new', (done) => {
      const wrapper = mount(Component, {
        localVue,
        store
      })
      Component.beforeRouteEnterOrUpdate(wrapper.vm, { params: { id: 'new' }}, null, () => {
        expect(wrapper.element).toMatchSnapshot()
        done()
      })
    })
    test('edit', (done) => {
      mock.onGet(/.*foodfleet\/document\/template\/[A-z0-9]+/)
        .reply(200, { data: FIXTURE_DOCUMENT_TEMPLATES })
      const wrapper = mount(Component, {
        localVue,
        store
      })
      Component.beforeRouteEnterOrUpdate(wrapper.vm, { params: { id: FIXTURE_DOCUMENT_TEMPLATES[0].uuid }}, null, () => {
        expect(wrapper.element).toMatchSnapshot()
        done()
      })
    })
  })
  describe('Methods', () => {
    let store, mock, localVue
    beforeEach(() => {
      const v = createLocalVue()
      mock = v.mock
      store = createStore()
    })
    describe('errorVars', () => {
      test('when no variables', async () => {
        const wrapper = shallowMount(Component, {
          localVue,
          store
        })
        const errorVars = wrapper.vm.errorVars(`
          This is a sample text with no variables at all
        `, {})
        expect(errorVars).toHaveLength(0)
      })
      test('when one variable is not valid', async () => {
        const wrapper = shallowMount(Component, {
          localVue,
          store
        })
        const errorVars = wrapper.vm.errorVars(`
          This is a sample text with {{ foo }} as variable at all but not {{ bar }}
        `, {
          foo: 'abc'
        })
        expect(errorVars).toHaveLength(1)
        expect(errorVars).toContain('bar')
      })
      test('when all variables are valid', async () => {
        const wrapper = shallowMount(Component, {
          localVue,
          store
        })
        const errorVars = wrapper.vm.errorVars(`
          This is a sample text with {{ foo }} as variable at all but not {{ bar }}
        `, {
          foo: 'abc',
          bar: 123
        })
        expect(errorVars).toHaveLength(0)
      })
    })
  })
})
