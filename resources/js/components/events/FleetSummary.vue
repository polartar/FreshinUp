<template>
  <v-layout>
    <v-flex md4>
      <v-card>
        <v-card-title class="header grey--text">
          {{ title }}
        </v-card-title>
        <v-divider/>
        <v-container pt-0>
          <v-layout v-for="(value, key) in items" :key="key" column>
            <v-layout row align-center>
              <v-flex xs6 title grey--text>
                <v-card-text>{{key}}</v-card-text>
              </v-flex>
              <v-flex xs6 text-right>
                <v-card-text>{{value}}</v-card-text>
              </v-flex>
            </v-layout>
            <v-divider/>
          </v-layout>
          <v-layout v-if="tags" xs6 title grey--text>
            <v-card-text>TAGS</v-card-text>
          </v-layout>
          <v-layout v-if="tags" row wrap>
            <f-chip
              v-for="item in tags"
              :key="item.id"
              :color="'primary'"
            >
              {{ item.name }}
            </f-chip>
          </v-layout>

          <v-btn
            v-if="button_text"
            class="button-grey mt-4"
            @click="$emit('onButtonClick')"
          >
            {{button_text}}
          </v-btn>
          <v-layout v-if="button_remove_text" flex row>
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
    items: {
      type: Object,
      required: true
    },
    tags: {
      type: Array
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
    }
  }
}
</script>

<style lang="scss" scoped>
  .header {
    font-weight: 600;
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
