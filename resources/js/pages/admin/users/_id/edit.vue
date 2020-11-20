<template>
  <div>
    <v-layout
      row
      align-center
      pt-3
    >
      <v-btn
        flat
        small
        @click="backToList"
      >
        <div class="back-btn-inner">
          <v-icon>fas fa-arrow-left</v-icon>
          <span>Return to User list</span>
        </div>
      </v-btn>
    </v-layout>

    <create-update />
  </div>
</template>

<script>
import CreateUpdate from 'fresh-bus/components/pages/admin/users/CreateUpdate.vue'

export default {
  components: { CreateUpdate },
  extends: CreateUpdate,
  layout: CreateUpdate.layout,
  beforeRouteEnterOrUpdate: CreateUpdate.beforeRouteEnterOrUpdate,
  data () {
    return {
      enabledFields: [
        'company',
        'title',
        'mobile_phone',
        'office_phone',
        'notes',
        'level',
        'type',
        'street',
        'street_2',
        'city',
        'country_id',
        'post_code'
      ]
    }
  },
  methods: {
    backToList () {
      this.$router.push({ path: '/admin/users' })
    }
  }
}
</script>

<style scoped>
  .back-btn-inner{
    color: #fff;
    display: flex;
    align-items: center;
    font-size: 13px;
  }
  .back-btn-inner span{
    margin-left: 10px;
    font-weight: bold;
    text-transform: initial;
  }
  .back-btn-inner .v-icon{
    font-size: 16px;
  }
</style>
