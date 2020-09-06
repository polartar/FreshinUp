import makeRestStore from '@freshinup/core-ui/src/store/utils/makeRestStore'

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
