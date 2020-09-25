import { App, createStore } from 'fresh-bus'
import theme from './theme'
import NotFoundPage from 'fresh-bus/pages/404.vue'
import page from './store/modules/page.js'
import devices from './store/modules/devices.js'
import financialModifiers from './store/modules/financialModifiers.js'
import financialReports from './store/modules/financialReports.js'
import paymentTypes from './store/modules/paymentTypes.js'
import financialsummary from './store/modules/financialsummary.js'
import squares from './store/modules/squares.js'
import documents from './store/modules/documents.js'
import documentStatuses from './store/modules/documentStatuses.js'
import documentTypes from './store/modules/documentTypes.js'
import transactions from './store/modules/transactions.js'
import stores from './store/modules/stores.js'
import storeTypes from './store/modules/storeTypes.js'
import storeAreas from './store/modules/storeAreas.js'
import storeStatuses from './store/modules/storeStatuses.js'
import companyOwners from './store/modules/companyOwners.js'
import companyDetails from './store/modules/companyDetails.js'
import events from './store/modules/events.js'
import eventStatuses from './store/modules/eventStatuses.js'
import eventMenuItems from './store/modules/eventMenuItems.js'
import eventSummary from './store/modules/eventSummary.js'
import eventTypes from './store/modules/eventTypes.js'
import eventHistories from './store/modules/eventHistories.js'
import messages from './store/modules/messages.js'
import venues from './store/modules/venues'
import venueStatuses from './store/modules/venueStatuses'
import companies from './store/modules/companies'
import locations from './store/modules/locations'

const initialState = {
  loginSuccessRedirectPath: '/admin/dashboard',
  requiredCompanyForRegister: false,
  ...window.__INITIAL_STATE__
}
const appInstance = new App({
  components: {},
  store: createStore({
    navigation: {
      title: 'Food Fleet',
      logo: '/images/logo.png',
      drawerItems: [
        {
          action: 'icon-dashboard',
          title: 'Dashboard',
          to: '/admin'
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
          action: 'icon-venues',
          title: 'Venues',
          to: '/admin/venues'
        },
        {
          action: 'icon-events',
          title: 'Events',
          to: '/admin/events'
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
      ],
      items: []
    },
    navigationAdmin: {
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
    },
    ...initialState
  },
  {
    modules: {
      page: page({}),
      devices: devices({}),
      financialModifiers: financialModifiers({}),
      financialReports: financialReports({}),
      paymentTypes: paymentTypes({}),
      financialsummary: financialsummary({}),
      squares: squares({}),
      documents: documents({}),
      documentStatuses: documentStatuses({}),
      documentTypes: documentTypes({}),
      stores: stores({}),
      storeTypes: storeTypes({}),
      storeAreas: storeAreas({}),
      storeStatuses: storeStatuses({}),
      companyOwners: companyOwners({}),
      transactions: transactions({}),
      companyDetails: companyDetails({}),
      events: events({}),
      eventStatuses: eventStatuses({}),
      eventMenuItems: eventMenuItems({}),
      eventSummary: eventSummary({}),
      eventTypes: eventTypes({}),
      messages: messages({}),
      venues: venues({}),
      venueStatuses: venueStatuses({}),
      eventHistories: eventHistories({}),
      companies: companies({}),
      locations: locations({})
    }
  }),
  theme,

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
export default appInstance
