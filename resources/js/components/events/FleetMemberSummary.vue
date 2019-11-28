<template>
  <v-card>
    <v-container class="header grey--text">
      Fleet Member Summary
    </v-container>
    <v-divider/>
    <v-container pt-0>
      <v-layout row align-center>
        <v-flex xs6 title grey--text>
          <v-card-text>owner</v-card-text>
        </v-flex>
        <v-flex xs6 text-right grey--text>
          <v-card-text>{{member.owner}}</v-card-text>
        </v-flex>
      </v-layout>
      <v-divider/>
      <v-layout row align-center>
        <v-flex xs6 title grey--text>
          <v-card-text>lisence due</v-card-text>
        </v-flex>
        <v-flex xs6 text-right grey--text>
          <v-card-text>{{member.lisence_due}}</v-card-text>
        </v-flex>
      </v-layout>
      <v-divider/>
      <v-layout row align-center>
        <v-flex xs6 title grey--text>
          <v-card-text>contact phone</v-card-text>
        </v-flex>
        <v-flex xs6 text-right grey--text>
          <v-card-text>{{member.phone}}</v-card-text>
        </v-flex>
      </v-layout>
      <v-divider/>
      <v-layout row align-center>
        <v-flex xs6 title grey--text>
          <v-card-text>contact email</v-card-text>
        </v-flex>
        <v-flex xs6 text-right grey--text>
          <v-card-text>{{member.email}}</v-card-text>
        </v-flex>
      </v-layout>
      <v-divider/>
      <v-layout v-if="member.tags" xs6 title grey--text mt-2>
        <v-card-text>TAGS</v-card-text>
      </v-layout>
      <v-layout v-if="member.tags" row wrap>
        <f-chip
          v-for="(tag, index) in member.tags"
          :key="index"
          :color="'primary'"
        >
          {{ tag }}
        </f-chip>
      </v-layout>

      <v-divider class="mt-3"/>
      <v-btn
        class="button-grey grey mt-4"
        @click="$emit('onButtonClick')"
      >
        View Fleet Member Profile
      </v-btn>
      <v-btn
        class="button-remove grey--text mt-3"
        @click="$emit('remove')"
      >
        <v-icon class="icon left">far fa-trash-alt</v-icon>
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
    .with-icon {
      display: flex;
      flex-flow: row;
      justify-content: flex-end;
      align-items: center;
    }
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
    background: none !important;
    box-shadow: none !important;
    width: 100%;
    margin: 0;
    text-transform: none;
    font-size: 12px;
  }

  .icon {
    font-size: 1em;
    padding-left: 9px;
    color: inherit;
    align-self: center;
    justify-self: center;

    &.left {
      padding-right: 9px;
    }
    &.right {
      padding-left: 9px;
    }
  }

</style>
