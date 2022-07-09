<template>
  <div class="accordion-item" id="">
    <h2
      class="accordion-header"
      :id="'heading-institutie-' + tip_user + '-' + n"
    >
      <button
        class="accordion-button collapsed"
        type="button"
        data-bs-toggle="collapse"
        :data-bs-target="'#collapse-institutie-' + tip_user + '-' + n"
        aria-expanded="false"
        :aria-controls="'collapse-institutie-' + tip_user + '-' + n"
      >
        Institutia #{{ n }}
      </button>
    </h2>
    <div
      :id="'collapse-institutie-' + tip_user + '-' + n"
      class="accordion-collapse collapse"
      :aria-labelledby="'heading-institutie-' + tip_user + '-' + n"
      :data-bs-parent="'#accordion-' + tip_user"
    >
      <div class="accordion-body">
        <div class="form-floating">
          <input
            type="text"
            class="form-control"
            :name="'nume-institutie[' + tip_user + '][' + n + ']'"
            placeholder="Denumirea institutiei"
            required
            minlength="4"
            maxlength="40"
            v-model="$v.validationArray.denumireaInstitutie.$model"
          />
          <label for="" class="text-secondary">Denumirea institutiei</label>
        </div>
        <small
          :class="[
            ($v.validationArray.denumireaInstitutie.$dirty ||
              clickSubmit > 0) &&
            (!$v.validationArray.denumireaInstitutie.minLength ||
              !$v.validationArray.denumireaInstitutie.required)
              ? 'text-danger'
              : 'text-muted',
          ]"
          >* Denumirea intre 8 si 40 de caractere</small
        >
        <div class="mb-3 separator"></div>
        <div class="row range-container">
          <div class="col-sm-6">
            <div class="form-floating">
              <input
                required
                type="date"
                class="form-control"
                :name="'start-studii-institutie[' + tip_user + '][' + n + ']'"
                :id="'start-studii-institutie[' + tip_user + '][' + n + ']'"
                v-model="$v.validationArray.dataStart.$model"
              />
              <label
                class="text=secondary"
                :for="'start-studii-institutie[' + tip_user + '][' + n + ']'"
                >Start</label
              >
            </div>
            <div
              :class="[
                ($v.validationArray.dataStart.$dirty || clickSubmit > 0) &&
                !(validationArray.dataStart <= today)
                  ? ''
                  : 'd-none',
              ]"
              class="alert alert-danger"
              role="alert"
            >
              <small
                >Data de start nu poate fi mai mare decat ziua de azi</small
              >
            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-floating">
              <input
                type="date"
                required
                :readonly="prezentChecked"
                class="form-control"
                :name="'finish-studii-institutie[' + tip_user + '][' + n + ']'"
                :id="'finish-studii-institutie[' + tip_user + '][' + n + ']'"
                v-model="$v.validationArray.dataFinal.$model"
              />
              <label
                class="text-secondary"
                :for="'finish-studii-institutie[' + tip_user + '][' + n + ']'"
                >Finish</label
              >
            </div>
            <div class="form-check form-switch">
              <input
                v-model="prezentChecked"
                class="form-check-input"
                type="checkbox"
                role="switch"
                :id="'prezent-checked-institution[' + tip_user + '][' + n + ']'"
                :name="
                  'prezent-checked-institution[' + tip_user + '][' + n + ']'
                "
              />
              <label
                class="form-check-label"
                :for="
                  'prezent-checked-institution[' + tip_user + '][' + n + ']'
                "
              >
                <div class="text-secondary">Prezent?</div>
              </label>
            </div>
            <div
              :class="[
                ($v.validationArray.dataFinal.$dirty ||
                  $v.validationArray.dataStart.$dirty ||
                  clickSubmit > 0) &&
                validationArray.dataStart > validationArray.dataFinal
                  ? ''
                  : 'd-none',
              ]"
              class="alert alert-start-final alert-danger"
            >
              Data de start nu poate fi mai mare decat de final
            </div>
          </div>
        </div>
        <div class="separator mb-3"></div>
        <div class="row">
          <div class="col-sm-6">
            <div class="form-floating">
              <input
                required
                type="text"
                class="form-control"
                placeholder=""
                :name="'specialitate-user[' + tip_user + '][' + n + ']'"
                v-model="$v.validationArray.specializare.$model"
              />
              <label class="text-secondary">
                <template v-if="tip_user == 'profesor'">
                  <small> Specializarea (Ex. Profesor de fizica)</small>
                </template>
                <template v-else-if="tip_user == 'student1'">
                  <small> Specializarea (Ex. Informatica)</small>
                </template>
              </label>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-floating">
              <input
                required
                type="text"
                class="form-control"
                placeholder=""
                :name="'nivel-user[' + tip_user + '][' + n + ']'"
                v-model="$v.validationArray.grad.$model"
              />
              <label class="text-secondary">
                <small>
                  <template v-if="tip_user == 'profesor'">
                    Gradul tau (Conf. univ. dr.)
                  </template>
                  <template v-else-if="tip_user == 'student1'">
                    Stagiul studiilor (Ex. licenta)
                  </template>
                </small>
              </label>
            </div>
          </div>
        </div>
        <div class="mb-3 separator"></div>
        <div class="row">
          <div class="col-sm-6">
            <div class="form-floating">
              <select
                v-model="$v.validationArray.tara.$model"
                class="form-select"
                :name="'tara-institutie[' + tip_user + '][' + n + ']'"
                :id="'tara-institutie[' + tip_user + '][' + n + ']'"
                required
              >
                <option value="Afganistan">Afghanistan</option>
                <option value="Albania">Albania</option>
                <option value="Algeria">Algeria</option>
                <option value="American Samoa">American Samoa</option>
                <option value="Andorra">Andorra</option>
                <option value="Angola">Angola</option>
                <option value="Anguilla">Anguilla</option>
                <option value="Antigua & Barbuda">Antigua & Barbuda</option>
                <option value="Argentina">Argentina</option>
                <option value="Armenia">Armenia</option>
                <option value="Aruba">Aruba</option>
                <option value="Australia">Australia</option>
                <option value="Austria">Austria</option>
                <option value="Azerbaijan">Azerbaijan</option>
                <option value="Bahamas">Bahamas</option>
                <option value="Bahrain">Bahrain</option>
                <option value="Bangladesh">Bangladesh</option>
                <option value="Barbados">Barbados</option>
                <option value="Belarus">Belarus</option>
                <option value="Belgium">Belgium</option>
                <option value="Belize">Belize</option>
                <option value="Benin">Benin</option>
                <option value="Bermuda">Bermuda</option>
                <option value="Bhutan">Bhutan</option>
                <option value="Bolivia">Bolivia</option>
                <option value="Bonaire">Bonaire</option>
                <option value="Bosnia & Herzegovina">
                  Bosnia & Herzegovina
                </option>
                <option value="Botswana">Botswana</option>
                <option value="Brazil">Brazil</option>
                <option value="British Indian Ocean Ter">
                  British Indian Ocean Ter
                </option>
                <option value="Brunei">Brunei</option>
                <option value="Bulgaria">Bulgaria</option>
                <option value="Burkina Faso">Burkina Faso</option>
                <option value="Burundi">Burundi</option>
                <option value="Cambodia">Cambodia</option>
                <option value="Cameroon">Cameroon</option>
                <option value="Canada">Canada</option>
                <option value="Canary Islands">Canary Islands</option>
                <option value="Cape Verde">Cape Verde</option>
                <option value="Cayman Islands">Cayman Islands</option>
                <option value="Central African Republic">
                  Central African Republic
                </option>
                <option value="Chad">Chad</option>
                <option value="Channel Islands">Channel Islands</option>
                <option value="Chile">Chile</option>
                <option value="China">China</option>
                <option value="Christmas Island">Christmas Island</option>
                <option value="Cocos Island">Cocos Island</option>
                <option value="Colombia">Colombia</option>
                <option value="Comoros">Comoros</option>
                <option value="Congo">Congo</option>
                <option value="Cook Islands">Cook Islands</option>
                <option value="Costa Rica">Costa Rica</option>
                <option value="Cote DIvoire">Cote DIvoire</option>
                <option value="Croatia">Croatia</option>
                <option value="Cuba">Cuba</option>
                <option value="Curaco">Curacao</option>
                <option value="Cyprus">Cyprus</option>
                <option value="Czech Republic">Czech Republic</option>
                <option value="Denmark">Denmark</option>
                <option value="Djibouti">Djibouti</option>
                <option value="Dominica">Dominica</option>
                <option value="Dominican Republic">Dominican Republic</option>
                <option value="East Timor">East Timor</option>
                <option value="Ecuador">Ecuador</option>
                <option value="Egypt">Egypt</option>
                <option value="El Salvador">El Salvador</option>
                <option value="Equatorial Guinea">Equatorial Guinea</option>
                <option value="Eritrea">Eritrea</option>
                <option value="Estonia">Estonia</option>
                <option value="Ethiopia">Ethiopia</option>
                <option value="Falkland Islands">Falkland Islands</option>
                <option value="Faroe Islands">Faroe Islands</option>
                <option value="Fiji">Fiji</option>
                <option value="Finland">Finland</option>
                <option value="France">France</option>
                <option value="French Guiana">French Guiana</option>
                <option value="French Polynesia">French Polynesia</option>
                <option value="French Southern Ter">French Southern Ter</option>
                <option value="Gabon">Gabon</option>
                <option value="Gambia">Gambia</option>
                <option value="Georgia">Georgia</option>
                <option value="Germany">Germany</option>
                <option value="Ghana">Ghana</option>
                <option value="Gibraltar">Gibraltar</option>
                <option value="Great Britain">Great Britain</option>
                <option value="Greece">Greece</option>
                <option value="Greenland">Greenland</option>
                <option value="Grenada">Grenada</option>
                <option value="Guadeloupe">Guadeloupe</option>
                <option value="Guam">Guam</option>
                <option value="Guatemala">Guatemala</option>
                <option value="Guinea">Guinea</option>
                <option value="Guyana">Guyana</option>
                <option value="Haiti">Haiti</option>
                <option value="Hawaii">Hawaii</option>
                <option value="Honduras">Honduras</option>
                <option value="Hong Kong">Hong Kong</option>
                <option value="Hungary">Hungary</option>
                <option value="Iceland">Iceland</option>
                <option value="Indonesia">Indonesia</option>
                <option value="India">India</option>
                <option value="Iran">Iran</option>
                <option value="Iraq">Iraq</option>
                <option value="Ireland">Ireland</option>
                <option value="Isle of Man">Isle of Man</option>
                <option value="Israel">Israel</option>
                <option value="Italy">Italy</option>
                <option value="Jamaica">Jamaica</option>
                <option value="Japan">Japan</option>
                <option value="Jordan">Jordan</option>
                <option value="Kazakhstan">Kazakhstan</option>
                <option value="Kenya">Kenya</option>
                <option value="Kiribati">Kiribati</option>
                <option value="Korea North">Korea North</option>
                <option value="Korea Sout">Korea South</option>
                <option value="Kuwait">Kuwait</option>
                <option value="Kyrgyzstan">Kyrgyzstan</option>
                <option value="Laos">Laos</option>
                <option value="Latvia">Latvia</option>
                <option value="Lebanon">Lebanon</option>
                <option value="Lesotho">Lesotho</option>
                <option value="Liberia">Liberia</option>
                <option value="Libya">Libya</option>
                <option value="Liechtenstein">Liechtenstein</option>
                <option value="Lithuania">Lithuania</option>
                <option value="Luxembourg">Luxembourg</option>
                <option value="Macau">Macau</option>
                <option value="Macedonia">Macedonia</option>
                <option value="Madagascar">Madagascar</option>
                <option value="Malaysia">Malaysia</option>
                <option value="Malawi">Malawi</option>
                <option value="Maldives">Maldives</option>
                <option value="Mali">Mali</option>
                <option value="Malta">Malta</option>
                <option value="Marshall Islands">Marshall Islands</option>
                <option value="Martinique">Martinique</option>
                <option value="Mauritania">Mauritania</option>
                <option value="Mauritius">Mauritius</option>
                <option value="Mayotte">Mayotte</option>
                <option value="Mexico">Mexico</option>
                <option value="Midway Islands">Midway Islands</option>
                <option value="Moldova">Moldova</option>
                <option value="Monaco">Monaco</option>
                <option value="Mongolia">Mongolia</option>
                <option value="Montserrat">Montserrat</option>
                <option value="Morocco">Morocco</option>
                <option value="Mozambique">Mozambique</option>
                <option value="Myanmar">Myanmar</option>
                <option value="Nambia">Nambia</option>
                <option value="Nauru">Nauru</option>
                <option value="Nepal">Nepal</option>
                <option value="Netherland Antilles">Netherland Antilles</option>
                <option value="Netherlands">
                  Netherlands (Holland, Europe)
                </option>
                <option value="Nevis">Nevis</option>
                <option value="New Caledonia">New Caledonia</option>
                <option value="New Zealand">New Zealand</option>
                <option value="Nicaragua">Nicaragua</option>
                <option value="Niger">Niger</option>
                <option value="Nigeria">Nigeria</option>
                <option value="Niue">Niue</option>
                <option value="Norfolk Island">Norfolk Island</option>
                <option value="Norway">Norway</option>
                <option value="Oman">Oman</option>
                <option value="Pakistan">Pakistan</option>
                <option value="Palau Island">Palau Island</option>
                <option value="Palestine">Palestine</option>
                <option value="Panama">Panama</option>
                <option value="Papua New Guinea">Papua New Guinea</option>
                <option value="Paraguay">Paraguay</option>
                <option value="Peru">Peru</option>
                <option value="Phillipines">Philippines</option>
                <option value="Pitcairn Island">Pitcairn Island</option>
                <option value="Poland">Poland</option>
                <option value="Portugal">Portugal</option>
                <option value="Puerto Rico">Puerto Rico</option>
                <option value="Qatar">Qatar</option>
                <option value="Republic of Montenegro">
                  Republic of Montenegro
                </option>
                <option value="Republic of Serbia">Republic of Serbia</option>
                <option value="Reunion">Reunion</option>
                <option value="Romania">Romania</option>
                <option value="Russia">Russia</option>
                <option value="Rwanda">Rwanda</option>
                <option value="St Barthelemy">St Barthelemy</option>
                <option value="St Eustatius">St Eustatius</option>
                <option value="St Helena">St Helena</option>
                <option value="St Kitts-Nevis">St Kitts-Nevis</option>
                <option value="St Lucia">St Lucia</option>
                <option value="St Maarten">St Maarten</option>
                <option value="St Pierre & Miquelon">
                  St Pierre & Miquelon
                </option>
                <option value="St Vincent & Grenadines">
                  St Vincent & Grenadines
                </option>
                <option value="Saipan">Saipan</option>
                <option value="Samoa">Samoa</option>
                <option value="Samoa American">Samoa American</option>
                <option value="San Marino">San Marino</option>
                <option value="Sao Tome & Principe">Sao Tome & Principe</option>
                <option value="Saudi Arabia">Saudi Arabia</option>
                <option value="Senegal">Senegal</option>
                <option value="Seychelles">Seychelles</option>
                <option value="Sierra Leone">Sierra Leone</option>
                <option value="Singapore">Singapore</option>
                <option value="Slovakia">Slovakia</option>
                <option value="Slovenia">Slovenia</option>
                <option value="Solomon Islands">Solomon Islands</option>
                <option value="Somalia">Somalia</option>
                <option value="South Africa">South Africa</option>
                <option value="Spain">Spain</option>
                <option value="Sri Lanka">Sri Lanka</option>
                <option value="Sudan">Sudan</option>
                <option value="Suriname">Suriname</option>
                <option value="Swaziland">Swaziland</option>
                <option value="Sweden">Sweden</option>
                <option value="Switzerland">Switzerland</option>
                <option value="Syria">Syria</option>
                <option value="Tahiti">Tahiti</option>
                <option value="Taiwan">Taiwan</option>
                <option value="Tajikistan">Tajikistan</option>
                <option value="Tanzania">Tanzania</option>
                <option value="Thailand">Thailand</option>
                <option value="Togo">Togo</option>
                <option value="Tokelau">Tokelau</option>
                <option value="Tonga">Tonga</option>
                <option value="Trinidad & Tobago">Trinidad & Tobago</option>
                <option value="Tunisia">Tunisia</option>
                <option value="Turkey">Turkey</option>
                <option value="Turkmenistan">Turkmenistan</option>
                <option value="Turks & Caicos Is">Turks & Caicos Is</option>
                <option value="Tuvalu">Tuvalu</option>
                <option value="Uganda">Uganda</option>
                <option value="United Kingdom">United Kingdom</option>
                <option value="Ukraine">Ukraine</option>
                <option value="United Arab Erimates">
                  United Arab Emirates
                </option>
                <option value="United States of America">
                  United States of America
                </option>
                <option value="Uraguay">Uruguay</option>
                <option value="Uzbekistan">Uzbekistan</option>
                <option value="Vanuatu">Vanuatu</option>
                <option value="Vatican City State">Vatican City State</option>
                <option value="Venezuela">Venezuela</option>
                <option value="Vietnam">Vietnam</option>
                <option value="Virgin Islands (Brit)">
                  Virgin Islands (Brit)
                </option>
                <option value="Virgin Islands (USA)">
                  Virgin Islands (USA)
                </option>
                <option value="Wake Island">Wake Island</option>
                <option value="Wallis & Futana Is">Wallis & Futana Is</option>
                <option value="Yemen">Yemen</option>
                <option value="Zaire">Zaire</option>
                <option value="Zambia">Zambia</option>
                <option value="Zimbabwe">Zimbabwe</option>
              </select>
              <label
                class="form-floating text-secondary"
                :for="'tara-institutie[' + tip_user + '][' + n + ']'"
                >Tara institutiei</label
              >
            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-floating">
              <input
                required
                type="text"
                class="form-control"
                placeholder="Tara institutiei"
                :name="'orasul-institutie[' + tip_user + '][' + n + ']'"
                :id="'orasul-institutie[' + tip_user + '][' + n + ']'"
                v-model="$v.validationArray.oras.$model"
              />
              <label
                class="form-floating text-secondary"
                :for="'orasul-institutie[' + tip_user + '][' + n + ']'"
                >Orasul institutiei</label
              >
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { required, minLength } from "vuelidate/lib/validators";

export default {
  props: ["nrInstitutie", "tip_user", "clickSubmit", "infoInstitution"],
  data() {
    return {
      // tip_user: this.tip_user,
      n: this.nrInstitutie,
      prezentChecked: false,
      today: new Date().toISOString().split("T")[0],
      validationArray: {
        dataStart: "",
        dataFinal: "",
        tara: "Afganistan",
        oras: "",
        grad: "",
        specializare: "",
        denumireaInstitutie: "",
      },
    };
  },
  validations: {
    validationArray: {
      dataFinal: { required },
      dataStart: {
        required,
      },
      denumireaInstitutie: { required, minLength: minLength(8) },
      specializare: { required },
      tara: { required },
      oras: { required },
      grad: { required },
      tara: { required },
    },
  },
  created() {
    this.$emit("callBackErrors", this.errorTotal, this.nrInstitutie);
  },
  mounted() {
    this.createSettings();
  },
  computed: {
    errorTotal() {
      let error = 0;
      for (const key in this.validationArray) {
        if (
          this.$v.validationArray[key].$anyError == true ||
          this.$v.validationArray[key].required == false
        ) {
          error++;
        }
        if (
          key == "dataStart" &&
          this.$v.validationArray[key].$anyError == false &&
          this.$v.validationArray[key].required == true &&
          this.$v.validationArray.dataFinal.required == true &&
          this.validationArray[key] > this.validationArray.dataFinal
        ) {
          error++;
        }
        if (
          key == "dataStart" &&
          this.$v.validationArray[key].$anyError == false &&
          this.$v.validationArray[key].required == true &&
          this.$v.validationArray.dataFinal.required == true &&
          !(this.validationArray[key] <= this.today)
        ) {
          error++;
        }
      }
      return error;
    },
  },
  methods: {
    createSettings() {
      console.log(this.infoInstitution);
      if (this.infoInstitution != null) {
        var dictionar = {
          denumireaInstitutie: "name_institution",
          dataStart: "date_start",
          dataFinal: "date_finish",
          tara: "country",
          oras: "city",
          grad: "grade",
          specializare: "specialization",
        };
        for (const key in dictionar) {
          this.validationArray[key] = this.infoInstitution[dictionar[key]];
        }
        this.prezentChecked = this.infoInstitution.prezent_activity;
      }
    },
  },
  watch: {
    infoInstitution: {
      handler(newValue, oldValue) {
        this.createSettings();
      },
      deep: true,
    },
    prezentChecked: function () {
      this.validationArray.dataFinal = new Date().toISOString().split("T")[0];
    },
    errorTotal: function () {
      this.$emit("callBackErrors", this.errorTotal, this.nrInstitutie);
    },
  },
};
</script>
