<template>
  <div>
    <b-col
      v-if="loading"
    >
      <b-spinner
        style="width: 3rem; height: 3rem;"
        variant="primary"
        label="Loading..." type="grow"
        class="text-center"
      />
    </b-col>
    <b-card v-if="!loading">
      <h4 class="text-center mt-2">
        Profile details
      </h4>
      <div class="text-center mt-3">
        <b-link @click="$refs.refInputEl2.click()">
          <b-avatar
            ref="profilePicture"
            :src="profilePicture"
            :text="avatarText(client.clientname)"
            variant="light-dark"
            size="130px"
          />
        </b-link>
        <input
          ref="refInputEl2"
          type="file"
          class="d-none"
          accept="image/jpeg, image/png, image/gif, image/jpg"
          @change="updatePicture"
        >
        <b-card-text
          v-if="updating"
          class="mt-1"
        >
          <b-spinner
            small
            label="Small Spinner"
            type="grow"
            class="mb-1 mr-1"
          />
          Please wait while we upload your profile picture...
        </b-card-text>
      </div>
      <b-row class="mt-3">
        <b-col />
        <b-col lg="8">
          <b-row>
            <b-col lg="6">
              <p>
                <strong class="mr-1">Client Name:</strong> {{ client.clientname }}
              </p>
              <p>
                <strong class="mr-1">Email:</strong> {{ client.email }}
              </p>
              <p>
                <strong class="mr-1">Client Type:</strong> {{ client.clienttype }}
              </p>
              <p>
                <strong class="mr-1">Address:</strong> {{ client.address }}
              </p>
            </b-col>
            <b-col lg="6">
              <p>
                <strong class="mr-1">Phone:</strong> {{ client.phone }}
              </p>
              <p>
                <strong class="mr-1">Location:</strong> {{ client.state }}
              </p>
              <p>
                <strong class="mr-1">House Size:</strong> {{ client.housesize }}
              </p>
            </b-col>
          </b-row>
        </b-col>
        <b-col />
      </b-row>
    </b-card>
  </div>
</template>

<script>
import { avatarText } from '@core/utils/filter'

export default {
  data() {
    return {
      avatarText,
      loading: true,
      updating: false,
      client: null,
      profilePicture: this.$store.getters['auth/getProfilePicture'],
    }
  },
  created() {
    this.getClientInfo()
  },
  methods: {
    getClientInfo() {
      this.$store.dispatch('account/getClientAccount').then(res => {
        const { clientObject } = res.data.data
        this.client = clientObject
        this.loading = false
      })
    },
    updatePicture(e) {
      this.updating = true
      const files = e.target.files || e.dataTransfer.files
      if (!files.length) {
        return
      }
      this.createImage(files[0])
      const form = {
        profilePicture: files[0],
      }
      this.$store.dispatch('account/updateProfilePicture', { form }).then(res => {
        this.updating = false
        const { success, url } = res.data
        this.$store.commit('auth/SET_NEW_PROFILE_PIC', { profilePicture: url })
        const userData = JSON.parse(localStorage.getItem('user'))
        userData.profilepictureurl = url
        localStorage.setItem('user', JSON.stringify(userData))
        this.toast('Update Profile Picture', 'CheckCircleIcon', success, 'success')
        // eslint-disable-next-line no-restricted-globals
        location.reload()
      })
    },
    createImage(file) {
      const reader = new FileReader()
      reader.onload = e => {
        this.profilePicture = e.target.result
      }
      reader.readAsDataURL(file)
    },
    removeImage() {
      this.profilePicture = null
    },
  },
}
</script>
