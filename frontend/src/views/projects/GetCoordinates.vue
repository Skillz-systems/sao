<template>
  <div>
    <b-card>
      <validation-observer ref="editProductValidation">
        <b-form @submit.prevent>
          <h4 style="color: #4D6D8A; margin-top: 0.5em">
            Edit-coordinate
          </h4>

          <b-col lg="12">
            <b-form-group>
              <label
                for="state"
                class="mt-lg-0 mt-1 mb-lg-1 font-weight-bolder"
              >
                Address
              </label>
              <vue-google-autocomplete
                id="map"
                ref="address"
                classname="form-control"
                placeholder="Please type your current address"
                country="ng"
                @placechanged="getAddressData"
              />
            </b-form-group>
          </b-col>

          <div class="d-flex float-right mt-1">
            <b-button
              style="background-color: #C4C4C4 !important; border-color: #C4C4C4 !important; border-radius: 40px; margin-right: 1em"
              @click="backToProject"
            >
              Cancel
            </b-button>
            <b-button
              v-if="!isLoading"
              type="submit"
              style="background-color: #4D6D8A !important; border-color: #4D6D8A !important; border-radius: 40px;"
              @click="saveChanges"
            >
              Save Changes
            </b-button>
            <b-button
              v-if="isLoading"
              disabled
              style="background-color: #4D6D8A !important; border-color: #4D6D8A !important; border-radius: 40px;"
            >
              Saving...
            </b-button>
          </div>
        </b-form>
      </validation-observer>
    </b-card>
  </div>
</template>

<script>
import { required } from "@validations";
import Vue from "vue";
import { ValidationProvider, ValidationObserver } from "vee-validate";

import { avatarText } from "@core/utils/filter";
// import VueNumerals from 'vue-numerals';
import axios from "axios";
import VueGoogleAutocomplete from "vue-google-autocomplete";
import GetCoordinates from "./GetCoordinates.vue";

export default {
  components: {
    ValidationProvider,
    ValidationObserver,
    VueGoogleAutocomplete,
  },
  data() {
    return {
      accessoryType: [],
      isLoading: false,
      currentPassword: null,
      newPassword: null,
      required,
      accessory: null,
      installationAddress: "",
      isLoading: false,
    };
  },
  mounted() {
    this.getSubItems(6);
    console.log(this.$router.history.current.params.id, "djsjjs");
  },
  created() {
    this.getSubItems(6);
  },
  methods: {
    backToProject() {
      this.$router.go(-1);
    },
    async saveChanges() {
      if (!this.installationAddress.lat) {
        // alert();
        this.toast(
          "GEOLOCATION",
          "CheckCircleIcon",
          "select location",
          "danger"
        );
        return;
      }

      this.isLoading = true;

      const formData = new FormData();
      formData.append("log", this.installationAddress.lat);
      formData.append("lat", this.installationAddress.long);

      const token = localStorage.getItem("token");
      axios.defaults.headers.Authorization = `Bearer ${token}`;

      try {
        const res = await axios.post(
          `${process.env.VUE_APP_BASE_URL}/updategeoaddress/${this.$router.history.current.params.id}`,
          formData
        );

        this.isLoading = false;

        // alert();
        this.toast(
          "GEOLOCATION",
          "CheckCircleIcon",
          res?.data?.message,
          "success"
        );
        this.$router.go(-1);
      } catch (error) {
        this.isLoading = false;
        this.toast(
          "GEOLOCATION",
          "CheckCircleIcon",
          error.response.data?.message,
          "danger"
        );
        // alert();
        console.log(error);
      }
    },
    getAddressData(addressData, placeResultData, id) {
      const addressLat = {
        lat: addressData.latitude,
        long: addressData.longitude,
        country: addressData.country,
        route: addressData.route,
        locality: addressData.locality,
      };
      this.installationAddress = addressLat;
    },
    updatePassword() {
      this.isLoading = true;
      this.$refs.updatePasswordValidation.validate().then((success) => {
        if (success) {
          const form = {
            currentPassword: this.currentPassword,
            newPassword: this.newPassword,
          };
          this.$store
            .dispatch("account/updatePassword", { form })
            .then((res) => {
              this.isLoading = false;
              const { message } = res.data;
              this.toast(
                "Update Password",
                "CheckCircleIcon",
                message,
                "success"
              );
            })
            .catch((err) => {
              this.isLoading = false;
              this.toast(
                "Update Password",
                "SlashIcon",
                err.response.data.message,
                "danger"
              );
            });
        } else {
          this.isLoading = false;
          this.toast(
            "Update Password Attempt",
            "AlertTriangleIcon",
            "You must fill in all required fields",
            "warning"
          );
        }
      });
    },
    getSubItems(item) {
      const itemID = item;
      this.$store
        .dispatch("inventory/getSubItemByID", { itemID })
        .then((res) => {
          const { data } = res.data;

          if (item === 2) {
            data.forEach((subItem) => {
              const systemSize = { value: subItem.id, text: subItem.name };
              this.systemSizes.push(systemSize);
            });
          } else if (item === 1) {
            data.forEach((subItem) => {
              const inverterType = { value: subItem.id, text: subItem.name };
              this.inverterTypes.push(inverterType);
            });
          } else if (item === 6) {
            data.forEach((subItem) => {
              const accessoryType = { value: subItem.id, text: subItem.name };
              this.accessoryType.push(accessoryType);
            });
          } else {
            data.forEach((subItem) => {
              const batteryType = { value: subItem.id, text: subItem.name };
              this.batteryTypes.push(batteryType);
            });
          }
        });
    },
  },
};
</script>

<style>
[dir] .form-control:focus {
  border-color: #4D6D8A;
}
</style>
