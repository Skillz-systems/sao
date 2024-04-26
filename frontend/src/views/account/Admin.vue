<template>
  <b-tabs
    vertical
    content-class="col-12 col-md-9 mt-1 mt-md-0"
    pills
    nav-wrapper-class="col-md-3 col-12"
    nav-class="nav-left"
  >
    <b-tab active>
      <template #title>
        <feather-icon
          icon="UsersIcon"
          size="18"
          class="mr-50"
        />
        <span class="font-weight-bold">Team</span>
      </template>
      <team />
    </b-tab>
    <b-tab>
      <template #title>
        <feather-icon
          icon="ShieldIcon"
          size="18"
          class="mr-50"
        />
        <span class="font-weight-bold">Security</span>
      </template>
      <security />
    </b-tab>
        <b-tab>
      <template #title>
        <feather-icon
          icon="ShieldIcon"
          size="18"
          class="mr-50"
        />
        <span class="font-weight-bold">Miscellaneous</span>
      </template>
      <miscellaneous />
    </b-tab>
    <b-tab @click="logout">
      <template #title>
        <feather-icon
          icon="LogOutIcon"
          size="18"
          class="mr-50"
        />
        <span class="font-weight-bold">Logout</span>
      </template>
    </b-tab>
  </b-tabs>
</template>

<script>
import Team from './Team.vue'
import Security from './Security.vue'
import Miscellaneous from './Miscellaneous.vue'

export default {
  components: {
    Team,
    Security,
    Miscellaneous
  },
  mounted() {
    document.getElementById('custom-breadcrumb').innerHTML = 'Account'
  },
  methods: {
    logout() {
      this.$store.dispatch('auth/logout').then(() => {
        this.$router.push({ name: 'login' }).then(() => {
          this.$store.dispatch('auth/logout').then(() => {
            this.toast('Thank You', 'LogOutIcon', 'You have logged out successfully', 'success')
          })
        })
      })
    },
  },
}
</script>

<style>
[dir] .nav-pills .nav-link.active {
  border-color: transparent;
  box-shadow: none;
  color: #4D6D8A;
}
[dir] .nav-pills .nav-link.active, [dir] .nav-pills .show > .nav-link {
  background-color: transparent;
}
</style>
