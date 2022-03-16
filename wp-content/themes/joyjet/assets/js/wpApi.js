import axios from "axios";

const wpApi = axios.create({
  baseURL: window.wpAdminAjax + "admin-ajax.php",
  headers: {
    "Content-Type": "application/x-www-form-urlencoded",
  },
  validateStatus: (status) => {
    return status >= 200 && status < 400;
  },
});
const restApi = axios.create({
  baseURL: joyjet.rest_url,
  headers: {
    "Content-Type": "application/x-www-form-urlencoded",
    "X-WP-Nonce": joyjet.nonce,
  },
  validateStatus: (status) => {
    return status >= 200 && status < 400;
  },
});

const errorHandling = (error) => {
  if (typeof error.response !== "undefined") {
    const { data } = error.response;

    if (data.error) {
      return Promise.reject({
        error: data.error,
        errorCode: data.errorCode,
        message: data.message,
      });
    }
  }

  return Promise.reject({
    error: "GENERIC",
    message:
      "Something went wrong on client. Please try again or contact support.",
  });
};
wpApi.interceptors.response.use(function(response) {
  return response;
}, errorHandling);

restApi.interceptors.response.use(function(response) {
  return response;
}, errorHandling);

export const post = async (action, data, options = {}) => {
  options = {
    ...options,
    params: {
      action,
    },
  };

  return await wpApi.post("", data, options);
};
export const restPost = async (endpoint, data, options) => {
  options = {
    ...options,
  };

  return await restApi.post(endpoint, data, options);
};
