import busUsers from 'fresh-bus/store/modules/users'
import axios from 'axios'

export default ({ items, item }) => {
  const store = busUsers({ items, item }) //
  store.actions = {
    ...store.actions,
    createCustomerOrSupplier (context, { data }) {
      return new Promise((resolve, reject) => {
        axios({
          url: '/foodfleet/users/customer-or-supplier',
          method: 'POST',
          data
        })
          .then(response => resolve(response.data))
          .catch(error => reject(error))
      })
    }
  }
  return {
    ...store,
    namespaced: true
  }
}
