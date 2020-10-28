
import AppComponent from 'fresh-bus/App'
import App from '@freshinup/core-ui/src/App'
import axios from 'axios'
import Vue from 'vue'
import { install as installAuthRouter } from 'fresh-bus/router/auth'

// Providers
import FreshBusProvider from 'fresh-bus/Provider'
import CoreProvider from '@freshinup/core-ui/src/Provider'
import ClientProvider from './Provider'

import theme from './theme'
import NotFoundPage from 'fresh-bus/pages/404.vue'

const navigationAdmin = {
  title: 'Food Fleet',
  logo: '/images/logo.png',
  items: [
    {
      action: 'icon-dashboard',
      title: 'Dashboard',
      to: '/admin#'
    },
    {
      action: 'icon-users',
      title: 'Users',
      to: '/admin/users'
    },
    {
      action: 'icon-companies',
      title: 'Companies',
      to: '/admin/companies'
    },
    {
      action: 'icon-trucks',
      title: 'Fleet Members',
      to: '/admin/fleet-members'
    },
    {
      action: 'icon-events',
      title: 'Events',
      to: '/admin/events'
    },
    {
      action: 'icon-venues',
      title: 'Venues',
      to: '/admin/venues'
    },
    {
      action: 'icon-documents',
      title: 'Documents',
      to: '/admin/docs'
    },
    {
      action: 'icon-templates',
      title: 'Doc. Templates',
      to: '/admin/doc-templates'
    },
    {
      action: 'icon-financial',
      title: 'Financials',
      to: '/admin/financials'
    },
    {
      action: 'icon-reports',
      title: 'Analytics',
      to: '/admin/analytics'
    }
  ]
}
const navigation = {
  title: navigationAdmin.title,
  logo: navigationAdmin.logo,
  // for small screens
  drawerItems: navigationAdmin.items,

  items: []
}

const initialState = {
  loginSuccessRedirectPath: '/admin/dashboard',
  requiredCompanyForRegister: false,
  page: {
    loadingColor: 'accent'
  },
  navigation,
  navigationAdmin,
  ...window.__INITIAL_STATE__
}

const appInstance = new App({
  modules: [
    CoreProvider,
    FreshBusProvider,
    ClientProvider,
  ],
  initialState,
  theme,
  redirectOnNotFound: false,
  NotFoundPage,
  axios,
  Vue,
  middleware: require.context('../../vendor/freshinup/fresh-bus-forms/resources/assets/js/middleware/', true, /\.js$/)
})

installAuthRouter(Vue)
appInstance.boot(AppComponent)
appInstance.getRouter().addRoutes([ { path: '/', redirect: '/admin' }, { path: '', redirect: '/admin' } ])

// We may consider only exposing the app when a certain key is set (true EXPOSE_APP=true)
window.__APP__ = appInstance
export default appInstance
