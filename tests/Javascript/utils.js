import { createLocalVue as _createLocalVue } from '@vue/test-utils'
import Vuex from 'vuex'
import axios from 'axios'
import MockAdapter from 'axios-mock-adapter'
import { install as installValidationCore } from 'fresh-bus/validation'

export const installValidation = (Vue) => {
  installValidationCore(Vue)
}

export const createLocalVue = (options = { validation: false, vuex: true }) => {
  const localVue = _createLocalVue()
  const mock = new MockAdapter(axios)
  if (options.validation) {
    installValidation(localVue)
  }
  if (options.vuex) {
    localVue.use(Vuex)
  }
  return {
    mock,
    localVue
  }
}
