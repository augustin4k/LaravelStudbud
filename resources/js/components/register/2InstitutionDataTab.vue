<template>
  <div class="form2">
    <div class="card-header bg-info text-white">
      Date referitoare la institutia de invatamant
    </div>
    <div class="card-body">
      <p for="" class="m-0 text-secondary mb-2 fw-bold">
        Selecteaza tipul user-ului:
      </p>
      <div class="hide-input-tipUser d-none">
        <input type="text" name="tip_user" :value="institutionData.tipUser" />
      </div>
      <div>
        <div class="div-for-collapsed-elements">
          <div class="select-user hstack gap-2 flex-wrap">
            <button
              type="button"
              @click="institutionData['tipUser'] = 'student0'"
              :class="[
                institutionData['tipUser'] == 'student0'
                  ? 'btn btn-primary'
                  : 'btn btn-outline-primary',
              ]"
            >
              Student 0
            </button>
            <button
              type="button"
              @click="institutionData['tipUser'] = 'student1'"
              :class="[
                institutionData['tipUser'] == 'student1'
                  ? 'btn btn-primary'
                  : 'btn btn-outline-primary',
              ]"
            >
              Student 1
            </button>
            <button
              type="button"
              @click="institutionData['tipUser'] = 'profesor'"
              :class="[
                institutionData['tipUser'] == 'profesor'
                  ? 'btn btn-primary'
                  : 'btn btn-outline-primary',
              ]"
            >
              Profesor
            </button>
            <button
              type="button"
              @click="institutionData['tipUser'] = 'universitate'"
              :class="[
                institutionData['tipUser'] == 'universitate'
                  ? 'btn btn-primary'
                  : 'btn btn-outline-primary',
              ]"
            >
              Universitate
            </button>
          </div>
          <ul class="lyst-unstyled text-secondary text-justify">
            <li>
              <small
                >Student 0* - daca nu esti in cautarea unei universitati</small
              >
            </li>
            <li>
              <small>Student 1* - daca esti student la o universitate</small>
            </li>
          </ul>

          <!-- div pentru collapse -->
          <div id="collapse-by-user">
            <!-- collapse pentru student 0 -->
            <div
              class="multi-collapse-register"
              id="student0-info-collapse"
            ></div>

            <!-- collapse pentru student -->
            <div
              class="multi-collapse-register"
              :id="institutionData['tipUser'] + '-info-collapse'"
              v-if="
                institutionData.tipUser == 'student1' ||
                institutionData.tipUser == 'profesor'
              "
            >
              <listInstitutionsByUser
                @sendErors="getErrors"
                :tip_user="institutionData['tipUser']"
                :clickSubmit="clickSubmit"
              />
            </div>

            <!-- collapse pentru universitate -->
            <div
              v-if="institutionData['tipUser'] == 'universitate'"
              data
              class="multi-collapse-register"
              id="universitate-info-collapse"
            >
              <div class="mb-3 separator"></div>
              <div class="row range-container">
                <div class="col-sm-6">
                  <div class="form-floating">
                    <input
                      type="date"
                      class="form-control"
                      required
                      name="an-fondare-institutie"
                      id="an-fondare-institutie"
                      v-model="
                        $v.institutionData['dataInstitutionFoundation'].$model
                      "
                      disabled
                    />
                    <label for="an-fondare-institutie">Start</label>
                  </div>
                  <small
                    :class="[
                      $v.institutionData.dataInstitutionFoundation.required ==
                      false
                        ? 'text-danger'
                        : 'text-muted',
                    ]"
                    >* Se va lua anul introdus la data de nastere</small
                  >
                </div>
                <div class="col-sm-6">
                  <div class="form-floating">
                    <input
                      type="date"
                      class="form-control"
                      required
                      v-model="
                        $v.institutionData['dataInstitutionFinish'].$model
                      "
                      :readonly="prezentChecked"
                      name="an-finish-institutie"
                      id="an-finish-institutie"
                    />
                    <label for="an-finish-institutie">Finish</label>
                  </div>
                  <div class="form-check form-switch">
                    <input
                      class="form-check-input"
                      type="checkbox"
                      role="switch"
                      name="prezent-checked-institutie-single"
                      id="prezent-checked-institutie-single"
                      v-model="prezentChecked"
                    />
                    <label
                      class="form-check-label"
                      for="prezent-checked-institutie-single"
                    >
                      <div class="text-secondary">Prezent?</div>
                    </label>
                  </div>
                  <div
                    :class="[
                      ($v.institutionData.dataInstitutionFinish.$dirty ||
                        clickSubmit > 0) &&
                      institutionData.dataInstitutionFinish <
                        institutionData.dataInstitutionFoundation
                        ? ''
                        : 'd-none',
                    ]"
                    class="alert alert-start-final alert-danger"
                  >
                    Data de start nu poate fi mai mare decat de final
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import listInstitutionsByUser from "./InstitutionDataTab/RegisterInstitutionsList.vue";
import { required } from "vuelidate/lib/validators";

export default {
  components: { listInstitutionsByUser },
  props: ["clickSubmit", "dataNastere"],
  data() {
    return {
      errorPrimite: 0,
      prezentChecked: false,
      institutionData: {
        tipUser: "student0",
        dataInstitutionFoundation: "",
        dataInstitutionFinish: "",
      },
    };
  },
  validations: {
    institutionData: {
      dataInstitutionFoundation: { required },
      dataInstitutionFinish: { required },
    },
  },
  created() {
    this.$emit("sendErrorForm2", this.errorForm2);
  },
  computed: {
    errorLocal() {
      let error = 0;
      for (const key in this.institutionData) {
        if (key !== "tipUser") {
          if (
            this.$v.institutionData[key].$anyError == true ||
            this.$v.institutionData[key].required == false
          )
            error++;
          if (
            key == "dataInstitutionFoundation" &&
            this.$v.institutionData[key].$anyError == false &&
            this.$v.institutionData[key].required == true &&
            (this.$v.institutionData.dataInstitutionFinish.required == true) &
              (this.institutionData[key] >
                this.institutionData.dataInstitutionFinish)
          ) {
            error++;
          }
        }
      }
      return error;
    },
    errorForm2() {
      if (
        this.institutionData.tipUser == "student1" ||
        this.institutionData.tipUser == "profesor"
      )
        return this.errorPrimite;
      else if (this.institutionData.tipUser == "universitate")
        return this.errorLocal;
      else return 0;
    },
    schimbareData() {
      return this.dataNastere;
    },
  },

  watch: {
    errorForm2: function () {
      this.$emit("sendErrorForm2", this.errorForm2);
    },
    prezentChecked: function () {
      if (this.prezentChecked == true) {
        this.institutionData.dataInstitutionFinish = new Date()
          .toISOString()
          .split("T")[0];
      }
    },
    schimbareData: function () {
      this.institutionData.dataInstitutionFoundation = this.schimbareData;
    },
  },
  methods: {
    getErrors(data) {
      this.errorPrimite = data;
      this.$emit("SendErrors", this.errorForm2);
    },
  },
};
</script>