import makeRestStore from 'fresh-bus/store/utils/makeRestStore'

export default (initialState = {}) => {
  const store = makeRestStore(
    'devices',
    { item: initialState.item, items: initialState.items },
    {
      itemsPath: () => `/foodfleet/devices`,
      itemPath: ({ id }) => `/foodfleet/devices/${id}`
    }
  )

  return {
    namespaced: true,
    ...store
  }
}
