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
                <svg width="126" height="19" viewBox="0 0 126 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M8.36375 13.3106C8.5371 13.7388 8.48214 14.2877 8.39757 14.6204C8.24959 15.2108 7.84369 15.8137 6.6429 15.8137C5.51821 15.8137 4.83325 15.1734 4.83325 14.2087V12.4914H0.000488281V13.8553C0.000488281 17.8055 3.15891 18.9989 6.54565 18.9989C9.80132 18.9989 12.482 17.9094 12.909 14.953C13.1289 13.4228 12.9682 12.4207 12.8921 12.0507C12.131 8.34162 5.29835 7.23557 4.78674 5.16067C4.7157 4.85469 4.70994 4.53754 4.76983 4.22925C4.89667 3.65959 5.28989 3.04003 6.41458 3.04003C7.47161 3.04003 8.08469 3.68038 8.08469 4.64506V5.74281H12.5792V4.49537C12.5792 0.636648 9.05716 0.0337219 6.51182 0.0337219C3.30689 0.0337219 0.689675 1.07741 0.211895 3.96729C0.0850511 4.75733 0.0639104 5.46421 0.254177 6.35405C1.03638 9.9716 7.43356 11.0194 8.36375 13.3106Z" fill="#0C4DA2"/>
                  <path d="M66.7431 13.3106C66.9165 13.7388 66.8615 14.2877 66.777 14.6204C66.629 15.2108 66.2231 15.8137 65.0223 15.8137C63.8976 15.8137 63.2126 15.1734 63.2126 14.2087V12.4914H58.3799V13.8553C58.3799 17.8055 61.5383 18.9989 64.925 18.9989C68.1807 18.9989 70.8614 17.9094 71.2884 14.953C71.5083 13.4228 71.3476 12.4207 71.2715 12.0507C70.5104 8.34162 63.6777 7.23557 63.1661 5.16067C63.0951 4.85469 63.0893 4.53754 63.1492 4.22925C63.2761 3.65959 63.6693 3.04003 64.794 3.04003C65.851 3.04003 66.4641 3.68038 66.4641 4.64506V5.74281H70.9586V4.49537C70.9586 0.636648 67.4366 0.0337219 64.8912 0.0337219C61.6863 0.0337219 59.0691 1.07741 58.5913 3.96729C58.4644 4.75733 58.4433 5.46421 58.6336 6.35405C59.4158 9.9716 65.813 11.0194 66.7431 13.3106Z" fill="#0C4DA2"/>
                  <path d="M19.2807 0.59919L15.9404 18.3086H20.807L23.272 2.23748H23.3735L25.7709 18.3086H30.6121L27.293 0.595032L19.2807 0.59919ZM46.4887 0.59919L44.269 14.138H44.1633L41.9477 0.59919H34.6034L34.206 18.3086H38.7132L38.8231 2.38718H38.9246L41.9308 18.3086H46.5014L49.5119 2.39133H49.6091L49.7233 18.3086H54.2262L53.8288 0.595032L46.4887 0.59919Z" fill="#0C4DA2"/>
                  <path d="M82.0262 15.6557C83.2777 15.6557 83.6625 14.8075 83.7513 14.375C83.7893 14.1838 83.7935 13.926 83.7935 13.6973V0.599223H88.3515V13.2939C88.3523 13.6824 88.3382 14.0708 88.3092 14.4582C87.9921 17.7639 85.3368 18.8367 82.0262 18.8367C78.7113 18.8367 76.056 17.7639 75.7389 14.4582C75.7263 14.2836 75.6924 13.6183 75.6967 13.2939V0.595065H80.2546V13.6931C80.2504 13.926 80.2588 14.1838 80.2969 14.375C80.3814 14.8075 80.7704 15.6557 82.0262 15.6557ZM104.046 0.599223L104.292 14.4998H104.194L100.051 0.599223H93.3703V18.1215H97.7972L97.5519 3.7386H97.6492L102.093 18.1215H108.507V0.599223H104.046ZM119.585 15.4728C120.887 15.4728 121.344 14.6619 121.424 14.1838C121.462 13.9883 121.466 13.7388 121.466 13.5185V10.9404H119.619V8.36241H125.999V13.111C125.999 13.4436 125.99 13.6848 125.935 14.2752C125.639 17.5019 122.794 18.6537 119.602 18.6537C116.409 18.6537 113.568 17.5019 113.268 14.2752C113.217 13.6848 113.204 13.4436 113.204 13.111V5.65964C113.204 5.34363 113.247 4.78644 113.281 4.49537C113.682 1.18136 116.409 0.116882 119.602 0.116882C122.794 0.116882 125.593 1.17304 125.923 4.49121C125.982 5.05672 125.965 5.65548 125.965 5.65548V6.25009H121.424V5.25631C121.424 5.25631 121.424 4.84049 121.369 4.58269C121.284 4.19183 120.946 3.29368 119.568 3.29368C118.257 3.29368 117.872 4.14609 117.775 4.58269C117.72 4.81555 117.699 5.13156 117.699 5.41847V13.5143C117.699 13.7388 117.707 13.9883 117.741 14.1879C117.826 14.6619 118.282 15.4728 119.585 15.4728Z" fill="#0C4DA2"/>
                </svg>
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
