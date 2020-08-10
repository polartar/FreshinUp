import createStore from 'tests/createStore'
import makePageStory from 'vue-cli-plugin-freshinup-ui/utils/makePageStory'
import { MAIN } from '../../../../../../.storybook/categories.js'
import { FIXTURE_USER } from 'tests/__data__/user'
import Page from './edit.vue'
import PageStoryInstances from 'tests/PageStoryInstances'

export default {
  title: `${MAIN} Pages|admin/events/edit`,
  id: 'pages/admin/events/edit'
}

export const IsLoading = () => {
  const store = createStore({
    currentUser: FIXTURE_USER
  })
  return makePageStory(Page, store, {
    ...PageStoryInstances,
    apiMockRoutes: [
      {
        path: 'api/currentUser',
        GET: [200, FIXTURE_USER]
      }
    ],
    beforeMount () {
      store.dispatch('page/setLoading', true)
    }
  })
}
//
// export const PopulatedList = () => {
//   const store = createStore({
//     currentUser: FIXTURE_USER
//   })
//   return makePageStory(Page, store, {
//     ...PageStoryInstances,
//     apiMockRoutes: [
//       {
//         path: /.*api\/financial-reports.*/,
//         GET: [200, { data: FIXTURE_FINANCIAL_SEARCHES.slice(0, 10) }]
//       },
//       {
//         path: 'api/currentUser',
//         GET: [200, FIXTURE_USER]
//       }
//     ],
//     beforeMount () {
//       Page.beforeRouteEnterOrUpdate(this)
//     }
//   })
// }
//
// export const EmptyList = () => {
//   const store = createStore({
//     currentUser: FIXTURE_USER
//   })
//   return makePageStory(Page, store, {
//     ...PageStoryInstances,
//     apiMockRoutes: [
//       {
//         path: /.*api\/finacial-reports.*/,
//         GET: [200, { data: [] }]
//       },
//       {
//         path: 'api/currentUser',
//         GET: [200, FIXTURE_USER]
//       }
//     ],
//     beforeMount () {
//       Page.beforeRouteEnterOrUpdate(this)
//     }
//   })
// }
