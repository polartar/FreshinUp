import { storiesOf } from '@storybook/vue'
import { action } from '@storybook/addon-actions'

import FRichTextEditor from './FRichTextEditor'
import { MAIN } from '../../../../.storybook/categories'

const SAMPLE_TEXT = `
<h2>
  Hi there,
</h2>
<p>
  this is a very <em>basic</em> example of tiptap.
</p>
<pre><code>body { display: none; }</code></pre>
<ul>
  <li>
    A regular list
  </li>
  <li>
    With regular items
  </li>
</ul>
<blockquote>
  It's amazing 👏
  <br />
  – mom
</blockquote>
`

export const Default = () => ({
  components: { FRichTextEditor },
  template: `
    <v-container>
      <f-rich-text-editor />
    </v-container>
  `
})

export const Populated = () => ({
  components: { FRichTextEditor },
  data () {
    return {
      text: SAMPLE_TEXT
    }
  },
  methods: {
    onInput (value) {
      this.text = value
      action('onInput')(value)
    }
  },
  template: `
    <v-container>
      <f-rich-text-editor
        :value="text"
        @input="onInput"
      />
    </v-container>
  `
})

storiesOf(`${MAIN}|components/FRichTextEditor`, module)
  .addParameters({
    backgrounds: [
      { name: 'default', value: '#f1f3f6', default: true }
    ]
  })
  .add('Default', Default)
  .add('Populated', Populated)
