<script>
export default {
  data() {
    return {};
  },
  mounted() {},
  methods: {
    // work with post card
    populate_post_card(data) {
      this.post_card.text = data.text;
      this.post_card.id = data.id;
      if (this.type == "reviews") {
        this.post_card.note = data.note;
      }
      this.post_card.images = [];
      for (let i = 0; i < data.images.length; i++) {
        this.post_card.images.push(data.images[i]);
      }
    },
    preload_images(ref_name) {
      let array_images = [];
      let files = this.$refs[ref_name].files;
      if (files.length > 10 && this.type != "files") {
        alert("Maxim 10 imagini poti incarca.");
        let over = files.length - 10;
        files.splice(9, over);
      }
      for (let index = 0; index < files.length; index++) {
        let image = {};
        image["name"] = files[index].name;
        image["extension"] = files[index].name.split(".").pop();
        if (files[index].size < 1000000) {
          image["size"] = Math.floor(files[index].size / 1000) + "Kb";
        } else {
          image["size"] = Math.floor(files[index].size / 1000000) + "Mb";
        }
        image["path"] = URL.createObjectURL(files[index]);
        array_images.push(image);
      }
      return array_images;
    },
    delete_preload_document(index, to) {
      this.post_card.images.splice(index, to);
      if (this.post_card.images.length == 0 && this.type !== "files")
        Vue.nextTick(() => {
          this.$refs.nav_post.click();
        });
    },
  },
};
</script>