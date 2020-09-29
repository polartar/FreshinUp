import { storiesOf } from '@storybook/vue'

// Components
import FileUploader from './FileUploader.vue'
import { action } from '@storybook/addon-actions'

export const Default = () => ({
  components: { FileUploader },
  data: () => ({
    file: { name: '', src: '' },
    maxFileSize: 5 // 5 MB
  }),
  methods: {
    onValueChange (value) {
      action('onValueChange')(value)
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
          @onValueChange="onValueChange"
        />
      </v-container>
`
})

storiesOf('FoodFleet|ui/FileUploader', module)
  .add('default', Default)
