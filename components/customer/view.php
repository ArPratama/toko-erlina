<div class="mt-4 mb-6">
  <div class="mb-2 text-lg font-semibold text-gray-700">Customer Details</div>
  <div class="text-sm text-gray-700">

    <label class="block mt-4">
      <span class="block text-gray-500">Customer Name:</span>
      <span id="lblFrmName" class="block"></span>
    </label>

    <label class="block mt-4">
      <span class="block text-gray-500">Last Purchase:</span>
      <span id="lblFrmLastPurchase" class="block"></span>
    </label>

    <div class="block mt-4">
      <span class="block text-gray-500">Status:</span>
      <span id="lblFrmStatus" class="block"></span>
    </div>
  </div>
</div>
<footer class="flex flex-col items-center justify-end px-6 py-3 -mx-6 -mb-4 space-y-4 sm:space-y-0 sm:space-x-6 sm:flex-row bg-gray-50">
  <button
    onclick="modal.close()"
    class="w-full px-5 py-3 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-blue-600 border border-transparent rounded-lg sm:w-32 sm:px-4 sm:py-2 active:bg-blue-600 hover:bg-blue-700 focus:outline-none focus:shadow-outline-blue"
  >
    Close
  </button>
</footer>

<script>
  function onDetailForm(ID) {
    doFetch('customer/get','_i='+ID);
  }
  function onCompleteFetch(data) {
    Swal.close();
    var status = html = data.Status==1 ? '<span class="px-2 py-1 font-semibold leading-tight text-green-700 bg-green-100 rounded-full">Active</span>' : '<span class="px-2 py-1 font-semibold leading-tight text-white bg-red-400 rounded-full">Inactive</span>';
    $('#lblFrmName').html(data.Name);
    $('#lblFrmLastPurchase').html(data.LastPurchase);
    $('#lblFrmStatus').html(status);
  }
</script>