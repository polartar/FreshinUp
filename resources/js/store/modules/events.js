import makeRestStore from 'fresh-bus/store/utils/makeRestStore'
import omitBy from 'lodash/omitBy'
import isNull from 'lodash/isNull'

export default ({ items, item }) => {
  const store = makeRestStore(
    'events',
    { items, item },
    {
      itemsPath: () => `/foodfleet/events`,
      itemPath: ({ id }) => `/foodfleet/events/${id}`
    }
  )

  const _updateItem = store.actions.updateItem
  store.actions = {
    ...store.actions,
    updateItem (context, payload) {
      payload.data = omitBy(payload.data, (value) => String(value).length === 0 || isNull(value))
      return _updateItem(context, payload)
    }
  }

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
