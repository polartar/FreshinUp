<template>
  <v-card>
    <v-container font-weight-bold grey--text subheading py-3>
      Fleet Member Summary
    </v-container>
    <v-divider/>
    <v-container pt-0 font-weight-bold grey--text>
      <v-layout align-center line>
        <v-flex caption font-weight-bold>OWNER</v-flex>
        <v-flex text-xs-right subheading xs6 text-truncate>{{member.owner}}</v-flex>
      </v-layout>
      <v-divider/>
      <v-layout align-center line>
        <v-flex caption font-weight-bold>LISENCE DUE</v-flex>
        <v-flex text-xs-right subheading xs6 text-truncate>{{member.lisence_due}}</v-flex>
      </v-layout>
      <v-divider/>
      <v-layout align-center line>
        <v-flex caption font-weight-bold>CONTACT PHONE</v-flex>
        <v-flex text-xs-right subheading xs6 text-truncate>{{member.phone}}</v-flex>
      </v-layout>
      <v-divider/>
      <v-layout align-center line>
        <v-flex caption font-weight-bold>CONTACT EMAIL</v-flex>
        <v-flex text-xs-right subheading xs6 text-truncate>{{member.email}}</v-flex>
      </v-layout>
      <v-divider/>
      <v-layout v-if="member.tags" tags_title font-weight-bold grey--text mt-2 pa-1>
        <v-flex>TAGS</v-flex>
      </v-layout>
      <v-layout v-if="member.tags" row wrap>
        <f-chip
          v-for="(tag, index) in member.tags"
          :key="index"
          :color="'primary'"
          class="my-1 ml-0 mr-2"
        >
          {{ tag }}
        </f-chip>
      </v-layout>

      <v-divider class="mt-3"/>
      <v-btn block
             class="grey ma-0 mt-4 white--text text-none"
             @click="onButtonClick"
      >
        View Fleet Member Profile
      </v-btn>
      <v-btn block
             class="transparent elevation-0 ma-0 pr-0 caption  grey--text mt-3 button-remove text-none"
             @click="onRemoveClick"
      >
        <v-icon class="pr-2 caption">far fa-trash-alt</v-icon>
        <v-text>Remove fleet member from this event</v-text>
      </v-btn>
    </v-container>
  </v-card>
</template>

<script>
import FChip from 'fresh-bus/components/ui/FChip.vue'

export default {
  components: { FChip },

  props: {
    member: {
      type: Object,
      required: true
    }
  },
  methods: {
    onButtonClick: function () {
      this.$emit('onButtonClick')
    },
    onRemoveClick: function () {
      this.$emit('onRemoveClick')
    }
  }
}
</script>

<style scoped>
  .line {
    height: 40px;
  }
</style>
