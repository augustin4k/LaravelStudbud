<template>
  <div class="div-for-student-or-profesor">
    <div class="separator mb-3"></div>
    <div class="d-sm-flex align-items-center gap-2">
      <label class="w-100 text-secondary">
        <template v-if="tip_user == 'profesor'">
          Nr. de institutii in care ai lucrat
        </template>
        <template v-else-if="tip_user == 'student1'">
          Nr. de institutii in care ai invatat
        </template>
      </label>
      <div class="input-group">
        <input
          type="number"
          class="form-control"
          :id="'nr-institutii[' + tip_user + ']'"
          :name="'nr-institutii[' + tip_user + ']'"
          required
          v-model="numberInstitution"
        />
        <button
          class="btn btn-secondary"
          :name="'introdu-nr-' + tip_user"
          type="button"
          @click="renderFieldsInstitutions()"
        >
          Introdu
        </button>
      </div>
    </div>
    <small
      id="Numar_institutii"
      class="w-100 hstack justify-content-end form-text text-muted"
      >* Cel putin 1 institutie</small
    >
    <div class="accordion" :id="'accordion-' + tip_user">
      <institutieComponent
        @callBackErrors="getErrorFromItemInstitution"
        v-for="n in nr_institutii"
        :key="n"
        :nr-institutie="n"
        :tip_user="tip_user"
        :clickSubmit="clickSubmit"
      />
    </div>
  </div>
</template>

<script>
import institutieComponent from "./RegisterInstitutionItem.vue";

export default {
  components: { institutieComponent },
  props: ["tip_user", "clickSubmit"],
  data() {
    return {
      nr_institutii: 1,
      numberInstitution: 1,
      erroriPrimite: [],
      totalErrors: 0,
    };
  },
  mounted() {
    this.$emit("sendErors", this.totalErrors);
  },
  watch: {
    numberInstitution: function () {
      if (this.numberInstitution <= 0) this.numberInstitution = 1;
    },

    nr_institutii(newVal, oldVal) {
      let resetErrorsIndexes = newVal - oldVal;
      if (resetErrorsIndexes < 0) {
        resetErrorsIndexes = 0 - resetErrorsIndexes;
        for (let i = 0; i < resetErrorsIndexes; i++) {
          this.erroriPrimite.pop();
        }
      }
      this.countErrors();
    },

    totalErrors: function () {
      this.$emit("sendErors", this.totalErrors);
    },
  },
  methods: {
    countErrors() {
      let countErrors = 0;
      for (const key in this.erroriPrimite) {
        countErrors = countErrors + this.erroriPrimite[key];
      }
      this.totalErrors = countErrors;
    },
    getErrorFromItemInstitution(data1, data2) {
      this.erroriPrimite[data2] = data1;
      this.countErrors();
    },
    renderFieldsInstitutions() {
      this.nr_institutii = Number(this.numberInstitution);
    },
  },
};
</script>