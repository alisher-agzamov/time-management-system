export default {
  methods: {
    dateFormatter: function (date) {
      return date.toJSON().slice(0,10);
    },
    handleApiErrors: function(data) {

      this.$Progress.fail();
      this.buttonDisabled = false;

      // Display form field errors
      if(data.errors && data.errors) {
        for (let i in data.errors) {
          this.serverErrors = this.serverErrors.concat(data.errors[i]);
        }
      }
      // Display error message
      else if(data.error) {
        this.serverErrors.push(data.error);
      }
    },
    runFormValidation: function()
    {
      this.errors = [];

      for (const group in this.rules) {
        for (const field in this.rules[group]) {

          for (let i = 0; i < this.rules[group][field].length; i++) {

            let rules = this.rules[group][field][i].split(':');

            if(this.rules[group][field][i] == 'required') {
              if(!formValidationRuleRequired(this[group][field])) {

                let message = this.$t("validation.required.default").format_str(field);
                if(this.$t("validation.required." + field) != "validation.required." + field) {
                  message = this.$t("validation.required." + field);
                }

                this.errors.push(message);
                break;
              }
            }

            else if(this.rules[group][field][i] == 'email') {
              if(!formValidationRuleEmail(this[group][field])) {
                this.errors.push(this.$t("validation.email"));
                break;
              }
            }

            else if(this.rules[group][field][i] == 'confirmed') {
              if(!formValidationRuleСonfirmed(this[group][field], this[group][field + '_confirmation'])) {
                this.errors.push(this.$t("validation.confirmed"));
                break;
              }
            }

            else if(rules[0] == 'max') {

              if(!formValidationRuleMax(this[group][field] + '', rules[1])) {
                this.errors.push(this.$t("validation.max." + field).format_str(rules[1]));
                break;
              }
            }

            else if(rules[0] == 'min') {
              if(!formValidationRuleMin(this[group][field] + '', rules[1])) {
                this.errors.push(this.$t("validation.min." + field).format_str(rules[1]));
                break;
              }
            }

          }
        }
      }

      return this.errors;
    }
  },
  computed: {
    checkForm: function (e) {

      this.errors = [];

      if(!this.autoCheckForm) {
        return this.errors;
      }

      return this.runFormValidation();
    }
  }
};

/**
 * Form validation rule: required
 * @param value
 * @returns {boolean}
 */
function formValidationRuleRequired(value)
{
  if(Number.isInteger(value)) {
    if(value <= 0) {
      return false;
    }
  }
  else if(!value
    || !value.trim()) {
    return false;
  }

  return true;
}

/**
 * Form validation rule: email
 * @param value
 * @returns {boolean}
 */
function formValidationRuleEmail(value) {
  var re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
  return re.test(value);
}

/**
 * Form validation rule: confirmed
 * @param left
 * @param right
 * @returns {boolean}
 */
function formValidationRuleСonfirmed(left, right) {
  return left == right;
}

/**
 * Form validation rule: max
 * @param value
 * @param max
 * @returns {boolean}
 */
function formValidationRuleMax(value, max)
{
  if(value.trim().length > max) {
    return false;
  }

  return true;
}

/**
 * Form validation rule: min
 * @param value
 * @param min
 * @returns {boolean}
 */
function formValidationRuleMin(value, min)
{
  if(value.trim().length < min) {
    return false;
  }

  return true;
}

/**
 * Format string like sprintf
 */
if (!String.prototype.format_str) {
  String.prototype.format_str = function() {
    let args = arguments;
    return this.replace(/{(\d+)}/g, function(match, number) {
      return typeof args[number] != 'undefined'
        ? args[number]
        : match
        ;
    });
  };
}
