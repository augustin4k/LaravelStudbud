<template>
  <div class="clearfix row chat-app flex-row-reverse">
    <div
      :style="{ visibility: size_for_canvas ? 'visible' : 'hidden' }"
      :class="[
        size_for_canvas
          ? 'col-4 h-100'
          : 'p-0 offcanvas w-75 offcanvas-start border bottom-0',
      ]"
      class="right-wrapper h-100 people-list"
      data-bs-scroll="false"
      data-bs-backdrop="true"
      tabindex="-1"
      id="people-list-canvas"
      aria-labelledby="people-list-canvasLabel"
    >
      <div
        v-if="size_for_canvas == false"
        class="
          bg-white
          hstack
          offcanvas-header
          border-bottom
          justify-content-between
        "
      >
        <h5
          class="m-0 text-primary fw-bold offcanvas-title"
          id="offcanvasLabel"
        >
          Lista conversatii
        </h5>
        <button
          type="button"
          class="btn btn-secondary btn"
          data-bs-dismiss="offcanvas"
          aria-label="Close"
        >
          <i class="fa fa-bars fa-1x" aria-hidden="true"></i>
        </button>
      </div>
      <div class="offcanvas-body p-0 h-100">
        <div class="card h-100">
          <template v-if="placeholders">
            <div class="card-header placeholder-glow">
              <span class="placeholder col-12"></span>
            </div>
            <div class="card-body">
              <div
                v-for="index in users_info.length"
                :key="index"
                class="placeholder-glow mb-3"
              >
                <a class="btn btn-secondary disabled placeholder col-2"></a>
                <span class="placeholder placeholder-xs col-9"></span>
              </div>
            </div>
          </template>
          <template v-else>
            <div class="card-header">
              <input
                type="text"
                class="form-control"
                v-model="search_name"
                placeholder="Search..."
              />
            </div>
            <div class="card-body p-0 overflow-auto h-100">
              <div
                v-if="users_info.length > 0"
                class="list-group small py-2 list-group-flush chat-list"
              >
                <a
                  v-for="user in users_info"
                  :key="user.id"
                  :href="'/chat?id=' + user.id"
                  :class="[user.id == selected_user_id ? 'active' : '']"
                  class="
                    list-group-item list-group-item-action
                    rounded
                    border-0
                  "
                >
                  <div class="hstack align-items-center gap-1">
                    <img class="rounded" :src="user.avatar_path" alt="avatar" />
                    <div class="about w-100">
                      <div class="name">{{ user.name }}</div>
                      <div class="status hstack justify-content-between">
                        <div class="hstack">
                          <i class="fa fa-circle online"></i>
                          <p class="mb-0">offline</p>
                        </div>
                        <p class="mb-0 opacity-75">
                          {{
                            convert_timestamp(user.last_message, "only_date")
                          }}
                        </p>
                      </div>
                    </div>
                  </div>
                </a>
              </div>
              <div v-else-if="users_info.length == 0" class="p-3">
                <div class="alert alert-secondary" role="alert">
                  <strong>Nu aveti conversatii cu useri cu asa nume...</strong>
                </div>
              </div>
              <div v-else class="p-3">
                <div class="alert alert-secondary" role="alert">
                  <strong>Nu aveti nicio conversatie inceputa...</strong>
                </div>
              </div>
            </div>
          </template>
        </div>
      </div>
    </div>
    <div class="h-100 col-12 col-md-8 col-lg-12 col-xl-8 left-wrapper">
      <div class="h-100 card chat">
        <!-- pentru placeholder -->
        <template v-if="selected_user_id">
          <div
            v-if="placeholders == false"
            class="card-header d-flex justify-content-between"
          >
            <div class="d-flex justify-content-between">
              <div class="hstack align-items-start">
                <div>
                  <button
                    class="
                      d-block d-md-none d-lg-inline d-xl-none
                      btn btn-secondary
                      me-2
                    "
                    type="button"
                    data-bs-toggle="offcanvas"
                    data-bs-target="#people-list-canvas"
                    aria-controls="people-list-canvas"
                  >
                    <i class="fa fa-bars fa-1x" aria-hidden="true"></i>
                  </button>
                </div>
                <a :href="'/timeline?id=' + selected_user_id" class="me-1">
                  <img
                    class="rounded"
                    :src="user.avatar_path"
                    alt="avatar"
                    height="40px"
                  />
                </a>
                <div class="chat-about small">
                  <h6 class="mb-0">{{ user["name"] }}</h6>
                  <small class="text-muted">Last activities: 2 hours ago</small>
                </div>
              </div>
            </div>
            <div class="d-flex gap-1">
              <div>
                <button
                  v-if="show_messages"
                  @click="show_messages = !show_messages"
                  class="btn btn-primary"
                >
                  <i class="bi bi-images"></i>
                </button>
                <button
                  v-else
                  @click="
                    show_messages = !show_messages;
                    scroll_bottom();
                  "
                  class="btn btn-primary"
                >
                  <i class="bi bi-chat-left-text-fill"></i>
                </button>
                <button
                  v-if="status == 'blocked_by_me'"
                  class="btn btn-outline-secondary"
                  @click="edit_friendship('not_friends')"
                >
                  <i class="bi bi-unlock-fill"></i>
                </button>
                <button
                  v-else-if="status == 'block_me'"
                  class="opacity-75 btn btn-secondary"
                  disabled
                >
                  <i class="bi bi-lock-fill"></i>
                </button>
                <button
                  v-else
                  @click="edit_friendship('block')"
                  class="btn btn-outline-secondary"
                >
                  <i class="bi bi-lock-fill"></i>
                </button>
              </div>
            </div>
          </div>
          <div v-else class="card-header placeholder-glow">
            <a class="placeholder col-1 btn btn-secondary disabled"></a>
            <span class="placeholder placeholder-xs col-3"></span>
            <a class="placeholder btn btn-primary disabled col-1 offset-5"></a>
            <a class="placeholder btn btn-secondary disabled col-1"></a>
          </div>
          <template v-if="show_messages">
            <div class="card-body chat-history" id="chat_history">
              <ul
                v-if="messages.length > 0"
                id="chat_list_messages"
                class="mb-0 list-unstyled small chat_list_messages h-auto"
              >
                <li
                  v-for="(message, index) in messages"
                  :key="message.id"
                  :class="[
                    placeholders == false ? 'clearfix' : 'placeholder-glow',
                    messages[index + 1] &&
                    messages[index].sender_id == messages[index + 1].sender_id
                      ? 'mb-1'
                      : '',
                  ]"
                >
                  <template v-if="placeholders">
                    <button
                      class="btn btn disabled placeholder col-1 offset-11"
                    ></button>
                    <span class="placeholder col-11 placeholder-xs"></span>
                    <span class="placeholder col-10 placeholder-xs"></span>
                    <span class="placeholder col-12 placeholder-xs"></span>
                  </template>
                  <div
                    v-else
                    :class="[
                      message.sender_id != selected_user_id
                        ? 'float-end text-end'
                        : 'float-start text-start',
                    ]"
                  >
                    <div
                      v-if="
                        index == 0 ||
                        (index > 1 &&
                          messages[index - 1].sender_id != message.sender_id)
                      "
                      :class="[
                        message.sender_id != selected_user_id
                          ? 'text-end'
                          : 'text-start',
                      ]"
                      class="message-data"
                    >
                      <img
                        class="rounded"
                        :src="[
                          message.sender_id == selected_user_id
                            ? user.avatar_path
                            : my_avatar_path,
                        ]"
                        alt="avatar"
                      />
                    </div>
                    <div
                      class="message d-flex flex-column"
                      :class="[
                        (index > 0 &&
                          messages[index - 1].sender_id != message.sender_id) ||
                        index == 0
                          ? ''
                          : 'clearfix',
                        message.sender_id != selected_user_id
                          ? 'other-message'
                          : 'my-message',
                      ]"
                    >
                      {{ message.text }}
                      <div v-if="message.images" class="d-flex flex-wrap gap-2">
                        <img
                          v-for="image in message.images"
                          :key="image.id"
                          :src="image.path"
                          alt=""
                          class="rounded"
                        />
                      </div>
                      <small
                        class="w-100 text-right opacity-75 text-secondary"
                        >{{
                          convert_timestamp(message.updated_at, "date_and_time")
                        }}</small
                      >
                    </div>
                  </div>
                </li>
              </ul>
              <div
                v-else-if="placeholders == false"
                class="h-100 d-flex align-items-center justify-content-center"
              >
                <div class="alert alert-primary" role="alert">
                  <strong
                    >Nu aveti vreo conversatie inceputa cu acest user...</strong
                  >
                </div>
              </div>
            </div>
            <div class="card-footer chat-message clearfix">
              <template v-if="status == 'block_me'"
                ><div class="alert alert-danger mb-0" role="alert">
                  <small
                    >Acest user te-a blocat, nu-i mai poti scrie niciun
                    mesaj!</small
                  >
                </div>
              </template>
              <template v-else-if="status == 'blocked_by_me'"
                ><div class="alert alert-warning mb-0" role="alert">
                  <small
                    >Ai blocat acest user. Pentru a putea sa-i scrii un mesaj,
                    trebuie sa-l dezblochezi.</small
                  >
                </div>
              </template>
              <template v-else>
                <div
                  v-if="images.length > 0"
                  class="my-3 hstack gap-3 flex-wrap"
                >
                  <div
                    class="preload-images position-relative"
                    v-for="(image, index) in images"
                    :key="index"
                  >
                    <img class="rounded" :src="image.path" />
                    <button
                      @click="delete_image_preloaded(index)"
                      class="
                        btn btn-sm btn-danger
                        position-absolute
                        top-0
                        start-100
                        translate-middle
                      "
                    >
                      <i class="bi bi-trash3-fill"></i>
                    </button>
                  </div>
                </div>
                <div class="d-flex gap-2">
                  <input
                    @change="preload_images"
                    type="file"
                    class="d-none"
                    ref="images"
                    accept=".jpg, .png, .jpeg"
                    multiple
                  />
                  <button
                    @click="$refs.images.click()"
                    class="btn-secondary btn"
                  >
                    <i class="fa-solid fa-paperclip"></i>
                  </button>
                  <input
                    type="text"
                    class="form-control"
                    :class="[shake ? 'shake is-invalid' : '']"
                    placeholder="Enter text here..."
                    v-model="$v.message.$model"
                  />
                  <button
                    :disabled="!$v.message.required"
                    @click="send_message"
                    class="btn btn-primary text-white"
                  >
                    <i class="fa fa-send"></i>
                  </button>
                </div>
              </template>
            </div>
          </template>
          <div v-else class="card-body pb-0 overflow-auto">
            <photo-gallery
              v-if="photo_gallery.length > 0"
              :index_gallery="1"
              :images="photo_gallery"
            />
            <div
              v-else
              class="
                d-flex
                align-items-center
                pb-3
                h-100
                justify-content-center
              "
            >
              <div class="alert alert-secondary" role="alert">
                <strong
                  >Nu aveti nicio imagine trimisa in aceasta
                  conversatie...</strong
                >
              </div>
            </div>
          </div>
        </template>
        <template v-else>
          <div
            class="h-100 p-3 d-flex align-items-center justify-content-center"
          >
            <button
              class="
                position-absolute
                top-0
                start-0
                d-block d-md-none d-lg-inline d-xl-none
                btn btn-secondary
                m-3
              "
              type="button"
              data-bs-toggle="offcanvas"
              data-bs-target="#people-list-canvas"
              aria-controls="people-list-canvas"
            >
              <i class="fa fa-bars fa-1x" aria-hidden="true"></i>
            </button>
            <div class="d-flex flex-column align-items-center gap-3">
              <i class="fa fa-graduation-cap fa-5x"></i>
              <div class="alert alert-primary" role="alert">
                <strong
                  >Selecteaza vreun user pentru a incepe conversatia.</strong
                >
              </div>
            </div>
          </div>
        </template>
      </div>
    </div>
  </div>
</template>

<script>
import { required } from "vuelidate/lib/validators";
import photoGallery from "../components/others/PhotoGallery.vue";
import convert_and_resize from "../mixins/mixin1.vue";

export default {
  mixins: [convert_and_resize],
  props: ["selected_user_id", "my_avatar_path"],
  components: { photoGallery },
  data() {
    return {
      // inputs
      search_name: "",
      images: [],
      message: "",
      //culese din baza de date
      status: "",
      messages: [],
      save_users: [],
      user: [],
      // triggere pentru frontend
      show_messages: true,
      placeholders: true,
    };
  },
  validations: {
    message: { required },
  },
  computed: {
    photo_gallery() {
      let array = [];
      for (let index = 0; index < this.messages.length; index++) {
        if (this.messages[index].images) {
          for (
            let index1 = 0;
            index1 < this.messages[index].images.length;
            index1++
          ) {
            this.messages[index].images[index1]["date"] =
              this.messages[index].updated_at;
            array.push(this.messages[index].images[index1]);
          }
        }
      }
      return array;
    },
    users_info() {
      let array = this.save_users;
      return array.filter(
        (item) =>
          item.name.toLowerCase().indexOf(this.search_name.toLowerCase()) != -1
      );
    },
    size_for_canvas() {
      return (
        this.screen_width >= 1200 ||
        (this.screen_width >= 768 && this.screen_width < 992)
      );
    },
  },
  mounted() {
    // primirea informatiei din baza de date
    setInterval(() => {
      axios
        .post("api/show-messages", {
          selected_user_id: this.selected_user_id,
          messages: this.messages,
        })
        .then((response) => {
          this.user = response.data.users[0];
          this.save_users = response.data.users;
          this.status = response.data.status;
          if (this.messages.length == 0) {
            this.messages = response.data.messages;
          } else if (response.data.messages.length > 0) {
            for (
              let index = 0;
              index < response.data.messages.length;
              index++
            ) {
              this.messages.push(response.data.messages[index]);
            }
          }
          setTimeout(() => {
            this.placeholders = false;
          }, 1000);
          // for scrolling bottom
          if (response.data.messages.length > 0) {
            this.scroll_bottom();
          }
        });
    }, 1000);
  },
  methods: {
    edit_friendship(action) {
      axios
        .post("api/edit-friendship", {
          user: { id: this.selected_user_id },
          action: action,
        })
        .then((response) => {
          console.log(response.data);
        });
    },
    scroll_bottom() {
      setTimeout(function () {
      
      window
          .$("#chat_history")
          .scrollTop(window.$("#chat_list_messages").height());
      }, 100);
    },
    send_message() {
      let formData = new FormData();
      for (var i = 0; i < this.$refs.images.files.length; i++) {
        let file = this.$refs.images.files[i];
        formData.append("files[" + i + "]", file);
      }
      formData.append("message", this.message);
      formData.append("selected_user", this.selected_user_id);
      axios
        .post("api/insert-message", formData, {
          headers: {
            "Content-Type": "multipart/form-data",
          },
        })
        .then((response) => {
          console.log(response.data);
          this.images = [];
          this.message = "";
          this.$refs.images.value = null;
        })
        .catch((error) => {
          this.Shake(error);
        });
    },
    delete_image_preloaded(index) {
      this.images.splice(index, 1);
    },
    preload_images(event) {
      let array_images = [];
      let files = [];
      for (let index = 0; index < event.target.files.length; index++) {
        files.push(event.target.files[index]);
      }
      if (files.length > 5) {
        alert("Maxim 5 imagini poti incarca.");
        let over = files.length - 5;
        files.splice(4, over);
      }
      for (let index = 0; index < files.length; index++) {
        let image = { path: "" };
        image["path"] = URL.createObjectURL(files[index]);
        array_images.push(image);
      }
      this.images = array_images;
    },
  },
};
</script>
