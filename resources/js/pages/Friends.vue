<template>
  <div class="row">
    <div class="col-12 col-md-8 left-wrapper">
      <template v-if="im_user == true">
        <div class="card mb-3" v-if="users_invited_by_me.length > 0">
          <div class="card-header p-0 bg-white">
            <b-button
              class="w-100 hstack gap-2 justify-content-start"
              variant="primary"
              v-b-toggle.invitatii-prietenie
            >
              <i class="bi bi-person-plus-fill"></i>
              Invitatii in prieteni ({{ users_invited_by_me.length }})</b-button
            >
          </div>
          <b-collapse class="card-body pb-0" id="invitatii-prietenie">
            <!-- pentru lista -->
            <ul class="list-unstyled mb-0 row">
              <li
                v-for="user in users_invited_by_me"
                :key="user.id"
                class="col-12 col-sm-6 col-lg-12 col-xl-6"
              >
                <friend-item :item="user" :im_user="false"></friend-item>
              </li>
            </ul>
          </b-collapse>
        </div>
        <div class="card mb-3" v-if="users_invite_me.length > 0">
          <div class="card-header p-0 bg-white">
            <b-button
              class="w-100 hstack gap-2 justify-content-start"
              variant="primary"
              v-b-toggle.cereri-prietenie
            >
              <i class="bi bi-person-lines-fill"></i>

              Cereri de prietenie</b-button
            >
          </div>
          <b-collapse class="card-body pb-0" id="cereri-prietenie">
            <!-- pentru lista -->
            <ul class="list-unstyled mb-0 row">
              <li
                v-for="user in users_invite_me"
                :key="user.id"
                class="col-12 col-sm-6 col-lg-12 col-xl-6"
              >
                <friend-item :item="user" :im_user="false"></friend-item>
              </li>
            </ul>
          </b-collapse>
        </div>

        <div v-if="users_blocked_by_me.length > 0" class="card mb-3">
          <div class="card-header p-0 bg-white">
            <b-button
              class="w-100 hstack gap-2 justify-content-start"
              variant="secondary"
              v-b-toggle.block-persons
            >
              <i class="bi bi-lock-fill"></i>
              Persoane blocate</b-button
            >
          </div>
          <b-collapse class="card-body pb-0" id="block-persons">
            <!-- pentru lista -->
            <ul class="list-unstyled mb-0 row">
              <li
                v-for="user in users_blocked_by_me"
                :key="user.id"
                class="col-12 col-sm-6 col-lg-12 col-xl-6"
              >
                <friend-item
                  :item="user"
                  :im_user="me.id == user.id"
                ></friend-item>
              </li>
            </ul>
          </b-collapse>
        </div>
      </template>
      <div v-if="user_friends.length > 0" class="card mb-3">
        <div class="card-header p-0 bg-white">
          <b-button
            variant="primary"
            class="w-100 justify-content-start hstack gap-2"
            v-b-toggle.all-friends
            ><i class="fa-solid fa-user-group"></i> Prieteni ({{
              user_friends.length
            }})</b-button
          >
        </div>
        <b-collapse id="all-friends" visible>
          <ul
            class="nav nav-pills p-3 border-bottom bg-light"
            id="pills-tab"
            role="tablist"
          >
            <li class="nav-item" role="presentation">
              <button
                class="nav-link active"
                id="nav-prieteni-totali-tab"
                data-bs-toggle="pill"
                data-bs-target="#nav-prieteni-totali"
                type="button"
                role="tab"
                aria-controls="nav-prieteni-totali"
                aria-selected="true"
              >
                Prieteni
              </button>
            </li>
            <li class="nav-item" role="presentation">
              <button
                class="nav-link"
                id="nav-prieteni-online-tab"
                data-bs-toggle="pill"
                data-bs-target="#nav-prieteni-online"
                type="button"
                role="tab"
                aria-controls="nav-prieteni-online"
                aria-selected="false"
              >
                Prieteni online
              </button>
            </li>
            <li
              v-if="im_user == 0 && user_login == 1"
              class="nav-item"
              role="presentation"
            >
              <button
                class="nav-link"
                id="nav-prieteni-comuni-tab"
                data-bs-toggle="pill"
                data-bs-target="#nav-prieteni-comuni"
                type="button"
                role="tab"
                aria-controls="nav-prieteni-comuni"
                aria-selected="false"
              >
                Prieteni comuni
              </button>
            </li>
          </ul>
          <div class="card-body pb-0">
            <input
              v-model="search_name"
              type="text"
              class="form-control mb-3 form-control-sm"
              placeholder="Cauta persoana..."
            />
            <div class="tab-content" id="myTabContent">
              <div
                class="tab-pane fade active show"
                id="nav-prieteni-totali"
                role="tabpanel"
                aria-labelledby="nav-prieteni-totali-tab"
              >
                <!-- pentru lista -->
                <ul class="list-unstyled mb-0 row">
                  <li
                    v-for="user in user_friends"
                    :key="user.id"
                    class="col-12 col-sm-6 col-lg-12 col-xl-6"
                  >
                    <friend-item
                      :item="user"
                      :im_user="me.id == user.id"
                    ></friend-item>
                  </li>
                </ul>
              </div>
              <div
                class="tab-pane fade"
                id="nav-prieteni-online"
                role="tabpanel"
                aria-labelledby="nav-prieteni-online-tab"
              ></div>
              <div
                v-if="im_user == false"
                class="tab-pane fade"
                id="nav-prieteni-comuni"
                role="tabpanel"
                aria-labelledby="nav-prieteni-comuni-tab"
              >
                <ul class="list-unstyled mb-0 row">
                  <li
                    v-for="user in user_common_friends"
                    :key="user.id"
                    class="col-12 col-sm-6 col-lg-12 col-xl-6"
                  >
                    <friend-item :item="user" :im_user="im_user"></friend-item>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </b-collapse>
      </div>
      <div v-else>
        <div class="alert alert-primary" role="alert">
          <strong>User-ul nu are niciun prieten.</strong>
        </div>
      </div>
    </div>
    <div class="right-wrapper mb-3 col-12 col-md-4">
      <div v-if="user_login" class="card mb-3">
        <div class="card-header p-0 bg-white">
          <b-button
            class="w-100 hstack gap-2 justify-content-start"
            variant="primary"
            v-b-toggle.recomandari
            ><i class="bi bi-list-stars"></i>Recomandari</b-button
          >
        </div>
        <b-collapse visible class="card-body pb-0" id="recomandari">
          <!-- pentru lista -->
          <ul class="list-unstyled mb-0 row">
            <li v-for="user in recomendations" :key="user.id" class="col-12">
              <friend-item
                :user_login="user_login"
                :item="user"
                :im_user="false"
              ></friend-item>
            </li>
          </ul>
        </b-collapse>
      </div>
    </div>
  </div>
</template>

<script>
import friendItem from "../components/others/FriendItem.vue";

export default {
  components: { friendItem },
  props: ["users", "im_user", "friends", "user_login", "me"],
  data() {
    return {
      search_name: "",
      get_users: this.users,
    };
  },
  mounted() {
    if (this.user_login == 1) {
      this.me.name = this.me.name + " " + this.me.surname;
    }
  },
  computed: {
    user_common_friends() {
      let array = [];
      let array1 = [];
      array = this.sort_users_by("friends");
      for (const key in array) {
        for (const key1 in this.friends) {
          if (this.friends[key1].prieten_id == array[key].id) {
            array1.push(array[key]);
            break;
          }
        }
      }
      return array1.sort(function (a, b) {
        return a.common_friends - b.common_friends;
      });
    },
    user_friends() {
      let array = [];
      if (this.im_user == 1) {
        array = this.sort_users_by("friends");
      } else {
        for (const key1 in this.friends) {
          if (
            this.user_login == 1 &&
            this.friends[key1]["prieten_id"] == this.me["id"]
          ) {
            array.push(this.me);
          }
          for (const key in this.get_users) {
            if (this.friends[key1].prieten_id == this.get_users[key].id) {
              array.push(this.get_users[key]);
              break;
            }
          }
        }
      }
      return array
        .filter(
          (item) =>
            item.name.toLowerCase().indexOf(this.search_name.toLowerCase()) !=
            -1
        )
        .sort(function (a, b) {
          return a.name - b.name;
        });
    },
    users_invited_by_me() {
      return this.sort_users_by("invited_by_me");
    },
    users_blocked_by_me() {
      return this.sort_users_by("blocked_by_me");
    },
    users_invite_me() {
      return this.sort_users_by("invite_me");
    },
    recomendations() {
      return this.sort_users_by("not_friends")
        .sort(function (a, b) {
          return a.common_friends - b.common_friends;
        })
        .reverse();
    },
  },
  methods: {
    sort_users_by(status) {
      let array = [];
      for (const key in this.get_users) {
        if (this.get_users[key].status == status) {
          array.push(this.get_users[key]);
        }
      }
      return array;
    },
  },
};
</script>