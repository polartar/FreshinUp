const once = require('lodash/once')

export default (console, Vue) => {
  const logError = console.error
  const logWarn = console.warn
  // eslint-disable-next-line no-console
  const logInfo = console.log

  const logVuetifyBug = once(() => {
    console.warn(`[Vuetify] Multiple instances of Vue detected 
      See https://github.com/vuetifyjs/vuetify/issues/4068
      Basically you cannot use mount from Vue Utils
  `)
  })
  console.error = (...args) => {
    if (
      args.length &&
      args.length > 0 &&
      args[0].includes &&
      args[0].includes('[Vuetify]') &&
      args[0].includes('https://github.com/vuetifyjs/vuetify/issues/4068')
    ) {
      if (process.env.LOG_VUETIFY_WARNINGS) {
        logVuetifyBug()
      }
      return
    }
    logError(...args)
  }

  console.warn = (...args) => {
    if (
      args.length &&
      args.length > 0 &&
      args[0].includes &&
      args[0].includes('[Vuetify]') &&
      args[0].includes('Unable to locate target [data-app]')
    ) {
      if (process.env.LOG_VUETIFY_WARNINGS) {
        logInfo('Vuetify cannot find data-app property in the element tree. This should not be causing any issues.')
      }
      return
    }
    logWarn(...args)
  }
  Vue.config.productionTip = false
}
