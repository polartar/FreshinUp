import isObject from 'lodash/isObject'
import makeRestStore, {
  buildApi,
  makeModule
} from '@freshinup/core-ui/src/store/utils/makeRestStore'

export default ({ items, item }) => {
  const storesApi = buildApi('foodfleet/stores', { items, item })
  const store = makeModule(storesApi.getStore(), 'stores')

  const sortables = [
    { value: '-created_at', text: 'Newest' },
    { value: 'created_at', text: 'Oldest' },
    { value: 'name', text: 'Name (A - Z)' },
    { value: '-name', text: 'Name (Z - A)' }
  ]

  // Initial State
  store.state = {
    ...store.state,
    sortables
  }

  const _createItem = store.actions.createItem
  store.actions = {
    ...store.actions,
    createItem (context, payload) {
      if (payload.data.tags) {
        payload.data.tags = payload.data.tags.map(tag => {
          return isObject(tag) ? tag.uuid : tag
        })
      }
      return _createItem(context, payload)
    }
  }

  return {
    namespaced: true,
    ...store,
    modules: {
      summary: makeRestStore(
        'summary',
        { item },
        {
          itemPath: ({ id }) => `/foodfleet/store-summary/${id}`
        }
      ),
      serviceSummary: makeRestStore(
        'serviceSummary',
        { item },
        {
          itemPath: ({ id }) => `/foodfleet/store-service-summary/${id}`
        }
      )
    }
  }
}
