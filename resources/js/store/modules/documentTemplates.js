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

  store.modules = {
    ...store.modules,
    statuses: makeRestStore('foodfleet/document/template/statuses', { items, item })
  }
  return store
}
