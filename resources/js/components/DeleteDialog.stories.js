import { storiesOf } from '@storybook/vue'
import { action } from '@storybook/addon-actions'

// Components
import DeleteDialog from './DeleteDialog.vue'
import { FIXTURE_USERS } from '../../../tests/Javascript/__data__/users'

export const Basic = () => ({
  components: { DeleteDialog },
  data () {
    return {
      items: FIXTURE_USERS // any array of items
    }
  },
  methods: {
    onConfirm () {
      action('onConfirm')()
    },
    onCancel () {
      action('onCancel')()
    }
  },
  template: `
      <v-container>
        <delete-dialog
          :value="true"
          title="All to trash ?"
          item-title-prop="name"
          :items="items"
          @confirm="onConfirm"
          @cancel="onCancel"
        />
      </v-container>
    `
})

export const Loading = () => ({
  components: { DeleteDialog },
  template: `
      <v-container>
        <delete-dialog
          is-loading
          :value="true"
        />
      </v-container>
    `
})

export const Progress = () => ({
  components: { DeleteDialog },
  data () {
    return {
      progress: 0,
      total: 20
    }
  },
  async mounted () {
    const delay = (timeout) => new Promise((resolve) => {
      setTimeout(resolve, timeout)
    })
    for (let i = 0; i < this.total; i++) {
      await delay(1500)
      this.progress += 100 / this.total
    }
  },
  template: `
      <v-container>
        <delete-dialog
          is-loading
          :progress="progress"
          :progress-status="progressStatus"
          :value="true"
        />
      </v-container>
    `
})

storiesOf('FoodFleet|components/DeleteDialog', module)
  .addParameters({
    backgrounds: [
      { name: 'default', value: '#f1f3f6', default: true }
    ]
  })
  .add('Basic', Basic)
  .add('Loading', Loading)
  .add('Progress', Progress)
