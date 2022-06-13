<template>
  <div class="row">
    <div class="col-12 col-md-8 col-lg-12 col-xl-8 left-wrapper">
      <v-posts-reviews
        type="post"
        :my_id="my_id"
        :ClickFilter="ClickFilter"
        :selected_user_id="selected_user_id"
        :GetFilters="filter"
        :ResetFilters="ResetFilters"
        @ClickResetExist="
          ResetFilters = 1;
          ResetFromReviewsComponent = 1;
        "
        @send_filters="GetInfoForFilter"
        @ResetClicker="ClickFilter = 0"
        @ResetResetFilters="ResetFilters = 0"
      >
      </v-posts-reviews>
    </div>
    <filter-component
      @ResetFlagResetFromComponentReview="ResetFromReviewsComponent = 0"
      @ResetFilters="ResetFilters = 1"
      @send_filters="GetFilters"
      :ResetFilters="ResetFilters"
      :selected_user_id="selected_user_id"
      :GetInfoForFilter="FilterInfo"
      :ResetFromReviewsComponent="ResetFromReviewsComponent"
    />
  </div>
</template>

<script>
import filterComponent from "../components/others/FilterSearch.vue";

export default {
  components: { filterComponent },
  props: ["selected_user_id", "my_id"],
  data() {
    return {
      filter: [],
      FilterInfo: [],
      ClickFilter: 0,
      ResetFilters: 0,
      ResetFromReviewsComponent: 0,
    };
  },
  methods: {
    GetInfoForFilter(get_info) {
      this.FilterInfo = get_info;
    },
    GetFilters(get_filters) {
      this.ClickFilter = 1;
      this.filter = get_filters;
    },
  },
};
</script>