<div class="mt-4 mb-6">
  <div class="mb-2 text-lg font-semibold text-gray-700">View Setting Details</div>
  <div class="text-sm text-gray-700">
    <label class="block mt-4">
      <span class="block">Type:</span>
      <span id="txtType" class="block"></span>
    </label>

    <label class="block mt-4">
      <span class="block">Field 1:</span>
      <span id="txtField1" class="block"></span>
    </label>

    <label class="block mt-4">
      <span class="block">Field 2:</span>
      <span id="txtField2" class="block"></span>
    </label>

    <label class="block mt-4">
      <span class="block">Field 3:</span>
      <span id="txtField3" class="block"></span>
    </label>
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
    doFetch('settings/get','_i='+ID);
  }
  function onCompleteFetch(data) {
    Swal.close();
    $('#txtType').html(data.Type);
    $('#txtField1').html(data.Field1);
    $('#txtField2').html(data.Field2);
    $('#txtField3').html(data.Field3);
  }
</script>