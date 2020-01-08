import makeRestStore from '@freshinup/core-ui/src/store/utils/makeRestStore'

export default ({ items, item }) => {
  const store = makeRestStore(
    'event-summary',
    { items, item },
    {
      itemsPath: () => `/foodfleet/event-summary`,
      itemPath: ({ id }) => `/foodfleet/event-summary/${id}`
    }
  )

  return {
    namespaced: true,
    ...store
  }
}
