<!DOCTYPE html>
<html x-data="data()" lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Distribution Automation Management System</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet"/>
    <link href="assets/css/tailwind.css" rel="stylesheet" />
    <link href="assets/favicon.png" rel="shortcut icon" />
    <script src="assets/js/alpine-2.8.1.min.js"></script>
    <script src="assets/js/jquery-3.6.0.min.js"></script>
    <script src="assets/js/sweetalert2.all.min.js"></script>
    <script src="assets/js/polyfill.js"></script>
    <script src="assets/js/helper.js?ts=<?=time()?>"></script>
    <script src="assets/js/controller.js?ts=<?=time()?>"></script>
  </head>
  <body>
    <div
      class="flex h-screen bg-white text-gray-700"
      :class="{ 'overflow-hidden': isSideMenuOpen}"
    >
      <aside
        class="z-20 hidden w-64 overflow-y-auto bg-white md:block flex-shrink-0 border-r"
      >
        <div class="py-4">
          <span class="block ml-6 text-lg font-bold">DAMS</span>
          <span class="block ml-6 text-xs text-gray-500"><small>Distribution Automation Management System</small></span>
          <ul class="mt-6">
            <li class="relative ml-3 mr-3 px-6 py-3 bg-blue-50 rounded-full hover:bg-blue-50 hover:rounded-full">
              <a
                class="inline-flex items-center w-full text-sm transition-colors duration-150 text-blue-400 hover:text-blue-500"
                href="#"
              >
                <svg 
                  class="w-5 h-5"
                  aria-hidden="true"
                  fill="none"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  viewBox="0 0 24 24"
                  stroke="currentColor">
                  <path fill-rule="evenodd" clip-rule="evenodd" d="M16.2853 2H19.5519C20.9035 2 21.9998 3.1059 21.9998 4.47018V7.7641C21.9998 9.12735 20.9035 10.2343 19.5519 10.2343H16.2853C14.9328 10.2343 13.8364 9.12735 13.8364 7.7641V4.47018C13.8364 3.1059 14.9328 2 16.2853 2Z"/>
                  <path fill-rule="evenodd" clip-rule="evenodd" d="M4.44892 2H7.71449C9.06703 2 10.1634 3.1059 10.1634 4.47018V7.7641C10.1634 9.12735 9.06703 10.2343 7.71449 10.2343H4.44892C3.09638 10.2343 2 9.12735 2 7.7641V4.47018C2 3.1059 3.09638 2 4.44892 2Z"/>
                  <path fill-rule="evenodd" clip-rule="evenodd" d="M4.44892 13.7657H7.71449C9.06703 13.7657 10.1634 14.8716 10.1634 16.2369V19.5298C10.1634 20.8941 9.06703 22 7.71449 22H4.44892C3.09638 22 2 20.8941 2 19.5298V16.2369C2 14.8716 3.09638 13.7657 4.44892 13.7657Z"/>
                  <path fill-rule="evenodd" clip-rule="evenodd" d="M16.2853 13.7657H19.5519C20.9035 13.7657 21.9998 14.8716 21.9998 16.2369V19.5298C21.9998 20.8941 20.9035 22 19.5519 22H16.2853C14.9328 22 13.8364 20.8941 13.8364 19.5298V16.2369C13.8364 14.8716 14.9328 13.7657 16.2853 13.7657Z"/>
                </svg>
                <span class="ml-4">Dashboard</span>
              </a>
            </li>
            <li class="relative ml-3 mr-3 px-6 py-3 hover:bg-blue-50 hover:rounded-full">
              <a
                class="inline-flex items-center w-full text-sm transition-colors duration-150 hover:text-blue-500"
                href="#"
              >
                <svg 
                  class="w-5 h-5"
                  aria-hidden="true"
                  fill="none"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  viewBox="0 0 24 24"
                  stroke="currentColor">
                  <path fill-rule="evenodd" clip-rule="evenodd" d="M7.4222 19.8203C7.8442 19.8203 8.1872 20.1633 8.1872 20.5853C8.1872 21.0073 7.8442 21.3493 7.4222 21.3493C7.0002 21.3493 6.6582 21.0073 6.6582 20.5853C6.6582 20.1633 7.0002 19.8203 7.4222 19.8203Z"/>
                  <path fill-rule="evenodd" clip-rule="evenodd" d="M18.6747 19.8203C19.0967 19.8203 19.4397 20.1633 19.4397 20.5853C19.4397 21.0073 19.0967 21.3493 18.6747 21.3493C18.2527 21.3493 17.9097 21.0073 17.9097 20.5853C17.9097 20.1633 18.2527 19.8203 18.6747 19.8203Z"/>
                  <path d="M2.75 3.25L4.83 3.61L5.793 15.083C5.871 16.018 6.652 16.736 7.59 16.736H18.502C19.398 16.736 20.158 16.078 20.287 15.19L21.236 8.632C21.353 7.823 20.726 7.099 19.909 7.099H5.164"/>
                  <path d="M14.1255 10.795H16.8985"/>
                </svg>
                <span class="ml-4">PO to SEIN</span>
              </a>
            </li>
            <li class="relative ml-3 mr-3 px-6 py-3 hover:bg-blue-50 hover:rounded-full">
              <a
                class="inline-flex items-center w-full text-sm transition-colors duration-150 hover:text-blue-500"
                href="#"
              >
                <svg 
                  class="w-5 h-5"
                  aria-hidden="true"
                  fill="none"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  viewBox="0 0 24 24"
                  stroke="currentColor">
                  <path d="M8.84819 12.314V16.059"/>
                  <path d="M10.7586 14.1867H6.9375"/>
                  <path d="M15.3665 12.428H15.2595"/>
                  <path d="M17.179 16.0026H17.072"/>
                  <path d="M8.07227 2C8.07227 2.74048 8.68475 3.34076 9.44029 3.34076H10.4968C11.6624 3.34492 12.6065 4.27026 12.6118 5.41266V6.08771"/>
                  <path fill-rule="evenodd" clip-rule="evenodd" d="M16.4283 21.9626C13.4231 22.0135 10.473 22.0114 7.57275 21.9626C4.3535 21.9626 2 19.6664 2 16.5113V11.8617C2 8.70661 4.3535 6.41038 7.57275 6.41038C10.4889 6.36053 13.4411 6.36157 16.4283 6.41038C19.6476 6.41038 22 8.70764 22 11.8617V16.5113C22 19.6664 19.6476 21.9626 16.4283 21.9626Z"/>
                </svg>
                <span class="ml-4">Product Management</span>
              </a>
            </li>
            <li class="relative ml-3 mr-3 px-6 py-3 hover:bg-blue-50 hover:rounded-full">
              <a
                class="inline-flex items-center w-full text-sm transition-colors duration-150 hover:text-blue-500"
                href="#"
              >
              <svg 
                class="w-5 h-5"
                aria-hidden="true"
                fill="none"
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                viewBox="0 0 24 24"
                stroke="currentColor">
                  <path fill-rule="evenodd" clip-rule="evenodd" d="M11.737 0.761902H5.084C3.025 0.761902 1.25 2.4309 1.25 4.4909V15.2039C1.25 17.3799 2.909 19.1149 5.084 19.1149H13.073C15.133 19.1149 16.802 17.2649 16.802 15.2039V6.0379L11.737 0.761902Z"/>
                  <path d="M11.4746 0.750198V3.6592C11.4746 5.0792 12.6236 6.2312 14.0426 6.2342C15.3596 6.2372 16.7066 6.2382 16.7976 6.2322"/>
                  <path d="M11.2847 13.5578H5.8877"/>
                  <path d="M9.24272 8.60561H5.88672"/>
                </svg>
                <span class="ml-4">Inventory Management</span>
              </a>
            </li>
            <li class="relative ml-3 mr-3 px-6 py-3 hover:bg-blue-50 hover:rounded-full">
              <a
                class="inline-flex items-center w-full text-sm transition-colors duration-150 hover:text-blue-500"
                href="#"
              >
                <svg 
                  class="w-5 h-5"
                  aria-hidden="true"
                  fill="none"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  viewBox="0 0 24 24"
                  stroke="currentColor">
                  <path d="M13.7729 8.30504V5.27304C13.7729 3.18904 12.0839 1.50004 10.0009 1.50004C7.91694 1.49104 6.21994 3.17204 6.21094 5.25604V5.27304V8.30504"/>
                  <path fill-rule="evenodd" clip-rule="evenodd" d="M14.7422 20.0003H5.25778C2.90569 20.0003 1 18.0953 1 15.7453V10.2293C1 7.87933 2.90569 5.97433 5.25778 5.97433H14.7422C17.0943 5.97433 19 7.87933 19 10.2293V15.7453C19 18.0953 17.0943 20.0003 14.7422 20.0003Z"/>
                </svg>
                <span class="ml-4">Order Management</span>
              </a>
            </li>
            <li class="relative ml-3 mr-3 px-6 py-3 hover:bg-blue-50 hover:rounded-full">
              <a
                class="inline-flex items-center w-full text-sm transition-colors duration-150 hover:text-blue-500"
                href="#"
              >
                <svg 
                  class="w-5 h-5"
                  aria-hidden="true"
                  fill="none"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  viewBox="0 0 24 24"
                  stroke="currentColor">
                    <path d="M1.09277 8.40426H18.9167"/>
                    <path d="M14.442 12.3097H14.4512"/>
                    <path d="M10.0045 12.3097H10.0137"/>
                    <path d="M5.55818 12.3097H5.56744"/>
                    <path d="M14.442 16.1962H14.4512"/>
                    <path d="M10.0045 16.1962H10.0137"/>
                    <path d="M5.55818 16.1962H5.56744"/>
                    <path d="M14.0433 1V4.29078"/>
                    <path d="M5.96515 1V4.29078"/>
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M14.2383 2.57919H5.77096C2.83427 2.57919 1 4.21513 1 7.22222V16.2719C1 19.3262 2.83427 21 5.77096 21H14.229C17.175 21 19 19.3546 19 16.3475V7.22222C19.0092 4.21513 17.1842 2.57919 14.2383 2.57919Z"/>
                </svg>
                <span class="ml-4">Delivery Management</span>
              </a>
            </li>
            <li class="relative ml-3 mr-3 px-6 py-3 hover:bg-blue-50 hover:rounded-full">
              <a
                class="inline-flex items-center w-full text-sm transition-colors duration-150 hover:text-blue-500"
                href="#"
              >
                <svg 
                  class="w-5 h-5"
                  aria-hidden="true"
                  fill="none"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  viewBox="0 0 24 24"
                  stroke="currentColor">
                  <path d="M9.15722 20.7714V17.7047C9.1572 16.9246 9.79312 16.2908 10.581 16.2856H13.4671C14.2587 16.2856 14.9005 16.9209 14.9005 17.7047V17.7047V20.7809C14.9003 21.4432 15.4343 21.9845 16.103 22H18.0271C19.9451 22 21.5 20.4607 21.5 18.5618V18.5618V9.83784C21.4898 9.09083 21.1355 8.38935 20.538 7.93303L13.9577 2.6853C12.8049 1.77157 11.1662 1.77157 10.0134 2.6853L3.46203 7.94256C2.86226 8.39702 2.50739 9.09967 2.5 9.84736V18.5618C2.5 20.4607 4.05488 22 5.97291 22H7.89696C8.58235 22 9.13797 21.4499 9.13797 20.7714V20.7714"/>
                </svg>
                <span class="ml-4">Distributor Management</span>
              </a>
            </li>
            <li class="relative ml-3 mr-3 px-6 py-3 hover:bg-blue-50 hover:rounded-full">
              <a
                class="inline-flex items-center w-full text-sm transition-colors duration-150 hover:text-blue-500"
                href="#"
              >
              <svg 
                  class="w-5 h-5"
                  aria-hidden="true"
                  fill="none"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  viewBox="0 0 24 24"
                  stroke="currentColor">
                  <path d="M9.15722 20.7714V17.7047C9.1572 16.9246 9.79312 16.2908 10.581 16.2856H13.4671C14.2587 16.2856 14.9005 16.9209 14.9005 17.7047V17.7047V20.7809C14.9003 21.4432 15.4343 21.9845 16.103 22H18.0271C19.9451 22 21.5 20.4607 21.5 18.5618V18.5618V9.83784C21.4898 9.09083 21.1355 8.38935 20.538 7.93303L13.9577 2.6853C12.8049 1.77157 11.1662 1.77157 10.0134 2.6853L3.46203 7.94256C2.86226 8.39702 2.50739 9.09967 2.5 9.84736V18.5618C2.5 20.4607 4.05488 22 5.97291 22H7.89696C8.58235 22 9.13797 21.4499 9.13797 20.7714V20.7714"/>
                </svg>
                <span class="ml-4">Depo Management</span>
              </a>
            </li>
            <li class="relative ml-3 mr-3 px-6 py-3 hover:bg-blue-50 hover:rounded-full">
              <a
                class="inline-flex items-center w-full text-sm transition-colors duration-150 hover:text-blue-500"
                href="#"
              >
              <svg 
                  class="w-5 h-5"
                  aria-hidden="true"
                  fill="none"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  viewBox="0 0 24 24"
                  stroke="currentColor">
                  <path d="M9.15722 20.7714V17.7047C9.1572 16.9246 9.79312 16.2908 10.581 16.2856H13.4671C14.2587 16.2856 14.9005 16.9209 14.9005 17.7047V17.7047V20.7809C14.9003 21.4432 15.4343 21.9845 16.103 22H18.0271C19.9451 22 21.5 20.4607 21.5 18.5618V18.5618V9.83784C21.4898 9.09083 21.1355 8.38935 20.538 7.93303L13.9577 2.6853C12.8049 1.77157 11.1662 1.77157 10.0134 2.6853L3.46203 7.94256C2.86226 8.39702 2.50739 9.09967 2.5 9.84736V18.5618C2.5 20.4607 4.05488 22 5.97291 22H7.89696C8.58235 22 9.13797 21.4499 9.13797 20.7714V20.7714"/>
                </svg>
                <span class="ml-4">Store Management</span>
              </a>
            </li>
            <li class="relative ml-3 mr-3 px-6 py-3 hover:bg-blue-50 hover:rounded-full">
              <a
                class="inline-flex items-center w-full text-sm transition-colors duration-150 hover:text-blue-500"
                href="#"
              >
                <svg 
                  class="w-5 h-5"
                  aria-hidden="true"
                  fill="none"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  viewBox="0 0 24 24"
                  stroke="currentColor">
                  <path d="M17.5957 10.9319C19.198 10.9319 20.4979 9.63295 20.4979 8.03062C20.4979 6.42829 19.198 5.12937 17.5957 5.12937"/>
                  <path d="M18.9297 14.0847C19.4082 14.1177 19.8839 14.1856 20.3524 14.291C21.0032 14.4184 21.786 14.6852 22.0647 15.2691C22.2425 15.6431 22.2425 16.0785 22.0647 16.4534C21.7869 17.0373 21.0032 17.3032 20.3524 17.437"/>
                  <path d="M6.28889 10.9319C4.68655 10.9319 3.38672 9.63295 3.38672 8.03062C3.38672 6.42829 4.68655 5.12937 6.28889 5.12937"/>
                  <path d="M4.95565 14.0847C4.47715 14.1177 4.0014 14.1856 3.53298 14.291C2.88215 14.4184 2.09931 14.6852 1.82156 15.2691C1.64281 15.6431 1.64281 16.0785 1.82156 16.4534C2.0984 17.0373 2.88215 17.3032 3.53298 17.437"/>
                  <path fill-rule="evenodd" clip-rule="evenodd" d="M11.9367 14.7095C15.1835 14.7095 17.9573 15.2009 17.9573 17.1671C17.9573 19.1325 15.2018 19.6421 11.9367 19.6421C8.68893 19.6421 5.91602 19.1508 5.91602 17.1845C5.91602 15.2183 8.67152 14.7095 11.9367 14.7095Z"/>
                  <path fill-rule="evenodd" clip-rule="evenodd" d="M11.9383 11.9049C9.79699 11.9049 8.08008 10.188 8.08008 8.04575C8.08008 5.90442 9.79699 4.1875 11.9383 4.1875C14.0797 4.1875 15.7966 5.90442 15.7966 8.04575C15.7966 10.188 14.0797 11.9049 11.9383 11.9049Z"/>
                </svg>
                <span class="ml-4">User Management</span>
              </a>
            </li>
            <li class="relative ml-3 mr-3 px-6 py-3 hover:bg-blue-50 hover:rounded-full">
              <a
                class="inline-flex items-center w-full text-sm transition-colors duration-150 hover:text-blue-500"
                href="#"
              >
                <svg 
                  class="w-5 h-5"
                  aria-hidden="true"
                  fill="none"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  viewBox="0 0 24 24"
                  stroke="currentColor">
                  <path fill-rule="evenodd" clip-rule="evenodd" d="M20.8074 7.62357L20.185 6.54348C19.6584 5.62956 18.4914 5.31427 17.5763 5.83867V5.83867C17.1406 6.0953 16.6208 6.16811 16.1314 6.04104C15.6421 5.91398 15.2233 5.59747 14.9676 5.16133C14.803 4.8841 14.7146 4.56835 14.7113 4.24599V4.24599C14.7261 3.72918 14.5311 3.22836 14.1708 2.85762C13.8104 2.48689 13.3153 2.27782 12.7982 2.27803H11.5442C11.0377 2.27802 10.552 2.47987 10.1947 2.8389C9.83742 3.19793 9.63791 3.68455 9.64034 4.19107V4.19107C9.62533 5.23688 8.77321 6.07677 7.7273 6.07666C7.40494 6.07331 7.08919 5.9849 6.81197 5.82036V5.82036C5.89679 5.29597 4.72985 5.61125 4.20327 6.52517L3.53508 7.62357C3.00914 8.53635 3.32013 9.70256 4.23073 10.2323V10.2323C4.82263 10.574 5.18726 11.2055 5.18726 11.889C5.18726 12.5725 4.82263 13.204 4.23073 13.5458V13.5458C3.32129 14.0719 3.00996 15.2353 3.53508 16.1453V16.1453L4.16666 17.2346C4.41338 17.6797 4.82734 18.0082 5.31693 18.1474C5.80652 18.2865 6.33137 18.2249 6.77535 17.976V17.976C7.21181 17.7213 7.73192 17.6515 8.22007 17.7822C8.70822 17.9128 9.12397 18.233 9.3749 18.6716C9.53943 18.9488 9.62784 19.2646 9.63119 19.587V19.587C9.63119 20.6435 10.4877 21.5 11.5442 21.5H12.7982C13.8512 21.5 14.7062 20.6491 14.7113 19.5961V19.5961C14.7088 19.088 14.9096 18.6 15.2689 18.2407C15.6282 17.8814 16.1162 17.6806 16.6243 17.6831C16.9459 17.6917 17.2604 17.7797 17.5397 17.9394V17.9394C18.4524 18.4653 19.6186 18.1543 20.1484 17.2437V17.2437L20.8074 16.1453C21.0625 15.7075 21.1325 15.186 21.0019 14.6963C20.8714 14.2067 20.551 13.7893 20.1117 13.5366V13.5366C19.6725 13.2839 19.3521 12.8665 19.2215 12.3769C19.091 11.8873 19.161 11.3658 19.4161 10.9279C19.582 10.6383 19.8221 10.3982 20.1117 10.2323V10.2323C21.0169 9.70285 21.3271 8.54345 20.8074 7.63272V7.63272V7.62357Z"/>
                  <circle cx="12.1752" cy="11.889" r="2.63616"/>
                </svg>
                <span class="ml-4">Access Control</span>
              </a>
            </li>
            <li class="relative ml-3 mr-3 px-6 py-3 hover:bg-blue-50 hover:rounded-full">
              <a
                class="inline-flex items-center w-full text-sm transition-colors duration-150 hover:text-blue-500"
                href="#"
              >
                <svg 
                  class="w-5 h-5"
                  aria-hidden="true"
                  fill="none"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  viewBox="0 0 24 24"
                  stroke="currentColor">
                  <path d="M16.8407 20.1642V6.54644"/>
                  <path d="M20.9173 16.0681L16.8395 20.1648L12.7617 16.0681"/>
                  <path d="M6.91102 3.83289V17.4507"/>
                  <path d="M2.83398 7.929L6.91176 3.83233L10.9895 7.929"/>
                </svg>
                <span class="ml-4">Retur Management</span>
              </a>
            </li>
            <li class="relative ml-3 mr-3 px-6 py-3 hover:bg-blue-50 hover:rounded-full">
              <a
                class="inline-flex items-center w-full text-sm transition-colors duration-150 hover:text-blue-500"
                href="#"
              >
                <svg 
                  class="w-5 h-5"
                  aria-hidden="true"
                  fill="none"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  viewBox="0 0 24 24"
                  stroke="currentColor">
                  <path fill-rule="evenodd" clip-rule="evenodd" d="M21.5232 9.75286C20.8841 5.81946 17.5431 2.89322 13.5598 2.77803C13.3704 2.77061 13.1858 2.83879 13.0467 2.96757C12.9077 3.09634 12.8255 3.27513 12.8184 3.46453V3.46453V3.5286L13.2669 10.238C13.2963 10.6896 13.6846 11.033 14.1364 11.0069L20.8641 10.5584C21.0537 10.5443 21.2298 10.4553 21.3536 10.311C21.4773 10.1667 21.5383 9.97895 21.5232 9.78947V9.75286Z"/>
                  <path d="M8.90102 6.76888C9.32898 6.66989 9.76692 6.88886 9.9445 7.29062C9.99102 7.38511 10.019 7.48765 10.0269 7.59268C10.1184 8.89245 10.3106 11.7391 10.4205 13.2769C10.4392 13.5539 10.5676 13.812 10.7772 13.9941C10.9868 14.1762 11.2603 14.2673 11.5372 14.2471V14.2471L17.1848 13.8993C17.4369 13.8842 17.6841 13.9739 17.8678 14.1472C18.0515 14.3205 18.1555 14.5621 18.155 14.8146V14.8146C17.9262 18.225 15.4759 21.0761 12.1387 21.8151C8.80153 22.5541 5.37669 21.0041 3.7294 18.0092C3.23765 17.1472 2.92623 16.1943 2.81406 15.2082C2.76611 14.9056 2.74772 14.5991 2.75914 14.2929C2.76886 10.6509 5.32665 7.51284 8.89187 6.76888"/>
                </svg>
                <span class="ml-4">Report</span>
              </a>
            </li>
            <li class="relative ml-9 mr-3 px-6 py-3 hover:bg-blue-50 hover:rounded-full">
              <a
                class="inline-flex items-center w-full text-sm transition-colors duration-150 hover:text-blue-500"
                href="#"
              >
                <svg 
                  class="w-5 h-5"
                  aria-hidden="true"
                  fill="none"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  viewBox="0 0 24 24"
                  stroke="currentColor">
                  <path fill-rule="evenodd" clip-rule="evenodd" d="M14.736 2.7619H8.084C6.025 2.7619 4.25 4.4309 4.25 6.4909V17.2279C4.25 19.4039 5.908 21.1149 8.084 21.1149H16.072C18.132 21.1149 19.802 19.2879 19.802 17.2279V8.0379L14.736 2.7619Z"/>
                  <path d="M14.4727 2.7502V5.6592C14.4727 7.0792 15.6217 8.2312 17.0417 8.2342C18.3577 8.2372 19.7047 8.2382 19.7957 8.2322"/>
                  <path d="M11.6406 9.4406V16.0136"/>
                  <path d="M8.80273 12.2912L11.6407 9.4402L14.4797 12.2912"/>
                </svg>
                <span class="ml-4">Stock on Hand</span>
              </a>
            </li>
            <li class="relative ml-9 mr-3 px-6 py-3 hover:bg-blue-50 hover:rounded-full">
              <a
                class="inline-flex items-center w-full text-sm transition-colors duration-150 hover:text-blue-500"
                href="#"
              >
                <svg 
                  class="w-5 h-5"
                  aria-hidden="true"
                  fill="none"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  viewBox="0 0 24 24"
                  stroke="currentColor">
                  <path d="M7.24414 14.7815L10.2373 10.8913L13.6515 13.5732L16.5805 9.79291"/>
                  <circle cx="19.9945" cy="4.20023" r="1.9222"/>
                  <path d="M14.9238 3.12013H7.65606C4.64462 3.12013 2.77734 5.25286 2.77734 8.2643V16.3467C2.77734 19.3581 4.60801 21.4817 7.65606 21.4817H16.2602C19.2716 21.4817 21.1389 19.3581 21.1389 16.3467V9.30778"/>
                </svg>
                <span class="ml-4">Sales by Models</span>
              </a>
            </li>
          </ul>
        </div>
      </aside>
      <div
        x-show="isSideMenuOpen"
        x-transition:enter="transition ease-in-out duration-150"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in-out duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-10 flex items-end bg-black bg-opacity-50 sm:items-center sm:justify-center"
      ></div>
      <aside
        class="fixed inset-y-0 z-20 flex-shrink-0 w-64 mt-16 overflow-y-auto bg-white md:hidden"
        x-show="isSideMenuOpen"
        x-transition:enter="transition ease-in-out duration-150"
        x-transition:enter-start="opacity-0 transform -translate-x-20"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in-out duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0 transform -translate-x-20"
        @click.away="closeSideMenu"
        @keydown.escape="closeSideMenu"
      >
        <div class="py-4">
          <ul>
          <li class="relative ml-3 mr-3 px-6 py-3 bg-blue-50 rounded-xl">
              <a
                class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 text-blue-400 hover:text-gray-800"
                href="#"
              >
                <svg 
                  class="w-5 h-5"
                  aria-hidden="true"
                  fill="none"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  viewBox="0 0 24 24"
                  stroke="currentColor">
                  <path fill-rule="evenodd" clip-rule="evenodd" d="M16.2853 2H19.5519C20.9035 2 21.9998 3.1059 21.9998 4.47018V7.7641C21.9998 9.12735 20.9035 10.2343 19.5519 10.2343H16.2853C14.9328 10.2343 13.8364 9.12735 13.8364 7.7641V4.47018C13.8364 3.1059 14.9328 2 16.2853 2Z"/>
                  <path fill-rule="evenodd" clip-rule="evenodd" d="M4.44892 2H7.71449C9.06703 2 10.1634 3.1059 10.1634 4.47018V7.7641C10.1634 9.12735 9.06703 10.2343 7.71449 10.2343H4.44892C3.09638 10.2343 2 9.12735 2 7.7641V4.47018C2 3.1059 3.09638 2 4.44892 2Z"/>
                  <path fill-rule="evenodd" clip-rule="evenodd" d="M4.44892 13.7657H7.71449C9.06703 13.7657 10.1634 14.8716 10.1634 16.2369V19.5298C10.1634 20.8941 9.06703 22 7.71449 22H4.44892C3.09638 22 2 20.8941 2 19.5298V16.2369C2 14.8716 3.09638 13.7657 4.44892 13.7657Z"/>
                  <path fill-rule="evenodd" clip-rule="evenodd" d="M16.2853 13.7657H19.5519C20.9035 13.7657 21.9998 14.8716 21.9998 16.2369V19.5298C21.9998 20.8941 20.9035 22 19.5519 22H16.2853C14.9328 22 13.8364 20.8941 13.8364 19.5298V16.2369C13.8364 14.8716 14.9328 13.7657 16.2853 13.7657Z"/>
                </svg>
                <span class="ml-4">Dashboard</span>
              </a>
            </li>
            <li class="relative px-6 py-3">
              <a
                class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800"
                href="#"
              >
                <svg 
                  class="w-5 h-5"
                  aria-hidden="true"
                  fill="none"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  viewBox="0 0 24 24"
                  stroke="currentColor">
                  <path fill-rule="evenodd" clip-rule="evenodd" d="M7.4222 19.8203C7.8442 19.8203 8.1872 20.1633 8.1872 20.5853C8.1872 21.0073 7.8442 21.3493 7.4222 21.3493C7.0002 21.3493 6.6582 21.0073 6.6582 20.5853C6.6582 20.1633 7.0002 19.8203 7.4222 19.8203Z"/>
                  <path fill-rule="evenodd" clip-rule="evenodd" d="M18.6747 19.8203C19.0967 19.8203 19.4397 20.1633 19.4397 20.5853C19.4397 21.0073 19.0967 21.3493 18.6747 21.3493C18.2527 21.3493 17.9097 21.0073 17.9097 20.5853C17.9097 20.1633 18.2527 19.8203 18.6747 19.8203Z"/>
                  <path d="M2.75 3.25L4.83 3.61L5.793 15.083C5.871 16.018 6.652 16.736 7.59 16.736H18.502C19.398 16.736 20.158 16.078 20.287 15.19L21.236 8.632C21.353 7.823 20.726 7.099 19.909 7.099H5.164"/>
                  <path d="M14.1255 10.795H16.8985"/>
                </svg>
                <span class="ml-4">PO to SEIN</span>
              </a>
            </li>
          </ul>
        </div>
      </aside>
      <div class="flex flex-col flex-1">
        <header class="z-10 py-4 bg-white shadow-md">
          <div
            class="container flex items-center justify-between h-full px-6 mx-auto"
          >
            <button
              class="p-1 -ml-1 mr-5 rounded-md md:hidden focus:outline-none focus:shadow-outline-blue"
              @click="toggleSideMenu"
              aria-label="Menu"
            >
              <svg
                class="w-6 h-6"
                aria-hidden="true"
                fill="currentColor"
                viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
              </svg>
            </button>
            <div class="flex justify-center flex-1">
              <svg width="126" height="19" viewBox="0 0 126 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M8.36375 13.3106C8.5371 13.7388 8.48214 14.2877 8.39757 14.6204C8.24959 15.2108 7.84369 15.8137 6.6429 15.8137C5.51821 15.8137 4.83325 15.1734 4.83325 14.2087V12.4914H0.000488281V13.8553C0.000488281 17.8055 3.15891 18.9989 6.54565 18.9989C9.80132 18.9989 12.482 17.9094 12.909 14.953C13.1289 13.4228 12.9682 12.4207 12.8921 12.0507C12.131 8.34162 5.29835 7.23557 4.78674 5.16067C4.7157 4.85469 4.70994 4.53754 4.76983 4.22925C4.89667 3.65959 5.28989 3.04003 6.41458 3.04003C7.47161 3.04003 8.08469 3.68038 8.08469 4.64506V5.74281H12.5792V4.49537C12.5792 0.636648 9.05716 0.0337219 6.51182 0.0337219C3.30689 0.0337219 0.689675 1.07741 0.211895 3.96729C0.0850511 4.75733 0.0639104 5.46421 0.254177 6.35405C1.03638 9.9716 7.43356 11.0194 8.36375 13.3106Z" fill="#0C4DA2"/>
                <path d="M66.7431 13.3106C66.9165 13.7388 66.8615 14.2877 66.777 14.6204C66.629 15.2108 66.2231 15.8137 65.0223 15.8137C63.8976 15.8137 63.2126 15.1734 63.2126 14.2087V12.4914H58.3799V13.8553C58.3799 17.8055 61.5383 18.9989 64.925 18.9989C68.1807 18.9989 70.8614 17.9094 71.2884 14.953C71.5083 13.4228 71.3476 12.4207 71.2715 12.0507C70.5104 8.34162 63.6777 7.23557 63.1661 5.16067C63.0951 4.85469 63.0893 4.53754 63.1492 4.22925C63.2761 3.65959 63.6693 3.04003 64.794 3.04003C65.851 3.04003 66.4641 3.68038 66.4641 4.64506V5.74281H70.9586V4.49537C70.9586 0.636648 67.4366 0.0337219 64.8912 0.0337219C61.6863 0.0337219 59.0691 1.07741 58.5913 3.96729C58.4644 4.75733 58.4433 5.46421 58.6336 6.35405C59.4158 9.9716 65.813 11.0194 66.7431 13.3106Z" fill="#0C4DA2"/>
                <path d="M19.2807 0.59919L15.9404 18.3086H20.807L23.272 2.23748H23.3735L25.7709 18.3086H30.6121L27.293 0.595032L19.2807 0.59919ZM46.4887 0.59919L44.269 14.138H44.1633L41.9477 0.59919H34.6034L34.206 18.3086H38.7132L38.8231 2.38718H38.9246L41.9308 18.3086H46.5014L49.5119 2.39133H49.6091L49.7233 18.3086H54.2262L53.8288 0.595032L46.4887 0.59919Z" fill="#0C4DA2"/>
                <path d="M82.0262 15.6557C83.2777 15.6557 83.6625 14.8075 83.7513 14.375C83.7893 14.1838 83.7935 13.926 83.7935 13.6973V0.599223H88.3515V13.2939C88.3523 13.6824 88.3382 14.0708 88.3092 14.4582C87.9921 17.7639 85.3368 18.8367 82.0262 18.8367C78.7113 18.8367 76.056 17.7639 75.7389 14.4582C75.7263 14.2836 75.6924 13.6183 75.6967 13.2939V0.595065H80.2546V13.6931C80.2504 13.926 80.2588 14.1838 80.2969 14.375C80.3814 14.8075 80.7704 15.6557 82.0262 15.6557ZM104.046 0.599223L104.292 14.4998H104.194L100.051 0.599223H93.3703V18.1215H97.7972L97.5519 3.7386H97.6492L102.093 18.1215H108.507V0.599223H104.046ZM119.585 15.4728C120.887 15.4728 121.344 14.6619 121.424 14.1838C121.462 13.9883 121.466 13.7388 121.466 13.5185V10.9404H119.619V8.36241H125.999V13.111C125.999 13.4436 125.99 13.6848 125.935 14.2752C125.639 17.5019 122.794 18.6537 119.602 18.6537C116.409 18.6537 113.568 17.5019 113.268 14.2752C113.217 13.6848 113.204 13.4436 113.204 13.111V5.65964C113.204 5.34363 113.247 4.78644 113.281 4.49537C113.682 1.18136 116.409 0.116882 119.602 0.116882C122.794 0.116882 125.593 1.17304 125.923 4.49121C125.982 5.05672 125.965 5.65548 125.965 5.65548V6.25009H121.424V5.25631C121.424 5.25631 121.424 4.84049 121.369 4.58269C121.284 4.19183 120.946 3.29368 119.568 3.29368C118.257 3.29368 117.872 4.14609 117.775 4.58269C117.72 4.81555 117.699 5.13156 117.699 5.41847V13.5143C117.699 13.7388 117.707 13.9883 117.741 14.1879C117.826 14.6619 118.282 15.4728 119.585 15.4728Z" fill="#0C4DA2"/>
              </svg>
            </div>
            <ul class="flex items-center flex-shrink-0 space-x-6">
              <li class="relative">
                <button
                  class="align-middle rounded-full bg-gray-300 p-1"
                  @click="toggleProfileMenu"
                  @keydown.escape="closeProfileMenu"
                  aria-label="Account"
                  aria-haspopup="true"
                >
                  <svg
                    class="w-5 h-5"
                    aria-hidden="true"
                    fill="none"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    viewBox="0 0 24 24"
                    stroke="currentColor">
                    <circle cx="11.5788" cy="7.27803" r="4.77803"/>
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M4.00002 18.7014C3.99873 18.3655 4.07385 18.0337 4.2197 17.7311C4.67736 16.8158 5.96798 16.3307 7.03892 16.111C7.81128 15.9462 8.59431 15.836 9.38217 15.7815C10.8408 15.6533 12.3079 15.6533 13.7666 15.7815C14.5544 15.8367 15.3374 15.9468 16.1099 16.111C17.1808 16.3307 18.4714 16.77 18.9291 17.7311C19.2224 18.3479 19.2224 19.064 18.9291 19.6808C18.4714 20.6419 17.1808 21.0812 16.1099 21.2918C15.3384 21.4634 14.5551 21.5766 13.7666 21.6304C12.5794 21.7311 11.3866 21.7494 10.1968 21.6854C9.92221 21.6854 9.65677 21.6854 9.38217 21.6304C8.59663 21.5773 7.81632 21.4641 7.04807 21.2918C5.96798 21.0812 4.68652 20.6419 4.2197 19.6808C4.0746 19.3747 3.99955 19.0401 4.00002 18.7014Z"/>
                  </svg>
                </button>
                <template x-if="isProfileMenuOpen">
                  <ul
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    @click.away="closeProfileMenu"
                    @keydown.escape="closeProfileMenu"
                    class="absolute right-0 w-56 p-2 mt-2 space-y-2 text-gray-600 bg-white border border-gray-100 rounded-md shadow-md"
                    aria-label="submenu"
                  >
                    <li class="flex">
                      <a
                        class="inline-flex items-center w-full px-2 py-1 text-sm font-semibold transition-colors duration-150 rounded-md hover:bg-blue-50 hover:text-blue-500"
                        href="#"
                      >
                        <svg 
                          class="w-4 h-4 mr-3"
                          aria-hidden="true"
                          fill="none"
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          viewBox="0 0 24 24"
                          stroke="currentColor">
                          <path d="M15.0155 7.38951V6.45651C15.0155 4.42151 13.3655 2.77151 11.3305 2.77151H6.45548C4.42148 2.77151 2.77148 4.42151 2.77148 6.45651V17.5865C2.77148 19.6215 4.42148 21.2715 6.45548 21.2715H11.3405C13.3695 21.2715 15.0155 19.6265 15.0155 17.5975V16.6545"/>
                          <path d="M21.8086 12.0214H9.76758"/>
                          <path d="M18.8809 9.10632L21.8089 12.0213L18.8809 14.9373"/>
                        </svg>
                        <span>Log out</span>
                      </a>
                    </li>
                  </ul>
                </template>
              </li>
              <li class="relative text-gray-500 text-sm hidden sm:block cursor-pointer"
                @click="toggleProfileMenu"
                @keydown.escape="closeProfileMenu"
                aria-label="Account"
                aria-haspopup="true">
                Dummy Administrator
              </li>
            </ul>
          </div>
        </header>
        <main class="h-full pb-16 overflow-y-auto">
          <div class="flex items-center h-28 p-4 mb-8 text-2xl text-white shadow-md" style="background-image: linear-gradient(90deg, #0072E3 0%, rgba(129, 192, 255, 0) 100%), url('assets/img/bg/scott-webb-aHhhdKUP77M.jpg')">
            <span class="ml-6">Dashboard</span>
          </div>
          <div class="container px-6 mx-auto grid">
            <span id="lblBreadcrumb" class="text-gray-400">Dashboard</span>

            <div class="w-full my-6 mb-8 overflow-hidden rounded-lg shadow-xs">
              <div class="relative text-gray-600 w-52 mb-6 float-left">
                <input type="search" name="serch" placeholder="Search" class="bg-white h-10 px-5 pr-10 rounded-lg text-sm focus:outline-none border">
                <button type="submit" class="absolute right-0 top-0 mt-3 mr-4">
                <svg class="h-4 w-4 fill-current" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" viewBox="0 0 56.966 56.966" xml:space="preserve" width="512px" height="512px">
                  <path d="M55.146,51.887L41.588,37.786c3.486-4.144,5.396-9.358,5.396-14.786c0-12.682-10.318-23-23-23s-23,10.318-23,23  s10.318,23,23,23c4.761,0,9.298-1.436,13.177-4.162l13.661,14.208c0.571,0.593,1.339,0.92,2.162,0.92  c0.779,0,1.518-0.297,2.079-0.837C56.255,54.982,56.293,53.08,55.146,51.887z M23.984,6c9.374,0,17,7.626,17,17s-7.626,17-17,17  s-17-7.626-17-17S14.61,6,23.984,6z"/>
                </svg>
                </button>
              </div>

              <button
                  @click="openModal"
                  class="w-32 float-right px-4 py-2 text-sm text-white bg-blue-600 border border-transparent rounded-lg active:bg-blue-600 hover:bg-blue-700 focus:outline-none focus:shadow-outline-blue"
                >
                  Add New
                </button>

              <div class="w-full overflow-x-auto rounded-lg">
                <table class="w-full whitespace-no-wrap">
                  <thead>
                    <tr
                      class="text-xs font-semibold tracking-wide text-left text-gray-500 bg-gray-100 uppercase border-b"
                    >
                      <th class="px-4 py-3">Client</th>
                      <th class="px-4 py-3">Amount</th>
                      <th class="px-4 py-3">Status</th>
                      <th class="px-4 py-3">Date</th>
                    </tr>
                  </thead>
                  <tbody
                    class="bg-white divide-y"
                  >
                    <tr class="text-gray-700 hover:bg-gray-100">
                      <td class="px-4 py-3">
                        <div class="flex items-center text-sm">
                          <div>
                            <p class="font-semibold">Hans Burger</p>
                            <p class="text-xs text-gray-600">
                              10x Developer
                            </p>
                          </div>
                        </div>
                      </td>
                      <td class="px-4 py-3 text-sm">
                        $ 863.45
                      </td>
                      <td class="px-4 py-3 text-xs">
                        <span
                          class="px-2 py-1 font-semibold leading-tight text-green-700 bg-green-100 rounded-full"
                        >
                          Approved
                        </span>
                      </td>
                      <td class="px-4 py-3 text-sm">
                        6/10/2020
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <div
                class="grid px-4 py-3 text-xs font-semibold tracking-wide text-gray-500 uppercase border-t bg-gray-50 sm:grid-cols-9"
              >
                <span class="flex items-center col-span-3">
                  <small>Showing 21-30 of 100</small>
                </span>
                <span class="col-span-2"></span>
                <span class="flex col-span-4 mt-2 sm:mt-auto sm:justify-end">
                  <nav aria-label="Table navigation">
                    <ul class="inline-flex items-center">
                      <li>
                        <button
                          class="px-3 py-1 rounded-md rounded-l-lg focus:outline-none focus:shadow-outline-blue"
                          aria-label="Previous"
                        >
                          <svg
                            aria-hidden="true"
                            class="w-4 h-4 fill-current"
                            viewBox="0 0 20 20"
                          >
                            <path
                              d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                              clip-rule="evenodd"
                              fill-rule="evenodd"
                            ></path>
                          </svg>
                        </button>
                      </li>
                      <li>
                        <button
                          class="px-3 py-1 rounded-md focus:outline-none focus:shadow-outline-gray"
                        >
                          1
                        </button>
                      </li>
                      <li>
                        <button
                          class="px-3 py-1 rounded-md focus:outline-none focus:shadow-outline-gray"
                        >
                          2
                        </button>
                      </li>
                      <li>
                        <button
                          class="px-3 py-1 bg-blue-600 text-white rounded-md focus:outline-none focus:shadow-outline-blue"
                        >
                          3
                        </button>
                      </li>
                      <li>
                        <button
                          class="px-3 py-1 rounded-md focus:outline-none focus:shadow-outline-gray"
                        >
                          4
                        </button>
                      </li>
                      <li>
                        <button
                          class="px-3 py-1 rounded-md rounded-r-lg focus:outline-none focus:shadow-outline-blue"
                          aria-label="Next"
                        >
                          <svg
                            class="w-4 h-4 fill-current"
                            aria-hidden="true"
                            viewBox="0 0 20 20"
                          >
                            <path
                              d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                              clip-rule="evenodd"
                              fill-rule="evenodd"
                            ></path>
                          </svg>
                        </button>
                      </li>
                    </ul>
                  </nav>
                </span>
              </div>
            </div>
          </div>

        </main>
      </div>
    </div>

    <div
      x-show="isModalOpen"
      x-transition:enter="transition ease-out duration-150"
      x-transition:enter-start="opacity-0"
      x-transition:enter-end="opacity-100"
      x-transition:leave="transition ease-in duration-150"
      x-transition:leave-start="opacity-100"
      x-transition:leave-end="opacity-0"
      class="fixed inset-0 z-30 flex items-end bg-black bg-opacity-50 sm:items-center sm:justify-center"
    >
      <div
        x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0 transform translate-y-1/2"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0  transform translate-y-1/2"
        @click.away="closeModal"
        @keydown.escape="closeModal"
        class="w-full px-6 py-4 overflow-hidden bg-white rounded-t-lg sm:rounded-lg sm:m-4 sm:max-w-xl"
        role="dialog"
        id="modal"
      >
        <header class="flex justify-end">
          <button
            class="inline-flex items-center justify-center w-6 h-6 text-gray-400 transition-colors duration-150 rounded hover:text-gray-700"
            aria-label="close"
            @click="closeModal"
          >
            <svg
              class="w-4 h-4"
              fill="currentColor"
              viewBox="0 0 20 20"
              role="img"
              aria-hidden="true"
            >
              <path d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" fill-rule="evenodd"></path>
            </svg>
          </button>
        </header>
        <div class="mt-4 mb-6">
          <div class="mb-2 text-lg font-semibold text-gray-700">
            Modal header
          </div>
          <div class="text-sm text-gray-700">
            Lorem, ipsum dolor sit amet consectetur adipisicing elit. Nostrum et
            eligendi repudiandae voluptatem tempore!

              <label class="block mt-4">
                <span>Name</span>
                <input
                  class="border p-2 rounded w-full mt-1 text-sm form-input focus:border-gray-400 focus:outline-none focus:shadow-outline-gray"
                  placeholder="Jane Doe"
                />
              </label>

              <div class="block mt-4">
                <span>Account Type</span>
                <div class="mt-2">
                  <label
                    class="inline-flex items-center"
                  >
                    <input
                      type="radio"
                      class="text-blue-600 form-radio focus:border-blue-400 focus:outline-none focus:shadow-outline-gray"
                      name="accountType"
                      value="personal"
                    />
                    <span class="ml-2">Personal</span>
                  </label>
                  <label
                    class="inline-flex items-center ml-6"
                  >
                    <input
                      type="radio"
                      class="text-blue-600 form-radio focus:border-blue-400 focus:outline-none focus:shadow-outline-gray"
                      name="accountType"
                      value="busines"
                    />
                    <span class="ml-2">Business</span>
                  </label>
                </div>
              </div>

              <label class="block mt-4">
                <span>Requested Limit</span>
                <select
                  class="border p-2 rounded w-full mt-1 text-sm form-select focus:border-gray-400 focus:outline-none focus:shadow-outline-gray"
                >
                  <option>$1,000</option>
                  <option>$5,000</option>
                  <option>$10,000</option>
                  <option>$25,000</option>
                </select>
              </label>

              <label class="block mt-4">
                <span>Message</span>
                <textarea
                  class="border p-2 rounded w-full mt-1 text-sm form-textarea focus:border-gray-400 focus:outline-none focus:shadow-outline-gray"
                  rows="3"
                  placeholder="Enter some long form content."
                ></textarea>
              </label>

              <div class="flex mt-6 text-sm">
                <label class="flex items-center">
                  <input
                    type="checkbox"
                    class="text-blue-600 form-checkbox focus:border-blue-400 focus:outline-none focus:shadow-outline-blue"
                  />
                  <span class="ml-2">
                    I agree to the
                    <span class="underline">privacy policy</span>
                  </span>
                </label>
              </div>
          </div>
        </div>
        <footer
          class="flex flex-col items-center justify-end px-6 py-3 -mx-6 -mb-4 space-y-4 sm:space-y-0 sm:space-x-6 sm:flex-row bg-gray-50"
        >
          <button
            id="btnMdlOk"
            class="w-full px-5 py-3 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-blue-600 border border-transparent rounded-lg sm:w-auto sm:px-4 sm:py-2 active:bg-blue-600 hover:bg-blue-700 focus:outline-none focus:shadow-outline-blue"
          >
            Accept
          </button>
          <button
            @click="closeModal"
            id="btnMdlCancel"
            class="w-full px-5 py-3 text-sm font-medium leading-5 text-white text-gray-700 transition-colors duration-150 border border-gray-300 rounded-lg sm:px-4 sm:py-2 sm:w-auto active:bg-transparent hover:border-gray-500 focus:border-gray-500 active:text-gray-500 focus:outline-none focus:shadow-outline-gray"
          >
            Cancel
          </button>
        </footer>
      </div>
    </div>
    <script>
      $(function(){
        app.prep();
      });
    </script>
  </body>
</html>
