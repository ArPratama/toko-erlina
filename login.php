<!DOCTYPE html>
<html x-data="data()" lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sistem Pendataan Toko Erlina</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet"/>
    <link href="assets/css/tailwind.css" rel="stylesheet" />
    <link href="assets/css/pace-flash.css" rel="stylesheet" />
    <link href="assets/favicon.png" rel="shortcut icon" />
    <script src="assets/js/alpine-2.8.1.min.js"></script>
    <script src="assets/js/jquery-3.6.0.min.js"></script>
    <script src="assets/js/sweetalert2.all.min.js"></script>
    <script src="assets/js/polyfill.js"></script>
    <script src="assets/js/pace.min.js"></script>
    <script src="assets/js/helper.js?ts=<?=time()?>"></script>
    <script src="assets/js/controller.js?ts=<?=time()?>"></script>
  </head>
  <body>
    <div class="flex items-center min-h-screen p-6 bg-gray-50" style="background-image: url('assets/img/bg/christian-wiediger-70ku6P7kgmc.jpg')">
    <div
        class="lg:w-1/3 md:w-1/2 h-full max-w-4xl mx-auto overflow-hidden bg-white rounded-lg shadow-xl text-gray-600"
      >
        <div class="flex flex-col overflow-y-auto">
          <div class="flex items-center justify-center p-6 sm:p-12">
            <div class="w-full">
              
              <div class="flex justify-center flex-1">
               SRC ERLINA
              </div>

              <h1 class="mt-4 text-center">
                Sistem Pendataan Toko Erlina
              </h1>

              <div x-show="isLoginPage">
                <h1 class="mt-8 mb-4 font-semibold text-center">
                  Sign In
                </h1>
                <form id="frmLogin" onsubmit="return doSubmitForm(event,'auth/doLogin','frmLogin')">
                  <label class="block mt-4">
                    <span>Email</span>
                    <input
                      name="txtEmail"
                      class="border p-2 rounded w-full mt-1 text-sm form-input focus:border-gray-400 focus:outline-none focus:shadow-outline-gray"
                      placeholder="username@domain.com"
                      required
                    />
                  </label>
                  <label class="block mt-4">
                    <span>Password</span>
                    <input
                      name="txtPassword"
                      type="password"
                      class="border p-2 rounded w-full mt-1 text-sm form-input focus:border-gray-400 focus:outline-none focus:shadow-outline-gray"
                      placeholder="********"
                      required
                    />
                  </label>
                  <div class="flex mt-6 text-sm">
                    <label class="flex items-center">
                      <input
                        name="chkRemember"
                        type="checkbox"
                        class="text-blue-600 form-checkbox focus:border-blue-400 focus:outline-none focus:shadow-outline-blue"
                      />
                      <span class="ml-2">
                        Remember me
                      </span>
                    </label>
                  </div>
                  <button 
                    type="submit"
                    class="block w-full px-4 py-2 mt-4 text-sm font-medium leading-5 text-center text-white transition-colors duration-150 bg-blue-600 border border-transparent rounded-lg active:bg-blue-600 hover:bg-blue-700 focus:outline-none focus:shadow-outline-blue">
                    Log In
                  </button>
                </form>
                <p class="mt-4">
                  <a 
                    @click="openForgot"
                    class="text-sm font-medium text-blue-600 hover:underline" 
                    href="#">
                    Forgot your password?
                  </a>
                </p>
              </div>

              <div x-show="!isLoginPage">
                <h1 class="mt-8 mb-4 font-semibold text-center">
                  Forgot your Password?
                </h1>
                <p class="mt-4 text-gray-500 text-center">Submit your email address and we'll send you a link to reset your password</p>
                <form id="frmReset" onsubmit="return doSubmitForm(event,'auth/doReset','frmReset')">
                  <label class="block mt-4">
                    <span>Email</span>
                    <input
                      name="txtEmail"
                      type="email"
                      class="border p-2 rounded w-full mt-1 text-sm form-input focus:border-gray-400 focus:outline-none focus:shadow-outline-gray"
                      placeholder="username@domain.com"
                      required
                    />
                  </label>
                  <button 
                    type="submit"
                    class="block w-full px-4 py-2 mt-4 text-sm font-medium leading-5 text-center text-white transition-colors duration-150 bg-blue-600 border border-transparent rounded-lg active:bg-blue-600 hover:bg-blue-700 focus:outline-none focus:shadow-outline-blue">
                    Submit
                  </button>
                </form>
                <p class="mt-4">
                  Remember your password? 
                  <a 
                    @click="openLogin"
                    class="text-sm font-medium text-blue-600 hover:underline" href="#">
                    Try Sign In
                  </a>
                </p>
              </div>
              
            </div>
          </div>
        </div>
      </div>
    </div>
    <script>
      $(function(){
        app.auth.init();
      });
    </script>
  </body>
</html>
