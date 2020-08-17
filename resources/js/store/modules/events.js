import makeRestStore from 'fresh-bus/store/utils/makeRestStore'
import omitBy from 'lodash/omitBy'
import isNull from 'lodash/isNull'
import omit from 'lodash/omit'

export default ({ items, item }) => {
  const store = makeRestStore(
    'events',
    { items, item },
    {
      itemsPath: () => `/foodfleet/events`,
      itemPath: ({ id }) => `/foodfleet/events/${id}`
    }
  )
  const __createItem = store.actions.createItem
  store.actions = {
    ...store.actions,
    createItem (context, payload) {
      payload.data = omit(payload.data, ['event_recurring_checked', 'event_tags', 'host', 'venue', 'manager', 'status'])
      return __createItem(context, payload)
    }
  }

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
