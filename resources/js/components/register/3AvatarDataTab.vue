<template>
  <div class="form3">
    <div class="card-header bg-info text-white">Avatar</div>
    <div class="card-body">
      <div
        :class="[errorForm3 > 0 ? '' : 'd-none']"
        class="alert alert-warning d-flex justify-content-center"
        role="alert"
      >
        <strong>Nu uita sa incarci un avatar!</strong>
      </div>
      <p class="mb-0 fw-bold text-secondary">Alege avatarul:</p>
      <div class="hstack gap-2 justify-content-center">
        <img
          :src="userData['sourceAvatarPreload']"
          alt=""
          id="avatar-register"
          width="150px"
          height="150px"
          class="cover rounded-circle"
        />
        <div class="d-flex flex-column justify-content-center gap-2">
          <div class="">
            <div class="hstack">
              <input
                type="file"
                required
                @change="getFileSource"
                ref="inputFile"
                name="avatar-image"
                accept=".jpg, .png, .jpeg"
                class="text-secondary d-none custom-file-input form-control"
                id="avatar-image"
                aria-describedby="avatar-image"
              />
              <button
                @click="$refs.inputFile.click()"
                class="btn hstack gap-1 btn-primary"
                type="button"
                name="fake-upload"
              >
                <i class="bi bi-image-fill"></i>
                Incarca
              </button>
            </div>
            <small class="form-text text-muted"> * PNG, JPEG, JPG </small>
          </div>
          <div class="">
            <button
              type="button"
              name="sterge-avatar"
              class="btn btn-danger"
              :disabled="disabledDeleteButton"
              @click="resetAvatar"
            >
              <i class="fa fa-trash" aria-hidden="true"></i>
              Sterge
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      errorForm3: 1,
      userData: {
        sourceAvatarPreload: "/img/no-avatar.png",
      },
    };
  },
  watch: {
    errorForm3: function (newVal) {
      this.$emit("showErrorsForm3", newVal);
    },
  },
  methods: {
    getFileSource(event) {
      this.userData["sourceAvatarPreload"] = URL.createObjectURL(
        event.target.files[0]
      );
      this.errorForm3--;
    },
    resetAvatar() {
      this.userData["sourceAvatarPreload"] = "/img/no-avatar.png";
      this.errorForm3++;
    },
  },
  computed: {
    disabledDeleteButton() {
      if (this.userData["sourceAvatarPreload"] != "/img/no-avatar.png")
        return false;
      else return true;
    },
  },
};
</script>