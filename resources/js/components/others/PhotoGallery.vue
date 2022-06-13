<template>
  <div>
    <div class="row">
      <div class="col-4 mb-3" v-for="image in images" :key="image.id">
        <a
          @click="modal.id = image.id"
          :href="'#image_' + index_gallery"
          data-bs-toggle="modal"
        >
          <img class="rounded w-100 h-100" :src="image.path" alt="" />
        </a>
      </div>
    </div>

    <!-- Modal for photo gallery -->
    <div
      class="modal fade"
      :id="'image_' + index_gallery"
      tabindex="-1"
      role="dialog"
      aria-labelledby="modelTitleId"
      aria-hidden="true"
    >
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Photo gallery</h5>
            <button
              type="button"
              class="btn-close"
              data-bs-dismiss="modal"
              aria-label="Close"
            ></button>
          </div>
          <div class="modal-body p-0">
            <div
              :id="'carousel_photo_gallery' + index_gallery"
              class="carousel slide"
              data-bs-touch="false"
              data-bs-interval="false"
            >
              <div class="carousel-inner">
                <div
                  v-for="image in images"
                  :key="image.id"
                  class="carousel-item"
                  :class="{ active: image.id == modal.id }"
                >
                  <img :src="image.path" class="d-block w-100" alt="..." />
                  <div
                    class="
                      small
                      text-end
                      mb-0
                      text-primary
                      fw-bold
                      opacity-75
                      p-3
                    "
                  >
                    {{ convert_timestamp(image.updated_at, "date_and_time") }}
                  </div>
                </div>
              </div>
              <button
                class="carousel-control-prev"
                type="button"
                :data-bs-target="'#carousel_photo_gallery' + index_gallery"
                data-bs-slide="prev"
              >
                <span
                  class="carousel-control-prev-icon"
                  aria-hidden="true"
                ></span>
                <span class="visually-hidden">Previous</span>
              </button>
              <button
                class="carousel-control-next"
                type="button"
                :data-bs-target="'#carousel_photo_gallery' + index_gallery"
                data-bs-slide="next"
              >
                <span
                  class="carousel-control-next-icon"
                  aria-hidden="true"
                ></span>
                <span class="visually-hidden">Next</span>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import convertDate from "../../mixins/mixin1.vue";

export default {
  mixins: [convertDate],
  props: ["images", "index_gallery"],
  data() {
    return {
      modal: { id: "" },
    };
  },
};
</script>

<style>
img {
  object-fit: cover;
}
</style>