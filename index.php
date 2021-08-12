<!DOCTYPE html>
<html x-data="data()" lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sistem Pendataan Toko Erlina</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet"/>
    <link href="assets/css/tailwind.css" rel="stylesheet" />
    <link href="assets/css/pace-flash.css" rel="stylesheet" />
    <link href="assets/css/chart-2.9.3.min.css" rel="stylesheet" />
    <link href="assets/favicon.png" rel="shortcut icon" />
    <style>
      .dataTables_empty {
        text-align: center;
        font-size: 0.875rem;
        line-height: 1.25rem;
      }
    </style>
    <script src="assets/js/alpine-2.8.1.min.js"></script>
    <script src="assets/js/charts-2.9.3.min.js"></script>
    <script src="assets/js/jquery-3.6.0.min.js"></script>
    <script src="assets/js/dataTables.min.js"></script>
    <script src="assets/js/dataTables.buttons.min.js"></script>
    <script src="assets/js/dataTables.buttons.colVis.min.js"></script>
    <script src="assets/js/dataTables.buttons.html5.min.js"></script>
    <script src="assets/js/sweetalert2.all.min.js"></script>
    <script src="assets/js/polyfill.js"></script>
    <script src="assets/js/pace.min.js"></script>
    <script src="assets/js/helper.js?ts=<?=time()?>"></script>
    <script src="assets/js/controller.js?ts=<?=time()?>"></script>
    <script src="assets/js/jquery-ui.js"></script>
  </head>
  <body>
    <div
      class="flex h-screen bg-white text-gray-700"
      :class="{ 'overflow-hidden': isSideMenuOpen}"
    >
      <aside
        id="sidebarWrapper"
        class="z-20 hidden w-64 overflow-y-auto bg-white md:block flex-shrink-0 border-r"
      >
        <div class="py-4">
          <span class="block ml-6 text-lg font-bold">Toko Erlina</span>
          <span class="block ml-6 text-xs text-gray-500"><small>Sistem Pendataan Toko Erlina</small></span>
          <ul id="menuWrapperFull" class="mt-6"></ul>
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
          <ul id="menuWrapperMobile"></ul>
        </div>
      </aside>
      <div class="flex flex-col flex-1">
        <header id="headerWrapper" class="z-10 py-4 bg-white shadow-md">
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
            SRC ERLINA
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
                    <li class="flex" onclick="app.auth.doLogout()">
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
              <li 
                id="lblDisplayName"
                class="relative text-gray-500 text-sm hidden sm:block cursor-pointer"
                @click="toggleProfileMenu"
                @keydown.escape="closeProfileMenu"
                aria-label="Account"
                aria-haspopup="true">
                &nbsp;
              </li>
            </ul>
          </div>
        </header>
        <main class="h-full pb-16 overflow-y-auto">
          <div id="bgWrapper" class="flex items-center h-28 p-4 mb-6 text-2xl text-white shadow-md" style="background-image: linear-gradient(90deg, #0072E3 0%, rgba(129, 192, 255, 0) 100%), url('assets/img/bg/scott-webb-aHhhdKUP77M.jpg')">
            <span id="lblHero" class="ml-6">&nbsp;</span>
          </div>
          <div class="container px-2 sm:px-6 mx-auto grid">
            <span id="lblBreadcrumb" class="text-gray-400 text-sm hidden sm:block">&nbsp;</span>
            <div id="contentWrapper" class="w-full my-6 mb-8 overflow-hidden rounded-lg shadow-xs"></div>
          </div>
        </main>
      </div>
    </div>

    <div
      id="modalWrapper"
      style="display:none"
      class="fixed inset-0 z-30 flex items-end bg-black bg-opacity-50 sm:items-center sm:justify-center"
    >
      <div 
        id="modalBody"
        style="display:none"
        class="w-full px-6 py-4 overflow-scroll max-h-full bg-white rounded-t-lg sm:rounded-lg sm:m-4 sm:max-w-xl">
        <header class="flex justify-end">
          <button
            class="inline-flex items-center justify-center w-6 h-6 text-gray-400 transition-colors duration-150 rounded hover:text-gray-700"
            aria-label="close"
            onclick="modal.close()"
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
        <div id="modalContentWrapper">&nbsp;</div>
      </div>
    </div>
    <script>
      $(function(){
        app.boot();
      });
    </script>
  </body>
</html>
