import makeRestStore from '../utils/makeRestStore'

export const PAYMENT_STATUS = {
  PENDING: 1,
  OVERDUE: 2,
  PAID: 3,
  FAILED: 4,
  REFUNDED: 5
}

export default ({ items, item }) => {
  return makeRestStore('foodfleet/payment/statuses', { items, item })
}
