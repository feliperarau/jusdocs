export const hero = () => {
  const ref = document.querySelectorAll("._hero");

  ref.forEach((component) => {
    console.log(component);
  });
};

hero();
