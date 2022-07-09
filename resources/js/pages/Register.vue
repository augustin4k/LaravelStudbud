<template>

  <div>
    <!-- pentru alerta generala -->
    <div
      :class="{'d-none':ShowErrorRegister==false}"
      class="alert alert-danger"
      id="main-alert"
    >
      <p class="mb-0">
        Mai verifica odata toate campurile, careva din ele nu au fost completate
        deloc sau au fost completate gresit
      </p>
    </div>

    <form method="POST" :action="nameRoute"
    enctype=multipart/form-data  :class="[clickSubmit > 0 ?
    'was-validated' : 'needs-validation', ShowErrorRegister? 'shake': '']" novalidate >
    <input type="hidden" name='_token' :value="csrfToken">
      <div class="tabs-and-container">
        <nav class="">
          <div class="nav nav-tabs navbar-expand" id="nav-tab" role="tablist">
            <button
              class="nav-link hstack gap-1 active"
              id="list-personal-data-tab"
              data-bs-toggle="tab"
              data-bs-target="#list-personal-data"
              type="button"
              role="tab"
              aria-controls="list-personal-data"
              aria-selected="true"
              ref="personalData"
              @click="clickerCount = 0"
            >
              <i class="bi bi-file-person"></i>
              <span class="d-md-block d-none text-truncate">
                Date personale
              </span>
              <span
                :class="[
                  errors['form1'] > 0 && clickSubmit == 1 ? '' : 'd-none',
                ]"
                class="badge bg-danger"
              >
                {{ errors["form1"] }}</span
              >
            </button>
            <button
              class="nav-link hstack gap-1"
              id="list-institution-data-tab"
              data-bs-toggle="tab"
              data-bs-target="#list-institution-data"
              type="button"
              role="tab"
              aria-controls="list-institution-data"
              aria-selected="false"
              ref="institutionData"
              @click="clickerCount = 1"
            >
              <i class="fa-solid fa-building-columns"></i>
              <span class="d-none d-md-block"> Institutia </span>
              <span
                :class="[
                  errors['form2'] > 0 && clickSubmit > 0 ? '' : 'd-none',
                ]"
                class="badge bg-danger"
                >{{ errors["form2"] }}</span
              >
            </button>
            <button
              class="nav-link hstack gap-1"
              id="list-avatar-tab"
              data-bs-toggle="tab"
              data-bs-target="#list-avatar"
              type="button"
              role="tab"
              aria-controls="list-avatar"
              aria-selected="false"
              ref="avatarData"
              @click="clickerCount = 2"
            >
              <i class="bi bi-person-bounding-box"></i>
              <span class="d-md-block d-none"> Avatar </span>
              <span
                :class="[
                  errors['form3'] > 0 && clickSubmit > 0 ? '' : 'd-none',
                ]"
                class="badge bg-danger"
                >{{ errors["form3"] }}
              </span>
            </button>
            <button
              v-if='type!="setari"'
              class="nav-link hstack gap-1"
              id="list-auth-tab"
              data-bs-toggle="tab"
              data-bs-target="#list-auth"
              type="button"
              role="tab"
              aria-controls="list-auth"
              aria-selected="false"
              ref="loginData"
              @click="clickerCount = 3"
            >
              <i class="bi bi-box-arrow-in-right"></i>
              <span class="d-md-block d-none text-truncate">
                Date de autentificare
              </span>
              <span
                :class="[
                  errors['form4'] > 0 && clickSubmit > 0 ? '' : 'd-none',
                ]"
                class="badge bg-danger"
                >{{ errors["form4"] }}</span
              >
            </button>
          </div>
        </nav>
        <div class="card">
          <div class="tab-content" id="nav-tabContent">
            <div
              class="show active tab-pane fade"
              id="list-personal-data"
              role="tabpanel"
              aria-labelledby="personal-data"
            >
              <personalDataTab
                @sendFieldsPersonal="getPersonalFields"
                @sendErrorForm1="getForm1Error"
                @sendDataNastere="getDataNasterii"
                :clickSubmit="clickSubmit"
              />
            </div>
            <div
              class="tab-pane fade"
              id="list-institution-data"
              role="tabpanel"
              aria-labelledby="institution-data"
            >
              <institutionDataTab
                :clickSubmit="clickSubmit"
                @sendErrorForm2="getForm2Error"
                :dataNastere="dataNastere"
              />
            </div>
            <div
              class="tab-pane fade"
              id="list-avatar"
              role="tabpanel"
              aria-labelledby="avatar"
            >
              <avatarDataTab
                @sendAvatar="getAvatar"
                @showErrorsForm3="getForm3Error"
                :clickSubmit="clickSubmit"
              />
            </div>
            <div
              v-if='type!="setari"'
              class="tab-pane fade"
              id="list-auth"
              role="tabpanel"
              aria-labelledby="auth"
            >
              <authDataTab
                @sendFields="getFieldsAuth"
                @sendErrorForm4="getForm4Error"
                :clickSubmit="clickSubmit"
              />
            </div>
          </div>
          <div class="card-footer bg-white">
            <div class="hstack flex-row-reverse justify-content-between">
              <template v-if="clickerCount < 3">
                <button
                  type="button"
                  @click="clickerCount++"
                  class="btn btn-primary"
                >
                  Next
                </button>
              </template>
              <template v-else>
                <button
                  class="btn btn-primary float-right"
                  :class="{disabled:SuccessfulRegistered}"
                  :type="[errorsTotal > 0 ? 'button' : 'submit']"
                  @click="clickSubmit = 1; SubmitClickerCount++;"
                  name="finish-register"
                >
                <template v-if='SuccessfulRegistered==false'>
                  Finiseaza
                </template>
                <template v-else>
                  <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                   Inregistrare cu success...
                </template>
                </button>
              </template>
              <template v-if="clickerCount == 0">
                <button type="button" disabled class="btn btn-primary">
                  Back
                </button>
              </template>
              <template v-else>
                <button
                  type="button"
                  @click="clickerCount--"
                  class="btn btn-primary"
                >
                  Back
                </button>
              </template>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
</template>

<script>
import personalDataTab from "../components/register/1PersonalDataTab.vue";
import institutionDataTab from "../components/register/2InstitutionDataTab.vue";
import avatarDataTab from "../components/register/3AvatarDataTab.vue";
import authDataTab from "../components/register/4AuthDataTab.vue";

export default {
  components: {
    personalDataTab,
    institutionDataTab,
    avatarDataTab,
    authDataTab,
  },
  props: ["type", "nameRoute", "csrfToken"],
  data() {
    return {
      ShowErrorRegister: false,
      clickerCount: 0,
      clickSubmit: 0,
      SubmitClickerCount: 0,
      SuccessfulRegistered: false,
      errors: {
        form1: 4,
        form2: 6,
        form3: 1,
        form4: 3,
      },
      dataNastere: "",
      fields: {
        auth: {},
        personal: {},
        avatar: {},
        auth: {},
      },
    };
  },
  computed: {
    errorsTotal: function () {
      let errors = 0;
      for (const key in this.errors) {
        errors = errors + this.errors[key];
      }
      return errors;
    },
  },
  watch: {
    SubmitClickerCount: function () {
      if (this.errorsTotal > 0) {
        this.ShowErrorRegister = true;
        setTimeout(() => {
          this.ShowErrorRegister = false;
        }, 2000);
      } else this.SuccessfulRegistered = true;
    },
    clickerCount: function () {
      if (this.clickerCount == 0) this.$refs.personalData.click();
      else if (this.clickerCount == 1) this.$refs.institutionData.click();
      else if (this.clickerCount == 2) this.$refs.avatarData.click();
      else if (this.clickerCount == 3) this.$refs.loginData.click();
    },
  },
  methods: {
    getPersonalFields(data) {
      this.fields.personal = data;
    },
    getAvatar(data) {
      this.fields.avatar = data;
    },
    getFieldsAuth(data) {
      this.fields.auth = data;
    },

    getFieldsAvatar(data) {
      this.fields.avatar = data;
      console.log(this.avatar);
    },
    getDataNasterii(event) {
      this.dataNastere = event;
    },
    getForm1Error(event) {
      this.errors["form1"] = event;
    },
    getForm2Error(event) {
      this.errors["form2"] = event;
    },
    getForm3Error(event) {
      this.errors["form3"] = event;
    },
    getForm4Error(event) {
      this.errors["form4"] = event;
    },
  },
};
</script>