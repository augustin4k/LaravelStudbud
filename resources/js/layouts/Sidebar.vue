<template>
  <div
    class="
      d-flex
      flex-column flex-shrink-0
      text-white
      justify-content-between
      p-3
      bg-dark
    "
    :class="TruncateLeftBar ? 'h-100' : 'offcanvas offcanvas-start w-75'"
    tabindex="-1"
    id="offcanvasSidebar"
    aria-labelledby="offcanvasSidebarLabel"
  >
    <div>
      <strong class="d-flex justify-content-center position-relative">
        <a href="/timeline" class="text-white text-decoration-none">
          <i class="fa fa-graduation-cap" aria-hidden="true"></i>
          StudBud
        </a>
        <button
          v-if="size_for_canvas == false"
          type="button"
          class="btn-close btn-close-white position-absolute top-0 end-0"
          data-bs-dismiss="offcanvas"
          aria-label="Close"
        ></button>
      </strong>
      <hr />
      <div class="list-group">
        <a
          v-for="link in links"
          :key="link.name"
          :href="link.link"
          :class="{ active: currentLocation.includes(link.link) }"
          class="list-group-item list-group-item-action list-group-item-dark"
          aria-current="true"
        >
          <i :class="link.icon"></i>
          {{ link.name }}
        </a>
      </div>
    </div>
    <div>
      <hr />
      <div class="hstack justify-content-between">
        <a
          href="/timeline"
          class="hstack gap-1 text-decoration-none text-white"
        >
          <img
            :src="user_info.avatar_path"
            alt=""
            width="32"
            height="32"
            class="rounded"
          />
          <small>{{ user_info.name + " " + user_info.surname }}</small>
        </a>
        <a href="/logout" class="btn btn-danger btn-sm">Logout</a>
      </div>
    </div>
  </div>
</template>

<script>
import size from "../mixins/mixin1.vue";

export default {
  mixins: [size],
  props: ["user_info"],
  data() {
    return {
      currentLocation: "",
      links: [
        {
          name: "Users",
          link: "/admin-users",
          icon: "bi bi-list-columns-reverse",
        },
        {
          name: "Notifications",
          link: "/admin-notifications",
          icon: "bi bi-app-indicator",
        },
      ],
    };
  },
  computed: {
    TruncateLeftBar() {
      return this.screen_width >= 992;
    },
  },
  mounted() {
    this.currentLocation = $(location).attr("href");
  },
};
</script>