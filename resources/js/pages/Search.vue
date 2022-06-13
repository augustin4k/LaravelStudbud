<template>
  <div class="row">
    <div class="col-12 col-md-8 col-lg-12 col-xl-8 left-wrapper">
      <div class="mb-3 card shadow-sm">
        <div class="card-header d-flex gap-2">
          <!-- <div class="form-group d-flex align-items-center gap-2"> -->
          <label
            for="adv_search"
            class="
              d-none d-md-flex d-lg-none d-xl-flex
              align-items-center
              fw-bold
              form-label
              m-0
              text-secondary
            "
            >Cautare:</label
          >
          <div v-if="size_for_canvas == false">
            <!-- Button trigger modal -->
            <button
              class="btn btn-dark hstack gap-1"
              type="button"
              data-bs-toggle="offcanvas"
              data-bs-target="#offcanvasFiltru"
              aria-controls="offcanvasFiltru"
            >
              <i class="bi bi-funnel-fill"></i>
              <span class=""><small class="fw-bold">Filtru</small></span>
            </button>
          </div>
          <div class="input-group">
            <input
              type="text"
              class="form-control form-control-sm"
              placeholder="Introdu persoana..."
              v-model="search"
            />
            <button
              @click="
                filter.name_search = search;
                users = filter_info(filter, get_users);
              "
              class="btn btn-secondary"
            >
              <i class="bi bi-search"></i>
            </button>
          </div>
        </div>
        <div class="px-3 pt-3 container-for-list-friends">
          <div
            class="list-friends"
            :class="[
              users.length == 0
                ? 'd-flex align-items-center justify-content-center'
                : 'row',
            ]"
          >
            <div
              v-if="users.length == 0"
              class="
                py-5
                d-flex
                flex-column
                gap-2
                text-secondary
                fw-light
                text-center
              "
            >
              <div class="alert alert-secondary text-center" role="alert">
                <strong> Useri cu asa caracteristici nu exista... </strong>
                <i class="bi bi-emoji-frown-fill"></i>
              </div>
            </div>
            <div
              v-else
              class="col-12 col-sm-6"
              v-for="user in users"
              :key="user.id"
            >
              <friend-item :im_user="false" :item="user"></friend-item>
            </div>
          </div>
        </div>
      </div>
    </div>
    <filter-component
      @send_filters="get_filters"
      @reset_filters="reset_filters"
      :GetInfoForFilter="prep_filter"
    ></filter-component>
  </div>
</template>

<script>
import filterComponent from "../components/others/FilterSearch.vue";
import friendItem from "../components/others/FriendItem.vue";
import Filter from "../mixins/mixin1.vue";

export default {
  mixins: [Filter],
  components: { filterComponent, friendItem },
  props: ["get_users", "name_search"],
  data() {
    return {
      search: this.name_search,
      users: [],
      filter: { name_search: this.search },
    };
  },
  computed: {
    prep_filter() {
      let list_keys = ["universities", "user_type", "city", "country"];
      let object = {};
      list_keys.forEach((element) => {
        object[element] = this.users
          .map((item) => item[element])
          .filter((value, index, self) => self.indexOf(value) == index);
      });
      return object;
    },
  },
  mounted() {
    this.users = this.filter_info(this.filter, this.get_users);
  },
  methods: {
    reset_filters() {
      this.filter = [];
      // default filter keys with value
      this.filter.orderBy = "prieteni_desc";
      //
      this.users = this.filter_info(this.filters, this.get_users);
    },
    get_filters(filter) {
      this.filter = filter;
      this.filter.name_search = this.search;
      this.users = this.filter_info(filter, this.get_users);
    },
  },
};
</script>