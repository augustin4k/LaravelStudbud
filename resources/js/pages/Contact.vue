<template>
  <div id="top-of-form">
    <!-- alerts -->
    <div
      v-if="Message.Sended"
      class="alert alert-success w3-animate-left"
      role="alert"
    >
      <strong class="hstack gap-1"
        ><i class="fa-2x bi bi-telegram"></i> Mesajul a fost trimis cu
        succes!</strong
      >
    </div>
    <div v-if="errorSending" class="alert alert-danger" role="alert">
      <strong>Au aparut erori la trimiterea mesajului dat!</strong>
    </div>

    <form
      @submit.prevent=""
      method="post"
      class="card rounded"
      :class="[
        clickSend > 0 ? 'was-validated' : 'needs-validation',
        errorSending ? 'shake' : '',
      ]"
    >
      <div
        class="text-center card-header position-relative image-contact-header"
      >
        <i class="fa-solid fa-pen fa-2x"></i>
        <h3 class="text-center">Contactează-ne</h3>
        <div
          :class="{ 'd-none': ((clickSend > 0) & (errors > 0)) == false }"
          class="
            m-3
            px-3
            py-1
            rounded
            bg-danger
            text-white
            fw-bold
            position-absolute
            top-0
            end-0
          "
        >
          {{ errors }}
        </div>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-6">
            <div class="form-floating">
              <input
                type="text"
                placeholder="Numele"
                name="nume"
                class="form-control"
                required
                v-model="$v.field.flName.$model"
              />
              <label for="nume">Numele si prenumele</label>
            </div>
            <div class="separator mb-3"></div>
            <div class="form-floating">
              <input
                type="email"
                name="email"
                placeholder="Email de contact"
                class="form-control"
                required
                v-model="$v.field.email.$model"
              />
              <label for="email">Email-ul de contact</label>
            </div>
            <div class="separator mb-3"></div>
            <div class="form-floating">
              <input
                type="text"
                placeholder="Subiectul"
                name="subject"
                class="form-control"
                required
                v-model="$v.field.subject.$model"
              />
              <label for="subject">Subiectul</label>
            </div>
            <div class="separator mb-3"></div>
          </div>

          <div class="col-md-6">
            <div class="form-floating h-100">
              <textarea
                class="contact-textarea form-control"
                required
                placeholder="Mesajul"
                name="message"
                id=""
                rows="5"
                maxlength="500"
                v-model="$v.field.message.$model"
              ></textarea>
              <label>Mesajul</label>
            </div>
          </div>
        </div>
      </div>
      <div class="card-footer bg-white d-flex justify-content-end">
        <button
          v-if="Message.IsSending == false"
          class="btn btn-primary"
          :type="[errors > 0 ? 'button' : 'submit']"
          @click="(clickSend = 1), SendingMessage()"
        >
          Trimite
          <i class="fa fa-paper-plane" aria-hidden="true"></i>
        </button>
        <button v-else class="btn btn-primary" type="button" disabled>
          <span
            class="spinner-border spinner-border-sm"
            role="status"
            aria-hidden="true"
          ></span>
          Trimitere mesaj...
        </button>
      </div>
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
        flName: "",
        email: "",
        subject: "",
        message: "",
      },
      Message: {
        IsSending: false,
        Sended: false,
      },
      errorSending: false,
      clickSend: 0,
    };
  },
  validations: {
    field: {
      flName: { required },
      email: { required, email },
      subject: { required },
      message: { required },
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
  methods: {
    SendingFailed() {
      this.errorSending = true;
      setTimeout(() => {
        this.errorSending = false;
      }, 2000);
    },
    SendingMessage() {
      if (this.errors > 0) {
        this.SendingFailed();
      } else {
        this.Message.IsSending = true;
        axios
          .post("api/send_feedback", {
            info: this.field,
          })
          .then((response) => {
            this.Message.Sended = true;
            this.Message.IsSending = false;
            setTimeout(() => {
              this.Message.Sended = false;
              for (let key in this.field) this.field[key] = [];
              this.clickSend = 0;
            }, 2000);
          })
          .catch((error) => {
            this.Message.Sended = false;
            this.SendingFailed();
          })
          .finally(() => {
            const element = document.getElementById("top-of-form");
            element.scrollIntoView();
          });
      }
    },
  },
};
</script>