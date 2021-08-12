<form id="frmStock" onsubmit="return doSubmitForm(event,'stock/doSave','frmStock')" enctype="multipart/form-data">
  <div class="mt-4 mb-6">
    <div id="lblMdlTitle" class="mb-2 text-lg font-semibold text-gray-700">Add New Stock</div>
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
        <input type="hidden" id="txtFrmProductID" name="txtFrmProductID">
      </label>

      <label class="block mt-4">
        <span>Amount</span>
        <input
          id="txtFrmAmount"
          name="txtFrmAmount"
          type="number"
          maxlength="250"
          class="border p-2 rounded w-full mt-1 text-sm form-input focus:border-gray-400 focus:outline-none focus:shadow-outline-gray"
          placeholder="Type here"
        />
      </label>

      <label class="block mt-4">
        <span>Source</span>
        <input
          id="txtFrmSource"
          name="txtFrmSource"
          type="text"
          class="border p-2 rounded w-full mt-1 text-sm form-input focus:border-gray-400 focus:outline-none focus:shadow-outline-gray"
          placeholder="Type here"
        />
      </label>

      <label class="block mt-4">
        <span>Incoming Date</span>
        <input
          id="txtFrmIncomingDate"
          name="txtFrmIncomingDate"
          type="date"
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
            <span class="ml-2">Display</span>
          </label>
          <label class="inline-flex items-center ml-6">
            <input
              id="radFrmStatus_2"
              name="radFrmStatus"
              type="radio"
              class="text-blue-600 form-radio focus:border-blue-400 focus:outline-none focus:shadow-outline-gray"
              value="2"
            />
            <span class="ml-2">On Storage</span>
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
    $('#lblMdlTitle').html('Edit Stock Details');
    $('#hdnFrmID').val(ID);
    $('#hdnFrmAction').val('edit');
    doFetch('stock/get','_i='+ID);
  }
  function onCompleteFetch(data) {
    Swal.close();
    $('#txtFrmName').val(data.ProductName);
    $('#txtFrmAmount').val(data.Amount);
    $('#txtFrmSource').val(data.Source);
    $('#radFrmStatus_'+data.Status).prop('checked', true);
  }

var delay = (function(){
  var timer = 0;
  return function(callback, ms){
  clearTimeout (timer);
  timer = setTimeout(callback, ms);
 };
})();

  $('#txtFrmName').keyup(function() {
    $('#txtFrmProductID').val('');
    delay(function(){
        var value = $('#txtFrmName').val();
        fetchProductInfo();
    }, 1000 );
  });

 function fetchProductInfo() {
    var value = $('#txtFrmName').val();
    if (value) {
        doFetch('products/doLookup','_i='+value+'&_cb=onCompleteFetchProductInfo',loading=false);
    }
  }

  function onCompleteFetchProductInfo(data) {
    Swal.close();
    var ProductArr = [];
    for (i=0;i<data.length;i++) {
      var formatData = data[i].Name + ' ' + data[i].Description + ' ['+ data[i].ID + ']';
      ProductArr.push(formatData);
    }
    $( '#txtFrmName' ).autocomplete({
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
        $('#txtFrmProductID').val(ProductID);
      }
    });
    $('#txtFrmName').autocomplete("search", $('#txtFrmName').val());
    }
</script>