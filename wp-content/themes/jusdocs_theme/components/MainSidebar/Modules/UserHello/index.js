import $ from "jquery";

const userHello = () => {
  const ref = $("._user-hello");

  for (let c = 0; c < ref.length; c++) {
    const component = $(ref[c]);
  }
};
userHello();
