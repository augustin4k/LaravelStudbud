<script>
export default {
  data() {
    return {
      shake: false,
      errors: [],
      screen_width: screen.width,
    };
  },
  computed: {
    size_for_canvas() {
      return (
        this.screen_width >= 1200 ||
        (this.screen_width >= 768 && this.screen_width < 992)
      );
    },
  },
  created() {
    window.addEventListener("resize", this.myEventHandler);
  },
  destroyed() {
    window.removeEventListener("resize", this.myEventHandler);
  },
  methods: {
    // variable for animation
    Shake(error) {
      this.shake = true;
      this.errors = error.response.data.errors;
      setTimeout(() => {
        this.errors = [];
        this.shake = false;
      }, 1500);
    },
    // work with variable
    myEventHandler(e) {
      this.screen_width = screen.width;
    },
    sortInfoForFilters(filter) {
      for (let key in filter)
        if (key != "orderBy") {
          filter[key] = Object.values(filter[key]).sort(function (a, b) {
            if (a > b) return 1;
            if (a < b) return -1;
            return 0;
          });
        }
      return filter;
    },
    filter_info(filter, filtered_array) {
      for (let key in filter) {
        if (key != "orderBy" && key != "name_search") {
          if (Object.values(filter[key]).includes(true))
            filtered_array = filtered_array.filter(
              (item) => filter[key][item[key]] == true
            );
        } else if (key == "name_search") {
          if (filter[key] == undefined) filter[key] = "";
          filtered_array = filtered_array.filter((item) =>
            item.name.toLowerCase().includes(filter[key].toLowerCase())
          );
        } else if (key == "orderBy") {
          filtered_array = filtered_array.sort(function (a, b) {
            if (a.common_friends > b.common_friends) {
              return 1;
            }
            if (a.common_friends < b.common_friends) {
              return -1;
            }
            return 0;
          });
          if (filter[key].includes("desc")) {
            filtered_array = filtered_array.reverse();
          }
        }
      }
      return filtered_array;
    },
    capitalizeFirstLetter(string) {
      return string.charAt(0).toUpperCase() + string.slice(1);
    },
    convert_orderBy(name) {
      let name_help = name.split("_");
      return (
        this.capitalizeFirstLetter(name_help[0]) +
        " - " +
        this.capitalizeFirstLetter(name_help[1])
      );
    },
    convert_user_type(name) {
      if (name == "student0") {
        return "Student in cautare";
      } else if (name == "student1") return "Student";
      else if (name == "universitate") return "Universitate";
      else if (name == "profesor") return "Profesor";
    },
    convert_filter_name(key) {
      if (key == "user_type") return "Tip-ul user-ului";
      else if (key == "universities") return "Universitatile";
      else if (key == "city") return "Orasele user-ilor";
      else if (key == "country") return "Tarile user-ilor";
      else if (key == "note") return "Notele review-rilor";
    },
    convert_timestamp(element, tip_date) {
      if (element !== "No one message") {
        let d = new Date(element);
        let date = "";
        if (tip_date == "date_and_time")
          date =
            this.addZero(d.getHours()) +
            ":" +
            this.addZero(d.getMinutes()) +
            ", ";
        let today = new Date();
        if (d.getDate() == today.getDate()) {
          date = date + "Today";
        } else if (d.getDate() == today.getDate() - 1)
          date = date + "Yesterday";
        else date = date + d.toLocaleDateString();
        element = date;
      }
      return element;
    },
    addZero(i) {
      if (i < 10) {
        i = "0" + i;
      }
      return i;
    },
    dont_show_blocked(item) {
      if (
        item.status &&
        item.status != "block_me" &&
        item.status != "blocked_by_me"
      ) {
        return true;
      } else return false;
    },
    // api
    like(action, TableName, item) {
      axios
        .post("api/like", {
          action: action,
          table_name: TableName,
          id: item.id,
        })
        .then((response) => {
          item.likes = response.data.item.likes;
          item.dislikes = response.data.item.dislikes;
          item.liked_by_me = response.data.item.liked_by_me;
        });
      return item;
    },
  },
};
</script>

