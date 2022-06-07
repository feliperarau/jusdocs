import { Modal } from "bootstrap";

export const miroEmbed = () => {
  const ref = document.querySelectorAll("._miro-embed");

  const openModal = (modalController) => {
    modalController.show();
  };

  ref.forEach((component) => {
    const modalToggler = component.querySelector(".toggle-modal");
    const modal = component.querySelector(".modal");

    const modalController = Modal.getOrCreateInstance(modal);

    modalToggler?.addEventListener("click", (e) => openModal(modalController));
  });
};

miroEmbed();
