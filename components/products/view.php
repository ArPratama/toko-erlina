<div class="mt-4 mb-6">
  <div class="mb-2 text-lg font-semibold text-gray-700">Product Details</div>
  <div class="text-sm text-gray-700">

    <label class="block mt-4">
      <span class="block text-gray-500">Product Name:</span>
      <span id="lblFrmName" class="block"></span>
    </label>

    <label class="block mt-4">
      <span class="block text-gray-500">Description:</span>
      <span id="lblFrmDescription" class="block"></span>
    </label>

    <label class="block mt-4">
      <span class="block text-gray-500">Price :</span>
      <span id="lblFrmPrice" class="block"></span>
    </label>

        <label class="block mt-4">
      <span class="block text-gray-500">On Stock:</span>
      <span id="lblFrmOnStock" class="block"></span>
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
    doFetch('products/get','_i='+ID);
  }
  function onCompleteFetch(data) {
    Swal.close();
    var status = html = data.Status==1 ? '<span class="px-2 py-1 font-semibold leading-tight text-green-700 bg-green-100 rounded-full">Active</span>' : '<span class="px-2 py-1 font-semibold leading-tight text-white bg-red-400 rounded-full">Inactive</span>';
    $('#lblFrmName').html(data.Name);
    $('#lblFrmDescription').html(data.Description);
    $('#lblFrmPrice').html(doFormatNumber(data.Price));
    $('#lblFrmOnStock').html(doFormatNumber(data.OnStock));
    $('#lblFrmStatus').html(status);
  }
</script>