import axios from "axios";

// Create an Axios instance
const axiosInstance = axios.create({
    baseURL: "http://127.0.0.1:8000", // Adjust base URL as needed
});

// Interceptor to handle responses
axiosInstance.interceptors.response.use(
    (response) => response, // Pass through successful responses
    (error) => {
        // Suppress console logs for specific status codes (e.g., 403 Forbidden)
        if (error.response?.status === 403) {
            // Temporarily suppress console.error
            const originalConsoleError = console.error;
            console.error = function () {}; // No-op function
            setTimeout(() => (console.error = originalConsoleError), 1000); // Restore original after 1 second
        }

        // Reject the error so it can be handled in application logic
        return Promise.reject(error);
    }
);

export default axiosInstance;
