<template>
  <b-card>
    <div>
      <b-col lg="6">
        <label for="product" class="mb-lg-1 mb-2 font-weight-bolder">
          Name
        </label>
        <b-form-input
          id="product"
          style="margin-bottom: 2rem"
          v-model="miscellaneous"
        />
      </b-col>

      <b-col lg="3">
        <b-button
          class="mt-lg-0 mt-1 btn-block"
          style="background-color: #4D6D8A !important; border-color: #4D6D8A !important; margin-top: 2rem"
          @click="addMiscellaneous"
        >
          ADD
        </b-button>
      </b-col>
      <!-- </b-row> -->
    </div>

    <div class="misceBoarder">
      <div
        v-for="(miscellaneous, index) of allMiscellaneous"
        v-if="allMiscellaneous.lenght != 0"
      >
        <div class="flex">
          <div>{{ miscellaneous.title }}</div>
          <div>
            <feather-icon
              v-b-tooltip.hover.top="'Delete'"
              icon="Trash2Icon"
              size="16"
              class="cursor-pointer mx-1"
              @click="deleteMiscellaneous(miscellaneous.id)"
            />
          </div>
        </div>
      </div>
    </div>
  </b-card>
</template>

<script>
import { ValidationObserver, ValidationProvider } from "vee-validate";
import { required } from "@validations";
import axios from "axios";

export default {
  components: {
    ValidationProvider,
    ValidationObserver,
  },
  data() {
    return {
      miscellaneous: null,
      allMiscellaneous: [{ title: "Default" }],
      accessoryType: [],
      isLoading: false,
      currentPassword: null,
      newPassword: null,
      required,
      accessory: null,
    };
  },
  mounted() {
    this.getSubItems(6);
    this.getMiscellaneous();
  },
  created() {
    this.getSubItems(6);
    this.getMiscellaneous();
  },

  methods: {
    deleteMiscellaneous(id) {
      const token = localStorage.getItem("token");
      axios.defaults.headers.Authorization = `Bearer ${token}`;

      const formData = new FormData();
      formData.append("title", this.miscellaneous);

      axios
        .delete(`${process.env.VUE_APP_BASE_URL}/mis/${id}`)
        .then((response) => {
          this.getMiscellaneous();
          // alert();
          this.toast(
            "Miscellaneous",
            "CheckCircleIcon",
            response.data?.message,
            "success"
          );
        })
        .catch((err) => {
          alert();
          this.toast(
            "Product Serial Numbers",
            "CheckCircleIcon",
            err.response.data?.message,
            "danger"
          );
          this.message = err.response.data?.message;
          console.log(err);
        });
    },

    getMiscellaneous() {
      const token = localStorage.getItem("token");
      axios.defaults.headers.Authorization = `Bearer ${token}`;

      const formData = new FormData();
      formData.append("title", this.miscellaneous);

      axios
        .get(`${process.env.VUE_APP_BASE_URL}/mis`)
        .then((response) => {
          this.allMiscellaneous = response.data.data;
        })
        .catch((err) => {
          this.toast(
            "Miscellaneous",
            "CheckCircleIcon",
            err.response.data?.message,
            "danger"
          );
          // alert();
          this.message = err.response.data?.message;
          console.log(err);
        });
    },
    addMiscellaneous() {
      if (!this.miscellaneous) {
        this.toast("Miscellaneous", "CheckCircleIcon", "Input value", "danger");

        return;
      }

      const token = localStorage.getItem("token");
      axios.defaults.headers.Authorization = `Bearer ${token}`;

      const formData = new FormData();
      formData.append("title", this.miscellaneous);
      // formData.append('_method', "put")

      axios
        .post(`${process.env.VUE_APP_BASE_URL}/mis`, formData)
        .then((response) => {
          this.getMiscellaneous();
          this.toast(
            "Miscellaneous",
            "CheckCircleIcon",
            response.data?.message,
            "success"
          );
          // alert();
        })
        .catch((err) => {
          this.toast(
            "Miscellaneous",
            "CheckCircleIcon",
            err.response.data?.message,
            "danger"
          );
          // alert();
          this.message = err.response.data?.message;
          console.log(err);
        });
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
          console.log(res, "jsjksk");
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
.flex {
  display: flex;
  justify-content: space-between;
}
.misceBoarder {
  border: 3px solid #4D6D8A;
  margin-top: 3rem;
  line-height: 2.5rem;
}
</style>
