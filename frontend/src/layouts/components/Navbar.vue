<template>
  <div class="navbar-container d-flex content align-items-center" style="background-color: #FAFBFF">

    <!-- Nav Menu Toggler -->
    <ul class="nav navbar-nav d-xl-none">
      <li class="nav-item">
        <b-link
          class="nav-link"
          to="/"
        >
          <b-img
            :src="require('@/assets/images/logo/logo.png')"
            height="35"
            alt="logo"
            class="mr-2"
          />
        </b-link>
      </li>
      <li class="nav-item ml-n2">
        <b-link
          class="nav-link"
          style="margin-top: 0.3em"
          @click="toggleVerticalMenuActive"
        >
          <feather-icon
            icon="MenuIcon"
            size="30"
          />
        </b-link>
      </li>
    </ul>

    <!-- Left Col -->
    <div class="bookmark-wrapper align-items-center flex-grow-1 d-none d-lg-flex">
      <feather-icon
        icon="MenuIcon"
        size="20"
        class="d-none d-xl-block"
        style="cursor: pointer;"
        @click="toggleCollapsed"
      />
      <!-- <p
        id="custom-breadcrumb"
        class="user-name font-weight-bolder mb-0 ml-1"
      /> -->
      <div class="d-sm-flex d-none user-nav ml-1">
            <p class="user-name font-weight-bolder mb-0">
              Hello <span class="text-muted">{{ firstname }}</span> <div style="font-size: 18px;">&#128079;</div> 	
            </p>
          </div>
    </div>

    <b-navbar-nav class="nav align-items-center ml-auto">
      <notification-dropdown />

      <b-nav-item-dropdown
        right
        toggle-class="d-flex align-items-center dropdown-user-link"
        class="dropdown-user"
      >
        <template #button-content>
          <b-avatar
            size="40"
            :src="profilePicture"
            :text="avatarText(firstname)"
            variant="light-dark"
          />
        
        </template>

        <b-dropdown-item
          link-class="d-flex align-items-center"
          @click="$router.push('/account')"
        >
          <feather-icon
            size="16"
            icon="UserIcon"
            class="mr-50"
          />
          <span>Account</span>
        </b-dropdown-item>
        <b-dropdown-divider />
        <b-dropdown-item
          link-class="d-flex align-items-center"
          @click="logout"
        >
          <feather-icon
            size="16"
            icon="LogOutIcon"
            class="mr-50"
          />
          <span>Logout</span>
        </b-dropdown-item>
      </b-nav-item-dropdown>
    </b-navbar-nav>
  </div>
</template>

<script>
import NotificationDropdown from '@core/layouts/components/app-navbar/components/NotificationDropdown.vue'
import useVerticalNavMenu from '@core/layouts/layout-vertical/components/vertical-nav-menu/useVerticalNavMenu'
import { mapGetters } from 'vuex'
import { avatarText } from '@core/utils/filter'

export default {
  components: {
    // Navbar Components
    NotificationDropdown,
  },
  props: {
    toggleVerticalMenuActive: {
      type: Function,
      default: () => {},
    },
  },
  data() {
    return {
      avatarText,
      profilePicture: this.$store.getters['auth/getProfilePicture'],
    }
  },
  computed: {
    ...mapGetters({
      firstname: 'auth/getFirstname',
    }),
  },
  watch: {
    profilePicture() {
      return this.$store.getters['auth/getProfilePicture']
    },
  },
  setup() {
    const {
      toggleCollapsed,
    } = useVerticalNavMenu()
    return {
      toggleCollapsed,
    }
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
.dropdown-item:hover, .dropdown-item:focus {
  color: #4D6D8A;
  background-color: #f0f8ed;
}
</style>
