<template>
  <v-card>
    <v-container font-weight-bold grey--text header>
      Fleet Member Summary
    </v-container>
    <v-divider/>
    <v-container pt-0 font-weight-bold grey--text>
      <v-layout line align-center>
        <v-card-text class="left ">OWNER</v-card-text>
        <v-card-text class="right">{{member.owner}}</v-card-text>
      </v-layout>
      <v-divider/>
      <v-layout line align-center>
        <v-card-text class="left ">LISENCE DUE</v-card-text>
        <v-card-text class="right">{{member.lisence_due}}</v-card-text>
      </v-layout>
      <v-divider/>
      <v-layout line align-center >
        <v-card-text class="left ">CONTACT PHONE</v-card-text>
        <v-card-text class="right">{{member.phone}}</v-card-text>
      </v-layout>
      <v-divider/>
      <v-layout line align-center>
        <v-card-text class="left ">CONTACT EMAIL</v-card-text>
        <v-card-text class="right">{{member.email}}</v-card-text>
      </v-layout>
      <v-divider/>
      <v-layout v-if="member.tags" tags_title font-weight-bold grey--text mt-2>
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
        class="button-grey grey mt-4 white--text ma-0"
        @click="$emit('onButtonClick')"
      >
        View Fleet Member Profile
      </v-btn>
      <v-btn
        class="transparent elevation-0 ma-0 pr-0  grey--text mt-3 button-remove"
        @click="$emit('remove')"
      >
        <v-icon class="icon pr-2">far fa-trash-alt</v-icon>
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
    font-size: 17px;
  }
  .line {
    .left {
      font-size: 11px;
    }
    .right {
      font-size: 17px;
      text-align: end;
    }
  }

  .button-grey {
    width: 100%;
    text-transform: none;
  }

  .tags_title {
    font-size: 11px;
  }

  .v-card__text {
    padding: 12px 0px;
    width: 100%;
    overflow: hidden;
    white-space: nowrap;
    text-overflow: ellipsis;
  }

  .v-chip {
    margin: 6px 12px 6px 0px;
  }

  .button-remove {
    font-size: 11px;
    width: 100%;
    text-transform: none;
  }

  .icon {
    font-size: 1em;
    color: inherit;
    align-self: center;
    justify-self: center;
  }

</style>
