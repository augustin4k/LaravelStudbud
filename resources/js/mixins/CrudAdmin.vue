<script>
export default {
  data() {
    return {
      checkIfGradeIsMore: false,
      getRoleOfuser: "",
    };
  },
  mounted() {
    axios.post("api/getRoleOfUser", {}).then((response) => {
      this.getRoleOfuser = response.data;
    });
  },
  methods: {
    // BUTTON ACTIONS
    listAction(action) {
      if (action == "delete") {
        this.deleteInfo(this.selected, this.tableName);
      } else {
        this.updateInfo(action);
      }
    },
    openNewTab(selected) {
      console.log(selected);
      for (let index = 0; index < selected.length; index++) {
        window.open("/timeline?id=" + selected[index]["#ID"], "_blank");
      }
    },
    // axios
    updatecheckIfGradeIsMore(selectedArray) {
      axios
        .post("api/checkIfArrayIsAllowedForDelete", {
          users: selectedArray,
        })
        .then((response) => {
          if (response.data == "1") this.checkIfGradeIsMore = true;
          else this.checkIfGradeIsMore = false;
        });
    },
    deleteInfo(array, FromTableName) {
      axios
        .post("api/deleteInfo", {
          table: FromTableName,
          info: array,
        })
        .then((response) => {
          this.getInfo();
        });
    },
    updateInfo(action) {
      axios
        .post("api/updateInfo", {
          table: this.tableName,
          action: action,
          selectedInfo: this.selected,
        })
        .then((response) => {
          this.getInfo();
        });
    },
    getInfo() {
      axios
        .post("api/getInfo", {
          tipInfo: this.tableName,
        })
        .then((response) => {
          let getInfo = [];
          if (this.tableName == "users") {
            getInfo = response.data.info.map((item) => {
              let ageInMilliseconds = new Date() - new Date(item.date_birth);
              let age = Math.floor(
                ageInMilliseconds / 1000 / 60 / 60 / 24 / 365
              );
              let isActive = false;
              if (item.email_verified_at !== null) {
                isActive = true;
              }
              let role = "User simplu";
              if (item.role.length > 0) {
                role = "";
                for (let index = 0; index < item.role.length; index++) {
                  role =
                    role + this.capitalizeFirstLetter(item.role[index].name);
                  if (index + 1 < item.role.length) {
                    role = role + ", ";
                  }
                }
              }
              let color_active = "primary";
              let color_admin = "";
              if (isActive == false) color_active = "danger";
              if (role.includes("Admin")) {
                color_admin = "danger";
              }
              return {
                "#ID": item.id,
                first_name: item.name,
                last_name: item.surname,
                age: age,
                "Inregistrat la": this.convert_timestamp(
                  item.created_at,
                  "single_date"
                ),
                role: role,
                "Email verificat la": this.convert_timestamp(
                  item.email_verified_at,
                  "single_date"
                ),
                isActive: isActive,
                _cellVariants: {
                  isActive: color_active,
                  role: color_admin,
                },
              };
            });
          } else if (this.tableName == "emails") {
            getInfo = response.data.info.map((item) => {
              let indeplinit_la = "Neindeplinit";
              let color_indeplinit = "danger";
              if (item.indeplinit_de) {
                color_indeplinit = "success";
                indeplinit_la = this.convert_timestamp(
                  item.updated_at,
                  "single_date"
                );
              }
              return {
                "#ID": item.id,
                text: item.text,
                email: item.contact_email,
                "name of sender": item.name_of_sender,
                subject: item.subject,
                "primit la": this.convert_timestamp(
                  item.created_at,
                  "single_date"
                ),
                "indeplinit la": indeplinit_la,
                "indeplinit de": item.indeplinit_de,
                _rowVariant: color_indeplinit,
              };
            });
          }
          this.originalItems = getInfo;
        });
    },
  },
};
</script>