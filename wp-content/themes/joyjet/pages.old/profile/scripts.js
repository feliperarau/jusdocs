import $ from "jquery";
import "../../assets/js/main";
import "../../assets/fonts/icons.font";

// Components
import "../../components/Modal";
import "../../components/Profile/FormProfessional";
import "../../components/Profile/FormPatient";
import "../../components/ProfileAvatar";

const profile = () => {
  const profile = $("#profile-page");
  const form = profile.find(".profile-form");
  const notification = $("#profile-updated");

  form.on("profile:updated", function(e) {
    notification.modal("show");
  });
};

profile();
