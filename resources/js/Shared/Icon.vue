<template>
    <!-- Chevron Down -->
    <svg
        v-if="name === 'cheveron-down'"
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 20 20"
    >
        <path
            d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"
        />
    </svg>

    <!-- Chevron Right -->
    <svg
        v-else-if="name === 'cheveron-right'"
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 20 20"
    >
        <polygon
            points="12.95 10.707 13.657 10 8 4.343 6.586 5.757 10.828 10 6.586 14.243 8 15.657 12.95 10.707"
        />
    </svg>

    <!-- Dashboard -->
    <svg
        v-else-if="name === 'dashboard'"
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 20 20"
    >
        <path
            d="M10 20a10 10 0 1 1 0-20 10 10 0 0 1 0 20zm-5.6-4.29a9.95 9.95 0 0 1 11.2 0 8 8 0 1 0-11.2 0zm6.12-7.64l3.02-3.02 1.41 1.41-3.02 3.02a2 2 0 1 1-1.41-1.41z"
        />
    </svg>

    <!-- Office -->
    <svg
        v-else-if="name === 'office'"
        xmlns="http://www.w3.org/2000/svg"
        width="100"
        height="100"
        viewBox="0 0 100 100"
    >
        <path
            fill-rule="evenodd"
            d="M7 0h86v100H57.108V88.418H42.892V100H7V0zm9 64h11v15H16V64zm57 0h11v15H73V64zm-19 0h11v15H54V64zm-19 0h11v15H35V64zM16 37h11v15H16V37zm57 0h11v15H73V37zm-19 0h11v15H54V37zm-19 0h11v15H35V37zM16 11h11v15H16V11zm57 0h11v15H73V11zm-19 0h11v15H54V11zm-19 0h11v15H35V11z"
        />
    </svg>

    <!-- Printer -->
    <svg
        v-else-if="name === 'printer'"
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 20 20"
    >
        <path
            d="M4 16H0V6h20v10h-4v4H4v-4zm2-4v6h8v-6H6zM4 0h12v5H4V0zM2 8v2h2V8H2zm4 0v2h2V8H6z"
        />
    </svg>

    <!-- Trash -->
    <svg
        v-else-if="name === 'trash'"
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 20 20"
    >
        <path
            d="M6 2l2-2h4l2 2h4v2H2V2h4zM3 6h14l-1 14H4L3 6zm5 2v10h1V8H8zm3 0v10h1V8h-1z"
        />
    </svg>

    <!-- Users -->
    <svg
        v-else-if="name === 'users'"
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 20 20"
    >
        <path
            d="M7 8a4 4 0 1 1 0-8 4 4 0 0 1 0 8zm0 1c2.15 0 4.2.4 6.1 1.09L12 16h-1.25L10 20H4l-.75-4H2L.9 10.09A17.93 17.93 0 0 1 7 9zm8.31.17c1.32.18 2.59.48 3.8.92L18 16h-1.25L16 20h-3.96l.37-2h1.25l1.65-8.83zM13 0a4 4 0 1 1-1.33 7.76 5.96 5.96 0 0 0 0-7.52C12.1.1 12.53 0 13 0z"
        />
    </svg>

    <!-- Settings -->
    <svg
        v-else-if="name === 'settings'"
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 20 20"
    >
        <path
            d="M10 16a6 6 0 1 1 0-12 6 6 0 0 1 0 12zM10 4a4 4 0 1 0 0 8 4 4 0 0 0 0-8z"
        />
    </svg>

    <!-- Add -->
    <svg
        v-else-if="name === 'add'"
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 20 20"
    >
        <path
            d="M10 1v18M1 10h18"
            stroke="currentColor"
            stroke-width="2"
            stroke-linecap="round"
            stroke-linejoin="round"
        />
    </svg>

    <!-- Bell -->
    <svg
        v-else-if="name === 'bell'"
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 20 20"
    >
        <path
            d="M10 2a6 6 0 0 1 6 6v5a6 6 0 0 1 4 5v1H0v-1a6 6 0 0 1 4-5V8a6 6 0 0 1 6-6zm1 13h-2v2h2v-2z"
        />
    </svg>

    <!-- Lock -->
    <svg
        v-else-if="name === 'lock'"
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 20 20"
    >
        <path
            d="M10 2a4 4 0 0 1 4 4v3h2v10H4V9h2V6a4 4 0 0 1 4-4zM8 6v3h4V6a2 2 0 0 0-4 0z"
        />
    </svg>

    <!-- Search -->
    <svg
        v-else-if="name === 'search'"
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 20 20"
    >
        <path
            d="M14 12l4 4-1.5 1.5-4-4a6 6 0 1 0-1.5 1.5L12 14a8 8 0 1 1 2-3z"
        />
    </svg>

    <!-- Star -->
    <svg
        v-else-if="name === 'star'"
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 20 20"
    >
        <path d="M10 15l-6 3 2-7-5-4h7l2-7 2 7h7l-5 4 2 7-6-3z" />
    </svg>

    <!-- Heart -->
    <svg
        v-else-if="name === 'heart'"
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 20 20"
    >
        <path
            d="M10 16s4-3.8 6-5c1.5-1.7 3-3 3-5 0-2.8-2.2-5-5-5-1.8 0-3.5 1-4.5 2.5C9.5 5 8 4 6 4 3.2 4 1 6.2 1 9c0 2 1.5 3.3 3 5 2 1.7 6 5 6 5z"
        />
    </svg>

    <!-- Circle -->
    <svg
        v-else-if="name === 'circle'"
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 20 20"
    >
        <circle
            cx="10"
            cy="10"
            r="8"
            stroke="currentColor"
            stroke-width="2"
            fill="none"
        />
    </svg>
    <svg
        v-else-if="name === 'road'"
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 20 20"
    >
        <path
            d="M2 18h16V2H2v16zm1-1V3h14v14H3zm7-5h2v2h-2v-2zm0-3h2v2h-2v-2zm-3 3h2v2H7v-2zm0-3h2v2H7v-2z"
        />
    </svg>
    <svg
        v-else-if="name === 'list'"
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 20 20"
    >
        <path d="M3 4h14V2H3v2zm0 4h14V6H3v2zm0 4h14V10H3v2zm0 4h14v-2H3v2z" />
    </svg>
    <svg
        v-else-if="name === 'categories'"
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 20 20"
    >
        <path d="M3 3h14v4H3V3zm0 6h14v4H3v-4zm0 6h14v4H3v-4z" />
    </svg>
    <svg
        v-else-if="name === 'patrol'"
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 20 20"
    >
        <path d="M3 2v3H2v2h1v7h14V7h1V5h-1V2H3zM4 6h12v2H4V6z" />
    </svg>
    <svg
        v-else-if="name === 'encroached-areas'"
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 20 20"
    >
        <path d="M5 2h10v3H5V2zM5 5h10v3H5V5zm0 5h10v3H5v-3z" />
    </svg>
    <svg
        v-else-if="name === 'confiscated-items'"
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 20 20"
    >
        <path d="M5 2h10v3H5V2zM5 5h10v2H5V5zm10 4H5v7h10V9zm0 9H5v1h10v-1z" />
    </svg>
    <svg
        v-else-if="name === 'arrests'"
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 20 20"
    >
        <path
            d="M8 2v4H6V4H5v4H4V4H2v2H1v2h2v2h1v3h1v2h1v1h2v-1h1v-2h1V8h2V6h-1V4h-1V2H8z"
        />
    </svg>
    <svg
        v-else-if="name === 'crimes'"
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 20 20"
    >
        <path
            d="M10 2a8 8 0 1 0 0 16A8 8 0 0 0 10 2zm0 14a6 6 0 1 1 0-12 6 6 0 0 1 0 12zm-1-9h2v4h-2zm0 5h2v2h-2z"
        />
    </svg>
    <svg
        v-else-if="name === 'stations'"
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 20 20"
    >
        <path d="M4 3H2v14h2V9h12v8h2V3h-2v6H4V3z" />
    </svg>
    <svg
        v-else-if="name === 'districts'"
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 20 20"
    >
        <path d="M2 2h6v6H2V2zm0 8h6v6H2v-6zm8-8h6v6h-6V2zm0 8h6v6h-6v-6z" />
    </svg>

    <svg
        v-else-if="name === 'list-item'"
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 20 20"
    >
        <path d="M3 4h14v2H3V4zm0 4h14v2H3V8zm0 4h14v2H3v-2zm0 4h14v2H3v-2z" />
    </svg>

    <!-- Dot Icon SVG -->
    <svg
        v-else-if="name === 'dot'"
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 20 20"
    >
        <circle cx="10" cy="10" r="5" />
    </svg>

    <svg
        v-else-if="name === 'menu'"
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 20 20"
    >
        <path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z" />
    </svg>

    <svg
        v-else-if="name === 'user-profile'"
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 20 20"
    >
        <path
            d="M10 10c2.761 0 5-2.239 5-5s-2.239-5-5-5-5 2.239-5 5 2.239 5 5 5zm0 2c-3.333 0-10 1.667-10 5v3h20v-3c0-3.333-6.667-5-10-5z"
        />
    </svg>

    <svg
        v-else-if="name === 'roles'"
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 20 20"
    >
        <path
            d="M2 2h16v2H2V2zm0 4h16v2H2V6zm0 4h16v2H2v-2zm0 4h16v2H2v-2zm0 4h16v2H2v-2z"
        />
    </svg>

    <svg
        v-else-if="name === 'permissions'"
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 20 20"
    >
        <path
            d="M10 2C5.58 2 2 5.58 2 10s3.58 8 8 8 8-3.58 8-8-3.58-8-8-8zM10 14c-2.21 0-4-1.79-4-4s1.79-4 4-4 4 1.79 4 4-1.79 4-4 4z"
        />
    </svg>

    <svg
        v-else-if="name === 'location'"
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 20 20"
    >
        <path
            d="M10 2C7.79 2 6 3.79 6 6c0 2.21 4 7 4 7s4-4.79 4-7c0-2.21-1.79-4-4-4zm0 10c-1.66 0-3-1.34-3-3s1.34-3 3-3 3 1.34 3 3-1.34 3-3 3z"
        />
    </svg>
    <svg
        v-else-if="name === 'operations'"
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 20 20"
    >
        <path
            d="M12.49 2.51a1 1 0 011.42 0l2.12 2.12a1 1 0 010 1.42l-2.83 2.83a1 1 0 01-1.42-1.42L13.89 5a7.41 7.41 0 00-1.06-.34 4.99 4.99 0 00-3.5 1.42l-.94.94a1 1 0 01-1.42-1.42l.94-.94a6.98 6.98 0 011.76-.9c2.5-.61 5.16.22 6.71 2.12l.35.37zM16 8h2v10a1 1 0 01-1 1h-2V8zM2 9h2v10a1 1 0 001 1h2V9H2z"
        />
    </svg>
    <svg
        v-else-if="name === 'countries'"
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 24 24"
        fill="currentColor"
    >
        <path
            d="M12 2a10 10 0 0 0-3.217.512A10 10 0 1 0 21.488 8.783 9.984 9.984 0 0 0 12 2zm0 2a8 8 0 0 1 7.418 4.928c-1.372.225-2.84.028-4.12-.584a1.965 1.965 0 0 0-2.472.658l-1.732-1.732a4.99 4.99 0 0 0-1.79-1.048L12 4zm-2.25 2.97c.678.244 1.237.798 1.44 1.474a4.964 4.964 0 0 1-.854 4.416L6 15.004A4.977 4.977 0 0 1 4 12a4.978 4.978 0 0 1 .574-2.317l5.176 2.306zM8.936 16l4.19 2.09c.836-.5 1.515-1.39 1.734-2.418l-3.67-1.83-2.254 2.158z"
        />
    </svg>
    <svg
        v-else-if="name === 'zones'"
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 24 24"
        fill="currentColor"
    >
        <path
            d="M12 2a10 10 0 1 1 0 20 10 10 0 0 1 0-20zm0 2a8 8 0 1 0 0 16 8 8 0 0 0 0-16zm4.293 10.707-4.293-4.293-4.293 4.293 1.414 1.414L12 12.828l2.879 2.879 1.414-1.414z"
        />
    </svg>
    <svg
        v-else-if="name === 'department'"
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 24 24"
        fill="currentColor"
    >
        <path
            d="M12 2a6 6 0 1 1 0 12A6 6 0 0 1 12 2zm0 2a4 4 0 1 0 0 8 4 4 0 0 0 0-8zM3 21v-2a4 4 0 0 1 4-4h10a4 4 0 0 1 4 4v2H3zm2-2h14v-1a2 2 0 0 0-2-2H7a2 2 0 0 0-2 2v1z"
        />
    </svg>

    <svg
        v-else-if="name === 'home'"
        xmlns="http://www.w3.org/2000/svg"
        width="24"
        height="24"
        viewBox="0 0 24 24"
    >
        <path
            d="M21 13v10h-6v-6h-6v6h-6v-10h-3l12-12 12 12h-3zm-1-5.907v-5.093h-3v2.093l3 3z"
        />
    </svg>

    <svg
        v-else-if="name === 'scheduling'"
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 24 24"
        fill="currentColor"
    >
        <path
            d="M3 3h18v2H3V3zm2 4h14a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V9a2 2 0 0 1 2-2zm1 3v8h12v-8H6zm3 2h6v2H9v-2z"
        />
    </svg>

    <svg
        v-else-if="name === 'team'"
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 24 24"
        fill="currentColor"
    >
        <path
            d="M16 11c1.66 0 3-1.34 3-3s-1.34-3-3-3-3 1.34-3 3 1.34 3 3 3zm-8 0c1.66 0 3-1.34 3-3s-1.34-3-3-3-3 1.34-3 3 1.34 3 3 3zm0 2c-2.67 0-8 1.34-8 4v3h16v-3c0-2.66-5.33-4-8-4zm8 0c-.29 0-.62.02-.97.05C16.61 13.62 18 14.84 18 16v3h6v-3c0-2.66-5.33-4-8-4z"
        />
    </svg>

    <svg
        v-else-if="name === 'funders'"
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 24 24"
        fill="currentColor"
    >
        <path
            d="M12 2C7.03 2 3 3.34 3 5v1c0 1.66 4.03 3 9 3s9-1.34 9-3V5c0-1.66-4.03-3-9-3zm0 8c-4.97 0-9-1.34-9-3v6c0 1.66 4.03 3 9 3s9-1.34 9-3V7c0 1.66-4.03 3-9 3zm0 6c-4.97 0-9-1.34-9-3v6c0 1.66 4.03 3 9 3s9-1.34 9-3v-6c0 1.66-4.03 3-9 3z"
        />
    </svg>

    <svg
        v-else-if="name === 'settings'"
        fill="currentColor"
        xmlns="http://www.w3.org/2000/svg"
        x="0px"
        y="0px"
        width="100"
        height="100"
        viewBox="0 0 24 24"
    >
        <path
            d="M 22.205078 2 A 1.0001 1.0001 0 0 0 21.21875 2.8378906 L 20.246094 8.7929688 C 19.076509 9.1331971 17.961243 9.5922728 16.910156 10.164062 L 11.996094 6.6542969 A 1.0001 1.0001 0 0 0 10.708984 6.7597656 L 6.8183594 10.646484 A 1.0001 1.0001 0 0 0 6.7070312 11.927734 L 10.164062 16.873047 C 9.583454 17.930271 9.1142098 19.051824 8.765625 20.232422 L 2.8359375 21.21875 A 1.0001 1.0001 0 0 0 2.0019531 22.205078 L 2.0019531 27.705078 A 1.0001 1.0001 0 0 0 2.8261719 28.691406 L 8.7597656 29.742188 C 9.1064607 30.920739 9.5727226 32.043065 10.154297 33.101562 L 6.6542969 37.998047 A 1.0001 1.0001 0 0 0 6.7597656 39.285156 L 10.648438 43.175781 A 1.0001 1.0001 0 0 0 11.927734 43.289062 L 16.882812 39.820312 C 17.936999 40.39548 19.054994 40.857928 20.228516 41.201172 L 21.21875 47.164062 A 1.0001 1.0001 0 0 0 22.205078 48 L 27.705078 48 A 1.0001 1.0001 0 0 0 28.691406 47.173828 L 29.751953 41.1875 C 30.920633 40.838997 32.033372 40.369697 33.082031 39.791016 L 38.070312 43.291016 A 1.0001 1.0001 0 0 0 39.351562 43.179688 L 43.240234 39.287109 A 1.0001 1.0001 0 0 0 43.34375 37.996094 L 39.787109 33.058594 C 40.355783 32.014958 40.813915 30.908875 41.154297 29.748047 L 47.171875 28.693359 A 1.0001 1.0001 0 0 0 47.998047 27.707031 L 47.998047 22.207031 A 1.0001 1.0001 0 0 0 47.160156 21.220703 L 41.152344 20.238281 C 40.80968 19.078827 40.350281 17.974723 39.78125 16.931641 L 43.289062 11.933594 A 1.0001 1.0001 0 0 0 43.177734 10.652344 L 39.287109 6.7636719 A 1.0001 1.0001 0 0 0 37.996094 6.6601562 L 33.072266 10.201172 C 32.023186 9.6248101 30.909713 9.1579916 29.738281 8.8125 L 28.691406 2.828125 A 1.0001 1.0001 0 0 0 27.705078 2 L 22.205078 2 z M 23.056641 4 L 26.865234 4 L 27.861328 9.6855469 A 1.0001 1.0001 0 0 0 28.603516 10.484375 C 30.066026 10.848832 31.439607 11.426549 32.693359 12.185547 A 1.0001 1.0001 0 0 0 33.794922 12.142578 L 38.474609 8.7792969 L 41.167969 11.472656 L 37.835938 16.220703 A 1.0001 1.0001 0 0 0 37.796875 17.310547 C 38.548366 18.561471 39.118333 19.926379 39.482422 21.380859 A 1.0001 1.0001 0 0 0 40.291016 22.125 L 45.998047 23.058594 L 45.998047 26.867188 L 40.279297 27.871094 A 1.0001 1.0001 0 0 0 39.482422 28.617188 C 39.122545 30.069817 38.552234 31.434687 37.800781 32.685547 A 1.0001 1.0001 0 0 0 37.845703 33.785156 L 41.224609 38.474609 L 38.53125 41.169922 L 33.791016 37.84375 A 1.0001 1.0001 0 0 0 32.697266 37.808594 C 31.44975 38.567585 30.074755 39.148028 28.617188 39.517578 A 1.0001 1.0001 0 0 0 27.876953 40.3125 L 26.867188 46 L 23.052734 46 L 22.111328 40.337891 A 1.0001 1.0001 0 0 0 21.365234 39.53125 C 19.90185 39.170557 18.522094 38.59371 17.259766 37.835938 A 1.0001 1.0001 0 0 0 16.171875 37.875 L 11.46875 41.169922 L 8.7734375 38.470703 L 12.097656 33.824219 A 1.0001 1.0001 0 0 0 12.138672 32.724609 C 11.372652 31.458855 10.793319 30.079213 10.427734 28.609375 A 1.0001 1.0001 0 0 0 9.6328125 27.867188 L 4.0019531 26.867188 L 4.0019531 23.052734 L 9.6289062 22.117188 A 1.0001 1.0001 0 0 0 10.435547 21.373047 C 10.804273 19.898143 11.383325 18.518729 12.146484 17.255859 A 1.0001 1.0001 0 0 0 12.111328 16.164062 L 8.8261719 11.46875 L 11.523438 8.7734375 L 16.185547 12.105469 A 1.0001 1.0001 0 0 0 17.28125 12.148438 C 18.536908 11.394293 19.919867 10.822081 21.384766 10.462891 A 1.0001 1.0001 0 0 0 22.132812 9.6523438 L 23.056641 4 z M 25 17 C 20.593567 17 17 20.593567 17 25 C 17 29.406433 20.593567 33 25 33 C 29.406433 33 33 29.406433 33 25 C 33 20.593567 29.406433 17 25 17 z M 25 19 C 28.325553 19 31 21.674447 31 25 C 31 28.325553 28.325553 31 25 31 C 21.674447 31 19 28.325553 19 25 C 19 21.674447 21.674447 19 25 19 z"
        ></path>
    </svg>
</template>

<script>
export default {
    props: {
        name: String,
    },
};
</script>
