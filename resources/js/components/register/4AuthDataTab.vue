<template>
  <div class="form4">
    <div class="card-header bg-info text-white">Date de autentificare</div>
    <div class="card-body">
      <div class="form-floating">
        <input
          type="email"
          class="form-control"
          name="email"
          v-model.trim="$v.userData['email'].$model"
          placeholder="Introdu email"
          required
        />
        <label for="email">Email</label>
      </div>
      <div
        :class="[
          (($v.userData.email.$dirty || clickSubmit > 0) &&
            !$v.userData.email.required) ||
          (($v.userData.email.$dirty || clickSubmit > 0) &&
            !$v.userData.email.email)
            ? ''
            : 'd-none',
        ]"
        class="alert alert-warning"
        role="alert"
      >
        <strong>Email invalid</strong>
      </div>

      <div class="mb-3 sep"></div>
      <div class="row">
        <div class="col-md">
          <div class="form-floating">
            <input
              type="password"
              name="password"
              id="password"
              v-model.trim="$v.userData['parola'].$model"
              class="form-control"
              placeholder="Introdu parola dorita"
              maxlength="12"
              minlength="4"
              required
            />
            <label for="password">Parola </label>
          </div>
          <small
            :class="[
              ($v.userData.parola.$dirty || clickSubmit > 0) &&
              (!$v.userData.parola.minLength || userData.parola.length == 0)
                ? 'text-danger'
                : 'text-muted',
            ]"
            class="form-text"
          >
            * Intre 4 si 12 caractere
          </small>
          <div class="mb-3 sep"></div>
        </div>
        <div class="col-md">
          <div class="form-floating">
            <input
              name="password_confirmation"
              id="password_confirmation"
              type="password"
              class="form-control"
              placeholder="Confirma parola"
              required
              maxlength="12"
              minlength="4"
              v-model.trim="$v.userData['confirmaParola'].$model"
            />
            <label for="password_confirmation">Confirma parola</label>
          </div>
          <div
            :class="[
              ($v.userData.confirmaParola.$dirty || clickSubmit > 0) &&
              !$v.userData.confirmaParola.sameAsPassword
                ? 'invalid'
                : 'd-none',
            ]"
            class="alert alert-parole alert-danger"
          >
            Parolele nu corespund
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { email, required, minLength, sameAs } from "vuelidate/lib/validators";

export default {
  props: ["clickSubmit"],
  data() {
    return {
      userData: {
        email: "",
        parola: "",
        confirmaParola: "",
      },
    };
  },
  validations: {
    userData: {
      email: { required, email },
      parola: { required, minLength: minLength(4) },
      confirmaParola: { required, sameAsPassword: sameAs("parola") },
    },
  },
  computed: {
    errorTotal() {
      let error = 0;
      for (const key in this.userData) {
        if (
          this.$v.userData[key].$anyError == true ||
          this.$v.userData[key].required == false
        ) {
          error++;
        }
      }
      return error;
    },
  },
  watch: {
    errorTotal: function () {
      this.$emit("sendErrorForm4", this.errorTotal);
    },
  },
};
</script>