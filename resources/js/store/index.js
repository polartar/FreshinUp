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
import companies from './modules/companies'

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
        stores: stores({}),
        storeStatuses: storeStatuses({}),
        storeTypes: storeTypes({}),
        companies: companies({})
      }
    }
  )
}
