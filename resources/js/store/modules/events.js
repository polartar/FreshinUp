import makeRestStore from 'fresh-bus/store/utils/makeRestStore'

export default (initialState = {}) => {
  const store = makeRestStore(
    'events',
    { item: initialState.item, items: initialState.items },
    {
      itemsPath: () => `/foodfleet/events`,
      itemPath: ({ id }) => `/foodfleet/events/${id}`
    }
  )

  return {
    namespaced: true,
    ...store
  }
}
