<template>
  <div
    class="main-menu menu-fixed menu-accordion menu-shadow"
    :class="[
      { 'expanded': !isVerticalMenuCollapsed || (isVerticalMenuCollapsed && isMouseHovered) },
      skin === 'semi-dark' ? 'menu-dark' : 'menu-light'
    ]"
    @mouseenter="updateMouseHovered(true)"
    @mouseleave="updateMouseHovered(false)"
  >
    <!-- main menu header-->
    <div class="navbar-header expanded">
      <slot
        name="header"
        :toggleVerticalMenuActive="toggleVerticalMenuActive"
        :toggleCollapsed="toggleCollapsed"
        :collapseTogglerIcon="collapseTogglerIcon"
      >
        <ul class="nav navbar-nav flex-row">
          <!-- Logo & Text -->
          <li class="nav-item mr-auto">
            <b-link
              v-if="isVerticalMenuCollapsed==true"
              class="navbar-brand"
              to="/"
            >
              <span
                class="brand-logo"
                style="margin-top: -0.6rem !important;"
              >
                <b-img
                  :src="appLogoImage"
                  height="50"
                  width="600"
                  alt="logo"
                />
              </span>
              <h2 class="brand-text">
                {{ appName }}
              </h2>
            </b-link>

            <div
              v-if="isVerticalMenuCollapsed==false"
              style="width: 50px; heigth: 50px; margin-top: 20px; margin-bottom: 15px;"
            >
              <img
                style="width: 100%; heigth: 100%"
                src="../../../../../assets/images/logo/full-logo.png"
              >
            </div>

          </li>

          <!-- Toggler Button -->
          <li class="nav-item nav-toggle">
            <b-link class="nav-link modern-nav-toggle">
              <!--              <feather-icon-->
              <!--                icon="XIcon"-->
              <!--                size="20"-->
              <!--                class="d-block d-xl-none"-->
              <!--                @click="toggleVerticalMenuActive"-->
              <!--              />-->
              <b-img
                :src="require('@/assets/images/icons/sidebar-icon.png')"
                height="15"
                alt="icon"
                class="d-block d-xl-none"
                @click="toggleVerticalMenuActive"
              />
              <feather-icon
                icon="ArrowLeftIcon"
                size="35"
                height="20"
                alt="icon"
                class="d-none d-xl-block collapse-toggle-icon"
                @click="ToggleSideBar"
              />
              <!-- <b-img
                :src="require('@/assets/images/icons/sidebar-icon.png')"
                height="15"
                alt="icon"
                class="d-none d-xl-block collapse-toggle-icon"
                @click="toggleCollapsed"
              /> -->
              <!--              <feather-icon-->
              <!--                :icon="collapseTogglerIconFeather"-->
              <!--                size="20"-->
              <!--                class="d-none d-xl-block collapse-toggle-icon"-->
              <!--                -->
              <!--              />-->
            </b-link>
          </li>
        </ul>
      </slot>
    </div>
    <!-- / main menu header-->

    <!-- Shadow -->
    <!--    <div-->
    <!--      :class="{'d-block': shallShadowBottom}"-->
    <!--      class="shadow-bottom"-->
    <!--    />-->

    <!-- main menu content-->
    <vue-perfect-scrollbar
      :settings="perfectScrollbarSettings"
      class="main-menu-content scroll-area mt-2"
      tagname="ul"
      @ps-scroll-y="evt => { shallShadowBottom = evt.srcElement.scrollTop > 0 }"
    >
      <vertical-nav-menu-items
        :items="navMenuItems"
        class="navigation navigation-main"
      />

      <!-- <b-nav-item-dropdown

        toggle-class="d-flex align-items-center dropdown-user-link"
        class="dropdown-user"
      >

        <template #button-content>

          <b-avatar
            size="40"
            :src="profilePicture"
            :text="avatarText(firstname)"
            variant="primary"
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
      </b-nav-item-dropdown> -->

    </vue-perfect-scrollbar>
    <!-- /main menu content-->

  </div>
</template>

<script>
import VuePerfectScrollbar from 'vue-perfect-scrollbar'
import { BLink, BImg } from 'bootstrap-vue'
import { provide, computed, ref } from '@vue/composition-api'
import useAppConfig from '@core/app-config/useAppConfig'
import { $themeConfig } from '@themeConfig'
// eslint-disable-next-line import/no-unresolved
import { avatarText } from '@core/utils/filter'
import navMenuItems from '@/navigation/vertical'
import VerticalNavMenuItems from './components/vertical-nav-menu-items/VerticalNavMenuItems.vue'
import useVerticalNavMenu from './useVerticalNavMenu'

export default {
  components: {
    VuePerfectScrollbar,
    VerticalNavMenuItems,
    BLink,
    BImg,
  },
  props: {
    isVerticalMenuActive: {
      type: Boolean,
      required: true,
    },
    toggleVerticalMenuActive: {
      type: Function,
      required: true,
    },
  },
  data() {
    return {
      showBigLogo: false,
      avatarText,
      profilePicture: this.$store.getters['auth/getProfilePicture'],
    }
  },
  computed() {
    ToggleSideBar()
  },
  methods: {
    ToggleSideBar() {
      if (this.isVerticalMenuCollapsed) {
        this.showBigLogo = !this.showBigLogo
      }

      this.toggleCollapsed()
    },
  },
  setup(props) {
    const {
      isMouseHovered,
      isVerticalMenuCollapsed,
      collapseTogglerIcon,
      toggleCollapsed,
      updateMouseHovered,
    } = useVerticalNavMenu(props)

    const { skin } = useAppConfig()

    // Shadow bottom is UI specific and can be removed by user => It's not in `useVerticalNavMenu`
    const shallShadowBottom = ref(false)

    provide('isMouseHovered', isMouseHovered)

    const perfectScrollbarSettings = {
      maxScrollbarLength: 60,
      wheelPropagation: false,
    }

    const collapseTogglerIconFeather = computed(() => (collapseTogglerIcon.value === 'unpinned' ? 'CircleIcon' : 'DiscIcon'))

    // App Name
    const { appName, appLogoImage } = $themeConfig.app

    return {
      navMenuItems,
      perfectScrollbarSettings,
      isVerticalMenuCollapsed,
      collapseTogglerIcon,
      toggleCollapsed,
      isMouseHovered,
      updateMouseHovered,
      collapseTogglerIconFeather,

      // Shadow Bottom
      shallShadowBottom,

      // Skin
      skin,

      // App Name
      appName,
      appLogoImage,
    }
  },
}
</script>

<style lang="scss">
@import "~@core/scss/base/core/menu/menu-types/vertical-menu.scss";
</style>
