import {
  App,
  createStore
} from 'fresh-bus'
import NotFoundPage from 'fresh-bus/pages/404.vue'

const initialState = {
  loginSuccessRedirectPath: '/admin',
  ...window.__INITIAL_STATE__
}
const appInstance = new App({
  components: {},
  store: createStore({
    navigation: {
      title: 'Food Fleet',
      drawerItems: [],
      items: []
    },
    navigationAdmin: {
      title: 'Food Fleet',
      logo: '/images/logo.png',
      items: [
        {
          title: 'Dashboard',
          to: '/admin'
        },
        {
          title: 'Users',
          to: '/admin/users'
        },
        {
          title: 'Companies',
          to: '/admin/companies'
        },
        {
          title: 'Fleet Members',
          to: '/admin/fleet-members'
        },
        {
          title: 'Events',
          to: '/admin/events'
        },
        {
          title: 'Venues',
          to: '/admin/venues'
        },
        {
          title: 'Documents',
          to: '/admin/docs'
        },
        {
          title: 'Doc. Templates',
          to: '/admin/doc-templates'
        },
        {
          title: 'Financials',
          to: '/admin/financials'
        },
        {
          title: 'Analytics',
          to: '/admin/analytics'
        }
      ]
    },
    ...initialState
  }),
  theme: {
    primary: '#9fcebb',
    secondary: '#d0883d',
    accent: '#888888',
    inputaccent: '#E4EDEC',
    error: '#d94545',
    info: '#2196F3',
    success: '#71b179',
    warning: '#f9ad36'
  },

  // Page Not Found Configuration
  redirectOnNotFound: false,
  NotFoundPage,

  // Pages passed here will come before fresh-bus pages, therefore will be given first change to match routes
  pages: require.context('./pages', true, /\.vue$/),
  layouts: require.context('./layouts', false, /\.vue$/)
})

appInstance.getRouter().addRoutes([ { path: '/', redirect: '/admin' }, { path: '', redirect: '/admin' } ])

// We may consider only exposing the app when a certain key is set (true EXPOSE_APP=true)
window.__APP__ = appInstance
