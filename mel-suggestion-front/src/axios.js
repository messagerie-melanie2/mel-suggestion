import axios from "axios";

const axiosClient = axios.create({
  baseURL: `${process.env.VUE_APP_ROOT_API}/api`
})

export default axiosClient;