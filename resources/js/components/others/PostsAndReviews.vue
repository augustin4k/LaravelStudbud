<template>
  <div>
    <!-- PENTRU PLACEHOLDER -->
    <template v-if="placeholders == true">
      <div class="card mb-3" v-if="showInputPost">
        <div class="placeholder-glow card-header">
          <button class="col-2 placeholder disabled btn btn-secondary"></button>
          <button class="col-2 placeholder disabled btn btn-secondary"></button>
        </div>
        <div class="card-body placeholder-glow">
          <div class="placeholder col-11"></div>
          <div class="placeholder col-12"></div>
          <div class="placeholder col-9"></div>
        </div>
        <div
          class="card-footer placeholder-glow d-flex justify-content-between"
        >
          <button class="placeholder btn btn-warning disabled col-2"></button>
          <button class="placeholder btn btn-primary disabled col-2"></button>
        </div>
      </div>
      <div class="mb-3 placeholder-glow">
        <span class="placeholder col-6 offset-3"></span>
      </div>
      <div class="card mb-3" aria-hidden="true">
        <div class="card-header placeholder-glow">
          <button class="placeholder btn btn-secondary disabled col-1"></button
          ><span class="placeholder col-3 offset-1"></span>
          <button
            class="btn btn-secondary disabled placeholder col-1 offset-5"
          ></button>
        </div>
        <div class="d-flex gap-2 p-3 pb-0">
          <div v-for="index in 3" :key="index">
            <img
              src="/img/gray-color-solid-background-1920x1080.png"
              class="w-100 rounded"
              alt="..."
            />
          </div>
        </div>
        <div class="card-body">
          <div class="placeholder-glow">
            <span class="placeholder col-11"></span>
            <span class="placeholder col-12"></span>
            <span class="placeholder col-10"></span>
          </div>
        </div>
        <div class="card-footer placeholder-glow d-flex gap-1">
          <button
            v-for="index in 3"
            :key="index"
            class="btn btn-primary col-2 placeholder disabled"
          ></button>
        </div>
      </div>
    </template>
    <!--  -->
    <template v-else>
      <div
        v-if="showInputPost"
        id="input-post"
        class="mb-3 card shadow-sm"
        :class="{ shake: shake }"
      >
        <div class="card-header">
          <div class="nav navbar-expand nav-tabs card-header-tabs">
            <div
              class="nav nav-tabs navbar-expand border-0"
              id="nav-tab"
              role="tablist"
            >
              <button
                ref="nav_post"
                class="nav-link active"
                id="nav-post-tab"
                data-bs-toggle="tab"
                data-bs-target="#nav-post"
                type="button"
                role="tab"
                aria-controls="nav-post"
                aria-selected="true"
              >
                {{ type.charAt(0).toUpperCase() + type.slice(1) }}
              </button>
              <button
                v-if="post_card.images && post_card.images.length > 0"
                class="nav-link"
                id="nav-incarca-tab"
                data-bs-toggle="tab"
                type="button"
                data-bs-target="#nav-incarca"
                role="tab"
                aria-controls="nav-incarca"
                aria-selected="false"
              >
                Images ({{ post_card.images.length }})
              </button>
            </div>
          </div>
        </div>
        <div class="tab-content" id="myTabContent">
          <div
            class="tab-pane fade show active"
            id="nav-post"
            role="tabpanel"
            aria-labelledby="nav-post-tab"
          >
            <div class="card-body">
              <textarea
                maxlength="500"
                v-model="$v.post_card.text.$model"
                name="Post"
                class="form-control"
                :placeholder="[
                  type == 'post' ? 'Lasa un post...' : 'Lasa un review...',
                ]"
                rows="3"
                :class="[post_card.text.length >= 500 ? 'is-valid' : '']"
              ></textarea>
              <div class="d-flex justify-content-between">
                <div v-if="type == 'reviews'" class="mt-3 btn-group">
                  <button
                    v-for="index in 5"
                    :key="index"
                    class="btn btn-sm"
                    @click="
                      [
                        post_card.note != index
                          ? (post_card.note = index)
                          : (post_card.note = 0),
                      ]
                    "
                    :class="NoteColor(index)"
                  >
                    <i class="bi bi-star-fill"></i> {{ index }}
                  </button>
                </div>
                <span
                  v-if="$v.post_card.text.required"
                  class="text-primary small"
                  >{{ post_card.text.length }}/500</span
                >
              </div>
              <small v-if="errors.note" class="text-danger opacity-75"
                >* Te rog alege o nota</small
              >
            </div>
          </div>
          <div
            v-if="post_card.images && post_card.images.length > 0"
            class="tab-pane fade"
            id="nav-incarca"
            role="tabpanel"
            aria-labelledby="nav-incarca-tab"
          >
            <div class="card-body position-relative">
              <ul class="row mb-0 list-unstyled">
                <li
                  v-for="(document, index) in post_card.images"
                  :key="document.path"
                  class="
                    col-12 col-sm-6 col-lg-12 col-xl-6
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
                    <img class="img-xs rounded" :src="document.path" alt="" />
                  </div>
                  <div>
                    <button
                      class="btn btn-sm btn-outline-danger"
                      @click="delete_preload_document(index, 1)"
                    >
                      Sterge
                    </button>
                  </div>
                </li>
              </ul>
            </div>
          </div>
        </div>
        <div class="card-footer d-flex justify-content-between">
          <div class="d-flex flex-column align-items-start">
            <input
              type="file"
              class="d-none"
              ref="documents"
              @input="post_card.images = preload_images('documents')"
              accept=".jpg, .png, .jpeg"
              multiple
            />
            <button
              class="btn btn-warning text-white"
              @click="$refs.documents.click()"
            >
              <i class="bi bi-image-fill"></i> Incarca
            </button>
          </div>
          <div>
            <template v-if="post_card.id">
              <button
                class="btn btn-danger"
                @click="
                  post_card.id = '';
                  post_card.text = '';
                  post_card.note = 0;
                  delete_preload_document(0, post_card.images.length);
                "
              >
                Reset
              </button>
              <button
                @click="
                  send(post_card.id, type);
                  $emit('ClickResetExist');
                "
                class="btn btn-primary"
                :disabled="$v.post_card.text.required == false"
              >
                Update
              </button>
            </template>

            <template v-else class="d-flex gap-1">
              <button
                @click="
                  send(null, type);
                  $emit('ClickResetExist');
                "
                class="btn"
                :class="type == 'reviews' ? 'btn-success' : 'btn-primary'"
                :disabled="$v.post_card.text.required == false"
              >
                Post
              </button>
            </template>
          </div>
        </div>
      </div>
      <div v-if="ClickSearchPost" class="d-flex align-items-start gap-2 mb-3">
        <div class="form-floating w-100">
          <input
            @keyup.enter="
              posts_info = FilterReviewsPosts(GetFilters, original_posts_info)
            "
            v-model="GetFilters.message"
            type="text"
            class="form-control"
            placeholder="Cauta postarea..."
            required
          />
          <label>Cauta postarea...</label>
        </div>
        <button
          @click="
            ClickSearchPost = !ClickSearchPost;
            GetFilters.message = '';
            posts_info = FilterReviewsPosts(GetFilters, original_posts_info);
          "
          class="btn btn-secondary btn-sm"
        >
          <i class="bi bi-x-circle"></i>
        </button>
      </div>
      <template v-if="posts_info && posts_info.length !== 0">
        <div v-if="ClickSearchPost == false" class="gap-2 hstack mb-3">
          <div
            v-if="type == 'reviews' || selected_user_id == 0"
            class="hstack gap-2 w-100"
          >
            <button
              v-if="size_for_canvas == false"
              type="button"
              data-bs-toggle="offcanvas"
              data-bs-target="#offcanvasFiltru"
              aria-controls="offcanvasFiltru"
              class="btn btn-sm btn-dark hstack gap-1"
            >
              <i class="bi bi-funnel-fill"></i>
              <span><small class="fw-bold">Filtru</small></span>
            </button>
            <div class="border-top w-100"></div>
          </div>
          <div
            class="border-top w-100"
            v-if="type == 'post' && selected_user_id !== 0"
          ></div>
          <p class="text-muted text-nowrap mb-0">
            {{ posts_info.length }} postari
          </p>
          <div class="border-top w-100" v-if="selected_user_id !== 0"></div>
          <div
            v-if="selected_user_id == 0"
            class="hstack flex-row-reverse gap-2 w-100"
          >
            <button
              @click="ClickSearchPost = !ClickSearchPost"
              v-if="size_for_canvas == false"
              type="button"
              class="btn btn-sm btn-dark"
            >
              <i class="bi bi-search"></i>
            </button>
            <div class="border-top w-100"></div>
          </div>
        </div>
        <post-item
          v-for="post in posts_info"
          :key="post.id"
          @send_post="send_post"
          @modal_user_list="modal_user_list"
          @populate_post_card="populate_post_card"
          @modal_delete="modal_delete"
          :selected_user_id="selected_user_id"
          :get_post="post"
          :my_id="my_id"
          :type="type"
        ></post-item>
      </template>
      <template v-else>
        <div class="alert alert-primary" role="alert">
          <strong>Nu exista postari.</strong>
        </div>
      </template>
    </template>
    <!-- FOR MODALS/OFFCANVAS -->
    <!-- Offcanvas for comentarii -->
    <comments-items
      @modal_user_list="modal_user_list"
      :my_avatar="my_avatar"
      :my_id="my_id"
      :selected_user_id="selected_user_id"
      :get_post_id="modal.post.id"
    />
    <!-- Modal for likes/dislikes -->
    <div
      class="modal fade"
      :id="'users_list'"
      tabindex="-1"
      role="dialog"
      aria-labelledby="modelTitleId"
      aria-hidden="true"
    >
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Like-uri</h5>
            <button
              type="button"
              class="btn-close"
              data-bs-dismiss="modal"
              aria-label="Close"
            ></button>
          </div>
          <div class="modal-body">
            <div
              v-if="modal.users_list && modal.users_list.length > 0"
              class="row"
            >
              <div
                v-for="user in modal.users_list"
                :key="user.user_id"
                class="col-md-6 col-12"
              >
                <friend-item
                  :item="user"
                  :im_user="selected_user_id == my_id"
                />
              </div>
            </div>
            <div v-else class="alert alert-secondary" role="alert">
              <strong>Nu exista useri pentru aceasta lista</strong>
            </div>
          </div>
          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-secondary"
              data-bs-dismiss="modal"
            >
              Close
            </button>
          </div>
        </div>
      </div>
    </div>
    <!-- modal pentru stergere postare  -->
    <div
      class="modal fade"
      id="delete_post"
      tabindex="-1"
      role="dialog"
      aria-labelledby="modelTitleId"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Stergere postare</h5>
            <button
              type="button"
              class="btn-close"
              data-bs-dismiss="modal"
              aria-label="Close"
            ></button>
          </div>
          <div class="modal-body">Sigur ca doresti sa stergi postarea?</div>
          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-secondary"
              data-bs-dismiss="modal"
            >
              Close
            </button>
            <button
              type="button"
              class="btn btn-primary"
              data-bs-dismiss="modal"
              @click="delete_post(modal.post)"
            >
              Confirm
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { required } from "vuelidate/lib/validators";
// components
import friendItem from "./FriendItem.vue";
import postItem from "./Post.vue";
import commentsItems from "./Comments.vue";
// mixins
import ShakeAndFilter from "../../mixins/mixin1.vue";
import CRUD from "../../mixins/CrudPostMixin.vue";
import PostCard from "../../mixins/CardPost.vue";

export default {
  mixins: [ShakeAndFilter, CRUD, PostCard],
  props: [
    "type",
    "selected_user_id",
    "my_id",
    "GetFilters",
    "ClickFilter",
    "ResetFilters",
  ],
  components: { friendItem, postItem, commentsItems },
  data() {
    return {
      placeholders: true,
      my_avatar: "",
      modal: {
        users_list: [],
        post: {},
      },
      posts_info: [],
      ClickSearchPost: false,
      original_posts_info: [],
      post_card: {
        note: 0,
        images: [],
        text: "",
      },
    };
  },
  validations: {
    post_card: { text: { required } },
  },
  computed: {
    showInputPost() {
      return (
        ((this.my_id == this.selected_user_id || this.selected_user_id == 0) &&
          this.type == "post") ||
        (this.my_id != this.selected_user_id &&
          this.selected_user_id != 0 &&
          this.type == "reviews")
      );
    },
    // Pregatirea informatiei pentru filtru din componenta filtru
    PrepInfoForFilter: {
      get() {
        if (this.selected_user_id == 0 || this.type == "reviews") {
          let object = {};
          if (this.type == "reviews") {
            object.note = this.posts_info
              .map((item) => item.note)
              .filter((value, index, self) => self.indexOf(value) == index);
          }
          let list_keys = ["user_type", "city", "country"];
          list_keys.forEach((element) => {
            object[element] = this.posts_info
              .map((item) => item.user[element])
              .filter((value, index, self) => self.indexOf(value) == index);
          });
          return object;
        } else return;
      },
      set() {},
    },
  },
  watch: {
    ResetFilters: function (newVal) {
      if (newVal == 1) {
        this.posts_info = this.original_posts_info;
      }
      this.$emit("ResetResetFilters");
    },
    // cand primeste un filtru nou, acesta va actualiza afisarea informatiei cu postari
    ClickFilter: function (NewVal) {
      if (NewVal == 1) {
        this.posts_info = this.FilterReviewsPosts(
          this.GetFilters,
          this.original_posts_info
        );
        this.$emit("ResetClicker");
      }
    },
    // la orice schimbare a valorilor filtrului, acesta se va transmite mai sus pentru a actualiza componenta filtru
    PrepInfoForFilter: function () {
      this.$emit("send_filters", this.PrepInfoForFilter);
    },
  },
  mounted() {
    if (this.type == "reviews") {
      $("#nav-recenzii-tab").addClass("active");
    }
  },
  methods: {
    // for styles
    NoteColor(index) {
      if (index == 1) {
        if (this.post_card.note != index) {
          return "btn-outline-danger";
        } else return "btn-danger";
      }
      if (index > 1 && index < 4) {
        if (this.post_card.note != index) {
          return "btn-outline-warning";
        } else return "btn-warning";
      }
      if (index > 3 && index < 6) {
        if (this.post_card.note != index) {
          return "btn-outline-success";
        } else return "btn-success";
      }
    },
    // work with modals
    send_post(data) {
      this.modal.post = data;
    },
    modal_user_list(users) {
      this.modal.users_list = users;
    },
    modal_delete(data) {
      this.modal.post = data;
    },
  },
};
</script>