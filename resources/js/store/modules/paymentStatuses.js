import makeRestStore from '../utils/makeRestStore'

export default ({ items, item }) => {
  return makeRestStore('foodfleet/payment/statuses', { items, item })
}
