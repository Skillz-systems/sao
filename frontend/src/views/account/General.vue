<template>
  <b-card>
    <h4 class="text-center">
      Profile details
    </h4>
    <div class="text-center mt-2">
      <b-avatar
        size="80px"
        :src="require('@/assets/images/avatars/avatar.png')"
      />
    </div>
    <b-row class="mt-1 px-lg-3">
      <b-col lg="6">
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
              v-model="name"
            />
          </b-input-group>
        </b-form-group>
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
              :options="roles"
            />
          </b-input-group>
        </b-form-group>
      </b-col>
      <b-col lg="6">
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
              v-model="email"
            />
          </b-input-group>
        </b-form-group>
      </b-col>
      <b-col lg="6">
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
              v-model="phone"
            />
          </b-input-group>
        </b-form-group>
      </b-col>
      <b-col cols="12">
        <div class="d-flex float-right mt-2">
          <b-button
            style="background-color: #C4C4C4 !important; border-color: #C4C4C4 !important; border-radius: 40px; margin-right: 1.5em"
          >
            Cancel
          </b-button>
          <b-button
            style="background-color: #4D6D8A !important; border-color: #4D6D8A !important; border-radius: 40px;"
          >
            Save
          </b-button>
        </div>
      </b-col>
    </b-row>
  </b-card>
</template>

<script>
export default {
  data() {
    return {
      name: 'John Doe',
      email: 'johndoe@email.com',
      phone: '083 567 3456',
      role: 1,
      roles: [
        { value: 1, text: 'Admin' },
        { value: 2, text: 'Write' },
        { value: 3, text: 'Read' },
      ],
    }
  },
}
</script>

<style>
[dir] .input-group:not(.bootstrap-touchspin):focus-within .form-control, [dir] .input-group:not(.bootstrap-touchspin):focus-within .input-group-text {
  border-color: #4D6D8A;
}
[dir] .custom-select:focus {
  border-color: #4D6D8A;
}
</style>
