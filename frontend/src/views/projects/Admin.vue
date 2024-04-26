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
                @input="search"
              />
            </b-input-group>
          </b-col>
          <b-col
            lg="4"
            class="mt-lg-0 mt-2"
          >
            <b-button
              class="float-right btn-block"
              style="background-color: #4D6D8A !important; border-color: #4D6D8A !important;"
              @click="$router.push('/projects/add-project')"
            >
              ADD NEW PROJECT
            </b-button>
          </b-col>
        </b-row>
      </b-col>
    </b-row>
    <b-spinner
      v-if="!loaded"
      style="width: 3rem; height: 3rem;"
      variant="primary"
      label="Loading..."
      type="grow"
      class="text-center mt-3"
    />
    <b-row
      v-if="loaded"
      class="mt-3"
    >
      <b-col
        v-for="project in projectList"
        :key="project.id"
        lg="3"
      >
        <b-card
          no-body
          style="border-radius: 10px; height: 300px;"
        >
          <b-card-header>
            <div>
              <b-card-title>{{ project.projectname }}</b-card-title>
              <b-card-text
                v-if="project.client != null"
                class="font-small-3"
              >
                {{ project.client.clientname }}
              </b-card-text>
            </div>
            <b-avatar
              v-if="project.client != null"
              ref="profilePicture"
              :text="avatarText(project.client.clientname)"
              variant="light-dark"
              size="30px"
            />
          </b-card-header>
          <div style="padding: 0 21px 10px 21px; margin-top: -1.2em">
            <!-- <b-card-text>
              <b-img
                :src="require('@/assets/images/icons/projects-location.svg')"
                style="margin-right: 0.5em;"
              />
              <small class="text-muted">{{ project.state }}</small>
            </b-card-text>
            <b-card-text style="margin-top: -1em">
              <b-img
                :src="require('@/assets/images/icons/projects-unit.svg')"
                style="margin-right: 0.5em;"
              />
              <small class="text-muted">{{ project.projecttype }}</small>
            </b-card-text> -->
            <b-card-text>
              <h6>Selection Mode</h6>
              <p
                v-if="project.description !== undefined"
                class="font-small-3"
              >
                {{ project.project_selection_mode }}
              </p>
            </b-card-text>
            <b-card-text>
              <h6>Type of project</h6>
              <p
                v-if="project.description !== undefined"
                class="font-small-3"
              >
                {{ project.description }}
              </p>
            </b-card-text>

            <b-card-text>
              <div><h6>Project ID:</h6></div>
              <div>{{ project.projectnumber }}</div>

              <!-- <p v-if="project.description !== undefined" class="font-small-3">
                {{ project.description }}
              </p> -->
            </b-card-text>
            <div class="d-flex justify-content-between mt-2">
              <b-card-text
                v-if="
                  project.status
                "
                style="color: #C4C4C4;"
              >
                <span
                  v-if="
                    project.status.toLowerCase() === 'ongoing' ||
                      project.status.toLowerCase() === 'none'
                  "
                > {{ project.status }}</span>

              </b-card-text>
              <b-card-text
                v-if="project.status"
                style="color: #4D6D8A;"
              >
                <span v-if="project.status.toLowerCase() === 'completed'" />
                {{ project.status }}
              </b-card-text>
              <b-dropdown
                variant="link"
                no-caret
                class="chart-dropdown"
                toggle-class="p-0"
                dropleft
              >
                <template #button-content>
                  <feather-icon
                    icon="MoreVerticalIcon"
                    size="18"
                    class="text-body cursor-pointer"
                  />
                </template>
                <b-dropdown-item @click="viewProject(project)">
                  <feather-icon
                    icon="EyeIcon"
                    size="15"
                    class="mr-1"
                  />
                  <span v-if="project.project_selection_mode == 'product'">View</span>
                  <span v-if="project.project_selection_mode == 'meshgrid' || project.project_selection_mode == 'mesh grid'">View Mesh</span>
                </b-dropdown-item>
                <!-- <b-dropdown-item @click="editProject(project.id)">
                  <feather-icon icon="EditIcon" size="15" class="mr-1" />
                  Edit
                </b-dropdown-item> -->
                <b-dropdown-item @click="deleteProject(project)">
                  <feather-icon
                    icon="TrashIcon"
                    size="15"
                    class="mr-1"
                  />
                  Delete
                </b-dropdown-item>
              </b-dropdown>
            </div>
          </div>
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
  </div>
</template>

<script>
import { avatarText } from '@core/utils/filter'

export default {
  components: {},
  data() {
    return {
      avatarText,
      currentView: 'all',
      loaded: false,
      projects: [],
      currentPage: 1,
      perPage: 8,
      rows: 0,
      completed: 0,
      ongoing: 0,
      filter: null,
    }
  },
  computed: {
    projectList() {
      if (this.currentView === 'completed') {
        return this.projects
          .filter(project => project.status === 'Completed')
          .slice(
            (this.currentPage - 1) * this.perPage,
            this.currentPage * this.perPage,
          )
      }
      if (this.currentView === 'ongoing') {
        return this.projects
          .filter(project => project.status === 'Ongoing')
          .slice(
            (this.currentPage - 1) * this.perPage,
            this.currentPage * this.perPage,
          )
      }
      if (this.filter) {
        return this.projects
          .filter(
            project => project.projectname.toLowerCase().includes(this.filter)
              // project?.client?.clientname?.toLowerCase().includes(this.filter) ||
              || project.state.toLowerCase().includes(this.filter)
              || project.projecttype.toLowerCase().includes(this.filter),
          )
          .slice(
            (this.currentPage - 1) * this.perPage,
            this.currentPage * this.perPage,
          )
      }
      return this.projects.slice(
        (this.currentPage - 1) * this.perPage,
        this.currentPage * this.perPage,
      )
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
      this.$store.dispatch('project/getProjects').then(res => {
        const { data } = res.data

        console.log(data, 'dskjdkjs')
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
    viewProject(project) {
      // this.$router.push("/projects/view-project").then(() => {
      if (project.project_selection_mode == 'product') {
        this.$router.push('/projects/view-project').then(() => {
          localStorage.setItem('projectID', project.id)
        })
      } else {
        this.$router.push('/projects/view-mesh-project').then(() => {
          console.log(project.id, 'bbdbdbd')
          localStorage.setItem('projectID', project.id)
        })
      }
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
    search() {},
    editProject(projectID) {
      console.log(projectID, 'msjsjsjjkskj')
      this.$router.push('/projects/edit-project').then(() => {
        localStorage.setItem('projectID', projectID)
      })
    },
    deleteProject(project) {
      this.$swal({
        title: `Delete ${project.projectname}?`,
        text: 'Please note, deleting a project is irreversible!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        customClass: {
          confirmButton: 'btn btn-success',
          cancelButton: 'btn btn-outline-danger ml-1',
        },
        buttonsStyling: false,
      }).then(result => {
        this.loaded = false
        if (result.value) {
          const form = {
            projectID: project.id,
          }
          this.$store
            .dispatch('project/deleteProject', { form })
            .then(res => {
              this.$store.dispatch('project/getProjects').then(res2 => {
                this.isLoading = false
                const { message } = res.data
                const { data } = res2.data
                this.projects = data
                this.ongoing = 0
                this.completed = 0
                this.projects.forEach(proj => {
                  if (proj.status === 'Ongoing') {
                    this.ongoing += 1
                  } else if (proj.status === 'Completed') {
                    this.completed += 1
                  }
                })
                this.rows = this.projects.length
                this.loaded = true
                this.$swal({
                  title: `Deleted ${project.projectname}`,
                  text: message,
                  icon: 'success',
                })
              })
            })
            .catch(err => {
              this.isLoading = false
              this.$swal({
                title: `Delete ${project.projectname}?`,
                text: err.response.data.message,
                icon: 'danger',
              })
            })
        }
      })
    },
  },
}
</script>

<style>
[dir] .input-group:not(.bootstrap-touchspin):focus-within .form-control,
[dir] .input-group:not(.bootstrap-touchspin):focus-within .input-group-text {
  border-color: #4D6D8A;
}
.btn-group,
.btn-group-vertical {
  display: block;
}
.dropdown-item:hover,
.dropdown-item:focus {
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
