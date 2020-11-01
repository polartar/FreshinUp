import axios from 'axios'

export default (initialState = {}) => {
  const actions = {
    authorize (context, payload) {
      return new Promise((resolve, reject) => {
        axios({
          url: '/foodfleet/squares/authorize',
          method: 'POST',
          headers: {
            Authorization: `Bearer ${localStorage.default_auth_token}`
          },
          data: payload.data
        })
          .then((response) => {
            resolve(response.data)
          })
          .catch(error => {
            reject(error)
          })
      })
    }
  }

  const mutations = {}
  const getters = {}
  const state = {
    ...initialState
  }

  const store = {
    state,
    actions,
    getters,
    mutations
  }

  return {
    namespaced: true,
    ...store
  }
}
