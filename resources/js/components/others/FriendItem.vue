<template>
  <div class="mb-3 pb-3 border-bottom">
    <div class="d-flex align-items-start">
      <a :href="'/timeline?id=' + user.id">
        <img
          class="rounded me-1"
          src="https://bootdey.com/img/Content/avatar/avatar6.png"
          alt=""
          width="50px"
        />
      </a>
      <div class="d-flex flex-column w-100">
        <div class="d-flex justify-content-between flex-wrap gap-2">
          <p
            class="
              m-0
              small
              text-primary
              fw-bold
              text-nowrap
              vstack
              align-items-start
            "
          >
            <span> {{ user.name }} </span>
            <span class="badge rounded-pill bg-success">Online</span>
          </p>
          <div
            class="hstack gap-1"
            v-if="im_user == false || user_login == false"
          >
            <template v-if="user.status == 'not_friends'">
              <button
                @click="edit_friendship('invited_by_me')"
                class="btn btn-sm btn-outline-primary"
              >
                <i class="bi bi-person-plus-fill"></i>
              </button>
            </template>
            <template v-else-if="user.status == 'friends'">
              <button
                class="btn btn-sm btn-danger"
                @click="edit_friendship('not_friends')"
              >
                <i class="bi bi-person-dash-fill"></i>
              </button>
            </template>
            <template v-else-if="user.status == 'invite_me'">
              <button
                class="btn btn-sm btn-primary"
                @click="edit_friendship('friends')"
              >
                <i class="bi bi-person-lines-fill"></i>
              </button>
              <button
                class="btn btn-sm btn-danger"
                @click="edit_friendship('not_friends')"
              >
                <i class="bi bi-person-lines-fill"></i>
              </button>
            </template>
            <template v-else-if="user.status == 'invited_by_me'">
              <button
                @click="edit_friendship('not_friends')"
                class="btn btn-sm btn-primary"
              >
                <i class="bi bi-person-plus-fill"></i>
              </button>
            </template>
            <template v-else-if="user.status == 'block_me'">
              <button class="btn btn-sm btn-secondary" disabled>
                <i class="bi bi-lock-fill"></i>
              </button>
            </template>
            <template v-else-if="user.status == 'blocked_by_me'">
              <button
                class="btn btn-sm btn-outline-secondary"
                @click="edit_friendship('not_friends')"
              >
                <i class="bi bi-unlock-fill"></i>
              </button>
            </template>
          </div>
        </div>
        <template v-if="user.common_friends > 0">
          <small class="text-secondary fw-bold">
            {{ user.common_friends }} prieteni comuni
          </small>
        </template>
        <template v-else-if="im_user == true">
          <small class="text-secondary fw-bold">It's me</small>
        </template>
        <template v-else
          ><small class="text-secondary">Niciun prieten comun</small></template
        >
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ["item", "im_user", "user_login"],
  data() {
    return {
      user: this.item,
    };
  },
  methods: {
    edit_friendship(action) {
      axios
        .post("api/edit-friendship", { user: this.user, action: action })
        .then((response) => {
          this.user["status"] = response.data;
        });
    },
  },
};
</script>