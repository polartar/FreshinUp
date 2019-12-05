import makeRestStore from 'fresh-bus/store/utils/makeRestStore'

export default ({ items, item }) => {
  const store = makeRestStore(
    'events',
    { items, item },
    {
      itemsPath: () => `/foodfleet/events`,
      itemPath: ({ id }) => `/foodfleet/events/${id}`
    }
  )

  return {
    namespaced: true,
    ...store,
    modules: {
      stores: makeRestStore(
        'stores',
        { items, item },
        {
          itemsPath: ({ eventId }) => `/foodfleet/events/${eventId}/stores`,
          itemPath: ({ eventId, id }) => `/foodfleet/events/${eventId}/stores/${id}`
        }
      )
    }
  }
}
