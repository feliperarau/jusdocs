// Dependencies
import $ from "jquery";
import { post } from "../../../assets/js/wpApi";
import "jquery-mask-plugin";

// Components
import "../../../components/FormControls/InputMasked";

const forgotPasswordForm = () => {
  const ref = $(".forgot-password-form");
  const refFormAlert = ref.find(".form-alert");
  const refFormSpinner = ref.find(".form-spinner");

  ref.on("submit", async function(e) {
    e.preventDefault();

    refFormSpinner.removeClass("d-none");
    ref.find("button").attr("disabled", "disabled");

    try {
      const { data } = await post("forgot_password", ref.serialize());
      refFormAlert.removeClass("alert-danger");
      refFormAlert.html(data.message);
    } catch (error) {
      refFormAlert.addClass("alert-danger");
      refFormAlert.html(error.message);
    } finally {
      ref.find("input[type=email]").val("");
      refFormAlert.removeClass("d-none");
      refFormSpinner.addClass("d-none");
      ref.find("button").removeAttr("disabled");
    }
  });
};

forgotPasswordForm();
