import { makeModule } from '../utils/makeRestStore'
import { buildApi } from '@freshinup/core-ui/src/store/utils/makeRestStore'
export default (initialState = {}) => {
  const { items, item } = initialState
  const documentsApi = buildApi('foodfleet/documents', { items, item })
  const store = makeModule(documentsApi.getStore(), 'documents')

  const sortables = [
    { value: '-created_at', text: 'Newest' },
    { value: 'created_at', text: 'Oldest' },
    { value: 'title', text: 'Title (A - Z)' },
    { value: '-title', text: 'Title (Z - A)' }
  ]

  // Initial State
  store.state = {
    ...store.state,
    sortables
  }
  // Add Mutations
  store.mutations = {
    ...store.mutations,
    sortBy (state, value) {
      state.sortBy = value
    }
  }
  // Add Actions
  store.actions = {
    ...store.actions,
    sortBy ({ commit, dispatch }, value) {
      commit('UPDATE_SORT', { sortBy: value })
    }
  }

  // Add Getters
  store.getters = {
    ...store.getters
  }

  return {
    namespaced: true,
    ...store
  }
}
