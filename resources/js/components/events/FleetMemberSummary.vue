<template>
  <v-layout>
    <v-flex xs12 sm12 md4>
      <v-card class="pt-2">
        <v-card-title
          class="px-2 ml-2"
        >
          <div class="font-weight-bold grey--text header">
            {{ title }}
          </div>
        </v-card-title>
        <v-divider/>
        <v-container pt-0>
          <v-layout v-for="(value, key) in fleet" :key="key" column>
            <v-layout row align-center>
              <v-flex xs6 title grey--text class="ma-0 pa-0">
                <v-card-text>{{key}}</v-card-text>
              </v-flex>
              <v-flex xs6 text-right>
                <v-card-text text-align-end>{{value}}</v-card-text>
              </v-flex>
            </v-layout>
            <v-divider/>
          </v-layout>
          <v-layout xs6 title grey--text class="ma-0 pa-0">
            <v-card-text>TAGS</v-card-text>
          </v-layout>
          <v-layout row wrap>
            <f-chip
              v-for="item in tagsComputed"
              :key="item.id"
              :color="isSelected(item.id) ? 'primary' : 'accent'"
              @click.prevent="changeTags(item)"
            >
              {{ item.name }}
            </f-chip>
          </v-layout>

          <v-btn
            class="button-grey mt-4"
            @click="$emit('view_profile')"
          >
            {{button_text}}
          </v-btn>
          <v-layout flex row>
            <v-btn
              class="button-remove mt-3"
              @click="$emit('remove')"
            >
             <v-text
               class="remove-text">{{button_remove_text}}</v-text>
          </v-btn>
          </v-layout>
        </v-container>
      </v-card>
    </v-flex>
  </v-layout>
</template>

<script>
import remove from 'lodash/remove'
import FChip from 'fresh-bus/components/ui/FChip.vue'
import '../../../../resources/fonts/css/fontello.css'

export default {
  components: { FChip },
  props: {
    title: {
      type: String,
      default: null,
      required: true
    },
    button_text: {
      type: String,
      default: null,
      required: true
    },
    button_remove_text: {
      type: String,
      default: null,
      required: true
    },
    show_remove: {
      type: Boolean,
      default: true,
      required: true
    },
    fleet: {
      type: Object,
      required: true
    },
    tags: {
      type: Array
    },
    selected: {
      type: Array,
      default: () => []
    }
  },

  data () {
    return {
      selectedTagsData: [...this.selected]
    }
  },
  computed: {
    hasTitle () {
      return this.title.length > 0
    },
    tagsComputed () {
      return this.tags
    }
  },
  watch: {
    selected (val) {
      this.selectedTagsData = val
    }
  },
  methods: {
    changeTags (tag) {
      event.stopPropagation()
      if (!this.isSelected(tag.id)) {
        this.selectedTagsData.push(tag)
      } else {
        this.selectedTagsData = remove(this.selectedTagsData, function (item) {
          return !(item.id === tag.id)
        })
      }
      this.$emit('input', this.selectedTagsData)
    },
    isSelected (id) {
      return this.selectedTagsData.some(element => element.id === id)
    },

    view () {
      this.$emit('view_profile')
    }
  }
}
</script>

<style scoped>
  .header {
    font-size: 17px;
  }

  .title {
    text-transform: uppercase;
    font-weight: bold;
    font-size: 11px;
  }

  .theme--light.v-btn:not(.v-btn--icon):not(.v-btn--flat) {
    background-color: #a0a9ba;
  }

  .v-card__text {
    padding: 12px 0px;
    width: 100%;
    overflow: hidden;
    white-space: nowrap;
    text-overflow: ellipsis;
  }

  .text-right {
    text-align: end;
    color: grey;
    font-size: 17px;
  }

  .button-grey {
    background-color: #a0a9ba;
    color: white;
    width: 100%;
    margin: 0;
    text-transform: none;
  }

  .button-remove {
    background: none!important;
    box-shadow: none!important;
    color: grey;
    width: 100%;
    margin: 0;
    text-transform: none;
    font-size: 12px;
  }

  .remove-text:before {
    font-family: 'fontello';
    content: '\e807';
    color: gray;
    opacity: 1;
    font-size: 1.2em;
    position: relative;
    padding-right: 9px;
  }

</style>
