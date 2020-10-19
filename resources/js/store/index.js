import { createStore } from 'fresh-bus/store'

import page from './modules/page'
import devices from './modules/devices'
import financialModifiers from './modules/financialModifiers'
import financialReports from './modules/financialReports'
import paymentTypes from './modules/paymentTypes'
import financialsummary from './modules/financialsummary'
import squares from './modules/squares'
import documents from './modules/documents'
import documentStatuses from './modules/documentStatuses'
import documentTypes from './modules/documentTypes'
import transactions from './modules/transactions'
import companyDetails from './modules/companyDetails'
import eventSummary from './modules/eventSummary'
import eventTypes from './modules/eventTypes'
import eventHistories from './modules/eventHistories'
import venues from './modules/venues'
import stores from './modules/stores'
import storeStatuses from './modules/storeStatuses'
import storeTypes from './modules/storeTypes'
import storeAreas from './modules/storeAreas'
import companies from './modules/companies'
import venueStatuses from './modules/venueStatuses'
import locations from './modules/locations'

// TODO file do delete since we now use Provider.js
export default (initialState = {}) => {
  return createStore(
    initialState,
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
        transactions: transactions({}),
        companyDetails: companyDetails({}),
        eventSummary: eventSummary({}),
        eventTypes: eventTypes({}),
        eventHistories: eventHistories({}),
        venues: venues({}),
        venueStatuses: venueStatuses({}),
        stores: stores({}),
        storeStatuses: storeStatuses({}),
        storeTypes: storeTypes({}),
        storeAreas: storeAreas({}),
        companies: companies({}),
        locations: locations({})
      }
    }
  )
}
