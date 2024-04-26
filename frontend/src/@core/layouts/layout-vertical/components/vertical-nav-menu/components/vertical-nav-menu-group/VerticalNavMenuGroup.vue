<template>
  <li
    v-if="canViewVerticalNavMenuGroup(item)"
    class="nav-item has-sub mb-1"
    :class="{
      'open': isOpen,
      'disabled': item.disabled,
      'sidebar-group-active': isActive,
    }"
  >
    <b-link
      class="d-flex align-items-center"
      @click="() => updateGroupOpen(!isOpen)"
    >
      <!--<feather-icon :icon="item.icon || 'CircleIcon'" />-->
      <div v-if="item.icon === 'inventory.svg'">
        <svg
          width="25"
          height="24"
          viewBox="0 0 25 24"
          fill="none"
          xmlns="http://www.w3.org/2000/svg"
        >
          <path
            stroke-width="0.5"
            fill-rule="evenodd"
            clip-rule="evenodd"
            d="M12.1478 0.134295C12.0187 0.206968 9.27611 2.09666 6.05303 4.33356C2.07492 7.09436 0.153726 8.48991 0.0708466 8.67849C-0.0905732 9.04624 0.0302747 9.47821 0.367868 9.74022C0.742561 10.0309 1.01637 10.0067 1.5886 9.63212L2.07677 9.31253L2.13101 16.0514L2.18525 22.7901L2.42695 23.0286C2.7615 23.3585 3.38928 23.354 3.66374 23.0196C3.8577 22.7834 3.86671 22.4439 3.86671 15.4105V8.04863L8.16527 5.06735C10.5294 3.42756 12.4854 2.09324 12.512 2.10223C12.5385 2.11111 14.5077 3.45967 16.8882 5.09903L21.2163 8.07956L21.22 15.3783L21.2237 22.677L21.4808 22.9719C21.8134 23.3534 22.4159 23.3797 22.7719 23.0286L23.0136 22.7902L23.0678 16.0941L23.1221 9.39794L23.3933 9.60697C23.8091 9.92752 24.1056 9.9821 24.4574 9.80261C24.8231 9.61606 25.0738 9.10607 24.9804 8.73886C24.9327 8.55177 23.4005 7.43568 19.1914 4.52268C12.4959 -0.111336 12.6582 -0.00152411 12.5029 0.000509433C12.4367 0.00136566 12.2769 0.0615157 12.1478 0.134295ZM5.70047 9.39066C5.63473 9.56084 5.60154 10.2851 5.62171 11.1072C5.64927 12.228 5.69114 12.5509 5.81937 12.6307C6.03134 12.7627 8.97031 12.7627 9.18228 12.6307C9.39024 12.5013 9.48646 9.67118 9.29499 9.3182C9.18119 9.10843 9.08334 9.09633 7.49431 9.09633H5.81394L5.70047 9.39066ZM6.11769 13.8762C5.64721 13.9561 5.58288 14.212 5.6215 15.8511C5.64937 17.036 5.69038 17.3668 5.81937 17.447C6.03134 17.579 8.97031 17.579 9.18228 17.447C9.3118 17.3664 9.35183 17.0336 9.37852 15.8164C9.423 13.7895 9.49427 13.8761 7.7576 13.8477C7.01971 13.8356 6.28171 13.8484 6.11769 13.8762ZM11.2977 13.8778C10.833 14.0017 10.8095 14.0906 10.8095 15.7238C10.8095 16.8029 10.8503 17.335 10.9397 17.4232C11.1214 17.6024 14.186 17.6024 14.3677 17.4232C14.4571 17.335 14.4978 16.8029 14.4978 15.7238C14.4978 14.3935 14.4704 14.1304 14.3185 14.0059C14.1349 13.8556 11.7594 13.7548 11.2977 13.8778ZM6.09057 18.6918C5.66445 18.8002 5.6024 19.029 5.6024 20.4916C5.6024 22.4466 5.52397 22.3683 7.47988 22.3663C8.32669 22.3653 9.09278 22.3191 9.18228 22.2633C9.3118 22.1827 9.35183 21.8499 9.37852 20.6327C9.42332 18.5913 9.49969 18.6811 7.69945 18.6559C6.9339 18.6452 6.2099 18.6614 6.09057 18.6918ZM11.2977 18.6952C10.8334 18.8167 10.8095 18.9067 10.8095 20.54C10.8095 21.6192 10.8503 22.1512 10.9397 22.2394C11.1214 22.4187 14.186 22.4187 14.3677 22.2394C14.4571 22.1512 14.4978 21.6192 14.4978 20.54C14.4978 19.2109 14.4703 18.9466 14.3189 18.8222C14.1364 18.6725 11.7654 18.5727 11.2977 18.6952ZM16.3633 18.693C15.9952 18.77 15.9081 19.1321 15.9081 20.5851C15.9081 22.4346 15.8372 22.3679 17.8049 22.3679C19.0776 22.3679 19.3953 22.3369 19.5345 22.1997C19.6729 22.0632 19.7049 21.7551 19.7049 20.5612C19.7049 18.5951 19.7809 18.6801 18.0051 18.6582C17.2396 18.6486 16.5007 18.6644 16.3633 18.693Z"
            fill="#CBCBCB"
          />
        </svg>
      </div>
      <span class="menu-title text-truncate ml-3">{{ t(item.title) }}</span>
      <b-badge
        v-if="item.tag"
        pill
        :variant="item.tagVariant || 'primary'"
        class="mr-1 ml-auto"
      >
        {{ item.tag }}
      </b-badge>
    </b-link>
    <b-collapse
      v-model="isOpen"
      class="menu-content"
      tag="ul"
    >
      <component
        :is="resolveNavItemComponent(child)"
        v-for="child in item.children"
        :key="child.header || child.title"
        ref="groupChild"
        :item="child"
      />
    </b-collapse>
  </li>
</template>

<script>
import { BLink, BBadge, BCollapse } from 'bootstrap-vue'
import { resolveVerticalNavMenuItemComponent as resolveNavItemComponent } from '@core/layouts/utils'
import { useUtils as useI18nUtils } from '@core/libs/i18n'
import { useUtils as useAclUtils } from '@core/libs/acl'
import VerticalNavMenuHeader from '../vertical-nav-menu-header'
import VerticalNavMenuLink from '../vertical-nav-menu-link/VerticalNavMenuLink.vue'

// Composition Function
import useVerticalNavMenuGroup from './useVerticalNavMenuGroup'
import mixinVerticalNavMenuGroup from './mixinVerticalNavMenuGroup'

export default {
  name: 'VerticalNavMenuGroup',
  components: {
    VerticalNavMenuHeader,
    VerticalNavMenuLink,
    BLink,
    BBadge,
    BCollapse,
  },
  mixins: [mixinVerticalNavMenuGroup],
  props: {
    item: {
      type: Object,
      required: true,
    },
  },
  setup(props) {
    const {
      isOpen,
      isActive,
      updateGroupOpen,
      updateIsActive,
    } = useVerticalNavMenuGroup(props.item)

    const { t } = useI18nUtils()
    const { canViewVerticalNavMenuGroup } = useAclUtils()

    return {
      resolveNavItemComponent,
      isOpen,
      isActive,
      updateGroupOpen,
      updateIsActive,

      // ACL
      canViewVerticalNavMenuGroup,

      // i18n
      t,
    }
  },
}
</script>

<style>
[dir] .main-menu.menu-light .navigation > li.open:not(.menu-item-closing) > a, [dir] .main-menu.menu-light .navigation > li.sidebar-group-active > a {
  background: none;
  border-radius: 0;
  color: #4D6D8A;
  fill: #4D6D8A;
  stroke: #4D6D8A;
  stroke-width: 0.2;
}

.main-menu.menu-light .navigation > li.active {
  border-left: 3px solid #4D6D8A;
}

[dir=ltr] .main-menu.menu-light .navigation > li ul .active {
  background: transparent;
  box-shadow: none;
}

.main-menu.menu-light .navigation > li ul .active > a {
  color: #4D6D8A;
}

.sidebar-group-active {
  border-left: 3px solid #4D6D8A;
}
</style>
