<template>
  <div class="editor">
    <editor-menu-bar
      v-slot="{ commands, isActive }"
      :editor="editor"
    >
      <div class="menubar">
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
      </div>
    </editor-menu-bar>
    <editor-content
      class="editor__content pa-3"
      :editor="editor"
    />
  </div>
</template>
<script>
import { Editor, EditorContent, EditorMenuBar } from 'tiptap'
import AlignText from '../tiptap/aligntext.plugin'
import {
  // Blockquote,
  // CodeBlock,
  // HardBreak,
  Heading,
  // HorizontalRule,
  // OrderedList,
  // BulletList,
  // ListItem,
  // TodoItem,
  // TodoList,
  Bold,
  // Code,
  Italic,
  // Link,
  // Strike,
  Underline
  // History
} from 'tiptap-extensions'

export default {
  components: {
    EditorContent,
    EditorMenuBar
  },

  props: {
    value: {
      type: String,
      default: ''
    }
  },

  data: () => ({
    editor: null
  }),

  watch: {
    value (val) {
      if (this.editor && val !== this.value) {
        this.editor.setContent(val, true)
      }
    }
  },

  mounted: function () {
    this.$nextTick(() => this.init())
  },

  beforeDestroy: function () {
    if (this.editor) {
      this.editor.destroy()
    }
  },

  methods: {
    async init () {
      this.editor = new Editor({
        extensions: [
          new Heading(),
          new Bold(),
          new Italic(),
          new Underline(),
          new AlignText()
        ],
        content: this.value,
        onUpdate: ({ getHTML }) => {
          const state = getHTML()
          this.$emit('input', state)
        }
      })
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
