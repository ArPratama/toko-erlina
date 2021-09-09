<form id="frmStock" onsubmit="return doSubmitForm(event,'stock/doSave','frmStock')">
  <div class="mt-4 mb-6">
    <div cid="lblMdlTitle" lass="mb-2 text-lg font-semibold text-gray-700">Update Status Form</div>
    <div class="text-sm text-gray-700">
      <p class="text-gray-400 mb-8">Please fill all the required fields marked with (*) symbol before saving this form</p>
      
      <label class="block mt-4">
        <span id="lblFrmItems" class="block text-gray-500"></span>
      </label>

      <label class="block mt-4">
        <span>Status *</span>
        <select 
          id="selFrmStatus"
          name="selFrmStatus"
          class="border p-2 rounded w-full mt-1 text-sm form-select focus:border-gray-400 focus:outline-none focus:shadow-outline-gray"
          required>
          <option value="">Please Select</option>
        </select>
      </label>

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
  <input type="hidden" id="hdnFrmAction" name="hdnFrmAction" value="statusBulk"/>
</form>

<script>
  function fetchStatus(ID='') {
    doFetch('global/getStockStatus','_cb=onCompleteFetchStatus&_p='+ID);
  }
  function onCompleteFetchStatus(data,ID) {
    Swal.close();
    var html = '';
    html += '<option value="">Please Select</option>';
    for (i=0;i<data.length;i++) {
      var statusName = "On Storage"
      if (data[i].Status == 1) {
        var statusName = "Display"
      }
      html += '<option value="'+data[i].Status+'">'+statusName+'</option>';
    }
    $('#selFrmStatus').html(html);
    if (ID!='') { $('#selFrmStatus').val(ID); }
  }

  function onDetailForm(count,data) {
    $('#lblFrmItems').html(count + ' item'+(count==1 ? '' : 's')+' will be updated to');
    $('#hdnFrmID').val(data);
    fetchStatus();
  }
</script>