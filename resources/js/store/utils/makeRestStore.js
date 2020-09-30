/**
 * All of this below is just so that when we call store.dispatch('module/deleteItem', { getItems: true })
 * it will get them with the needed include if that was set previously
 *
 * Example:
 * From
 * store.dispatch('module/getItems', { params: { include: 'submoduleA,submoduleB' } }
 * store.dispatch('module/deleteItem', { getItems: false, id: item.uuid })
 * store.dispatch('module/getItems', { params: { include: 'submoduleA,submoduleB' } }
 *
 * To
 * store.dispatch('module/getItems', { params: { include: 'submoduleA,submoduleB' } }
 * store.dispatch('module/deleteItem', { getItems: true, id: item.uuid })
 *
 * Hey that's one line less and a lot of code duplication avoided for each module
 */

import mapKeys from 'lodash/mapKeys'
import {
  _getPaginationAsParams, _getSortingAsParams,
  buildApi,
  makeModule as _makeModule
} from '@freshinup/core-ui/src/store/utils/makeRestStore'
import pickBy from 'lodash/pickBy'
import omitBy from 'lodash/omitBy'

export const makeModule = (store, moduleName = '') => {
  const m = _makeModule(store, moduleName)
  const _actions = store.actions

  m.mutations = {
    ...m.mutations,
    setFilters: (state, payload) => {
      payload = omitBy(payload, (value, key) => {
        return key.indexOf('sort') > -1
      })
      state.filters = mapKeys(payload, (value, key) => {
        if (['include'].includes(key)) {
          return key
        }
        return key.replace(/filter\[(.*)]/gi, '$1')
      })
    }
  }
  m.actions = {
    ...m.actions,
    getItems (context, payload = { params: {}, data: {} }) {
      const pagination = context.getters.pagination
      const sorting = context.getters.sorting
      // Convert filters into query parameters that is JSON API compliant
      const filters = mapKeys(context.state.filters, function (value, key) {
        // In order to be backwards compatible we need to ignore a few keys
        //    No deprecation plan yet as there is not a design change planned.
        //    Most likely we'll need a setSort() and term may remain
        if (['term', 'q', 'sort', 'include'].indexOf(key) > -1) {
          return key
        }
        return key.indexOf('filter[') === 0 ? key : `filter[${key}]`
      })

      // Deprecation Notice
      const deprecatedKeys = Object.keys(pickBy(payload.params, (value, key) => {
        return key.indexOf('filter[') > -1 || key.indexOf('sort') > -1
      }))
      if (deprecatedKeys.length > 0) {
        console.warn(`
FreshBUS: Version 1.15 of FreshBUS is the last support filtering through getList({ params }) on Store Modules made from makeRestStore.
Please switch to using setFilters() before calling getItems(). Keys provided are ${deprecatedKeys} on ${moduleName}
          `)
      }
      payload.params = {
        ..._getPaginationAsParams(pagination),
        ..._getSortingAsParams(sorting),
        ...filters,
        ...payload.params
      }

      return _actions.getItems.apply(null, [context, payload])
    }
  }
  return m
}

/**
 * Quickly build a REST Module with our Fresh Standards
 * @param name
 * @param items Initial State for Items
 * @param item  Initial State for Item (selected item)
 * @param filters
 * @param basePaths
 * @param options
 */
export default (name, { items, item, filters }, basePaths, options = {}) => {
  const api = buildApi(name, { items, item, filters }, basePaths, options)
  return makeModule(api.getStore(), name)
}
