import makeRestStore from '../utils/makeRestStore'

export default ({ items, item }) => {
  return makeRestStore('foodfleet/event-menu-items', { items, item })
}
