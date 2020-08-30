import axios from "axios";

export default {
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
  }
};
