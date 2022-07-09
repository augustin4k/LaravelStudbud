<template>
  <div>
    <!-- canvas -->
    <div
      class="offcanvas offcanvas-start w-75 h-100 bg-light"
      tabindex="-1"
      id="offcanvasMobileNavigation"
      aria-labelledby="offcanvasMobileNavigationLabel"
    >
      <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="offcanvasMobileNavigationLabel">
          Menu
        </h5>
        <button
          type="button"
          class="btn-close text-reset"
          data-bs-dismiss="offcanvas"
          aria-label="Close"
        ></button>
      </div>
      <div class="offcanvas-body"><v-left-bar></v-left-bar></div>
    </div>

    <nav class="navbar navbar-light bg-white">
      <div class="container">
        <div class="w-100">
          <div class="hstack" :class="[ScreenMoreMd ? 'row' : 'gap-2']">
            <div :class="{ 'col-3': ScreenMoreMd }">
              <a
                :data-bs-toggle="[ScreenMoreMd == false ? 'offcanvas' : '']"
                :aria-controls="[
                  ScreenMoreMd == false ? 'offcanvasMobileNavigation' : '',
                ]"
                class="navbar-brand m-0"
                :class="{
                  'text-white btn btn-secondary btn-secondary':
                    ScreenMoreMd == false,
                }"
                :href="[ScreenMoreMd ? '/feed' : '#offcanvasMobileNavigation']"
              >
                <i class="fa fa-graduation-cap" aria-hidden="true"></i>
                <span v-if="ScreenMoreMd == false"> SB </span>
                <span v-else> StudBud </span>
              </a>
            </div>
            <div :class="[ScreenMoreMd ? 'col-9' : 'w-100']">
              <div class="hstack" :class="[ScreenMoreMd ? 'row' : 'gap-2']">
                <div :class="[ScreenMoreMd ? 'col-8' : 'w-100']">
                  <div class="d-flex gap-2">
                    <div class="input-group">
                      <input
                        type="text"
                        placeholder="Cauta persoana..."
                        class="form-control form-control-sm"
                        v-model="name_search"
                      />
                      <a
                        :href="'/search?name=' + name_search"
                        class="btn btn-sm btn-secondary"
                      >
                        <i class="bi bi-search"></i>
                      </a>
                    </div>
                    <div>
                      <button
                        class="btn h-100 btn-sm btn-dark position-relative"
                        type="button"
                        data-bs-toggle="offcanvas"
                        data-bs-target="#offcanvasNotification"
                        aria-controls="offcanvasNotification"
                      >
                        <i class="bi bi-bell-fill"></i>
                        <span
                          class="
                            position-absolute
                            top-0
                            start-100
                            translate-middle
                            badge
                            rounded-pill
                            bg-danger
                          "
                        >
                          99+
                          <span class="visually-hidden">unread messages</span>
                        </span>
                      </button>
                      <div
                        class="
                          offcanvas offcanvas-top
                          rounded
                          h-75
                          col-6
                          offset-3
                        "
                        tabindex="-1"
                        id="offcanvasNotification"
                        aria-labelledby="offcanvasNotificationLabel"
                      >
                        <div class="offcanvas-header text-white bg-primary">
                          <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-bell-fill"></i>
                            <small class="fw-bold">Notificari</small>
                          </div>
                          <button
                            type="button"
                            class="btn-close text-reset"
                            data-bs-dismiss="offcanvas"
                            aria-label="Close"
                          ></button>
                        </div>
                        <div class="offcanvas-body px-0 small"></div>
                        <div class="card-footer bg-light text-secondary">
                          <small class="text-muted">12 notificari</small>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div
                  :class="{ 'col-4 right-wrapper': ScreenMoreMd }"
                  class="text-end"
                >
                  <div class="dropdown">
                    <button
                      class="btn py-0 btn-light w-auto dropdown-toggle"
                      type="button"
                      id="triggerId"
                      data-toggle="dropdown"
                      aria-haspopup="true"
                      aria-expanded="false"
                    >
                      <img
                        :src="user.avatar_path"
                        width="37.6px"
                        class="rounded avatar"
                      />
                    </button>
                    <div
                      class="dropdown-menu end-0"
                      aria-labelledby="triggerId"
                    >
                      <div class="list-group list-group-flush rounded">
                        <div
                          class="list-group-item bg-primary text-white fw-bold"
                        >
                          {{ user.name + " " + user.surname }}
                        </div>
                        <a
                          href="/timeline"
                          class="
                            list-group-item list-group-item-action
                            d-flex
                            align-items-center
                            gap-2
                          "
                          aria-current="true"
                        >
                          <i class="bi bi-person-circle"></i>
                          <small>Profilul meu</small>
                        </a>
                        <a
                          href="/settings"
                          class="
                            list-group-item list-group-item-action
                            d-flex
                            align-items-center
                            gap-2
                          "
                        >
                          <i class="bi bi-gear-fill"></i>
                          <small>Settings</small>
                        </a>
                        <a
                          href="/logout"
                          class="
                            list-group-item list-group-item-action
                            d-flex
                            align-items-center
                            gap-2
                            text-danger
                          "
                        >
                          <i class="bi bi-box-arrow-right"></i>
                          <small>Logout</small>
                        </a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </nav>
  </div>
</template>

<script>
export default {
  props: ["user"],
  data() {
    return {
      name_search: "",
      screen_width: screen.width,
    };
  },
  computed: {
    ScreenMoreMd() {
      return this.screen_width >= 768;
    },
  },
  created() {
    window.addEventListener("resize", this.myEventHandler);
  },
  destroyed() {
    window.removeEventListener("resize", this.myEventHandler);
  },
  methods: {
    myEventHandler(e) {
      this.screen_width = screen.width;
    },
  },
};
</script>