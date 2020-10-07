import omit from 'lodash/omit'
import makeRestStore from '../utils/makeRestStore'

export default ({ items, item }) => {
  const store = makeRestStore('foodfleet/document/templates', { items, item })
  store.getters = {
    ...store.getters,
    sortables (state) {
      return [
        { value: '-created_at', text: 'Newest' },
        { value: 'created_at', text: 'Oldest' },
        { value: 'title', text: 'Title (A - Z)' },
        { value: '-title', text: 'Title (Z - A)' }
      ]
    }
  }

  const _createItem = store.actions.createItem
  const _updateItem = store.actions.updateItem
  const unwantedKeys = ['id', 'uuid', 'status', 'updated_by', 'updated_at', 'created_at']
  store.actions = {
    ...store.actions,
    createItem (context, payload) {
      payload.data = omit(payload.data, unwantedKeys)
      return _createItem(context, payload)
    },
    updateItem (context, payload) {
      payload.data = omit(payload.data, unwantedKeys)
      return _updateItem(context, payload)
    }
  }

  store.modules = {
    ...store.modules,
    statuses: makeRestStore('foodfleet/document/template/statuses', { items, item })
  }
  return store
}
