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
