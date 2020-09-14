import makeRestStore from '@freshinup/core-ui/src/store/utils/makeRestStore'
import omitBy from 'lodash/omitBy'
import isNull from 'lodash/isNull'
import omit from 'lodash/omit'
import moment from 'moment'

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
  const stripeUnwantedObject = (data) => {
    return omit(data, ['event_recurring_checked', 'event_tags', 'host', 'venue', 'manager', 'status', 'type', 'location'])
  }
  store.actions = {
    ...store.actions,
    createItem (context, payload) {
      payload.data = stripeUnwantedObject(payload.data)

      const today = moment()
      const tomorrow = moment().add(1, 'day')
      const startsInTheFuture = moment(payload.data.start_at).diff(today) > 0
      const endsInTheFuture = moment(payload.data.end_at).diff(today) > 0
      if (!startsInTheFuture) {
        payload.data.start_at = `${tomorrow.format('YYYY-MM-DD')} 00:00`
      }
      if (!endsInTheFuture) {
        payload.data.end_at = `${tomorrow.format('YYYY-MM-DD')} 23:59`
      }
      return __createItem(context, payload)
    }
  }

  const _updateItem = store.actions.updateItem
  store.actions = {
    ...store.actions,
    updateItem (context, payload) {
      payload.data = stripeUnwantedObject(payload.data)
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
