<template>
  <v-layout>
    <v-flex
      xs12
      sm12
      md8
      xl9
    >
      <v-card>
        <v-card-title>
          <h3 class="grey--text">
            Basic Information
          </h3>
          <v-progress-linear
            v-if="isLoading"
            indeterminate
          />
        </v-card-title>
        <v-divider />
        <v-card-text>
          <div>
            <v-flex xs12>
              <div class="mb-2 text-uppercase grey--text font-weight-bold">
                Title
              </div>
              <v-text-field
                v-model="title"
                placeholder="Title"
                single-line
                outline
              />
            </v-flex>
            <v-flex xs12>
              <div class="mb-2 text-uppercase grey--text font-weight-bold">
                Short description
              </div>
              <v-text-field
                v-model="description"
                placeholder="Short description"
                single-line
                outline
              />
            </v-flex>
            <v-flex xs12>
              <div class="mb-2 text-uppercase grey--text font-weight-bold">
                Content
              </div>
              <div class="editor">
                <editor-menu-bar
                  v-slot="{ commands, isActive }"
                  :editor="editor"
                >
                  <div class="menubar">
                    <button
                      class="menubar__button"
                      :class="{ 'is-active': isActive.heading({ level: 1 }) }"
                      @click="commands.heading({ level: 1 })"
                    >
                      H1
                    </button>

                    <button
                      class="menubar__button"
                      :class="{ 'is-active': isActive.heading({ level: 2 }) }"
                      @click="commands.heading({ level: 2 })"
                    >
                      H2
                    </button>

                    <button
                      class="menubar__button"
                      :class="{ 'is-active': isActive.heading({ level: 3 }) }"
                      @click="commands.heading({ level: 3 })"
                    >
                      H3
                    </button>

                    <v-btn
                      icon
                      depressed
                      :class="{ 'is-active': isActive.bold() }"
                      @click="commands.bold"
                    >
                      <v-icon>format_bold</v-icon>
                    </v-btn>

                    <v-btn
                      icon
                      depressed
                      :class="{ 'is-active': isActive.italic() }"
                      @click="commands.italic"
                    >
                      <v-icon>format_italic</v-icon>
                    </v-btn>

                    <v-btn
                      icon
                      depressed
                      :class="{ 'is-active': isActive.underline() }"
                      @click="commands.underline"
                    >
                      <v-icon>format_underlined</v-icon>
                    </v-btn>

                    <v-btn
                      icon
                      depressed
                      :class="{ 'is-active': editor.activeMarkAttrs.aligntext.align === 'left' }"
                      @click="commands.aligntext({ align: 'left' })"
                    >
                      <v-icon>format_align_left</v-icon>
                    </v-btn>

                    <v-btn
                      icon
                      depressed
                      :class="{ 'is-active': editor.activeMarkAttrs.aligntext.align === 'center' }"
                      @click="commands.aligntext({ align: 'center' })"
                    >
                      <v-icon>format_align_center</v-icon>
                    </v-btn>

                    <v-btn
                      icon
                      depressed
                      :class="{ 'is-active': editor.activeMarkAttrs.aligntext.align === 'right' }"
                      @click="commands.aligntext({ align: 'right' })"
                    >
                      <v-icon>format_align_right</v-icon>
                    </v-btn>

                    <v-btn
                      icon
                      depressed
                      :class="{ 'is-active': editor.activeMarkAttrs.aligntext.align === 'justify' }"
                      @click="commands.aligntext({ align: 'justify' })"
                    >
                      <v-icon>format_align_justify</v-icon>
                    </v-btn>

                    <v-btn depressed>
                      Add variable
                    </v-btn>
                  </div>
                </editor-menu-bar>
                <editor-content
                  class="editor__content pa-3"
                  :editor="editor"
                />
              </div>
            </v-flex>
          </div>
        </v-card-text>
        <v-card-actions>
          <v-btn
            depressed
            @click="cancel"
          >
            Cancel
          </v-btn>
          <v-btn
            depressed
            color="primary"
            :loading="isLoading"
            @click="onSave"
          >
            {{ isNew? 'Submit' : 'Save changes' }}
          </v-btn>
          <v-spacer />
          <v-btn
            depressed
            @click="deleteItem"
          >
            Delete
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-flex>
    <v-flex
      xs12
      sm12
      md4
      xl3
      class="ml-2"
    >
      <v-card>
        <v-card-text>
          <h3
            v-if="!isNew"
            class="font-weight-bold mb-2"
          >
            Last Modified on
          </h3>
          <span
            v-if="!isNew"
            class="mb-2"
          >{{ formatDate(updated_at) }} by <u>{{ updaterName }}</u></span>
          <v-select
            v-model="status_id"
            :items="statuses"
            item-value="id"
            item-text="name"
            outline
            single-line
          />
        </v-card-text>
        <v-card-actions>
          <v-layout
            text-xs-center
            row
            wrap
          >
            <v-flex
              xs12
              class="mb-2"
            >
              <v-btn
                depressed
                color="primary"
                :loading="isLoading"
                @click="save"
              >
                {{ isNew? 'Submit' : 'Save changes' }}
              </v-btn>
            </v-flex>
            <v-flex
              xs12
              class="mb-2"
            >
              <v-btn
                depressed
                disabled
              >
                Preview
              </v-btn>
            </v-flex>
            <v-flex xs12>
              <a
                v-if="!isNew"
                href="#/move-to-trash"
                @click.prevent="deleteItem"
              >Move to trash</a>
            </v-flex>
          </v-layout>
        </v-card-actions>
      </v-card>
    </v-flex>
  </v-layout>
</template>

<script>
import MapValueKeysToData from '~/mixins/MapValueKeysToData'
import get from 'lodash/get'
import FormatDate from '@freshinup/core-ui/src/mixins/FormatDate'
import { Editor, EditorContent, EditorMenuBar } from 'tiptap'
import AlignText from '../tiptap/aligntext.plugin'
import {
  Blockquote,
  CodeBlock,
  HardBreak,
  Heading,
  HorizontalRule,
  OrderedList,
  BulletList,
  ListItem,
  TodoItem,
  TodoList,
  Bold,
  Code,
  Italic,
  Link,
  Strike,
  Underline,
  History
} from 'tiptap-extensions'

export const DEFAULT_TEMPLATE = {
  id: '',
  uuid: '',
  title: '',
  description: '',
  content: '',
  status_id: 1,
  updated_at: '',
  updated_by: null
}

export default {
  components: {
    EditorContent,
    EditorMenuBar
  },
  mixins: [MapValueKeysToData, FormatDate],
  props: {
    isLoading: { type: Boolean, default: false },
    // Override value from MapValueKeysToData mixin to get the default values
    value: { type: Object, default: () => DEFAULT_TEMPLATE },
    statuses: { type: Object, default: () => [] }
  },
  data () {
    return {
      ...DEFAULT_TEMPLATE,
      editor: new Editor({
        extensions: [
          new Blockquote(),
          new BulletList(),
          new CodeBlock(),
          new HardBreak(),
          new Heading({ levels: [1, 2, 3] }),
          new HorizontalRule(),
          new ListItem(),
          new OrderedList(),
          new TodoItem(),
          new TodoList(),
          new Link(),
          new Bold(),
          new Code(),
          new Italic(),
          new Strike(),
          new Underline(),
          new History(),
          new AlignText()
        ],
        content: this.value.content
      })
    }
  },
  computed: {
    isNew () {
      return !get(this, 'uuid')
    },
    updaterName () {
      return get(this, 'updated_by.name')
    }
  },
  beforeDestroy () {
    this.editor.destroy()
  },
  methods: {
    get,
    deleteItem () {
      this.$emit('delete', this.payload)
    },
    cancel () {
      this.$emit('cancel')
    },
    onSave () {
      this.value.content = this.editor.getHTML()
      // eslint-disable-next-line no-console
      console.log(this.value)
      this.save()
    }
  }
}
</script>

<style scoped>
.editor {
  position: relative;
  /* max-width: 30rem; */
  margin: 0 auto 5rem;
}

.menubar {
  margin-bottom: 1rem;
  -webkit-transition: visibility .2s .4s,opacity .2s .4s;
  transition: visibility .2s .4s,opacity .2s .4s;
}

.editor__content {
  overflow-wrap: break-word;
  word-wrap: break-word;
  word-break: break-word;
  border: solid 2px darkgray;
  border-radius: 4px;
}

.menubar__button {
  font-weight: 700;
  display: -webkit-inline-box;
  display: inline-flex;
  background: transparent;
  border: 0;
  color: #000;
  padding: .2rem .5rem;
  margin-right: .2rem;
  border-radius: 3px;
  cursor: pointer;
}
>>>.ProseMirror {
  outline: none;
}
</style>
