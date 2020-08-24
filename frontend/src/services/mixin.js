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
  },
  renderDuration: function(minutes) {
    minutes = parseInt(minutes);

    let hours = parseInt(minutes / 60);
    minutes = minutes - hours * 60;

    let duration = [];
    if(hours) {
      duration.push(hours + this.$t("tasks.hour_prefix"));
    }

    if(minutes) {
      duration.push(minutes + this.$t("tasks.minute_prefix"));
    }

    return duration.join(' ');
  }
};
