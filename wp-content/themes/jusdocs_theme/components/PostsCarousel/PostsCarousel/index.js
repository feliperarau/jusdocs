// Assets
import Swiper, { Navigation, Pagination } from "swiper";

// Components
import "../../PostEntry/PostEntry";

export const postsCarousel = () => {
  const ref = document.querySelectorAll("._posts-carousel");

  ref.forEach((component) => {
    const sliderContainer = component.querySelector(".swiper-container");
    const slider = sliderContainer.querySelector(".swiper");
    const sliderPrev = sliderContainer.querySelector(".swiper-button-prev");
    const sliderNext = sliderContainer.querySelector(".swiper-button-next");

    const swiper = new Swiper(slider, {
      // configure Swiper to use modules
      modules: [Navigation, Pagination],
      slidesPerView: 3,
      loop: true,
      spaceBetween: 30,
      navigation: {
        nextEl: sliderNext,
        prevEl: sliderPrev,
      },
    });
  });
};

postsCarousel();
