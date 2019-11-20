import { storiesOf } from '@storybook/vue'
import { action } from '@storybook/addon-actions'
import MessageSend from './MessageSend.vue'

const types = [
  { value: 1, text: 'From Template' },
  { value: 2, text: 'Downloadable' }
]
const doc = {
  title: 'mock title',
  type: 1,
  description: 'Type Message',
  notes: 'mock notes',
  template: null,
  file: { name: '', src: '' }
}

// Components
storiesOf('FoodFleet|doc/MessageSend', module)
  .addParameters({
    backgrounds: [
      { name: 'default', value: '#f1f3f6', default: true }
    ]
  })

  .add('initData is set', () => ({
    components: { MessageSend },
    data () {
      return {
        types: types,
        doc: doc
      }
    },
    methods: {
      changeBasicInfo (params) {
        action('dataChange')(params)
      }
    },
    template: `
      <v-container>
        <MessageSend
          :initData="doc"
          :types="types"
          @dataChange="changeBasicInfo"
        />
      </v-container>
    `
  }))
