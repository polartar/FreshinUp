import createStore from 'tests/createStore'
import mockApi from 'vue-cli-plugin-freshinup-ui/utils/mockApi'
import makePageStory from 'vue-cli-plugin-freshinup-ui/utils/makePageStory'
import Page from './index.vue'
import { FIXTURE_CURRENT_USER } from 'tests/__data__/user'
import { FIXTURE_USERS } from 'tests/__data__/users'
import { MAIN } from '../../../../../.storybook/categories'
import { storiesOf } from '@storybook/vue'

const apiMocked = mockApi()

/**
 * https://storybook.js.org/docs/guides/guide-vue/#step-4-write-your-stories
 */
export const Default = () => {
  apiMocked.reset()
  apiMocked.addRoutes({
    'api/currentUser': {
      GET: [200, FIXTURE_CURRENT_USER]
    },
    'api/users': {
      GET: [200, { data: FIXTURE_USERS }]
    }
  }).ready()
  const store = createStore({
    currentUser: FIXTURE_CURRENT_USER,
    page: {
      title: 'Dashboard'
    },
    userNotifications: {
      fetchInterval: 0
    }
  })
  return makePageStory(Page, store, {
    beforeMount () {
      Page.beforeRouteEnterOrUpdate(this, {}, null)
    }
  })
}

storiesOf(`${MAIN}|pages/admin/dashboard`, module)
  .addParameters({
    backgrounds: [
      { name: 'default', value: '#f1f3f6', default: true }
    ]
  })
  .add('Default', Default)
