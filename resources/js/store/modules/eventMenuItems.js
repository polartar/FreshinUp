import makeRestStore from '../utils/makeRestStore'

export default ({ items, item }) => {
  return makeRestStore('event-menu-items', { items, item })
}
