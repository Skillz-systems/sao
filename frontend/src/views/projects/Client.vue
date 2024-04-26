<template>
  <div>
    <b-row>
      <b-col lg="6">
        <div class="d-flex">
          <b-button
            v-if="currentView === 'all'"
            variant="dark"
            style="border-radius: 50px"
          >
            All ({{ rows }})
          </b-button>
          <b-button
            v-if="currentView === 'completed' || currentView === 'ongoing'"
            variant="outline-dark"
            style="border-radius: 50px"
            @click="viewAll"
          >
            All ({{ rows }})
          </b-button>
          <b-button
            v-if="currentView === 'completed'"
            variant="dark"
            class="ml-1"
            style="border-radius: 50px"
          >
            Completed ({{ completed }})
          </b-button>
          <b-button
            v-if="currentView === 'all' || currentView === 'ongoing'"
            variant="outline-dark"
            class="ml-1"
            style="border-radius: 50px"
            @click="viewCompleted"
          >
            Completed ({{ completed }})
          </b-button>
          <b-button
            v-if="currentView === 'ongoing'"
            variant="dark"
            class="ml-1"
            style="border-radius: 50px"
          >
            Ongoing ({{ ongoing }})
          </b-button>
          <b-button
            v-if="currentView === 'all' || currentView === 'completed'"
            variant="outline-dark"
            class="ml-1"
            style="border-radius: 50px"
            @click="viewOngoing"
          >
            Ongoing ({{ ongoing }})
          </b-button>
        </div>
      </b-col>
      <b-col lg="6">
        <b-row>
          <b-col />
          <b-col
            lg="8"
            class="mt-lg-0 mt-2"
          >
            <b-input-group style="border-radius: 20px">
              <b-input-group-prepend
                is-text
                style="background-color: #F9F9F9"
              >
                <feather-icon icon="SearchIcon" />
              </b-input-group-prepend>
              <b-form-input
                v-model="filter"
                placeholder="Quick Search"
              />
            </b-input-group>
          </b-col>
        </b-row>
      </b-col>
    </b-row>
    <b-row class="mt-3">
      <b-col
        v-if="!loaded"
      >
        <b-spinner
          style="width: 3rem; height: 3rem;"
          variant="primary"
          label="Loading..." type="grow"
          class="text-center"
        />
      </b-col>
      <b-col
        v-for="project in projectList"
        :key="project.id"
        lg="12"
      >
        <b-card
          no-body
          style="border-radius: 10px"
        >
          <b-card-header>
            <div />
            <b-button
              size="sm"
              class="float-right"
              style="background-color: #4D6D8A !important; border-color: #4D6D8A !important;"
              @click="showModal(project.id)"
            >
              Send Feedback
            </b-button>
          </b-card-header>
          <b-row class="pl-2 pl-lg-3 pb-1 pr-1">
            <b-col
              lg="3"
              md="6"
            >
              <strong class="mr-1">Project Name:</strong> {{ project.projectname }}
            </b-col>
            <b-col
              lg="3"
              md="6"
            >
              <strong class="mr-1">Number of Panels:</strong> {{ project.numberofpanels }}
            </b-col>
            <b-col
              lg="3"
              md="6"
            >
              <strong class="mr-1">Number of Batteries:</strong> {{ project.numberofbatteries }}
            </b-col>
            <b-col
              lg="3"
              md="6"
            >
              <strong class="mr-1">System Type:</strong> {{ project.projecttype }}
            </b-col>
            <b-col
              lg="3"
              md="6"
            >
              <strong class="mr-1">Installation Type:</strong> {{ project.installationtype }}
            </b-col>
            <b-col
              lg="3"
              md="6"
            >
              <strong class="mr-1">System Size:</strong> {{ project.solarsystemsize }}
            </b-col>
            <b-col
              lg="3"
              md="6"
            >
              <strong class="mr-1">Status:</strong> {{ project.status }}
            </b-col>
            <b-col
              lg="3"
              md="6"
            >
              <strong class="mr-1">Type of Project:</strong> {{ project.description }}
            </b-col>
            <b-col
              lg="3"
              md="6"
            >
              <strong class="mr-1">Location:</strong> {{ project.lga+',  '+project.state }}
            </b-col>
          </b-row>
        </b-card>
      </b-col>
    </b-row>
    <b-pagination
      v-if="loaded"
      v-model="currentPage"
      :per-page="perPage"
      :total-rows="rows"
      align="right"
      class="mt-1"
    />
    <b-modal
      id="send-message"
      ref="send-message"
      hide-footer
      hide-header
      centered
    >
      <validation-observer ref="sendMessageValidation">
        <b-form @submit.prevent>
          <h4 style="color: #4D6D8A; margin-top: 0.5em">
            Leave Message
          </h4>
          <b-row class="mt-1">
            <b-col
              cols="12"
              class="mb-1"
            >
              <validation-provider
                #default="{ errors }"
                name="subject"
                rules="required"
              >
                <b-form-input
                  id="product"
                  v-model="subject"
                  placeholder="Enter subject"
                />
                <small class="text-danger">{{ errors[0] }}</small>
              </validation-provider>
            </b-col>
            <b-col cols="12">
              <validation-provider
                #default="{ errors }"
                name="message"
                rules="required"
              >
                <b-form-textarea
                  id="textarea-default"
                  v-model="message"
                  placeholder="Write message"
                  rows="5"
                />
                <small class="text-danger">{{ errors[0] }}</small>
              </validation-provider>
            </b-col>
          </b-row>
          <div class="d-flex float-right mt-1">
            <b-button
              style="background-color: #C4C4C4 !important; border-color: #C4C4C4 !important; border-radius: 40px; margin-right: 1em"
              @click="hideModal"
            >
              Cancel
            </b-button>
            <b-button
              v-if="!isLoading"
              type="submit"
              style="background-color: #4D6D8A !important; border-color: #4D6D8A !important; border-radius: 40px;"
              @click="sendMessage"
            >
              Send
            </b-button>
            <b-button
              v-if="isLoading"
              disabled
              style="background-color: #4D6D8A !important; border-color: #4D6D8A !important; border-radius: 40px;"
            >
              Sending...
            </b-button>
          </div>
        </b-form>
      </validation-observer>
    </b-modal>
  </div>
</template>

<script>
import { ValidationProvider, ValidationObserver } from 'vee-validate'
import { required } from '@validations'

export default {
  components: {
    ValidationProvider,
    ValidationObserver,
  },
  data() {
    return {
      isLoading: false,
      projectID: null,
      subject: null,
      message: null,
      currentView: 'all',
      loaded: false,
      projects: [],
      currentPage: 1,
      perPage: 8,
      rows: 0,
      completed: 0,
      ongoing: 0,
      filter: null,
      required,
    }
  },
  computed: {
    projectList() {
      if (this.currentView === 'completed') {
        return this.projects.filter(project => project.status === 'Completed').slice((this.currentPage - 1) * this.perPage, this.currentPage * this.perPage)
      }
      if (this.currentView === 'ongoing') {
        return this.projects.filter(project => project.status === 'Ongoing').slice((this.currentPage - 1) * this.perPage, this.currentPage * this.perPage)
      }
      if (this.filter) {
        return this.projects.filter(project => project.projectname.toLowerCase().includes(this.filter) || project.client.clientname.toLowerCase().includes(this.filter) || project.state.toLowerCase().includes(this.filter) || project.projecttype.toLowerCase().includes(this.filter)).slice((this.currentPage - 1) * this.perPage, this.currentPage * this.perPage)
      }
      return this.projects.slice((this.currentPage - 1) * this.perPage, this.currentPage * this.perPage)
    },
  },
  mounted() {
    document.getElementById('custom-breadcrumb').innerHTML = 'Projects'
  },
  created() {
    this.getProjects()
  },
  methods: {
    getProjects() {
      this.$store.dispatch('project/getClientProjects').then(res => {
        const { data } = res.data
        this.projects = data
        this.projects.forEach(project => {
          if (project.status === 'Ongoing') {
            this.ongoing += 1
          } else if (project.status === 'Completed') {
            this.completed += 1
          }
        })
        this.rows = this.projects.length
        this.loaded = true
      })
    },
    viewProject(projectID) {
      // this.$router.push('/projects/view-project').then(() => {
        this.$router.push("/projects/view-mesh-project").then(() => {
        localStorage.setItem('projectID', projectID)
      })
    },
    viewAll() {
      this.currentView = 'all'
    },
    viewCompleted() {
      this.currentView = 'completed'
    },
    viewOngoing() {
      this.currentView = 'ongoing'
    },
    hideModal() {
      this.$refs['send-message'].hide()
    },
    showModal(id) {
      this.projectID = id
      this.$refs['send-message'].show()
    },
    sendMessage() {
      this.isLoading = true
      this.$refs.sendMessageValidation.validate().then(success => {
        if (success) {
          const form = {
            projectID: this.projectID,
            subject: this.subject,
            message: this.message,
          }
          this.$store.dispatch('project/sendMessage', { form }).then(res => {
            this.isLoading = false
            const { message } = res.data
            this.hideModal()
            this.toast('Send Message', 'CheckCircleIcon', message, 'success')
          }).catch(err => {
            this.isLoading = false
            this.toast('Send Message', 'SlashIcon', err.response.data.message, 'danger')
          })
        } else {
          this.isLoading = false
          this.toast('Send Message Attempt', 'AlertTriangleIcon', 'You must fill in all required fields', 'warning')
        }
      })
    },
  },
}
</script>

<style>
[dir] .input-group:not(.bootstrap-touchspin):focus-within .form-control, [dir] .input-group:not(.bootstrap-touchspin):focus-within .input-group-text {
  border-color: #4D6D8A;
}
.btn-group, .btn-group-vertical {
  display: block;
}
.dropdown-item:hover, .dropdown-item:focus {
  color: #4D6D8A;
  background-color: #f0f8ed;
}
[dir] .page-item.active .page-link {
  background-color: #4D6D8A;
  border-color: #4D6D8A;
}
.page-item .page-link:hover {
  color: #4D6D8A;
}
</style>
<style>
[dir] .form-control:focus {
  border-color: #4D6D8A;
}
</style>
