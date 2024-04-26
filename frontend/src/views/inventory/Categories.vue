<template>
  <div>
    <b-spinner
      v-if="!loaded"
      style="width: 3rem; height: 3rem;"
      variant="primary"
      label="Loading..."
      type="grow"
      class="mt-lg-1"
    />
    <b-row v-else>
      <b-col lg="3">
        <b-card no-body>
          <b-list-group flush>
            <b-list-group-item
              v-for="item in items"
              :key="item.id"
              href="javascript:void(0)"
              @click="getSubItems(item)"
            >
              {{ item.item }}
            </b-list-group-item>
          </b-list-group>
        </b-card>
      </b-col>
      <b-col lg="9">
        <b-row class="mb-2">
          <b-col
            cols="12"
            md="6"
          >
            <b-row>
              <b-col lg="8">
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
              <b-col
                lg="4"
                class="d-flex mt-lg-0 mt-1 text-center"
              >
                <label style="padding: 10px 0">Show</label>
                <b-form-select
                  v-model="perPage"
                  :options="perPageOptions"
                  class="per-page-selector d-inline-block ml-50 mr-50"
                />
                <label style="padding: 10px 0">entries</label>
              </b-col>
            </b-row>
          </b-col>
          <b-col
            cols="12"
            md="6"
          >
            <b-row>
              <b-col lg="7" />
              <b-col lg="5">
                <b-button
                  v-b-modal.add-sub-item
                  class="mt-lg-0 mt-1 btn-block"
                  style="background-color: #4D6D8A !important; border-color: #4D6D8A !important;"
                >
                  ADD SUB-CATEGORY
                </b-button>
              </b-col>
            </b-row>
          </b-col>
        </b-row>
        <b-spinner
          v-if="!subLoaded"
          style="width: 3rem; height: 3rem;"
          variant="dark"
          label="Loading..."
          type="grow"
          class="mt-lg-1"
        />
        <b-row v-else>
          <b-col cols="12">
            <div
              v-if="currentItem"
              class="alert alert-success p-1"
            >
              {{ currentItem.item }} sub-categories
            </div>
            <div
              v-else
              class="alert alert-warning p-1"
            >
              Please select a category to view sub-categories
            </div>
          </b-col>
          <b-col cols="12">
            <b-card
              no-body
              class="card-company-table"
            >
              <b-table
                ref="sub-category-table"
                :items="subItems"
                responsive
                :fields="subCategoryFields"
                :per-page="perPage"
                :current-page="currentPage"
                :filter="filter"
                show-empty
                empty-text="No sub-categories found"
                class="mb-0"
              >
                <template #cell(created_at)="data">
                  {{ new Date(data.item.created_at).toDateString() }}
                </template>
                <template #cell(action)="data">
                  <div class="text-nowrap text-center">
                    <feather-icon
                      :id="`view-${data.item.id}`"
                      v-b-tooltip.hover.top="'Edit'"
                      icon="Edit3Icon"
                      size="16"
                      class="cursor-pointer"
                      @click="edit(data.item)"
                    />
                    <feather-icon
                      :id="`view-${data.item.id}`"
                      v-b-tooltip.hover.top="'Delete'"
                      icon="Trash2Icon"
                      size="16"
                      class="cursor-pointer mx-1"
                      @click="deleteProduct(data.item)"
                    />
                  </div>
                </template>
              </b-table>
            </b-card>
            <div class="mx-2 mb-2 mt-1">
              <b-row>
                <b-col
                  cols="12"
                  sm="6"
                  class="d-flex align-items-center justify-content-center justify-content-sm-start"
                >
                  <span class="text-muted">There are {{ totalRows }} sub-categories</span>
                </b-col>
                <b-col
                  cols="12"
                  sm="6"
                  class="d-flex align-items-center justify-content-center justify-content-sm-end"
                >
                  <b-pagination
                    v-model="currentPage"
                    :total-rows="totalRows"
                    :per-page="perPage"
                    class="mb-0 mt-1 mt-sm-0"
                  />
                </b-col>
              </b-row>
            </div>
          </b-col>
        </b-row>
      </b-col>
    </b-row>
    <b-modal
      id="add-sub-item"
      ref="add-sub-item"
      hide-footer
      hide-header
      size="md"
      centered
    >
      <validation-observer ref="addSubItemValidation">
        <b-form @submit.prevent>
          <h4 style="color: #4D6D8A; margin-top: 0.5em">
            Add Sub-Category
          </h4>
          <b-row class="mt-1">
            <!-- <b-col cols="12">
              <b-form-group>
                <validation-provider
                  #default="{ errors }"
                  name="reference number"
                  rules="required"
                >
                  <label
                    for="ref-no"
                    class="mb-lg-1 font-weight-bolder"
                  >
                    Reference Number
                  </label>
                  <b-form-input
                    id="ref-no"
                    v-model="refNo"
                  />
                  <small class="text-danger">{{ errors[0] }}</small>
                </validation-provider>
              </b-form-group>
            </b-col> -->
            <b-col cols="12">
              <validation-provider
                #default="{ errors }"
                name="category"
                rules="required"
              >
                <b-form-group>
                  <label
                    for="item"
                    class="mb-lg-1 font-weight-bolder"
                  >
                    Category
                  </label>
                  <v-select
                    id="solar-panel-type"
                    v-model="item"
                    :reduce="(option) => option.value"
                    :options="itemsSelect"
                    label="title"
                  />
                  <small class="text-danger">{{ errors[0] }}</small>
                </b-form-group>
              </validation-provider>
            </b-col>
            <b-col cols="12">
              <b-form-group>
                <validation-provider
                  #default="{ errors }"
                  name="sub-category"
                  rules="required"
                >
                  <label
                    for="sub-item"
                    class="mb-lg-1 font-weight-bolder"
                  >
                    Sub-Category Name
                  </label>
                  <b-form-input
                    id="sub-item"
                    v-model="subItem"
                  />
                  <small class="text-danger">{{ errors[0] }}</small>
                </validation-provider>
              </b-form-group>
            </b-col>

            <b-col
              v-if="this.item == 1 || this.item == 4"
              lg="12"
            >
              <b-form-group
                v-slot="{ ariaDescribedby }"
                style="display: flex;"
                label="Has Internal Battery?"
              >

                <b-form-radio
                  v-model="selected"
                  :aria-describedby="ariaDescribedby"
                  name="some-radios"
                  value="yes"
                >
                  YES
                </b-form-radio>
                <b-form-radio
                  v-model="selected"
                  :aria-describedby="ariaDescribedby"
                  name="some-radios"
                  value="no"
                >
                  NO
                </b-form-radio>
              </b-form-group>
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
              v-if="!adding"
              type="submit"
              style="background-color: #4D6D8A !important; border-color: #4D6D8A !important; border-radius: 40px;"
              @click="addSubItem"
            >
              Add Sub-Category
            </b-button>
            <b-button
              v-if="adding"
              disabled
              style="background-color: #4D6D8A !important; border-color: #4D6D8A !important; border-radius: 40px;"
            >
              Adding...
            </b-button>
          </div>
        </b-form>
      </validation-observer>
    </b-modal>
    <b-modal
      id="edit-sub-item"
      ref="edit-sub-item"
      hide-footer
      hide-header
      size="md"
      centered
    >
      <validation-observer ref="editSubItemValidation">
        <b-form @submit.prevent>
          <h4 style="color: #4D6D8A; margin-top: 0.5em">
            Edit Sub-Category
          </h4>
          <b-row class="mt-1">
            <!-- <b-col cols="12">
              <b-form-group>
                <validation-provider
                  #default="{ errors }"
                  name="reference number"
                  rules="required"
                >
                  <label
                    for="ref-no"
                    class="mb-lg-1 font-weight-bolder"
                  >
                    Reference Number
                  </label>
                  <b-form-input
                    id="ref-no"
                    v-model="refNoEdit"
                  />
                  <small class="text-danger">{{ errors[0] }}</small>
                </validation-provider>
              </b-form-group>
            </b-col> -->
            <b-col cols="12">
              <validation-provider
                #default="{ errors }"
                name="category"
                rules="required"
              >
                <b-form-group>
                  <label
                    for="item"
                    class="mb-lg-1 font-weight-bolder"
                  >
                    Category
                  </label>
                  <v-select
                    id="solar-panel-type"
                    v-model="itemEdit"
                    :reduce="(option) => option.value"
                    :options="itemsSelect"
                    label="title"
                  />
                  <small class="text-danger">{{ errors[0] }}</small>
                </b-form-group>
              </validation-provider>
            </b-col>
            <b-col cols="12">
              <b-form-group>
                <validation-provider
                  #default="{ errors }"
                  name="sub-category"
                  rules="required"
                >
                  <label
                    for="sub-item"
                    class="mb-lg-1 font-weight-bolder"
                  >
                    Sub-Category
                  </label>
                  <b-form-input
                    id="sub-item"
                    v-model="subItemEdit"
                  />
                  <small class="text-danger">{{ errors[0] }}</small>
                </validation-provider>
              </b-form-group>
            </b-col>
            <!-- {{ editItemType }} -->

            <b-col
              v-if="this.editItemType == 1 || this.editItemType == 4"
              lg="12"
            >
              <b-form-group
                v-slot="{ ariaDescribedby }"
                style="display: flex;"
                label="Has Internal Battery?"
              >

                <b-form-radio
                  v-model="editSelected"
                  :aria-describedby="ariaDescribedby"
                  name="some-radios"
                  value="yes"
                >
                  YES
                </b-form-radio>
                <b-form-radio
                  v-model="editSelected"
                  :aria-describedby="ariaDescribedby"
                  name="some-radios"
                  value="no"
                >
                  NO
                </b-form-radio>
              </b-form-group>
            </b-col>
          </b-row>
          <div class="d-flex float-right mt-1">
            <b-button
              style="background-color: #C4C4C4 !important; border-color: #C4C4C4 !important; border-radius: 40px; margin-right: 1em"
              @click="hideEditModal"
            >
              Cancel
            </b-button>
            <b-button
              v-if="!adding"
              type="submit"
              style="background-color: #4D6D8A !important; border-color: #4D6D8A !important; border-radius: 40px;"
              @click="editSubItem"
            >
              Save Changes
            </b-button>
            <b-button
              v-if="adding"
              disabled
              style="background-color: #4D6D8A !important; border-color: #4D6D8A !important; border-radius: 40px;"
            >
              Saving...
            </b-button>
          </div>
        </b-form>
      </validation-observer>
    </b-modal>
  </div>
</template>

<script>
import { ValidationObserver, ValidationProvider } from 'vee-validate'
import { required } from '@validations'
import vSelect from 'vue-select'

export default {
  components: {
    ValidationProvider,
    ValidationObserver,
    vSelect,
  },
  data() {
    return {
      editItemType: null,
      selected: 'no',
      editSelected: 'no',
      loaded: false,
      subLoaded: true,
      adding: false,
      item: null,
      itemEdit: null,
      items: [],
      itemsSelect: [
        { value: null, title: 'Please select a category' },
      ],
      refNo: null,
      refNoEdit: null,
      subItem: null,
      subItemID: null,
      subItemEdit: null,
      currentItem: null,
      subItems: [],
      subCategoryFields: [
        // { key: 'referencenumber', label: 'Reference No' },
        { key: 'name', label: 'Sub-Category' },
        // { key: 'quantity', label: 'Quantity' },
        { key: 'created_at', label: 'Date Entered' },
        { key: 'action', label: 'Actions', thStyle: { width: '10%', textAlign: 'center' } },
      ],
      perPage: 5,
      perPageOptions: [5, 10, 20, 100],
      filter: null,
      totalRows: 0,
      currentPage: 1,
      required,
    }
  },
  mounted() {
    document.getElementById('custom-breadcrumb').innerHTML = 'Inventory / <span style="color: #C4C4C4">Categories</span>'
  },
  created() {
    this.getItems()
  },
  methods: {
    onFiltered(filteredItems) {
      this.totalRows = filteredItems.length
      this.currentPage = 1
    },
    hideModal() {
      this.$refs['add-sub-item'].hide()
    },
    hideEditModal() {
      this.$refs['edit-sub-item'].hide()
    },
    getItems() {
      this.$store.dispatch('inventory/getItems').then(res => {
        this.loaded = true
        const { data } = res.data
        console.log(data, 'sksjkskkjsd')
        this.items = data
        data.forEach(item => {
          const itemObj = { value: item.id, title: item.item }
          this.itemsSelect.push(itemObj)
        })
      })
    },
    getSubItems(item) {
      console.log(item, 'dkkklslklks')
      this.subLoaded = false
      this.currentItem = item
      const itemID = item.id
      this.$store.dispatch('inventory/getSubItemByID', { itemID }).then(res => {
        this.subLoaded = true
        const { data } = res.data

        console.log(data, 'resonsee')
        this.subItems = data
        this.totalRows = data.length
      })
    },
    addSubItem() {
      this.adding = true
      this.$refs.addSubItemValidation.validate().then(valid => {
        if (valid) {
          const form = {
            subItem: this.subItem,
            item: this.item,
            refNo: this.refNo,
            hasb3: this.selected,
          }
          this.$store.dispatch('inventory/addSubItem', { form }).then(res => {
            this.adding = false
            const { message } = res.data
            this.hideModal()
            this.toast('Add Sub-Category', 'CheckCircleIcon', message, 'success')
            if (this.currentItem.id === this.item) {
              this.subLoaded = false
              const itemID = this.item
              this.$store.dispatch('inventory/getSubItemByID', { itemID }).then(innerRes => {
                this.subLoaded = true
                const { data } = innerRes.data
                this.subItems = data
                this.totalRows = data.length
              })
            }
          }).catch(err => {
            this.isLoading = false
            this.toast('Add Sub-Category', 'SlashIcon', err.response.data.message, 'danger')
          })
        } else {
          this.adding = false
          this.toast('Add Sub-Category Attempt', 'AlertTriangleIcon', 'You must fill in all required fields', 'warning')
        }
      })
    },
    edit(subItem) {
      console.log(subItem, 'jjjjjj')
      this.editItemType = subItem.itemid
      this.editSelected = subItem.hasb3
      this.subItemID = subItem.id
      this.subItemEdit = subItem.name
      this.refNoEdit = subItem.referencenumber
      this.itemEdit = parseFloat(subItem.itemid)
      this.$refs['edit-sub-item'].show()
    },
    editSubItem() {
      this.adding = true
      this.$refs.editSubItemValidation.validate().then(valid => {
        if (valid) {
          const form = {
            subItemID: this.subItemID,
            subItem: this.subItemEdit,
            item: this.itemEdit,
            refNo: this.refNoEdit,
            hasb3: this.editSelected,
          }
          this.$store.dispatch('inventory/editSubItem', { form }).then(res => {
            this.adding = false
            const { message } = res.data
            this.hideEditModal()
            this.toast('Edit Sub-Category', 'CheckCircleIcon', message, 'success')
            this.subLoaded = false
            const itemID = this.itemEdit
            this.$store.dispatch('inventory/getSubItemByID', { itemID }).then(innerRes => {
              this.subLoaded = true
              const { data } = innerRes.data
              this.subItems = data
              this.totalRows = data.length
            })
          })
        }
      })
    },
    deleteProduct(product) {
      this.$swal({
        title: `Delete ${product.productname}?`,
        text: 'Please note, deleting a sub-category is irreversible!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        customClass: {
          confirmButton: 'btn btn-success',
          cancelButton: 'btn btn-outline-danger ml-1',
        },
        buttonsStyling: false,
      }).then(result => {
        if (result.value) {
          const form = {
            productID: product.id,
          }
          this.$store.dispatch('product/deleteProduct', { form }).then(res => {
            this.$store.dispatch('product/getProducts').then(res2 => {
              this.isLoading = false
              const { message } = res.data
              const { data } = res2.data
              this.products = data
              this.$swal({
                title: `Deleted ${product.productname}`,
                text: message,
                icon: 'success',
              })
            })
          }).catch(err => {
            this.isLoading = false
            this.$swal({
              title: `Delete ${product.productname}?`,
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
[dir] .list-group .list-group-item-action:focus, [dir] .list-group .list-group-item:hover {
  background-color: #FFFFFF;
  color: #4D6D8A;
}
[dir] .input-group:not(.bootstrap-touchspin):focus-within .form-control, [dir] .input-group:not(.bootstrap-touchspin):focus-within .input-group-text {
  border-color: #4D6D8A;
}
.per-page-selector {
  width: 70px;
}
.invoice-filter-select {
  min-width: 190px;
  ::v-deep .vs__selected-options {
    flex-wrap: nowrap;
  }
  ::v-deep .vs__selected {
    width: 100px;
  }
}
[dir] .custom-select:focus {
  border-color: #4D6D8A;
}
[dir] .page-item.active .page-link {
  background-color: #4D6D8A;
  border-color: #4D6D8A;
}
.page-item .page-link:hover {
  color: #4D6D8A;
}
[dir] .form-control:focus {
  border-color: #4D6D8A;
}
[dir] .b-form-spinbutton.form-control:focus, [dir] .b-form-spinbutton.form-control.focus {
  border-color: #4D6D8A;
}

.bartum-button {
  color: white;
  background-color: #4D6D8A !important;
  border-color: #4D6D8A !important;
}
</style>

<style lang="scss">
@import '@core/scss/vue/libs/vue-select.scss';
</style>
