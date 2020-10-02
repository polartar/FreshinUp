import makeRestStore from '../utils/makeRestStore'

export default function ({ items, item }) {
  return makeRestStore('foodfleet/menu-items', { items, item })
}
