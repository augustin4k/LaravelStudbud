<template>
  <div
    class="
      offcanvas offcanvas-bottom
      col-12
      offset-0
      col-md-10
      offset-md-1
      col-lg-6
      offset-lg-3
      rounded
    "
    tabindex="-1"
    id="commentsCanvas"
    aria-labelledby="commentsCanvasLabel"
  >
    <div
      class="
        offcanvas-header
        bg-light
        rounded
        d-flex
        border
        justify-content-end
        position-relative
      "
    >
      <p
        class="
          position-absolute
          top-50
          start-50
          translate-middle
          offcanvas-title
          mb-0
          opacity-75
        "
        id="offcanvasBottomLabel"
      >
        Comentarii
        <template v-if="comments.length > 0">- {{ comments.length }}</template>
      </p>
      <button
        type="button"
        class="btn-close text-reset"
        data-bs-dismiss="offcanvas"
        aria-label="Close"
      ></button>
    </div>
    <div class="offcanvas-body p-0">
      <ul v-if="placeholders" class="list-unstyled mb-0">
        <li
          v-for="index in comments.length"
          :key="index"
          class="d-flex gap-3 p-3"
        >
          <img
            class="rounded"
            src="/img/gray-color-solid-background-1920x1080.png"
            alt=""
            width="40"
            height="40"
          />
          <div class="vstack">
            <div class="placeholder-glow">
              <h6 class="placeholder col-4 placeholder"></h6>
              <p class="placeholder placeholder-xs col-11"></p>
              <p class="placeholder placeholder-xs col-12"></p>
              <p class="placeholder placeholder-xs col-10"></p>
            </div>
            <div class="placholder-glow">
              <button
                class="btn btn-sm btn-secondary disabled placeholder col-1"
              ></button>
              <button
                class="btn btn-sm btn-secondary disabled placeholder col-1"
              ></button>
              <button
                class="btn btn-sm btn-secondary disabled placeholder col-1"
              ></button>
              <div class="placeholder col-3 offset-5"></div>
            </div>
          </div>
        </li>
      </ul>
      <template v-else-if="comments && comments.length > 0">
        <ul class="my-3 list-unstyled">
          <li v-for="comment in comments" :key="comment.id">
            <div class="px-3 d-flex gap-1 py-2 border-bottom">
              <div class="rounded">
                <a class="d-flex">
                  <img
                    class="rounded"
                    :src="comment.user.avatar_path"
                    alt=""
                    width="40"
                  />
                </a>
              </div>
              <div class="vstack">
                <div class="d-flex justify-content-between">
                  <p class="fw-bold mb-0">
                    {{ comment.user.name + " " + comment.user.surname }}
                  </p>
                  <button
                    v-if="my_id == selected_user_id"
                    @click="delete_comment(comment, 'comment')"
                    class="btn btn-sm btn-danger"
                  >
                    <i class="bi bi-trash3-fill"></i>
                  </button>
                </div>
                <p class="mb-1 opacity-75 text-break">
                  {{ comment.message }}
                </p>
                <div class="hstack justify-content-between gap-2">
                  <div class="hstack gap-2">
                    <div class="btn-group">
                      <b-button
                        @click="
                          comment_reply = comment;
                          message =
                            comment.user.name +
                            ' ' +
                            comment.user.surname +
                            ', ';
                          $refs.input_message.focus();
                        "
                        class="btn btn-secondary btn-sm"
                        :class="{
                          disabled: dont_show_blocked(comment) == false,
                        }"
                      >
                        Reply
                        <template v-if="comment.replies.length > 0">
                          ({{ comment.replies.length }})
                        </template>
                      </b-button>
                      <button
                        v-if="comment.replies && comment.replies.length > 0"
                        v-b-toggle="'reply_to_comment' + comment.id"
                        class="btn btn-secondary btn-sm"
                      >
                        <i class="bi bi-caret-down-fill"></i>
                      </button>
                    </div>
                    <div class="btn-group">
                      <button
                        :class="[
                          comment.liked_by_me == 1
                            ? 'btn-danger'
                            : 'btn-secondary',
                        ]"
                        @click="comment = like('like', 'comentarii', comment)"
                        class="btn btn-sm"
                      >
                        <i class="fa-solid fa-thumbs-up"></i>
                        <template v-if="comment.likes.length > 0">
                          ({{ comment.likes.length }})
                        </template>
                      </button>
                      <button
                        data-bs-toggle="modal"
                        :data-bs-target="'#users_list'"
                        @click="$emit('modal_user_list', comment.likes)"
                        class="btn btn-secondary btn-sm"
                      >
                        <i class="bi bi-caret-down-fill"></i>
                      </button>
                    </div>
                    <div class="btn-group">
                      <button
                        @click="
                          comment = like('dislike', 'comentarii', comment)
                        "
                        :class="[
                          comment.liked_by_me == -1
                            ? 'btn-danger'
                            : 'btn-secondary',
                        ]"
                        class="btn btn-sm"
                      >
                        <i class="fa-solid fa-thumbs-down"></i>
                        <template v-if="comment.dislikes.length > 0">
                          ({{ comment.dislikes.length }})
                        </template>
                      </button>
                      <button
                        data-bs-toggle="modal"
                        :data-bs-target="'#users_list'"
                        @click="$emit('modal_user_list', comment.dislikes)"
                        class="btn btn-secondary btn-sm"
                      >
                        <i class="bi bi-caret-down-fill"></i>
                      </button>
                    </div>
                  </div>
                  <small class="opacity-50 text-nowrap">
                    {{ convert_timestamp(comment.updated_at, "date_and_time") }}
                  </small>
                </div>
              </div>
            </div>
            <b-collapse
              :id="'reply_to_comment' + comment.id"
              v-if="comment.replies && comment.replies.length > 0"
            >
              <li
                class="bg-light d-flex gap-1 py-1 px-3"
                v-for="reply in comment.replies"
                :key="reply.id"
              >
                <div class="rounded">
                  <a class="d-flex">
                    <div class="d-flex" style="width: 15px"></div>
                    <img
                      class="rounded"
                      :src="reply.user.avatar_path"
                      alt=""
                      width="25"
                    />
                  </a>
                </div>
                <div class="vstack">
                  <div class="d-flex justify-content-between">
                    <h6 class="mb-0">
                      {{ reply.user.name + " " + reply.user.surname }}
                    </h6>
                    <button
                      v-if="my_id == selected_user_id"
                      @click="delete_comment(reply, 'reply')"
                      class="btn btn-sm btn-danger"
                    >
                      <i class="bi bi-trash3-fill"></i>
                    </button>
                  </div>
                  <p class="mb-1 opacity-75">
                    {{ reply.message }}
                  </p>
                  <div class="hstack justify-content-between gap-2">
                    <div class="hstack gap-2">
                      <div class="btn-group">
                        <button
                          @click="reply = like('like', 'comentarii', reply)"
                          :class="[
                            reply.liked_by_me == 1
                              ? 'btn-danger'
                              : 'btn-secondary',
                          ]"
                          class="btn btn-secondary btn-sm"
                        >
                          <i class="fa-solid fa-thumbs-up"></i>
                          <template v-if="reply.likes.length > 0">
                            ({{ reply.likes.length }})
                          </template>
                        </button>
                        <button
                          data-bs-toggle="modal"
                          :data-bs-target="'#users_list'"
                          @click="$emit('modal_user_list', reply.likes)"
                          class="btn btn-secondary btn-sm"
                        >
                          <i class="bi bi-caret-down-fill"></i>
                        </button>
                      </div>
                      <div class="btn-group">
                        <button
                          @click="reply = like('dislike', 'comentarii', reply)"
                          :class="[
                            reply.liked_by_me == -1
                              ? 'btn-danger'
                              : 'btn-secondary',
                          ]"
                          class="btn btn-sm"
                        >
                          <i class="fa-solid fa-thumbs-down"></i>
                          <template v-if="reply.dislikes.length > 0">
                            ({{ reply.dislikes.length }})
                          </template>
                        </button>
                        <button
                          data-bs-toggle="modal"
                          :data-bs-target="'#users_list'"
                          @click="$emit('modal_user_list', reply.dislikes)"
                          class="btn btn-secondary btn-sm"
                        >
                          <i class="bi bi-caret-down-fill"></i>
                        </button>
                      </div>
                    </div>
                    <small class="opacity-50 text-nowrap">
                      {{ convert_timestamp(reply.updated_at, "date_and_time") }}
                    </small>
                  </div>
                </div>
              </li>
            </b-collapse>
          </li>
        </ul>
      </template>
      <div v-else class="p-3">
        <div class="alert alert-primary" role="alert">
          <strong>Inca nu exista comentarii pentru aceasta postare!</strong>
        </div>
      </div>
    </div>
    <div
      v-if="my_avatar && my_avatar.length > 0"
      class="offcanvas-footer p-3 border hstack gap-1 bg-light"
    >
      <a href="" class="rounded">
        <img class="rounded" :src="my_avatar" alt="" height="37.6px" />
      </a>
      <input
        ref="input_message"
        class="form-control"
        placeholder="Lasa aici un comentariu..."
        v-model="$v.message.$model"
      />
      <button
        @click="send_message()"
        :disabled="$v.message.required == false"
        class="btn btn-primary"
      >
        <i class="bi bi-send-fill"></i>
      </button>
    </div>
  </div>
</template>

<script>
import { required } from "vuelidate/lib/validators";
import ConvertAndLike from "../../mixins/mixin1.vue";

export default {
  props: ["get_post_id", "my_avatar", "my_id", "selected_user_id"],
  mixins: [ConvertAndLike],
  data() {
    return {
      message: "",
      comment_reply: [],
      comments: [],
      placeholders: true,
    };
  },
  validations: {
    message: { required },
  },
  computed: {
    post_id() {
      return this.get_post_id;
    },
  },
  watch: {
    post_id(newVal) {
      this.placeholders = true;
      axios
        .post("api/get_comments", {
          post_id: newVal,
        })
        .then((response) => {
          this.comments = response.data.comments;
          setTimeout(() => {
            this.placeholders = false;
          }, 1000);
        });
    },
  },
  methods: {
    delete_comment(comment, type) {
      axios
        .post("api/delete_comment", {
          id: comment.id,
        })
        .then((response) => {
          if (type == "comment") {
            this.$delete(
              this.comments,
              this.comments.indexOf(
                this.comments.find((item) => item.id == comment.id)
              )
            );
          } else {
            this.comments.forEach((element) => {
              this.$delete(
                element.replies,
                element.replies.indexOf(
                  element.replies.find((item) => item.id == comment.id)
                )
              );
            });
          }
        });
    },
    send_message() {
      axios
        .post("api/insert_comment", {
          message: this.message,
          post_id: this.post_id,
          id_reply: this.comment_reply.id,
        })
        .then((response) => {
          if (this.comment_reply.length == 0) {
            this.comments.unshift(response.data.comment);
          } else {
            this.comments
              .find((item) => item.id == this.comment_reply.id)
              .replies.unshift(response.data.comment);
          }
          this.message = "";
          this.comment_reply = [];
        })
        .catch((error) => {
          this.shake = true;
          setTimeout(() => {
            this.shake = false;
          }, 1500);
        });
    },
  },
};
</script>