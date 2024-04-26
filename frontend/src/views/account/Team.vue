<template>
  <div>
    <b-card class="px-lg-2">
      <validation-observer ref="newTeamMemberValidation">
        <b-form @submit.prevent>
          <h4>
            Add Team Member
          </h4>
          <b-row class="mt-1">
            <b-col lg="6">
              <validation-provider
                #default="{ errors }"
                name="full name"
                rules="required"
              >
                <b-form-group>
                  <label
                    for="name"
                    class="mb-lg-1 font-weight-bolder"
                  >Full Name</label>
                  <b-form-input
                    id="name"
                    v-model="name"
                    placeholder="E.g. John Doe"
                  />
                  <small class="text-danger">{{ errors[0] }}</small>
                </b-form-group>
              </validation-provider>
            </b-col>
            <b-col lg="6">
              <validation-provider
                #default="{ errors }"
                name="email"
                rules="required"
              >
                <b-form-group>
                  <label
                    for="email"
                    class="mb-lg-1 font-weight-bolder"
                  >Email</label>
                  <b-form-input
                    id="email"
                    v-model="email"
                    placeholder="E.g. johndoe@xyz.com"
                  />
                  <small class="text-danger">{{ errors[0] }}</small>
                </b-form-group>
              </validation-provider>
            </b-col>
            <b-col cols="12">
              <div class="">
                <b-button
                  v-if="!isLoading"
                  style="background-color: #4D6D8A !important; border-color: #4D6D8A !important; border-radius: 40px;"
                  @click="addTeamMember"
                >
                  Add
                </b-button>
                <b-button
                  v-if="isLoading"
                  disabled
                  style="background-color: #4D6D8A !important; border-color: #4D6D8A !important; border-radius: 40px;"
                >
                  Adding...
                </b-button>
              </div>
            </b-col>
          </b-row>
        </b-form>
      </validation-observer>
      <h4 class="mt-3">
        Team Members
      </h4>
      <hr>
      <vue-perfect-scrollbar
        :settings="perfectScrollbarSettings"
        class="team-area"
      >
        <b-spinner
          v-if="loading"
          style="width: 3rem; height: 3rem;"
          variant="primary"
          label="Loading..." type="grow"
          class="text-center"
        />
        <b-list-group
          v-if="!loading"
          class="mt-0"
          flush
        >
          <b-list-group-item
            v-for="person in team"
            :key="person.id"
          >
            <b-row>
              <b-col
                lg="4"
                class="d-flex align-content-center justify-content-center"
              >
                <b-media>
                  <template #aside>
                    <b-avatar
                      size="65"
                      :src="require('@/assets/images/avatars/avatar.png')"
                    />
                  </template>
                  <h5 class="mt-1">
                    {{ person.firstname }}
                  </h5>
                  <p class="mt-0">
                    {{ person.email }}
                  </p>
                </b-media>
              </b-col>
              <b-col
                lg="4"
                class="d-flex align-content-center justify-content-center"
              >
                <div style="padding: 29px 0">
                  Role:
                </div>
                <div style="padding: 20px 0">
                  <b-form-input
                    id="role"
                    :value="roles[person.role]"
                    style="width: 10em; margin-left: 1em"
                    disabled
                  />
                </div>
              </b-col>
              <b-col
                lg="4"
                class="d-flex align-content-center justify-content-center"
              >
                <feather-icon
                  v-b-tooltip.hover.top="'Edit'"
                  icon="EditIcon"
                  size="20"
                  class="cursor-pointer float-right mt-auto mb-auto mr-1"
                  @click="edit(person)"
                />
                <feather-icon
                  v-b-tooltip.hover.top="'Delete'"
                  icon="Trash2Icon"
                  size="20"
                  class="cursor-pointer float-right mt-auto mb-auto"
                />
              </b-col>
            </b-row>
          </b-list-group-item>
        </b-list-group>
      </vue-perfect-scrollbar>
    </b-card>
    <b-modal
      ref="edit-team-member"
      hide-footer
      hide-header
      size="lg"
      centered
    >
      <validation-observer ref="editMemberValidation">
        <b-form @submit.prevent>
          <h4 class="text-center mt-2">
            Profile details
          </h4>
          <div class="text-center mt-2">
            <b-avatar
              size="80px"
              :src="require('@/assets/images/avatars/avatar.png')"
            />
          </div>
          <b-row class="mt-1 px-lg-3 mb-2">
            <b-col lg="6">
              <validation-provider
                #default="{ errors }"
                name="name"
                rules="required"
              >
                <b-form-group>
                  <label
                    for="name"
                    class="mb-lg-1 font-weight-bolder"
                  >Name</label>
                  <b-input-group class="input-group-merge">
                    <b-input-group-prepend is-text>
                      <b-icon icon="person-fill" />
                    </b-input-group-prepend>
                    <b-form-input
                      id="name"
                      v-model="editName"
                    />
                  </b-input-group>
                  <small class="text-danger">{{ errors[0] }}</small>
                </b-form-group>
              </validation-provider>
            </b-col>
            <b-col lg="6">
              <b-form-group>
                <label
                  for="role"
                  class="mb-lg-1 font-weight-bolder"
                >Role</label>
                <b-input-group class="input-group-merge">
                  <b-input-group-prepend is-text>
                    <b-icon icon="person-badge" />
                  </b-input-group-prepend>
                  <b-form-select
                    id="role"
                    v-model="role"
                    :options="roleOptions"
                  />
                </b-input-group>
              </b-form-group>
            </b-col>
            <b-col lg="6">
              <validation-provider
                #default="{ errors }"
                name="email"
                rules="required"
              >
                <b-form-group>
                  <label
                    for="email"
                    class="mb-lg-1 font-weight-bolder"
                  >Email</label>
                  <b-input-group class="input-group-merge">
                    <b-input-group-prepend is-text>
                      <b-icon icon="envelope-fill" />
                    </b-input-group-prepend>
                    <b-form-input
                      id="email"
                      v-model="editEmail"
                      readonly
                    />
                  </b-input-group>
                  <small class="text-danger">{{ errors[0] }}</small>
                </b-form-group>
              </validation-provider>
            </b-col>
            <b-col lg="6">
              <validation-provider
                #default="{ errors }"
                name="phone"
                rules="required"
              >
                <b-form-group>
                  <label
                    for="phone"
                    class="mb-lg-1 font-weight-bolder"
                  >Phone</label>
                  <b-input-group class="input-group-merge">
                    <b-input-group-prepend is-text>
                      <b-icon icon="telephone-fill" />
                    </b-input-group-prepend>
                    <b-form-input
                      id="phone"
                      v-model="editPhone"
                    />
                  </b-input-group>
                  <small class="text-danger">{{ errors[0] }}</small>
                </b-form-group>
              </validation-provider>
            </b-col>
            <b-col cols="12">
              <div class="d-flex float-right mt-2">
                <b-button
                  style="background-color: #C4C4C4 !important; border-color: #C4C4C4 !important; border-radius: 40px; margin-right: 1.5em"
                  @click="close"
                >
                  Cancel
                </b-button>
                <b-button
                  v-if="!isLoading"
                  type="submit"
                  style="background-color: #4D6D8A !important; border-color: #4D6D8A !important; border-radius: 40px;"
                  @click="saveEdit"
                >
                  Save
                </b-button>
                <b-button
                  v-if="isLoading"
                  disabled
                  style="background-color: #4D6D8A !important; border-color: #4D6D8A !important; border-radius: 40px;"
                >
                  Saving...
                </b-button>
              </div>
            </b-col>
          </b-row>
        </b-form>
      </validation-observer>
    </b-modal>
  </div>
</template>

<script>
import VuePerfectScrollbar from 'vue-perfect-scrollbar'
import { ValidationObserver, ValidationProvider } from 'vee-validate'
import { required } from '@validations'

export default {
  components: {
    VuePerfectScrollbar,
    ValidationProvider,
    ValidationObserver,
  },
  data() {
    return {
      loading: false,
      isLoading: false,
      name: null,
      email: null,
      id: null,
      editName: null,
      editEmail: null,
      editPhone: null,
      roleOptions: [
        { value: 1, text: 'Admin', disabled: true },
        { value: 2, text: 'Write' },
        { value: 3, text: 'Read' },
        { value: 4, text: 'Client', disabled: true },
      ],
      role: null,
      team: [],
      perfectScrollbarSettings: {
        maxScrollbarLength: 60,
      },
      roles: {
        1: 'Admin',
        2: 'Write',
        3: 'Read',
        4: 'Client',
      },
      required,
    }
  },
  created() {
    this.getTeamMembers()
  },
  methods: {
    getTeamMembers() {
      this.loading = true
      this.$store.dispatch('account/getTeamMembers').then(res => {
        const { data } = res.data
        this.team = data
        this.loading = false
      })
    },
    addTeamMember() {
      this.isLoading = true
      this.$refs.newTeamMemberValidation.validate().then(success => {
        if (success) {
          const form = {
            email: this.email,
            name: this.name,
          }
          this.$store.dispatch('account/addTeamMember', { form }).then(res => {
            this.isLoading = false
            const { message } = res.data
            this.getTeamMembers()
            this.toast('Add Team Member', 'CheckCircleIcon', message, 'success')
          }).catch(err => {
            this.isLoading = false
            this.toast('Add Team Member', 'SlashIcon', err.response.data.message, 'danger')
          })
        } else {
          this.isLoading = false
          this.toast('Add Team Member Attempt', 'AlertTriangleIcon', 'You must fill in all required fields', 'warning')
        }
      })
    },
    edit(teamMember) {
      this.editName = teamMember.firstname
      this.editEmail = teamMember.email
      this.editPhone = teamMember.phone
      this.role = teamMember.role
      this.id = teamMember.id
      this.$refs['edit-team-member'].show()
    },
    close() {
      this.$refs['edit-team-member'].hide()
    },
    saveEdit() {
      this.isLoading = true
      this.$refs.editMemberValidation.validate().then(success => {
        if (success) {
          const form = {
            id: this.id,
            phone: this.editPhone,
            role: this.role,
            name: this.editName,
          }
          this.$store.dispatch('account/updateTeamMember', { form }).then(res => {
            this.isLoading = false
            const { message } = res.data
            this.getTeamMembers()
            this.close()
            this.toast('Edit Team Member', 'CheckCircleIcon', message, 'success')
          }).catch(err => {
            this.isLoading = false
            this.toast('Edit Team Member', 'SlashIcon', err.response.data.message, 'danger')
          })
        } else {
          this.isLoading = false
          this.toast('Edit Team Member Attempt', 'AlertTriangleIcon', 'You must fill in all required fields', 'warning')
        }
      })
    },
  },
}
</script>

<style>
.team-area {
  position: relative;
  margin: auto;
//width: 400px;
  height: 30vh;
  max-height: 30vh;
}
[dir] .form-control:focus {
  border-color: #4D6D8A;
}
[dir] .input-group:not(.bootstrap-touchspin):focus-within .form-control, [dir] .input-group:not(.bootstrap-touchspin):focus-within .input-group-text {
  border-color: #4D6D8A;
}
[dir] .custom-select:focus {
  border-color: #4D6D8A;
}
</style>
