<template>
  <div>
    <modal-confirm
      :confirmActionName="confirmActionName"
      @listAction="listAction"
    ></modal-confirm>
    <nav class="navbar navbar-expand-lg navbar-light bg-white">
      <div class="px-3 container-fluid">
        <div class="d-flex justify-content-between w-100">
          <div>
            <button
              :class="{ disabled: sizeDisButton }"
              class="btn btn-dark"
              type="button"
              data-bs-toggle="offcanvas"
              data-bs-target="#offcanvasSidebar"
              aria-controls="offcanvasSidebar"
            >
              <i class="fa fa-bars fa-1x"></i>
            </button>
          </div>
          <input
            type="text"
            placeholder="Search"
            class="w-auto form-control"
            v-model="searchName"
          />
        </div>
      </div>
    </nav>
    <div class="py-3">
      <div class="card">
        <div class="card-header">
          <div class="w-100 hstack justify-content-between">
            <div class="fw-bold">Selection mode:</div>
            <b-form-select
              id="table-select-mode-select"
              v-model="selectMode"
              :options="modes"
              class="w-auto form-control"
            ></b-form-select>
          </div>
        </div>
        <div class="card-body">
          <b-table
            :items="
              items.slice(
                (currentPage - 1) * range,
                (currentPage - 1) * range + range
              )
            "
            :fields="fields"
            :select-mode="selectMode"
            ref="selectableTable"
            responsive="sm"
            class="overflow-auto mb-0"
            selectable
            @row-selected="onRowSelected"
          >
            <!-- Example scoped slot for select state illustrative purposes -->
            <template #cell(selected)="{ rowSelected }">
              <template v-if="rowSelected">
                <span aria-hidden="true">&check;</span>
                <span class="sr-only">Selected</span>
              </template>
              <template v-else>
                <span aria-hidden="true">&nbsp;</span>
                <span class="sr-only">Not selected</span>
              </template>
            </template>
          </b-table>
          <p class="group-btns flex-wrap hstack gap-1 mt-1">
            <b-button size="sm" @click="selectAllRows">Select all</b-button>
            <b-button size="sm" @click="clearSelected">Clear selected</b-button>
            <template v-if="tableName == 'users'">
              <button
                @click="openNewTab(selected)"
                :class="{ disabled: selected.length == 0 }"
                class="btn btn-sm btn-primary"
              >
                Visit profile
              </button>
              <button
                type="button"
                data-bs-toggle="modal"
                data-bs-target="#confirmAction"
                :class="{ disabled: checkIfSomeoneHasActiveStatus(true) }"
                @click="confirmActionName = 'active'"
                class="btn btn-sm btn-success"
              >
                Set active
              </button>
              <button
                type="button"
                data-bs-toggle="modal"
                data-bs-target="#confirmAction"
                :class="{ disabled: checkIfSomeoneHasActiveStatus(false) }"
                @click="confirmActionName = 'inactive'"
                class="btn btn-sm btn-warning"
              >
                Set inactive
              </button>
              <button
                v-if="
                  getRoleOfuser &&
                  getRoleOfuser.filter((item) =>
                    item.name.includes('Super admin')
                  ).length > 0
                "
                type="button"
                data-bs-toggle="modal"
                data-bs-target="#confirmAction"
                :class="{
                  disabled: checkIfManipuLationIsAllowed == false,
                }"
                @click="confirmActionName = 'UpdateAdmin'"
                class="btn btn-sm btn-danger"
              >
                Adauga/scoate admin
              </button>
            </template>
            <template v-else>
              <button
                type="button"
                data-bs-toggle="modal"
                data-bs-target="#confirmAction"
                class="btn btn-primary btn-sm"
                :class="{ disabled: selected.length == 0 }"
                @click="confirmActionName = 'mark'"
              >
                Mark/Unmark
              </button>
              <button
                @click="showMessages = !showMessages"
                :class="{ disabled: selected.length == 0 }"
                class="btn btn-primary btn-sm"
              >
                <template v-if="showMessages == false">
                  Arata mesajele
                </template>
                <template v-else> Ascunde mesajele </template>
              </button>
            </template>
            <button
              type="button"
              data-bs-toggle="modal"
              data-bs-target="#confirmAction"
              :class="{
                disabled:
                  tableName == 'users' && checkIfManipuLationIsAllowed == false,
              }"
              @click="confirmActionName = 'delete'"
              class="btn btn-sm btn-danger"
            >
              Delete
            </button>
          </p>
        </div>
        <div class="card-footer d-flex justify-content-center">
          <b-pagination
            class="mb-0"
            v-model="currentPage"
            :total-rows="items.length"
            :per-page="range"
            first-number
          ></b-pagination>
        </div>
      </div>
      <div v-if="showMessages" class="accordion mt-3" role="tablist">
        <b-card
          v-for="item in selected"
          :key="item['#ID']"
          no-body
          class="mb-1"
        >
          <b-card-header header-tag="header" class="p-1" role="tab">
            <b-button
              block
              class="w-100 d-flex justify-content-start fw-bold"
              v-b-toggle="'accordion-' + item['#ID']"
              variant="success"
              >#ID: {{ item["#ID"] }}</b-button
            >
          </b-card-header>
          <b-collapse
            :id="'accordion-' + item['#ID']"
            accordion="my-accordion"
            role="tabpanel"
          >
            <b-card-body>
              <b-card-text
                ><p class="fw-bold mb-0">Text of email:</p>
                {{ item.text }}</b-card-text
              >
            </b-card-body>
          </b-collapse>
        </b-card>
      </div>
    </div>
  </div>
</template>

<script>
import ModalConfirm from "../others/ModalConfirmAction.vue";
// for mixins
import ConvertDate from "../../mixins/mixin1.vue";
import CrudAdmin from "../../mixins/CrudAdmin.vue";

export default {
  mixins: [ConvertDate, CrudAdmin],
  components: { ModalConfirm },
  props: ["tableName"],
  data() {
    return {
      // pagination variables
      currentPage: 1,
      range: 10,
      // filtering
      searchName: "",
      // modals and accordion variables
      showMessages: false,
      confirmActionName: "",
      modes: ["multi", "single"],
      fields: [],
      originalItems: [],
      selectMode: "multi",
      selected: [],
    };
  },
  computed: {
    sizeDisButton() {
      return this.screen_width >= 992;
    },
    items() {
      // return this.originalItems;
      let KeyNameContain = this.fields.filter(
        (item) =>
          item.toLowerCase().includes("name") ||
          item.toLowerCase().includes("email")
      );
      return this.originalItems.filter((item) => {
        for (let index = 0; index < KeyNameContain.length; index++) {
          if (
            item[KeyNameContain[index]]
              .toUpperCase()
              .includes(this.searchName.toUpperCase())
          ) {
            return item;
          }
        }
      });
    },
    checkIfManipuLationIsAllowed() {
      return this.selected.length > 0 && this.checkIfGradeIsMore == false;
    },
  },
  watch: {
    selected: {
      handler(newValue, oldValue) {
        if (this.selected.length == 0 && this.showMessages == true) {
          this.showMessages = !this.showMessages;
        }
        this.updatecheckIfGradeIsMore(newValue);
      },
      deep: true,
    },
  },
  mounted() {
    if (this.tableName == "users")
      this.fields = [
        "#ID",
        "isActive",
        "age",
        "first_name",
        "last_name",
        "role",
        "Inregistrat la",
        "Email verificat la",
      ];
    else if (this.tableName == "emails")
      this.fields = [
        "#ID",
        "name of sender",
        "email",
        "subject",
        "primit la",
        "indeplinit la",
        "indeplinit de",
      ];
    this.getInfo();
  },
  methods: {
    // for disabled or not button
    checkIfSomeoneHasActiveStatus(status) {
      return (
        this.selected.filter((item) => item.isActive == status).length > 0 ||
        this.checkIfManipuLationIsAllowed == false
      );
    },
    // stock function
    onRowSelected(items) {
      this.selected = items;
    },
    selectAllRows() {
      this.$refs.selectableTable.selectAllRows();
    },
    clearSelected() {
      this.$refs.selectableTable.clearSelected();
    },
  },
};
</script>