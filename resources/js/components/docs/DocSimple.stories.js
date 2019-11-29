import { storiesOf } from '@storybook/vue'
import { action } from '@storybook/addon-actions'
import MockAdapter from 'axios-mock-adapter'
import axios from 'axios/index'
import DocSimple from './DocSimple.vue'

const mock = new MockAdapter(axios)

mock.onGet('/users').reply(200, {
  data: [
    { uuid: 1, name: 'John Smith' },
    { uuid: 2, name: 'Bob Loblaw' },
    { uuid: 3, name: 'Mario Brother' }
  ]
})

// Components
storiesOf('FoodFleet|doc/DocSimple', module)
  .addParameters({
    backgrounds: [
      { name: 'default', value: '#f1f3f6', default: true }
    ]
  })
  .add('defaults (url is required)', () => ({
    components: { DocSimple },
    template: `
        <v-container>
            <DocSimple url="/users" />
        </v-container>
      `,
    methods: {
      onClose () {
        action('Close')()
      }
    }
  }))
  .add('formatItems is set', () => ({
    components: { DocSimple },
    data () {
      return {
        formatItems: list => {
          return list.map(item => {
            item.name = 'mock name'
            return item
          })
        }
      }
    },
    template: `
        <v-container>
            <DocSimple
              url="/users"
              :formatItems="formatItems"
            />
        </v-container>
      `,
    methods: {
      onClose () {
        action('Close')()
      }
    }
  }))
