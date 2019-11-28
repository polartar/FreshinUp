<template>
  <v-layout >
    <v-flex md4>
      <v-card>
        <v-container class="header grey--text">
          {{ title }}
        </v-container>
        <v-divider/>
        <v-container pt-0>
          <v-layout v-for="(value, key) in items" :key="key" column>
            <v-layout row align-center>
              <v-flex xs6 title grey--text>
                <v-card-text>{{key}}</v-card-text>
              </v-flex>
              <v-flex xs6 text-right grey--text>
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
            class="button-grey grey mt-4"
            @click="$emit('onButtonClick')"
          >
            {{button_text}}
          </v-btn>
          <v-layout v-if="button_remove" flex row >
            <v-btn
              class="button-remove grey--text mt-3"
              @click="$emit('remove')"
            >
              <v-icon class="remove-text-icon" >{{button_remove.icon}}</v-icon>
             <v-text>{{button_remove.text}}</v-text>
          </v-btn>
          </v-layout>
        </v-container>
      </v-card>
    </v-flex>
  </v-layout>
</template>

<script>
import FChip from 'fresh-bus/components/ui/FChip.vue'

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
    button_remove: {
      type: Object,
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

  .v-card__text {
    padding: 12px 0px;
    width: 100%;
    overflow: hidden;
    white-space: nowrap;
    text-overflow: ellipsis;
  }

  .text-right {
    text-align: end;
    font-size: 17px;
  }
  .v-chip {
    margin: 6px 12px 6px 0px;
  }

  .button-grey {
    color: white;
    width: 100%;
    margin: 0;
    text-transform: none;
  }

  .button-remove {
    background: none!important;
    box-shadow: none!important;
    width: 100%;
    margin: 0;
    text-transform: none;
    font-size: 12px;
  }

  .remove-text-icon {
    padding-right: 9px;
    font-size: 1em;
  }
</style>
