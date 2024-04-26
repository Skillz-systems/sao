<template>
  <div>
    <b-row>
      <b-col lg="12">
        <b-card no-body>
          Invoicing page
        </b-card>
      </b-col>
    </b-row>
  </div>
</template>

<script>
import { ValidationProvider, ValidationObserver } from "vee-validate";
import { required } from "@validations";
import vSelect from "vue-select";
import VueGoogleAutocomplete from "vue-google-autocomplete";
import VueNumericInput from "vue-numeric-input";
import axios from "axios";

export default {
  components: {
    ValidationProvider,
    ValidationObserver,
    vSelect,
    VueGoogleAutocomplete,
    VueNumericInput,
  },

  data() {
    return {
      misceMessage: null,
      showAddAnotherLocationBtn: false,
      showLocationInputFields: true,
      id: 0,
      installationLocationsObject: new Map(),
      installationAddress: "",
      installationLocations: [],
      message: "",
      selected: "false",
      locationCountAboveOne: false,
      isAddingLocation: false,
      isLoading: false,
      client: null,
      product: null,
      projectName: null,
      fullAddress: null,
      projectType: null,
      systemModel: null,
      systemSize: 0,
      installationType: null,
      selectionMode: null,
      category: null,
      subCategory: null,
      price: null,
      numBatteries: 1,
      numPanels: 1,
      status: null,
      location: null,
      state: null,
      lga: null,
      productDiscount: null,
      description: null,
      showSingleSelectionCont: false,
      showProductSalesCont: false,
      accessoryType: [],
      accessory: null,
      categories: [],
      subCategories: [],
      allMiscellaneous: [],
      createdMiscellaneous: [],
      miscellaneous: null,
      miscellanousAmount: null,
      clients: [{ value: null, title: "select a client" }],
      products: [{ value: null, title: "select a product", id: null }],
      projectTypes: [{ value: null, title: "select a project type" }],
      systemModels: [
        { value: null, text: "Please select a solar system model" },
        { value: 1, text: "BES-1" },
        { value: 2, text: "BES-3" },
        { value: 3, text: "BES-5" },
        { value: 4, text: "BES-8" },
      ],
      systemSizes: [
        { value: null, text: "Please select a solar system size" },
        { value: "1 - 2 kW", text: "1 - 2 kW" },
        { value: "3.0 kW", text: "3.0 kW" },
        { value: "4.0 kW", text: "4.0 kW" },
        { value: "5.0 kW", text: "5.0 kW" },
        { value: "5 - 10 kW", text: "5 - 10 kW" },
        { value: "10 + kW", text: "10 + kW" },
      ],
      selectionModes: [
        { value: null, title: "select product mode" },
        { value: "ProductSales", title: "Product Sales" },
        { value: "SingleStockSales", title: "Single Stock Sales" },
      ],
      installationTypes: [
        { value: null, title: "select an installation type" },
        { value: "Rooftop", title: "Rooftop" },
        { value: "Groundmount", title: "Groundmount" },
      ],
      statusOptions: [{ value: null, title: "select a project status" }],
      locations: [{ value: null, text: "Please select a location" }],
      states: [{ value: null, title: "Please select a state" }],
      address: [{ value: null, text: "Please select an address" }],
      lgas: [{ value: null, title: "Please select an lga" }],
      batteryType: null,
      batteryQuantity: 1,
      batteryTypes: [{ value: null, title: "Please select a battery type" }],
      inverterType: null,
      inverterQuantity: 1,
      inverterTypes: [{ value: null, title: "Please select an inverter type" }],
      solarPanelType: null,
      solarPanelQuantity: 1,
      solarPanelTypes: [
        { value: null, title: "Please select a solar panel type" },
      ],
      miscellaneousField: [
        { key: "miscellaneous_id", label: "Name" },
        { key: "amount", label: "amount" },
        { key: "title", label: "title" },
      ],

      required,
    };
  },

  watch: {
    price(newValue) {
      const result = newValue
        .replace(/\D/g, "")
        .replace(/\B(?=(\d{3})+(?!\d))/g, ",");
      // eslint-disable-next-line no-return-assign
      this.$nextTick(() => (this.price = result));
    },
  },
  mounted() {
    document.getElementById("custom-breadcrumb").innerHTML =
      'Projects / <span style="color: #C4C4C4">Add Project</span>';
    this.$refs.address.focus();
    this.getSubItemsMis(6);
    this.getMiscellaneous();
  },
  beforeDestroy() {
    localStorage.removeItem("products");
  },
  created() {
    this.getClients();
    this.getProjectTypes();
    this.getStatusOptions();
    this.getProducts();
    this.getSubItems();
    this.getStates();
    this.getItems();
    this.getSubItemsMis(6);
    this.getMiscellaneous();
  },
  methods: {
    deleteMiscellaneous(index) {
      this.createdMiscellaneous.splice(index, 1);
    },
    addMiscellaneous() {
      console.log(this.allMiscellaneous, "djdsjsjks");

      this.misceMessage = "";
      if (!this.miscellaneous || !this.miscellanousAmount) {
        this.misceMessage = "Select Options";
        return;
      }
      const miscellan = this.allMiscellaneous.filter(
        (e) => e.value == this.miscellaneous
      );
      this.createdMiscellaneous.push({
        miscellaneous_id: this.miscellaneous,
        amount: this.miscellanousAmount,
        title: miscellan[0].text,
      });
    },
    miscellaneousSelectChange(e, ki) {
      console.log(e, ki, "sjsdjkdjs");
    },
    getMiscellaneous() {
      const token = localStorage.getItem("token");
      axios.defaults.headers.Authorization = `Bearer ${token}`;

      const formData = new FormData();
      formData.append("title", this.miscellaneous);

      axios
        .get(`${process.env.VUE_APP_BASE_URL}/mis`)
        .then((response) => {
          const { data } = response.data;
          const output = [];
          data.map((subItem) => {
            const allMiscellaneous = { value: subItem.id, text: subItem.title };
            output.push(allMiscellaneous);
          });
          this.allMiscellaneous = output;
        })
        .catch((err) => {
          alert(err.response.data?.message);
          this.message = err.response.data?.message;
          console.log(err);
        });
    },
    removeLocation(id) {
      this.installationLocationsObject = this.installationLocationsObject.filter(
        (location) => location.id !== id
      );
    },
    getSubItemsMis(item) {
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
    getItems() {
      this.$store.dispatch("inventory/getItems").then((res) => {
        this.loaded = true;
        const { data } = res.data;
        console.log(data, "sksjkskkjsd");
        // this.categories = data
        data.forEach((item) => {
          const itemObj = { value: item.id, title: item.item };
          this.categories.push(itemObj);
        });
      });
    },
    getSubItemsSelected(item) {
      console.log(item, "dkkklslklks");
      this.subLoaded = false;
      this.currentItem = item;
      const itemID = item;
      this.$store
        .dispatch("inventory/getSubItemByID", { itemID })
        .then((res) => {
          this.subLoaded = true;
          const { data } = res.data;

          console.log(data, "resonsellle");
          // this.subItems = data;
          this.subCategories = [];
          data.forEach((item) => {
            const itemObj = { value: item.id, title: item.name };
            this.subCategories.push(itemObj);
          });
          this.totalRows = data.length;
        });
    },
    onLanguageChange(selection) {
      console.log("new lang", selection);
      if (selection === "ProductSales") {
        this.showProductSalesCont = true;
        this.showSingleSelectionCont = false;
      } else {
        this.showSingleSelectionCont = true;
        this.showProductSalesCont = false;
      }
    },
    onCategoryChange(item) {
      console.log(item, "sskjdsjsjdkjskkjsj");
      this.getSubItemsSelected(item);
    },
    onsubCategoryChange(item) {
      console.log(item, "itemmmmm");
    },

    /**
     * When the location found
     * @param {Object} addressData Data of the found location
     * @param {Object} placeResultData PlaceResult object
     * @param {String} id Input container ID
     */
    getAddressData(addressData, placeResultData, id) {
      console.log("changed");
      const addressLat = {
        lat: addressData.latitude,
        long: addressData.longitude,
        country: addressData.country,
        route: addressData.route,
        locality: addressData.locality,
      };
      this.installationAddress = addressLat;
      console.log(addressData, placeResultData, id);
    },

    addLocation() {
      this.isAddingLocation = true;
      this.message = "";

      if (
        this.state === "" ||
        this.lga === "" ||
        this.installationAddress === ""
      ) {
        this.message = "fill all address fields";
      } else {
        const currentLocation = {
          id: this.id + 1,
          log: this.installationAddress.long,
          lat: this.installationAddress.lat,
          installationAddress: this.installationAddress,
          states_id: this.state,
          lgas_id: this.lga,
          address: this.fullAddress,
        };
        this.id += 1;
        console.log(currentLocation, "currentLocation");
        this.installationLocations = this.installationLocations.concat(
          currentLocation
        );
        this.installationLocationsObject = this.installationLocations;
        this.installationAddress = "";
        this.state = "";
        this.lga = "";
      }

      this.isAddingLocation = false;
      this.showLocationInputFields = false;
      this.selected = "false";
      this.showAddAnotherLocationBtn = true;
    },

    showAddressField() {
      this.showLocationInputFields = true;
      this.showAddAnotherLocationBtn = false;
      this.selected = "true";
      this.fullAddress = "";
      this.isAddingLocation = false;
    },

    addProject() {
      this.isLoading = true;
      let clientAddress;

      console.log(this.installationLocationsObject, "dsjjs");
      if (Object.keys(this.installationLocationsObject).length !== 0) {
        console.log("yes");
        clientAddress = this.installationLocationsObject;
      } else {
        console.log("no");
        clientAddress = [
          {
            log: this.installationAddress.long,
            lat: this.installationAddress.lat,
            states_id: this.state,
            lgas_id: this.lga,
            address: this.fullAddress,
          },
        ];
      }

      this.$refs.addProjectValidation.validate().then((success) => {
        if (success) {
          const form = {
            address: JSON.stringify(clientAddress),
            projectName: this.projectName,
            projectType: this.projectType,
            systemSize: this.systemSize,
            solarPanelQuantity: this.solarPanelQuantity,
            batteryQuantity: this.batteryQuantity,
            description: this.description,
            installationType: this.installationType,

            status: this.status,
            client: this.client,
            lga: this.lga,
            product: this.product,
            price: parseFloat(this.price.replace(/,/g, "")),
            inverterQuantity: this.inverterQuantity,
            solarPanelType: this.solarPanelType,
            inverterType: this.inverterType,
            batteryType: this.batteryType,
            discount: this.productDiscount,
            miscellaneous: JSON.stringify(this.createdMiscellaneous),
          };
          this.$store
            .dispatch("project/addProject", { form })
            .then((res) => {
              this.isLoading = false;
              const { message } = res.data;
              this.$router.push("/projects").then(() => {
                this.toast(
                  "Add Project",
                  "CheckCircleIcon",
                  message,
                  "success"
                );
              });
            })
            .catch((err) => {
              console.log(err);
              this.isLoading = false;
              this.toast(
                "Add Project",
                "SlashIcon",
                err.response.data.message,
                "danger"
              );
            });
        } else {
          this.isLoading = false;
          this.toast(
            "Add Project Attempt",
            "AlertTriangleIcon",
            "You must fill in all required fields",
            "warning"
          );
        }
      });
    },
    getClients() {
      this.$store.dispatch("client/getClients").then((res) => {
        const { data } = res.data;
        data.forEach((client) => {
          const clientobj = { value: client.id, title: client.clientname };
          this.clients.push(clientobj);
        });
      });
    },
    getClient() {
      const clientID = this.client;
      this.$store.dispatch("client/getClientByID", { clientID }).then((res) => {
        const { data } = res.data;
        this.state = data.stateid;
        const stateID = this.state;
        this.lgas = [{ value: null, title: "Please select an lga" }];
        this.$store
          .dispatch("client/getLGAsByStateID", { stateID })
          .then((innerRes) => {
            const innerData = innerRes.data.data;
            innerData.forEach((lga) => {
              const lgaobj = { value: lga.lgaid, title: lga.lganame };
              this.lgas.push(lgaobj);
              this.lga = data.lgaid;
            });
          });
      });
    },
    getProjectTypes() {
      this.$store.dispatch("project/getProjectTypes").then((res) => {
        const { data } = res.data;
        data.forEach((type) => {
          const typeobj = { value: type, title: type };
          this.projectTypes.push(typeobj);
        });
      });
    },
    getStatusOptions() {
      this.$store.dispatch("project/getProjectStatus").then((res) => {
        const { data } = res.data;
        data.forEach((status) => {
          const statusobj = { value: status, title: status };
          this.statusOptions.push(statusobj);
        });
      });
    },
    getProducts() {
      this.$store.dispatch("product/getProducts").then((res) => {
        const { data } = res.data;
        console.log(data, "dsjsjjskjs");
        localStorage.setItem("products", JSON.stringify(data));
        data.forEach((product) => {
          const productobj = {
            value: product.id,
            title: product.productname,
            id: product.id,
          };
          this.products.push(productobj);
        });
        console.log(this.products, "sdjksdjks");
      });
    },
    getStates() {
      this.$store.dispatch("client/getStates").then((res) => {
        const { data } = res.data;
        data.forEach((state) => {
          const stateobj = { value: state.stateid, title: state.sname };
          this.states.push(stateobj);
        });
      });
    },
    getLGAsByStateID() {
      const stateID = this.state;
      this.lgas = [{ value: null, title: "Please select an lga" }];
      this.$store
        .dispatch("client/getLGAsByStateID", { stateID })
        .then((res) => {
          const { data } = res.data;
          data.forEach((lga) => {
            const lgaobj = { value: lga.lgaid, title: lga.lganame };
            this.lgas.push(lgaobj);
          });
        });
    },
    getBatteriesAndPanels() {
      const products = JSON.parse(localStorage.getItem("products"));
      products.every((product) => {
        if (product.id === this.product) {
          this.numBatteries = product.numberofbatteries;
          this.numPanels = product.numberofpanels;
          return false;
        }
        return true;
      });
    },
    getProduct() {
      const products = JSON.parse(localStorage.getItem("products"));
      console.log(products, "sdjksjsk");
      products.every((product) => {
        console.log(product, products, "dsksdskj");
        if (product.id === this.product) {
          this.price = parseFloat(product.price).toString();
          this.batteryType = product.batteries_type;
          this.batteryQuantity = parseFloat(product.numberofbatteries);
          this.inverterType = product.inverter_type;
          this.inverterQuantity = parseFloat(product.numberofinverters);
          this.solarPanelType = product.panel_type;
          this.solarPanelQuantity = parseFloat(product.numberofpanels);
          return false;
        }
        return true;
      });
    },
    getSubItems() {
      const itemIDs = [1, 2, 3];
      itemIDs.forEach((itemID) => {
        this.$store
          .dispatch("inventory/getSubItemByID", { itemID })
          .then((res) => {
            const { data } = res.data;
            switch (itemID) {
              case 1: // battery
                data.forEach((subItem) => {
                  const batteryType = {
                    value: subItem.id,
                    title: subItem.name,
                  };
                  this.batteryTypes.push(batteryType);
                });
                break;
              case 2: // solar panel
                data.forEach((subItem) => {
                  const solarPanelType = {
                    value: subItem.id,
                    title: subItem.name,
                  };
                  this.solarPanelTypes.push(solarPanelType);
                });
                break;
              default:
                // inverter
                data.forEach((subItem) => {
                  const inverterType = {
                    value: subItem.id,
                    title: subItem.name,
                  };
                  this.inverterTypes.push(inverterType);
                });
            }
          });
      });
    },
  },
};
</script>

<style>
[dir] .form-control:focus {
  border-color: #4D6D8A;
}
[dir] .custom-select:focus {
  border-color: #4D6D8A;
}
[dir] .b-form-spinbutton.form-control:focus,
[dir] .b-form-spinbutton.form-control.focus {
  border-color: #4D6D8A;
}
[dir] .input-group:not(.bootstrap-touchspin):focus-within .form-control,
[dir] .input-group:not(.bootstrap-touchspin):focus-within .input-group-text {
  border-color: #4D6D8A;
}
.number-input {
  height: 38px;
}
</style>

<style lang="scss">
.flex {
  display: flex;
  column-gap: 1rem;
}
@import "@core/scss/vue/libs/vue-select.scss";
</style>
