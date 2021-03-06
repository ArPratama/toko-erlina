<form id="frmUsers" onsubmit="return doSubmitForm(event,'users/doSave','frmUsers')">
  <div class="mt-4 mb-6">
    <div id="lblMdlTitle" class="mb-2 text-lg font-semibold text-gray-700">Add New User</div>
    <div class="text-sm text-gray-700">
      <p class="text-gray-400 mb-8">Please fill all the required fields marked with (*) symbol before saving this form</p>
      <label class="block mt-4">
        <span>Full Name *</span>
        <input
          id="txtFrmFullName"
          name="txtFrmFullName"
          type="text"
          maxlength="200"
          class="border p-2 rounded w-full mt-1 text-sm form-input focus:border-gray-400 focus:outline-none focus:shadow-outline-gray"
          placeholder="Type here"
          require
        />
      </label>

      <label class="block mt-4">
        <span>Contact Number</span>
        <input
          id="txtFrmContactNumber"
          name="txtFrmContactNumber"
          type="text"
          maxlength="20"
          class="border p-2 rounded w-full mt-1 text-sm form-input focus:border-gray-400 focus:outline-none focus:shadow-outline-gray"
          placeholder="Type here"
        />
      </label>

      <label class="block mt-4">
        <span>User Name / Email *</span>
        <input
          id="txtFrmEmail"
          name="txtFrmEmail"
          type="email"
          maxlength="250"
          class="border p-2 rounded w-full mt-1 text-sm form-input focus:border-gray-400 focus:outline-none focus:shadow-outline-gray"
          placeholder="Type here"
          required
        />
      </label>

      <label class="block mt-4">
        <span id="lblPassword">Password *</span>
        <input
          id="txtFrmPassword"
          name="txtFrmPassword"
          type="password"
          class="border p-2 rounded w-full mt-1 text-sm form-input focus:border-gray-400 focus:outline-none focus:shadow-outline-gray"
          placeholder="Type here"
          required
        />
      </label>

      <label class="block mt-4">
        <span>Account Type *</span>
        <select
          id="selFrmAccountType"
          name="selFrmAccountType"
          onchange="doSwitchType()"
          class="border p-2 rounded w-full mt-1 text-sm form-select focus:border-gray-400 focus:outline-none focus:shadow-outline-gray"
          required
        >
          <option value="">Please Select</option>
          <option value="1">Admin</option>
          <option value="2">Pegawai</option>
          <option value="3">Pemilik Toko</option>
        </select>
      </label>

      <label class="block mt-4">
        <span>Role *</span>
        <select
          id="selFrmRole"
          name="selFrmRole"
          class="border p-2 rounded w-full mt-1 text-sm form-select focus:border-gray-400 focus:outline-none focus:shadow-outline-gray"
          required
        >
          <option value="">Please Select</option>
        </select>
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

  function fetchRole(ID='') {
    doFetch('global/getRole','_cb=onCompleteFetchRole&_p='+ID);
  }
  function onCompleteFetchRole(data,ID) {
    Swal.close();
    var html = '';
    html += '<option value="">Please Select</option>';
    for (i=0;i<data.length;i++) {
      html += '<option value="'+data[i].ID+'">'+data[i].Name+'</option>';
    }
    $('#selFrmRole').html(html);
    if (ID!='') { $('#selFrmRole').val(ID); }
  }

  function onDetailForm(ID) {
    $('#lblMdlTitle').html('Edit User Details');
    $('#hdnFrmID').val(ID);
    $('#hdnFrmAction').val('edit');
    doFetch('users/get','_i='+ID);
  }
  function onCompleteFetch(data) {
    Swal.close();
    $('#txtFrmFullName').val(data.FullName);
    $('#txtFrmContactNumber').val(data.ContactNumber);
    $('#txtFrmEmail').val(data.UserName);
    $('#selFrmAccountType').val(data.AccountType);
    fetchRole(data.RoleID);
    $('#radFrmStatus_'+data.Status).prop('checked', true);
    $('#txtFrmEmail').prop('disabled', true);
    $('#txtFrmEmail').addClass('text-gray-400');
    $('#lblFrmPassword').html('Password');
    $('#txtFrmPassword').prop('required', false);
    doSwitchType();
  }
</script>