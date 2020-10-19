import { apiMocked, axios, Vue } from './Instances'
import Provider from '../../resources/js/Provider'
import CoreProvider from '@freshinup/core-ui/src/Provider'
import FreshBusProvider from 'fresh-bus/Provider'

const providers = [
  FreshBusProvider(),
  CoreProvider(),
  Provider()
]

export const resetRoutes = (routes) => {
  apiMocked
    .reset()
  return apiMocked.addRoutes(routes)
    .ready()
}

export { apiMocked, providers, Vue, axios }

export default {
  axios,
  apiMocked,
  providers,
  Vue
}
