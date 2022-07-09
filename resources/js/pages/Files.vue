<template>
  <div>
    <!-- commit check -->
    <modal-confirm
      :confirmActionName="modal.confirmActionName"
      @listAction="listAction"
    ></modal-confirm>
    <div class="row">
      <div class="col-12 col-md-8 col-lg-12 col-xl-8 left-wrapper">
        <div
          v-if="
            compartments.selected.id != 0 &&
            (my_id == selected_user_id || selected_user_id == 0)
          "
          id="input-post"
          class="mb-3 card shadow-sm"
          :class="{ 'shake border border-danger': shake }"
        >
          <div class="card-header fw-bold">Incarca fisierele</div>
          <div class="card-body position-relative">
            <ul
              v-if="post_card.images.length > 0"
              class="row mb-0 list-unstyled"
            >
              <li
                v-for="(document, index) in post_card.images"
                :key="document.path"
                class="col-12 col-sm-6 col-lg-12 col-xl-6"
              >
                <div
                  class="
                    border-bottom
                    mb-2
                    d-flex
                    justify-content-between
                    align-items-start
                    gap-1
                  "
                >
                  <div class="me-auto d-flex flex-row-reverse gap-1">
                    <div class="d-flex flex-column">
                      <div
                        class="
                          fw-bold
                          small
                          opacity-75
                          text-secondary text-break
                        "
                      >
                        {{ document.name }}
                      </div>
                      <small class="form-text text-muted"
                        >.{{ document.extension }}, {{ document.size }}</small
                      >
                    </div>
                    <div
                      :class="
                        filter_extension_color(document.extension, 'post_card')
                      "
                      class="p-2 align-self-center bg-opacity-10 rounded h-100"
                    >
                      .{{ document.extension }}
                    </div>
                  </div>
                  <div>
                    <button
                      class="btn btn-sm btn-outline-danger"
                      @click="delete_preload_document(index, 1)"
                    >
                      Sterge
                    </button>
                  </div>
                </div>
              </li>
            </ul>
            <div v-else class="alert alert-secondary" role="alert">
              <strong>Nu ai incarcat inca niciun fisier.</strong>
            </div>
          </div>
          <div class="card-footer hstack justify-content-between">
            <div>
              <small
                v-if="post_card.images.length > 0"
                class="fw-bold text-primary"
                >{{ post_card.images.length }} fisiere incarcate</small
              >
            </div>
            <input
              type="file"
              class="d-none"
              ref="documents"
              @input="post_card.images = preload_images('documents')"
              accept="application/msword, application/vnd.ms-excel, application/vnd.ms-powerpoint,
text/plain, application/pdf"
              multiple
            />
            <div class="btn-group">
              <button
                class="btn btn-warning text-white"
                @click="$refs.documents.click()"
              >
                Upload
              </button>
              <button @click="new_info('files')" class="btn btn-primary">
                Post
              </button>
            </div>
          </div>
        </div>
        <div
          v-if="
            compartments.get_compartments &&
            compartments.get_compartments.length > 0
          "
          class="alert alert-warning"
          role="alert"
        >
          <strong
            >Descriere compartiment: <br />
            <small class="text-dark fw-light">
              {{
                compartments.get_compartments.filter(
                  (item) => item.id == compartments.selected.id
                )[0].description
              }}
            </small>
          </strong>
        </div>

        <div class="count-files hstack gap-2 mb-3">
          <div class="hstack gap-2 w-100">
            <button
              v-if="size_for_canvas == false"
              type="button"
              data-bs-toggle="offcanvas"
              data-bs-target="#offcanvasCompartments"
              aria-controls="offcanvasCompartments"
              class="btn btn-sm text-white btn-info hstack gap-1"
            >
              <i class="bi bi-bookshelf"></i>
              <span><small class="fw-bold">Compartimente</small></span>
            </button>
            <div class="border-top w-100"></div>
          </div>
          <template v-if="get_files && get_files.length !== 0">
            <div
              class="border-top w-100"
              v-if="type == 'post' && selected_user_id !== 0"
            ></div>
            <p class="text-muted text-nowrap mb-0">
              {{ get_files.length }} fisiere
            </p>
            <div class="hstack flex-row gap-2 w-100">
              <div
                class="border-top w-100"
                v-if="
                  selected_user_id !== 0 && post_card.selected_mode !== 'multi'
                "
              ></div>
              <select
                v-model="post_card.selected_mode"
                class="w-100 form-control"
              >
                <option value="single">Single</option>
                <option value="multi">Multi</option>
              </select>
              <button
                data-bs-toggle="modal"
                data-bs-target="#confirmAction"
                @click="
                  (modal.table = 'files'),
                    (modal.confirmActionName = 'MultiDelete')
                "
                v-if="post_card.selected_mode == 'multi'"
                class="btn btn-danger w-100"
              >
                Delete
              </button>
            </div>
          </template>
        </div>
        <div v-if="get_files && get_files.length > 0" class="mb-3 row">
          <div
            v-for="file in get_files"
            :key="file.id"
            class="col-6 col-sm-4 col-md-6 col-xl-4 grid-margin"
          >
            <div
              :class="{ 'border border-info': file.selected }"
              class="card shadow-sm h-100"
            >
              <div
                class="
                  d-flex
                  justify-content-center
                  card-body
                  my-auto
                  position-relative
                "
              >
                <div class="position-absolute top-0 end-0 p-2">
                  <!-- delete file -->
                  <button
                    v-if="post_card.selected_mode == 'single'"
                    data-bs-toggle="modal"
                    data-bs-target="#confirmAction"
                    @click="
                      (modal.id = file.id),
                        (modal.table = 'files'),
                        (modal.confirmActionName = 'delete')
                    "
                    class="btn btn-sm btn-danger"
                  >
                    <i class="bi bi-trash-fill"></i>
                  </button>
                  <!-- select file -->
                  <div v-else-if="post_card.selected_mode == 'multi'">
                    <input
                      type="checkbox"
                      class="btn-check"
                      :id="'btn-check-outlined' + file.id"
                      autocomplete="off"
                      @change="file.selected = !file.selected"
                    />
                    <label
                      class="btn btn-sm btn-outline-primary"
                      :for="'btn-check-outlined' + file.id"
                    >
                      <template v-if="file.selected">Selected</template>
                      <template v-else>Select</template> </label
                    ><br />
                  </div>
                </div>
                <i
                  :class="
                    'bi bi-filetype-' +
                    getExtension(file.name) +
                    ' fa-3x ' +
                    filter_extension_color(getExtension(file.name), 'file_card')
                  "
                ></i>
              </div>
              <div class="card-footer" :class="{ 'bg-info': file.selected }">
                <h6 class="card-title">
                  <a href="">{{ file.name }} </a>
                </h6>
                <small
                  class="card-text form-text"
                  :class="[file.selected ? 'text-white' : 'text-muted']"
                >
                  {{ convert_timestamp(file.updated_at, "convert_timestamp") }}
                </small>
              </div>
            </div>
          </div>
        </div>
        <div v-else class="alert alert-primary" role="alert">
          <strong>Nu exista niciun fisier in acest compartiment.</strong>
        </div>
      </div>

      <!-- offcanvas compartiments -->
      <div :class="{ 'right-wrapper col-4': size_for_canvas }">
        <div
          :style="{ visibility: size_for_canvas ? 'visible' : 'hidden' }"
          data-bs-scroll="false"
          data-bs-backdrop="true"
          tabindex="-1"
          id="offcanvasCompartments"
          aria-labelledby="offcanvasCompartmentsLabel"
          :aria-hidden="[size_for_canvas ? 'visible' : 'hidden']"
          :class="[
            size_for_canvas
              ? 'card mb-3'
              : 'offcanvas w-100 rounded offcanvas-bottom border',
          ]"
        >
          <div
            :class="[size_for_canvas ? 'card-header' : 'offcanvas-header']"
            class="bg-light d-flex justify-content-between align-items-center"
          >
            <b>Compartimente </b>
            <i class="bi bi-bookshelf"></i>
          </div>
          <div :class="[size_for_canvas ? 'card-body' : 'offcanvas-body']">
            <!-- informatia in functie de ce am nevoie -->
            <div
              v-if="
                compartments.get_compartments &&
                compartments.get_compartments.length > 0
              "
              class="list-group"
              id="list-tab"
              role="tablist"
            >
              <a
                v-for="compartment in compartments.get_compartments"
                :key="compartment.id"
                :class="{
                  active: compartment.id == compartments.selected.id,
                }"
                class="position-relative list-group-item list-group-item-action"
                ><div @click="compartments.selected.id = compartment.id">
                  {{ compartment.name }}
                </div>
                <button
                  data-bs-toggle="modal"
                  data-bs-target="#confirmAction"
                  @click="
                    (modal.id = compartment.id),
                      (modal.table = 'compartments'),
                      (modal.confirmActionName = 'delete')
                  "
                  class="btn btn-sm btn-danger position-absolute top-0 end-0"
                >
                  Delete
                </button>
              </a>
            </div>
            <div v-else class="alert alert-primary" role="alert">
              <strong>Nu exista compartimente!</strong>
            </div>
          </div>
          <div
            :class="[
              size_for_canvas
                ? 'card-footer'
                : 'offcanvas-footer bg-white border-top pt-3 mt-0 m-3',
              compartments.error ? 'shake border border-danger' : '',
            ]"
          >
            <div class="w-100 btn-group">
              <input
                type="text"
                maxlength="50"
                v-model="$v.compartments.input.name.$model"
                placeholder="New compartment..."
                class="form-control"
              />
              <button
                @click="new_info('compartments')"
                :class="{
                  disabled: !(
                    $v.compartments.input.name.required &&
                    $v.compartments.input.description.required
                  ),
                }"
                class="btn btn-primary"
              >
                Adauga
              </button>
            </div>
            <div
              v-if="$v.compartments.input.name.required"
              class="d-flex flex-column align-items-end"
            >
              <textarea
                id="description-new-compartment"
                :class="{
                  'is-valid': compartments.input.description.length == 100,
                }"
                v-model="$v.compartments.input.description.$model"
                cols="30"
                rows="3"
                maxlength="100"
                placeholder="Adauga descrierea la acest compartiment..."
                class="mt-3 form-control"
              ></textarea>
              <small
                :class="[
                  compartments.input.description.length == 100
                    ? 'text-danger'
                    : 'text-muted',
                ]"
                >{{ compartments.input.description.length }}/100</small
              >
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
// components
import ModalConfirm from "../components/others/ModalConfirmAction.vue";
// mixins
import PostCard from "../mixins/CardPost.vue";
import ShakeAndFilter from "../mixins/mixin1.vue";
// validation
import { required } from "vuelidate/lib/validators";

export default {
  mixins: [PostCard, ShakeAndFilter],
  components: { ModalConfirm },
  props: ["my_id", "selected_user_id", "type"],
  data() {
    return {
      modal: {
        confirmActionName: "",
        id: "",
        table: "",
      },
      compartments: {
        error: false,
        selected: { id: 0 },
        input: { name: "", description: "" },
        get_compartments: [],
      },
      get_files: [],
      post_card: {
        selected_mode: "single",
        images: [],
      },
    };
  },
  watch: {
    "post_card.selected_mode": function () {
      let array = this.get_files;
      array
        .filter((item) => item.selected == true)
        .forEach((item) => (item.selected = false));
      this.get_files = array;
    },
    "compartments.selected.id": function (newVal) {
      this.getInfo("files", newVal);
    },
    "compartments.input.name": function () {
      if (this.$v.compartments.input.name.required)
        window.$(window).scrollTop(window.$("#offcanvasCompartments").height());
    },
  },
  validations: {
    compartments: { input: { name: { required }, description: { required } } },
  },
  mounted() {
    this.getInfo("compartments", null);
    this.getInfo("files", null);
  },
  computed: {
    CurrentCompartment() {
      if (
        this.compartments.get_compartments &&
        this.compartments.get_compartments.length > 0
      ) {
        return this.compartments.selected.id;
      } else return null;
    },
  },
  methods: {
    arrangeInformationAfterCRUD(table) {
      if (table == "compartments") {
        this.getInfo(table, null);
      } else this.getInfo(table, this.CurrentCompartment);
    },
    getExtension(fileName) {
      let extension = fileName.split(".");
      return extension.slice(-1);
    },
    clearInputs(table_name) {
      if (table_name == "compartments")
        for (let key in this.compartments.input)
          this.compartments.input[key] = "";
      else this.post_card.images = [];
    },
    listAction(action) {
      if (action == "delete") {
        this.delete_something(this.modal.id, this.modal.table);
      } else if (action == "MultiDelete")
        this.delete_something(
          this.get_files.filter((item) => item.selected == true),
          this.modal.table
        );
    },
    // API
    // read
    getInfo(type_of_info, compartment) {
      axios
        .post("api/get_info_files", {
          selectedUserID: this.selected_user_id,
          type: type_of_info,
          compartmentID: compartment,
        })
        .then((response) => {
          if (type_of_info == "files") {
            let array = response.data;
            array.forEach((item) => (item.selected = false));
            this.get_files = array;
          } else if (type_of_info == "compartments") {
            this.compartments.get_compartments = response.data;
            if (
              this.compartments.get_compartments.length > 0 &&
              this.compartments.selected.id == 0
            ) {
              this.compartments.selected.id =
                this.compartments.get_compartments[0].id;
            }
          }
        });
    },
    // create
    new_info(table) {
      let formData = new FormData();
      formData.append("table", table);
      formData.append("selectedUserID", this.selected_user_id);
      if (table == "files") {
        formData.append("compartmentID", this.compartments.selected.id);
        if (this.$refs.documents.files.length > 0) {
          for (var i = 0; i < this.$refs.documents.files.length; i++) {
            let file = this.$refs.documents.files[i];
            formData.append("files[" + i + "]", file);
          }
        }
      } else if (table == "compartments") {
        for (let key in this.compartments.input)
          formData.append("input[" + key + "]", this.compartments.input[key]);
      }
      axios
        .post("api/new_info", formData, {
          headers: {
            "Content-Type": "multipart/form-data",
          },
        })
        .then((response) => {
          this.arrangeInformationAfterCRUD(table);
          this.clearInputs(table);
        })
        .catch((error) => {
          if (table == "compartments") {
            this.compartments.error = true;
            this.errors = error.response.data.errors;
            setTimeout(() => {
              this.errors = [];
              this.compartments.error = false;
            }, 1500);
          } else this.Shake(error);
        });
    },
    // delete
    delete_something(id, type) {
      axios
        .post("api/delete_something", {
          id,
          type,
        })
        .then((response) => {
          if (type == "compartments") {
            if (id == this.compartments.selected.id)
              this.compartments.selected.id = 0;
          }
          this.arrangeInformationAfterCRUD(type);
        });
    },
    // for styling
    filter_extension_color(document_extension, _for) {
      if (_for == "post_card") {
        if (document_extension.includes("doc")) {
          return "bg-primary text-primary";
        } else if (document_extension.includes("xls"))
          return "bg-success text-success";
        else if (document_extension.includes("pdf"))
          return "bg-danger text-danger";
        else return "bg-secondary text-secondary";
      } else if (_for == "file_card") {
        if (document_extension.includes("doc")) {
          return "text-primary";
        } else if (document_extension.includes("xls")) return "text-success";
        else if (document_extension.includes("pdf")) return "text-danger";
        else return "text-secondary";
      }
    },
  },
};
</script>

<style>
.list-group-item-action > div {
  cursor: pointer;
}
</style>