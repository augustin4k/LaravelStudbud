<template>
  <div>
    <form
      :action="nameRoute"
      method="POST"
      :class="[clickLogin > 0 ? 'was-validated' : 'needs-validation']"
      novalidate
    >
      <input type="hidden" name="_token" :value="csrfToken" />
      <div class="form-floating">
        <input
          type="email"
          class="form-control"
          id="floatingInput"
          placeholder="name@example.com"
          name="email"
          v-model="$v.field.login.$model"
          required
        />
        <label for="floatingInput">Email address</label>
      </div>
      <div class="form-floating">
        <input
          type="password"
          class="form-control"
          id="floatingPassword"
          placeholder="Password"
          name='password'
          v-model="$v.field.password.$model"
          required
        />
        <label for="floatingPassword">Password</label>
      </div>
      <div class="checkbox mb-3 d-flex justify-content-between">
        <label
          ><input type="checkbox" name="remember" /> Remember me
        </label>
        <div>
          <small><a href="/reset">Ai uitat parola?</a></small>
        </div>
      </div>
      <button
        class="w-100 btn btn-lg btn-primary"
        :type="[errors > 0 ? 'button' : 'submit']"
        @click="clickLogin = 1"
      >
        Sign in
      </button>
    </form>
  </div>
</template>

<script>
import { required, email } from "vuelidate/lib/validators";

export default {
  props: ["nameRoute", "csrfToken"],
  data() {
    return {
      clickLogin: 0,
      field: {
        login: "",
        password: "",
      },
    };
  },
  validations: {
    field: {
      login: { required, email },
      password: { required },
    },
  },
  computed: {
    errors() {
      let errors = 0;
      for (const key in this.field) {
        if (
          !this.$v.field[key].required ||
          this.$v.field[key].$anyError == true
        ) {
          errors++;
        }
      }
      return errors;
    },
  },
};
</script>