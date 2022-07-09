<template>
  <div class="hstack gap-1 rounded">
    <template v-if="get_status == 'not_friends'">
      <button
        @click="edit_friendship('invited_by_me')"
        class="btn btn-sm btn-outline-primary"
      >
        <i class="bi bi-person-plus-fill"></i>
      </button>
    </template>
    <template v-else-if="get_status == 'friends'">
      <button
        class="btn btn-sm btn-danger"
        @click="edit_friendship('not_friends')"
      >
        <i class="bi bi-person-dash-fill"></i>
      </button>
    </template>
    <template v-else-if="get_status == 'invite_me'">
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
    <template v-else-if="get_status == 'invited_by_me'">
      <button
        @click="edit_friendship('not_friends')"
        class="btn btn-sm btn-primary"
      >
        <i class="bi bi-person-plus-fill"></i>
      </button>
    </template>
    <button
      v-else-if="get_status == 'blocked_by_me'"
      class="btn btn-sm btn-secondary"
      @click="edit_friendship('not_friends')"
    >
      <i class="bi bi-unlock-fill"></i>
    </button>
    <button
      @click="edit_friendship('block')"
      v-if="get_status != 'blocked_by_me'"
      class="btn btn-sm btn-outline-secondary"
    >
      <i class="bi bi-lock-fill"></i>
    </button>
  </div>
</template>

<script>
export default {
  props: ["status", "user"],
  data() {
    return {
      get_status: this.status,
    };
  },
  mounted() {},
  methods: {
    edit_friendship(action) {
      axios
        .post("api/edit-friendship", { user: this.user, action: action })
        .then((response) => {
          //   this.get_status = response.data;
          window.location.reload();
        });
    },
  },
};
</script>