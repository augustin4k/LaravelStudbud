<script>
export default {
  mounted() {
    this.post_card_initiate();
    axios
      .post("api/get_posts_reviews", {
        selected_user: this.selected_user_id,
        type_post: this.type,
      })
      .then((response) => {
        this.posts_info = this.FilterReviewsPosts(
          this.GetFilters,
          response.data.posts
        );
        // salvez informatia intr-o variabila pe care nu o voi modifica niciodata, pentru a nu o pierde
        this.original_posts_info = this.posts_info;
        this.my_avatar = response.data.my_avatar;
        setTimeout(() => {
          this.placeholders = false;
        }, 1000);
      });
  },
  methods: {
    post_card_initiate() {
      if (this.type == "reviews")
        this.post_card = { note: 0, images: [], text: "" };
      else this.post_card = { images: [], text: "" };
    },
    FilterReviewsPosts(filter, OriginalArray) {
      if (filter != undefined) {
        for (let key in filter) {
          if (key != "orderBy" && key != "note" && key !== "message") {
            if (Object.values(filter[key]).includes(true))
              OriginalArray = OriginalArray.filter(
                (item) => filter[key][item.user[key]] == true
              );
          } else if (key == "note") {
            if (Object.values(filter[key]).includes(true))
              OriginalArray = OriginalArray.filter(
                (item) => filter[key][item[key]] == true
              );
          } else if (key == "message") {
            console.log("sadsa");
            OriginalArray = OriginalArray.filter((item) =>
              item.text.includes(filter[key])
            );
          }
        }
      } else {
        filter = { orderBy: "date_desc" };
      }
      let dictionar = {
        date: "updated_at",
        likes: "likes",
        note: "note",
      };
      let value =
        dictionar[
          Object.keys(dictionar).filter((item) => filter.orderBy.includes(item))
        ];
      OriginalArray = OriginalArray.sort(function (a, b) {
        if (value == "updated_at" || value == "note") {
          if (a[value] > b[value]) {
            return 1;
          }
          if (a[value] < b[value]) {
            return -1;
          }
          return 0;
        } else {
          if (a[value].length > b[value].length) {
            return 1;
          }
          if (a[value].length < b[value].length) {
            return -1;
          }
          if (value == "likes") {
            if (a.dislikes.length > b.dislikes.length) {
              return -1;
            }
            if (a.dislikes.length < b.dislikes.length) {
              return 1;
            }
          }
          return 0;
        }
      });

      if (filter.orderBy.includes("desc")) {
        OriginalArray = OriginalArray.reverse();
      }
      return OriginalArray;
    },
    send(id, type) {
      let formData = new FormData();
      if (this.$refs.documents.files.length > 0) {
        for (var i = 0; i < this.$refs.documents.files.length; i++) {
          let file = this.$refs.documents.files[i];
          formData.append("files[" + i + "]", file);
        }
      } else if (
        this.post_card.images.length > 0 &&
        this.$refs.documents.files.length == 0
      ) {
        for (let i = 0; i < this.post_card.images.length; i++) {
          formData.append(
            "files_already_exist[" + i + "]",
            this.post_card.images[i].id
          );
        }
      }
      if (type == "reviews") {
        formData.append("note", this.post_card.note);
      }
      formData.append("text", this.post_card.text);
      if (id !== null) {
        formData.append("post_id", this.post_card.id);
        formData.append("action", "update");
      } else formData.append("action", "insert");
      formData.append("selected_user", this.selected_user_id);
      formData.append("type_of_post", type);
      axios
        .post("api/insert-post", formData, {
          headers: {
            "Content-Type": "multipart/form-data",
          },
        })
        .then((response) => {
          var response_posts = response.data.posts;
          if (id !== null) {
            let array = this.posts_info;
            array.splice(
              array.findIndex((item) => item.id == id),
              1,
              response_posts[0]
            );
            this.posts_info = array;
          } else {
            this.posts_info.unshift(response_posts[0]);
          }
          this.post_card_initiate();
          this.$refs.documents.files = null;
          this.original_posts_info = this.posts_info;
        })
        .catch((error) => {
          this.Shake(error);
        });
    },
    delete_post(post) {
      axios
        .post("api/delete_post", {
          post_id: post.id,
        })
        .then((response) => {
          this.$delete(
            this.posts_info,
            this.posts_info.findIndex((item) => item.id == post.id)
          );
          this.original_posts_info = this.posts_info;
        });
    },
  },
};
</script>