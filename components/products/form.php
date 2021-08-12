<form id="frmProducts" onsubmit="return doSubmitForm(event,'products/doSave','frmProducts')" enctype="multipart/form-data">
  <div class="mt-4 mb-6">
    <div id="lblMdlTitle" class="mb-2 text-lg font-semibold text-gray-700">Add New Product</div>
    <div class="text-sm text-gray-700">
      <p class="text-gray-400 mb-8">Please fill all the required fields marked with (*) symbol before saving this form</p>
      <label class="block mt-4">
        <span>Product Name *</span>
        <input
          id="txtFrmName"
          name="txtFrmName"
          type="text"
          maxlength="100"
          class="border p-2 rounded w-full mt-1 text-sm form-input focus:border-gray-400 focus:outline-none focus:shadow-outline-gray"
          placeholder="Type here"
          required
        />
      </label>

      <label class="block mt-4">
        <span>Description</span>
        <input
          id="txtFrmDescription"
          name="txtFrmDescription"
          type="text"
          maxlength="250"
          class="border p-2 rounded w-full mt-1 text-sm form-input focus:border-gray-400 focus:outline-none focus:shadow-outline-gray"
          placeholder="Type here"
        />
      </label>

      <label class="block mt-4">
        <span>Price *</span>
        <input
          id="txtFrmPrice"
          name="txtFrmPrice"
          type="number"
          class="border p-2 rounded w-full mt-1 text-sm form-input focus:border-gray-400 focus:outline-none focus:shadow-outline-gray"
          placeholder="Type here"
          required
        />
      </label>

      <div class="block mt-4">
        <span>Status *</span>
        <div class="mt-2">
          <label class="inline-flex items-center">
            <input
              id="radFrmStatus_1"
              name="radFrmStatus"
              type="radio"
              class="text-blue-600 form-radio focus:border-blue-400 focus:outline-none focus:shadow-outline-gray"
              value="1"
              checked
            />
            <span class="ml-2">Active</span>
          </label>
          <label class="inline-flex items-center ml-6">
            <input
              id="radFrmStatus_0"
              name="radFrmStatus"
              type="radio"
              class="text-blue-600 form-radio focus:border-blue-400 focus:outline-none focus:shadow-outline-gray"
              value="0"
            />
            <span class="ml-2">Inactive</span>
          </label>
        </div>
      </div>
    </div>
  </div>
  <footer class="flex flex-col items-center justify-end px-6 py-3 -mx-6 -mb-4 space-y-4 sm:space-y-0 sm:space-x-6 sm:flex-row bg-gray-50">
    <button
      type="submit"
      class="w-full px-5 py-3 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-blue-600 border border-transparent rounded-lg sm:w-32 sm:px-4 sm:py-2 active:bg-blue-600 hover:bg-blue-700 focus:outline-none focus:shadow-outline-blue"
    >
      Save
    </button>
  </footer>
  <input type="hidden" id="hdnFrmID" name="hdnFrmID" value=""/>
  <input type="hidden" id="hdnFrmAction" name="hdnFrmAction" value="add"/>
</form>

<script>

  function onDetailForm(ID) {
    $('#lblMdlTitle').html('Edit Product Details');
    $('#hdnFrmID').val(ID);
    $('#hdnFrmAction').val('edit');
    doFetch('products/get','_i='+ID);
  }
  function onCompleteFetch(data) {
    Swal.close();
    $('#txtFrmName').val(data.Name);
    $('#txtFrmDescription').val(data.Description);
    $('#txtFrmPrice').val(data.Price);
    $('#radFrmStatus_'+data.Status).prop('checked', true);
  }
</script>