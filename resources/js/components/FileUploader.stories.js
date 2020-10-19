import { storiesOf } from '@storybook/vue'

// Components
import FileUploader from './FileUploader.vue'
import { action } from '@storybook/addon-actions'
import { MAIN } from '../../../.storybook/categories'
import axios from 'axios'
import MockAdapter from 'axios-mock-adapter'

const mock = new MockAdapter(axios)

mock
  .onPost('foodfleet/tmp-media')
  .reply(200, 'mock url')
  .onAny()
  .reply(config => {
    console.warn('No mock match for ' + config.url, config)
    return [404, {}]
  })

export const Default = () => ({
  components: { FileUploader },
  template: `
    <v-container>
      <file-uploader
      />
    </v-container>
  `
})

export const Downloadable = () => ({
  components: { FileUploader },
  data: () => ({
    file: { name: 'mock name', src: 'https://downloadable.net/mock' },
    maxFileSize: 5 // 5 MB
  }),
  methods: {
    onInput (value) {
      action('onInput')(value)
    }
  },
  template: `
    <v-container>
      <pre>
        {{ file }}
      </pre>
      <file-uploader
        v-model="file"
        :max-file-size="maxFileSize"
        @input="onInput"
      />
    </v-container>
  `
})

storiesOf(`${MAIN}|components/FileUploader`, module)
  .add('Default', Default)
  .add('Downloadable', Downloadable)
