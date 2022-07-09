<template>
  <div :class="{ 'right-wrapper col-4': size_for_canvas }">
    <div
      :style="{ visibility: size_for_canvas ? 'visible' : 'hidden' }"
      data-bs-scroll="false"
      data-bs-backdrop="true"
      :tabindex="-1"
      id="offcanvasFiltru"
      aria-labelledby="offcanvasFiltruLabel"
      :aria-hidden="[size_for_canvas ? 'visible' : 'hidden']"
      :class="[
        size_for_canvas
          ? 'card mb-3'
          : 'offcanvas w-100 rounded offcanvas-bottom border',
      ]"
    >
      <div
        :class="[size_for_canvas ? 'card-header' : 'offcanvas-header']"
        class="
          bg-primary
          text-white
          d-flex
          justify-content-between
          align-items-center
        "
      >
        <b>Filtru </b>
        <i class="bi bi-funnel-fill"></i>
      </div>
      <div :class="[size_for_canvas ? 'card-body' : 'offcanvas-body']">
        <!-- informatia in functie de ce am nevoie -->
        <ul class="list-unstyled my-0 py-0">
          <li class="py-2 border-bottom">
            <label for=""><small>Sorteaza dupa:</small></label>
            <select v-model="filter.orderBy" class="form-select form-select-sm">
              <template
                v-if="type_filter != 'for reviews' && selected_user_id != 0"
              >
                <option value="prieteni_desc">Nr prieteni comuni - Desc</option>
                <option value="prieteni_asc">Nr prieteni comuni - Asc</option>
              </template>
              <template v-else>
                <option
                  v-for="(keyOrder, index) in KeysOrder"
                  :key="index"
                  :value="keyOrder"
                >
                  <template v-if="keyOrder.includes('likes')">Nr. </template>
                  {{ convert_orderBy(keyOrder) }}
                </option>
              </template>
            </select>
          </li>
          <li
            class="py-2 border-bottom"
            v-for="(item, index) in InfoForFilter"
            :key="index"
          >
            <label class="hstack justify-content-between"
              ><small>{{ convert_filter_name(index) }}</small>
              <b-button
                v-b-toggle="'filter_' + index"
                variant="outline-secondary"
                class="btn-sm"
                ><i class="bi bi-caret-down-fill"></i></b-button
            ></label>
            <b-collapse :id="'filter_' + index">
              <div
                v-for="(item1, index1) in InfoForFilter[index]"
                :key="index1"
              >
                <div class="form-check" v-if="item1">
                  <input
                    class="form-check-input"
                    type="checkbox"
                    v-model="filter[index][item1]"
                  />
                  <!-- {{ index + " " + item1 }} -->
                  <label class="form-check-label" for="">
                    <small v-if="index == 'user_type'">{{
                      convert_user_type(item1)
                    }}</small>
                    <small
                      v-else-if="
                        index == 'universities' && CheckIfObject(item1)
                      "
                      >{{ item1[0].name_institution }}</small
                    >
                    <small v-else
                      >{{ item1 }}
                      <template v-if="index == 'note'"
                        ><i class="bi bi-star-fill"></i></template
                    ></small>
                  </label>
                </div>
              </div>
            </b-collapse>
          </li>
        </ul>
      </div>
      <div
        :class="[
          size_for_canvas
            ? 'card-footer'
            : 'offcanvas-footer bg-white border-top pt-3 mt-0 m-3',
        ]"
        class="d-flex justify-content-between"
      >
        <button
          ref="ResetFilter"
          @click="
            $emit('ResetFilters');
            DefaultFilter();
          "
          class="btn btn-sm btn-danger"
        >
          Reset
        </button>
        <button
          @click="$emit('send_filters', filter)"
          class="btn btn-sm btn-primary"
        >
          Filter
        </button>
      </div>
    </div>
    <div v-if="selected_user_id == 0 && size_for_canvas" class="card mb-3">
      <div class="card-body">
        <div class="form-floating w-100">
          <input
            @keyup.enter="$emit('send_filters', filter)"
            v-model="filter.message"
            type="text"
            class="form-control"
            placeholder="Cauta postarea..."
            required
          />
          <label>Cauta postarea...</label>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import ResizeAndConvert from "../../mixins/mixin1.vue";

export default {
  props: [
    "GetInfoForFilter",
    "type_filter",
    "ResetFilters",
    "ResetFromReviewsComponent",
    "selected_user_id",
  ],
  mixins: [ResizeAndConvert],
  data() {
    return {
      KeysOrder: [],
      filter: {},
    };
  },
  computed: {
    InfoForFilter() {
      return this.sortInfoForFilters(this.GetInfoForFilter);
    },
  },
  watch: {
    ResetFromReviewsComponent: function (newVal) {
      if (newVal == 1) {
        this.$refs.ResetFilter.click();
        this.DefaultFilter();
        this.$emit("ResetFlagResetFromComponentReview");
      }
    },
    ResetFilters: function (newVal) {
      if (newVal == 1) {
        for (let key in this.filter) {
          if (key != "orderBy") {
            this.filter[key] = [];
          }
        }
      }
    },
  },
  mounted() {
    this.DefaultFilter();
  },
  methods: {
    CheckIfObject(myObj) {
      return myObj[0].hasOwnProperty("name_institution");
    },
    DefaultFilter() {
      this.filter = {
        orderBy: "",
        message: "",
        city: [],
        country: [],
        universities: [],
        user_type: [],
        note: [],
      };
      if (
        (this.type_filter && this.type_filter == "for reviews") ||
        this.selected_user_id == 0
      ) {
        this.KeysOrder = ["date_desc", "date_asc", "likes_desc", "likes_asc"];
        if (this.type_filter && this.type_filter == "for reviews") {
          this.KeysOrder.push("note_desc", "note_asc");
        }
        this.filter.orderBy = "date_desc";
      } else this.filter.orderBy = "prieteni_desc";
      this.$emit("send_filters", this.filter);
    },
  },
};
</script>