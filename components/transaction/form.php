<form id="frmTransaction" onsubmit="return doSubmitForm(event,'transaction/doSave','frmTransaction')" enctype="multipart/form-data">
  <div class="mt-4 mb-6">
    <div id="lblMdlTitle" class="mb-2 text-lg font-semibold text-gray-700">Add New Transaction</div>
    <div class="text-sm text-gray-700">
      <p class="text-gray-400 mb-8">Please fill all the required fields marked with (*) symbol before saving this form</p>
      <label class="block mt-4">
        <span>Transaction Date *</span>
        <input
          id="txtFrmTransactionDate"
          name="txtFrmTransactionDate"
          type="datetime-local"
          maxlength="100"
          class="border p-2 rounded w-full mt-1 text-sm form-input focus:border-gray-400 focus:outline-none focus:shadow-outline-gray"
          placeholder="Type here"
          required
        />
      </label>

      <label class="block mt-4">
        <span>Customer Name</span>
        <input
          id="txtFrmCustomerName"
          name="txtFrmCustomerName"
          type="text"
          maxlength="100"
          class="border p-2 rounded w-full mt-1 text-sm form-input focus:border-gray-400 focus:outline-none focus:shadow-outline-gray"
          placeholder="Type here"
        />
        <input type="hidden" id="txtFrmCustomerID" name="txtFrmCustomerID">
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

      <div class="block mt-4" style="display:none">
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
              disabled
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

<style>
.ui-autocomplete {
    position: absolute;
    max-height: 100px;
    top: 100%;
    left: 0;
    z-index: 1000;
    float: left;
    display: none;
    min-width: 160px;
    padding: 4px 0;
    margin: 0 0 10px 25px;
    list-style: none;
    background-color: #ffffff;
    border-color: #ccc;
    border-color: rgba(0, 0, 0, 0.2);
    border-style: solid;
    border-width: 1px;
    -webkit-border-radius: 5px;
    -moz-border-radius: 5px;
    border-radius: 5px;
    -webkit-box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
    -moz-box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
    box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
    -webkit-background-clip: padding-box;
    -moz-background-clip: padding;
    background-clip: padding-box;
    *border-right-width: 2px;
    *border-bottom-width: 2px;
    overflow-y: auto;
     overflow-x: hidden;
}

.ui-menu-item > a.ui-corner-all {
    display: block;
    padding: 3px 15px;
    clear: both;
    font-weight: normal;
    line-height: 18px;
    color: #555555;
    white-space: nowrap;
    text-decoration: none;
}

.ui-state-hover, .ui-state-active {
    color: #ffffff;
    text-decoration: none;
    background-color: #0088cc;
    border-radius: 0px;
    -webkit-border-radius: 0px;
    -moz-border-radius: 0px;
    background-image: none;
}

.ui-menu .ui-menu-item:hover, .ui-state-hover, .ui-widget-content .ui-state-hover, .ui-state-focus, .ui-widget-content .ui-state-focus {
  cursor: default; //'Hand' is not a valid value here !!!
}

.ui-autocomplete.ui-widget {
  font-size: 0.875rem;
  line-height: 1.25rem;
  text-align: left;
}

.ui-helper-hidden-accessible { display:none; }

</style>

<script>

  function onDetailForm(ID) {
    $('#lblMdlTitle').html('Edit Transaction Details');
    $('#hdnFrmID').val(ID);
    $('#hdnFrmAction').val('edit');
    doFetch('transaction/get','_i='+ID);
  }
  function onCompleteFetch(data) {
    Swal.close();
    $('#txtFrmCustomerName').val(data.CustomerName);
    $('#txtFrmDescription').val(data.Description);
    $('#txtFrmTransactionDate').val(data.TransactionDate);
    $('#radFrmStatus_'+data.Status).prop('checked', true);
  }

  var delay = (function(){
  var timer = 0;
  return function(callback, ms){
  clearTimeout (timer);
  timer = setTimeout(callback, ms);
 };
})();

  $('#txtFrmCustomerName').keyup(function() {
    $('#txtFrmCustomerID').val('');
    delay(function(){
        var value = $('#txtFrmCustomerName').val();
        fetchCustomerInfo();
    }, 1000 );
  });

 function fetchCustomerInfo() {
    var value = $('#txtFrmCustomerName').val();
    if (value) {
        doFetch('customer/doLookup','_i='+value+'&_cb=onCompleteFetchCustomerInfo',loading=false);
    }
  }

  function onCompleteFetchCustomerInfo(data) {
    Swal.close();
    var ProductArr = [];
    for (i=0;i<data.length;i++) {
      var formatData = data[i].Name + ' ['+ data[i].ID + ']';
      ProductArr.push(formatData);
    }
    $( '#txtFrmCustomerName' ).autocomplete({
      minLength: 0,
      autoFocus: true,
      source: function (request, response) {
        var results = $.ui.autocomplete.filter(ProductArr, request.term);
        response(results);
      },
      select : function(event, ui) {
        var ProductID = ui.item.label.substring(
            ui.item.label.lastIndexOf("[") + 1,
            ui.item.label.lastIndexOf("]")
        );
        console.log(ProductID);
        $('#txtFrmCustomerID').val(ProductID);
      }
    });
    $('#txtFrmCustomerName').autocomplete("search", $('#txtFrmCustomerName').val());
    }
</script>