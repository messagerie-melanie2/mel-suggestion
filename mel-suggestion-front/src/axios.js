import axios from "axios";

const axiosClient = axios.create({
  baseURL: `${process.env.VUE_APP_ROOT_API}/api`,
  timeout: 10000,
  headers: {
    'Content-Type': 'application/json',
  },
  withCredentials: true
})

export default axiosClient;