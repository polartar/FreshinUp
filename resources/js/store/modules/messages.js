import makeRestStore from 'fresh-bus/store/utils/makeRestStore'

export default ({ items, item }) => {
  const store = makeRestStore(
    'messages',
    { items, item },
    {
      itemsPath: () => `/foodfleet/messages`,
      itemPath: ({ id }) => `/foodfleet/messages/${id}`
    }
  )

  return {
    namespaced: true,
    ...store
  }
}
