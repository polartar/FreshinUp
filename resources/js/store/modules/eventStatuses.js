import makeRestStore from '@freshinup/core-ui/src/store/utils/makeRestStore'

export default ({ items, item }) => {
  return makeRestStore('foodfleet/event-statuses', { items, item })
}
