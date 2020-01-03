import makeRestStore from 'fresh-bus/store/utils/makeRestStore'

export default ({ items, item }) => {
  const store = makeRestStore(
    'event-menu-items',
    { items, item },
    {
      itemsPath: () => `/foodfleet/event-menu-items`,
      itemPath: ({ id }) => `/foodfleet/event-menu-items/${id}`
    }
  )

  return {
    namespaced: true,
    ...store
  }
}
