<script>
export default {
  created () {
    // we don't want user to access /admin/companies/:id
    // see https://github.com/FreshinUp/foodfleet/issues/574
    this.$router.push({
      path: `/admin/companies/${this.$route.params.id}/edit`
    })
  }
}
</script>
