import axios from 'axios'
import get from 'lodash/get'

export default (initialState = {}) => {
  const state = {
    placesLoading: false,
    places: [],
    ...initialState
  }
  const getters = {
    places: (state) => state.places,
    placesLoading: (state) => state.placesLoading,
  }
  const mutations = {
    SET_PLACES_LOADING (state, loading) {
      state.placesLoading = loading
    },
    SET_PLACES (state, places) {
      state.places = places
    }
  }
  const actions = {
    getPlaces (context, payload) {
      return new Promise((resolve, reject) => {
        if (!payload.text) {
          return reject(new Error('Text is required'))
        }
        if (!payload.accessToken) {
          return reject(new Error('Access token is required'))
        }
        context.commit('SET_PLACES_LOADING', true)
        axios
          .get(`https://api.mapbox.com/geocoding/v5/mapbox.places/${payload.text}.json?access_token=${payload.accessToken}`
          )
          .then(response => {
            const places = get(response, 'data.features', [])
            context.commit('SET_PLACES', places)
            resolve(places)
          })
          .catch(error => reject(error))
          .then(() => {
            context.commit('SET_PLACES_LOADING', false)
          })
      })
    }
  }
  return {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
  }
}
