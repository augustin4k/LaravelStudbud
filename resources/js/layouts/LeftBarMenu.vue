<template>
  <div class="navbar-left-on-page sticky-top">
    <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist">
      <a class="nav-link" id="list-home-list" href="/timeline">
        <div class="d-flex justify-content-between align-items center">
          <div class="d-flex gap-2 align-items-center">
            <i class="bi bi-person-circle"></i> Profile
          </div>
        </div>
      </a>
      <a class="nav-link" id="list-profile-list" href="/feed">
        <div class="d-flex justify-content-between align-items-center">
          <div class="d-flex gap-2 align-items-center">
            <i class="bi bi-newspaper"></i> Feed
          </div>
          <span class="badge bg-primary rounded-pill">+14</span>
        </div>
      </a>
      <a href="/chat" class="nav-link" id="list-messages-list">
        <div class="d-flex justify-content-between align-items-center">
          <div class="d-flex gap-2 align-items-center">
            <i class="fa fa-comments-o" aria-hidden="true"></i> Messages
          </div>
          <span class="badge bg-danger rounded-pill">+14</span>
        </div>
      </a>
      <a href="/friends" class="nav-link" id="list-messages-list">
        <div class="d-flex justify-content-between align-items-center">
          <div class="d-flex gap-2 align-items-center">
            <i class="fa-solid fa-user-group"></i> Friends
          </div>
          <span class="badge bg-light text-black rounded-pill">14</span>
        </div>
      </a>
      <a class="nav-link" id="list-messages-list" href="/search">
        <div class="d-flex justify-content-between align-items-center">
          <div class="d-flex gap-2 align-items-center">
            <i class="bi bi-search"></i> Advanced search
          </div>
        </div>
      </a>
    </div>
    <v-footer tip="no-auth" class="small"></v-footer>
  </div>
</template>

<script>
export default {
  props: ["my_id"],
  mounted() {
    // lucrul cu colorarea link-urilor
    let href_location = $(location).attr("href");
    if (href_location.split("?id=")[1] == this.my_id) {
      href_location = href_location.split("?id=")[0];
    }
    if (
      (href_location.includes("reviews") ||
        href_location.includes("documents")) &&
      href_location.includes("?id") == false
    ) {
      href_location = "/timeline";
    } else if (href_location.includes("chat"))
      href_location = href_location.split("?")[0];
    $(".nav-link").each(function () {
      if (
        href_location.includes($(this).attr("href")) &&
        href_location.includes("?id") == false
      ) {
        $(this).addClass("active");
      }
    });
  },
};
</script>
