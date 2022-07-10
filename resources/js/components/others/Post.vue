<template>
  <div class="card postare shadow-sm mb-3">
    <div class="card-header gap-1 d-flex justify-content-between">
      <div class="d-flex gap-1">
        <img class="img-xs rounded me-1" :src="post.user.avatar_path" alt="" />
        <div class="d-flex flex-column small opacity-75">
          <b>{{ post.user.name + " " + post.user.surname }} </b>
          <p class="mb-0">
            {{ convert_timestamp(post.updated_at, "date_and_time") }}
          </p>
        </div>
      </div>
      <div>
        <div class="hstack gap-1">
          <button class="btn btn-sm btn-secondary">
            <i class="bi bi-clipboard"></i>
          </button>
          <template v-if="my_id == post.user.id">
            <a
              href="#profile-page"
              class="btn btn-sm btn-primary"
              @click="$emit('populate_post_card', post)"
            >
              <i class="bi bi-pencil-square"></i>
            </a>
            <button
              class="btn btn-sm btn-danger"
              data-bs-toggle="modal"
              :data-bs-target="'#delete_post'"
              @click="$emit('modal_delete', post)"
            >
              <i class="bi bi-trash3-fill"></i>
            </button>
          </template>
        </div>
      </div>
    </div>
    <template>
      <div class="card-body">
        <div class="position-relative">
          <photo-gallery
            :index_gallery="1"
            :images="post.images.slice(0, to)"
          ></photo-gallery>
          <b-button
            @click="to = Math.abs(to - 3)"
            v-if="post.images && post.images.length > 3"
            class="position-absolute bottom-0 end-0 shadow mb-3"
            v-b-toggle="'show-more-' + post.id"
            variant="primary"
            >Extinde<i class="bi bi-caret-down-fill"></i
          ></b-button>
          <b-collapse
            v-if="post.images && post.images.length > 3"
            :id="'show-more-' + post.id"
          >
            <photo-gallery
              :index_gallery="2"
              :images="post.images.slice(to, post.images.length)"
            ></photo-gallery>
          </b-collapse>
        </div>
        <small class="card-text text-justify">{{ post.text }}</small>
      </div>
      <div class="card-footer hstack justify-content-between">
        <div>
          <div class="d-flex gap-1">
            <div class="btn-group">
              <button
                :disabled="!(my_id > 0)"
                @click="post = like('like', 'posts_reviews', post)"
                class="btn btn-sm"
                :class="[post.liked_by_me == 1 ? 'btn-danger' : 'btn-primary']"
              >
                <i class="fa-solid fa-thumbs-up"></i>
                <template v-if="post.likes && post.likes.length">
                  [{{ post.likes.length }}]
                </template>
              </button>
              <button
                @click="$emit('modal_user_list', post.likes, 'Like-uri')"
                data-bs-toggle="modal"
                :data-bs-target="'#users_list'"
                class="btn btn-primary btn-sm"
              >
                <i class="bi bi-caret-down-fill"></i>
              </button>
            </div>
            <div class="btn-group">
              <button
                :disabled="!(my_id > 0)"
                @click="post = like('dislike', 'posts_reviews', post)"
                class="btn btn-sm btn-primary"
                :class="[post.liked_by_me == -1 ? 'btn-danger' : 'btn-primary']"
              >
                <i class="fa-solid fa-thumbs-down"> </i>
                <template v-if="post.dislikes && post.dislikes.length">
                  [{{ post.dislikes.length }}]
                </template>
              </button>
              <button
                data-bs-toggle="modal"
                :data-bs-target="'#users_list'"
                @click="$emit('modal_user_list', post.dislikes, 'Dislike-uri')"
                class="btn btn-sm btn-primary"
              >
                <i class="bi bi-caret-down-fill"></i>
              </button>
            </div>
            <button
              class="btn btn-sm btn-primary btn-primary"
              type="button"
              @click="$emit('send_post', post)"
              data-bs-toggle="offcanvas"
              data-bs-target="#commentsCanvas"
              aria-controls="commentsCanvas"
            >
              <i class="fa-solid fa-message"></i>
              <template v-if="post.comments && post.comments.length > 0">
                [{{ post.comments.length }}]
              </template>
            </button>
          </div>
        </div>
        <button
          v-if="type == 'reviews'"
          :class="{
            'btn-danger': post.note == 1,
            'btn-warning': post.note > 1 && post.note < 4,
            'btn-success': post.note > 3 && post.note < 6,
          }"
          class="btn disabled"
        >
          <i class="bi bi-star-fill"></i> {{ post.note }}
        </button>
      </div>
    </template>
  </div>
</template>

<script>
import photoGallery from "./PhotoGallery.vue";
import ConvertAndLike from "../../mixins/mixin1.vue";

export default {
  mixins: [ConvertAndLike],
  props: ["type", "get_post", "my_id", "selected_user_id"],
  components: { photoGallery },
  data() {
    return {
      to: 3,
    };
  },
  computed: {
    post: {
      get: function () {
        return this.get_post;
      },
      set: function (newVal) {},
    },
  },
};
</script>