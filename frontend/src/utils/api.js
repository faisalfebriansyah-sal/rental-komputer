import axios from "axios";

const api = axios.create({
    baseURL: "http://10.10.9.26:8000/api",
    headers: {
        "Content-Type": "application/json",
        Accept: "application/json"
    }
})

export default api