<template>
  <div>
    <create-update ref="createUpdate" />
  </div>
</template>

<script>
import CreateUpdate from 'fresh-bus/components/pages/admin/companies/CreateUpdate.vue'

export default {
  extends: CreateUpdate,
  layout: CreateUpdate.layout,
  beforeRouteEnterOrUpdate: CreateUpdate.beforeRouteEnterOrUpdate
}
</script>
