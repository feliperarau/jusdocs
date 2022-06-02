import "../NavMenu";

const header = () => {
  const ref = document.querySelectorAll("._header");

  ref.forEach((component) => {
    const scrollListener = () => {
      if (window.scrollY > 50) {
        component.classList.add("sticky");
      } else {
        component.classList.remove("sticky");
      }
    };

    window.addEventListener("scroll", scrollListener);
  });
};

header();
