<template>
  <div>
    <b-row>
      <b-col
        lg="4"
        class="d-lg-block d-none"
      >
        <b-card
          no-body
        >
          <b-card-header style="flex-wrap: nowrap">
            <b-input-group class="input-group-merge">
              <b-input-group-prepend is-text>
                <feather-icon
                  icon="SearchIcon"
                  class="text-muted"
                />
              </b-input-group-prepend>
              <b-form-input
                placeholder="Search messages"
              />
            </b-input-group>
            <b-dropdown
              v-b-tooltip.hover.top="'Select Project'"
              variant="link"
              no-caret
              class="chart-dropdown ml-1"
              toggle-class="p-0"
            >
              <template #button-content>
                <feather-icon
                  icon="MoreVerticalIcon"
                  size="18"
                  class="text-body cursor-pointer"
                />
              </template>
              <b-dropdown-item
                v-for="project in projects"
                :key="project.value"
                @click="getProjectMessages(project.value, project.key)"
              >
                {{ project.key }}
              </b-dropdown-item>
            </b-dropdown>
          </b-card-header>
          <hr class="mt-0">
          <div
            class="custom-scroll mt-1"
          >
            <vue-perfect-scrollbar
              :settings="perfectScrollbarSettings"
              class="scroll-area"
            >
              <h4
                v-if="!currentProject"
                class="px-2"
              >
                Select Project
              </h4>
              <div
                v-if="currentProject"
                class="px-2 d-flex justify-content-between"
              >
                <h4>
                  {{ currentProject }}
                </h4>
                <b-spinner
                  v-if="!loadedMessages"
                  style="width: 1.1em; height: 1.1em"
                  variant="primary"
                  label="Loading..." type="grow"
                  class="text-center"
                />
              </div>
              <b-list-group
                v-if="messages.length > 0"
                flush
              >
                <b-list-group-item
                  v-for="message in messages"
                  :key="message.id"
                  href="javascript:void(0)"
                  @click="viewMessage(message)"
                >
                  <div class="flex-column align-items-start">
                    <div class="d-flex w-100 justify-content-between">
                      <h5 style="margin-bottom: 0.5em">
                        {{ message.subject }}
                      </h5>
                    </div>
                    <p class="mb-0">
                      <span class="bullet bullet-success bullet-sm svg-font-small-3 mr-50 cursor-pointer" />
                      {{ message.sendername }}
                    </p>
                    <small class="text-muted">{{ message.date }}</small>
                  </div>
                </b-list-group-item>
              </b-list-group>
            </vue-perfect-scrollbar>
          </div>
        </b-card>
      </b-col>
      <b-col lg="8">
        <b-card
          no-body
          style="height: 73vh"
        >
          <b-card-header class="d-block">
            <b-link
              v-b-toggle.sidebar-1
              class="d-lg-none"
              style="margin-top: 0.3em; color: #878491"
            >
              <feather-icon
                icon="MenuIcon"
                size="30"
              />
            </b-link>
            <div v-if="isHidden">
              <h4
                class="mt-lg-0 mt-2 mb-1"
              >
                {{ currentMessage.subject }}
              </h4>
              <b-media>
                <template #aside>
                  <b-avatar
                    size="62"
                    class="cursor-pointer badge-minimal avatar-border-2"
                    :src="require('@/assets/images/avatars/view-project-avatar.svg')"
                    variant="transparent"
                    badge
                    badge-variant="dark"
                  />
                </template>
                <h5 class="mt-1">
                  {{ currentMessage.sendername }}
                </h5>
                <p class="mt-0 text-muted">
                  {{ currentMessage.date }}
                </p>
              </b-media>
            </div>
          </b-card-header>
          <hr
            v-if="isHidden"
            class="mt-n1"
          >
          <div
            v-if="!isHidden"
            style="display: flex; justify-content: center; align-items: center; padding-top: 5em"
          >
            <b-img
              :src="require('@/assets/images/icons/messages-icon.svg')"
            />
          </div>
          <div
            v-if="!isHidden"
            class="mt-2 text-center mr-auto ml-auto"
            style="border: 0.5px solid #C4C4C4; box-sizing: border-box; border-radius: 50px; padding: 10px 15px 5px 15px;"
          >
            <h5
              style="color: #4D6D8A"
            >
              Select a message to view the message and reply
            </h5>
          </div>
          <vue-perfect-scrollbar
            v-if="isHidden"
            :settings="perfectScrollbarSettings"
            class="scroll-area"
          >
            <b-card-body>
              <p>
                {{ currentMessage.message }}
              </p>
            </b-card-body>
          </vue-perfect-scrollbar>
          <hr v-if="isHidden">
          <b-card-body
            v-if="isHidden"
            class="mt-n1 mb-1"
          >
            <validation-observer ref="sendReplyValidation">
              <b-form @submit.prevent>
                <validation-provider
                  #default="{ errors }"
                  name="message"
                  rules="required"
                >
                  <b-form-group>
                    <b-input-group class="input-group-merge">
                      <b-input-group-prepend is-text>
                        <feather-icon
                          icon="PaperclipIcon"
                          class="cursor-pointer"
                        />
                      </b-input-group-prepend>
                      <b-form-textarea
                        v-model="reply"
                        placeholder="Reply this message"
                        no-resize
                        size="sm"
                      />
                      <b-input-group-append is-text>
                        <feather-icon
                          v-if="isLoading"
                          icon="MoreHorizontalIcon"
                          class="cursor-pointer"
                        />
                        <feather-icon
                          v-if="!isLoading"
                          v-b-tooltip.hover.top="'Send Message'"
                          icon="SendIcon"
                          class="cursor-pointer"
                          @click="sendReply"
                        />
                      </b-input-group-append>
                    </b-input-group>
                    <small class="text-danger">{{ errors[0] }}</small>
                  </b-form-group>
                </validation-provider>
              </b-form>
            </validation-observer>
          </b-card-body>
        </b-card>
      </b-col>
    </b-row>
    <b-sidebar
      id="sidebar-1"
      shadow
      backdrop
      lazy
      bg-variant="white"
    >
      <div class="px-2 py-2 d-flex justify-content-between align-items-center">
        <b-input-group class="input-group-merge">
          <b-input-group-prepend is-text>
            <feather-icon
              icon="SearchIcon"
              class="text-muted"
            />
          </b-input-group-prepend>
          <b-form-input
            placeholder="Search message or client"
          />
        </b-input-group>
        <b-dropdown
          v-b-tooltip.hover.top="'Select Project'"
          variant="link"
          no-caret
          class="chart-dropdown ml-1"
          toggle-class="p-0"
        >
          <template #button-content>
            <feather-icon
              icon="MoreVerticalIcon"
              size="18"
              class="text-body cursor-pointer"
            />
          </template>
          <b-dropdown-item
            v-for="project in projects"
            :key="project.value"
            @click="getProjectMessages(project.value, project.key)"
          >
            {{ project.key }}
          </b-dropdown-item>
        </b-dropdown>

      </div>
      <hr class="mt-0">
      <div
        class="custom-scroll mt-1"
      >
        <vue-perfect-scrollbar
          :settings="perfectScrollbarSettings"
          class="scroll-area"
        >
          <h4
            v-if="!currentProject"
            class="px-2"
          >
            Select Project
          </h4>
          <div
            v-if="currentProject"
            class="px-2 d-flex justify-content-between"
          >
            <h4>
              {{ currentProject }}
            </h4>
            <b-spinner
              v-if="!loadedMessages"
              style="width: 1.1em; height: 1.1em"
              variant="dark"
              label="Loading..." type="grow"
              class="text-center"
            />
          </div>
          <b-list-group
            v-if="messages.length > 0"
            flush
          >
            <b-list-group-item
              v-for="message in messages"
              :key="message.id"
              v-b-toggle.sidebar-1
              href="javascript:void(0)"
              @click="viewMessage(message)"
            >
              <div class="flex-column align-items-start">
                <div class="d-flex w-100 justify-content-between">
                  <h5 style="margin-bottom: 0.5em">
                    {{ message.subject }}
                  </h5>
                </div>
                <p class="mb-0">
                  <span class="bullet bullet-success bullet-sm svg-font-small-3 mr-50 cursor-pointer" />
                  {{ message.sendername }}
                </p>
                <small class="text-muted">{{ message.date }}</small>
              </div>
            </b-list-group-item>
          </b-list-group>
        </vue-perfect-scrollbar>
      </div>
    </b-sidebar>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
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
      isLoading: false,
      perfectScrollbarSettings: {
        maxScrollbarLength: 60,
      },
      isHidden: false,
      sidebar: false,
      projects: [],
      messages: [],
      currentProject: null,
      currentProjectID: null,
      currentMessage: null,
      loadedMessages: false,
      reply: null,
      required,
    }
  },
  computed: {
    ...mapGetters({
      userType: 'auth/getUserType',
    }),
  },
  created() {
    this.getProjects()
  },
  mounted() {
    document.getElementById('custom-breadcrumb').innerHTML = 'Messages'
  },
  methods: {
    getProjects() {
      if (this.userType === 'client') {
        this.$store.dispatch('project/getClientProjects').then(res => {
          const { data } = res.data
          data.forEach(project => {
            this.projects.push({ key: project.projectname, value: project.id })
          })
        })
      } else {
        this.$store.dispatch('project/getProjects').then(res => {
          const { data } = res.data
          data.forEach(project => {
            this.projects.push({ key: project.projectname, value: project.id })
          })
        })
      }
    },
    getProjectMessages(projectID, projectName) {
      this.loadedMessages = false
      this.currentProject = projectName
      this.currentProjectID = projectID
      this.messages = []
      this.$store.dispatch('message/getMessageByProjectID', { projectID }).then(res => {
        const { data } = res.data
        this.messages = data
        this.loadedMessages = true
      })
    },
    viewMessage(message) {
      this.isHidden = true
      this.currentMessage = message
    },
    sendReply() {
      this.isLoading = true
      this.$refs.sendReplyValidation.validate().then(success => {
        if (success) {
          const form = {
            projectID: this.currentProjectID,
            subject: this.currentMessage.subject,
            message: this.reply,
          }
          this.$store.dispatch('project/sendMessage', { form }).then(res => {
            this.isLoading = false
            const { message } = res.data
            this.getProjectMessages(this.currentProjectID, this.currentProject)
            this.toast('Send Message', 'CheckCircleIcon', message, 'success')
          }).catch(err => {
            this.isLoading = false
            this.toast('Send Message', 'SlashIcon', err.response.data.message, 'danger')
          })
        } else {
          this.isLoading = false
          this.toast('Send Reply Attempt', 'AlertTriangleIcon', 'You must fill in all required fields', 'warning')
        }
      })
    },
  },
}
</script>

<style scoped>
[dir] .input-group:not(.bootstrap-touchspin):focus-within .form-control, [dir] .input-group:not(.bootstrap-touchspin):focus-within .input-group-text {
  border-color: #4D6D8A;
}

.scroll-area {
  position: relative;
  margin: auto;
  //width: 400px;
  height: 60vh;
  max-height: 60vh;
}

[dir] .form-control:focus {
  border-color: #4D6D8A;
}

</style>
