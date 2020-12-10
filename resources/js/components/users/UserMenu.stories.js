import { storiesOf } from '@storybook/vue'
import { action } from '@storybook/addon-actions'

import UserMenu from './UserMenu'
import { FIXTURE_USER } from '../../../../tests/Javascript/__data__/user'

export const Default = () => ({
  components: { UserMenu },
  data () {
    return {
      currentUser: FIXTURE_USER
    }
  },
  methods: {
    signout (act, item) {
      action('signout')(act, item)
    }
  },
  template: `
    <user-menu
      :user="currentUser"
      @signout="signout"
    />
  `
})

export const USER_MENU_ITEMS = [
  { title: 'My Profile', to: { name: 'myprofile' } }
]

export const Set = () => ({
  components: { UserMenu },
  data () {
    return {
      userMenuItems: USER_MENU_ITEMS,
      currentUser: FIXTURE_USER,
      isCurrentUserAdmin: FIXTURE_USER
    }
  },
  methods: {
    signout (act, item) {
      action('signout')(act, item)
    }
  },
  template: `
    <user-menu
      :user="currentUser"
      :user-is-admin="isCurrentUserAdmin"
      :menu-items="userMenuItems"
      @signout="signout"
    />
  `
})

storiesOf('FoodFleet|components/users/UserMenu', module)
  .addParameters({
    backgrounds: [
      { name: 'default', value: '#f1f3f6', default: true }
    ]
  })
  .add('Default', Default)
  .add('Set', Set)
