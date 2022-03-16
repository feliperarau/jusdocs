import "../NavMenu";

const header = () => {
  const ref = document.querySelectorAll("._header");

  ref.forEach((component) => {
    console.log(component);
  });
};

header();
