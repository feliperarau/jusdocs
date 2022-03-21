import $ from "jquery";
import { post } from "../../../assets/js/wpApi";
import "../../../components/FormControls/InputMasked";
import "../../../components/FormControls/Input";
//import "../../components/Modal";

const costumerForm = () => {
  const ref = $("._login-form");
  const refFormAlert = ref.find(".form-alert");
  const refFormSpinner = ref.find(".form-spinner");

  ref.on("submit", async function(e) {
    e.preventDefault();

    refFormSpinner.removeClass("d-none");
    ref.find("button").attr("disabled", "disabled");

    try {
      const { data } = await post("user_login", ref.serialize());
      window.location.href = data.redirect_to;
    } catch (error) {
      ref.find("button").removeAttr("disabled");
      ref.find("input[type=text], input[type=password]").val("");
      refFormAlert.addClass("alert-danger").removeClass("d-none");
      refFormAlert.html(error.message);
    } finally {
      refFormSpinner.addClass("d-none");
    }
  });
};

costumerForm();
