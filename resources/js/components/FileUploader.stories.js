import { storiesOf } from '@storybook/vue'

// Components
import FileUploader from './FileUploader.vue'

storiesOf('FoodFleet|ui/FileUploader', module)
  .add('default', () => ({
    components: { FileUploader },
    data: () => ({
      file: { name: '', src: '' }
    }),
    template: `
      <v-container>
        <file-uploader v-model="file"/>
      </v-container>
`
  }))
