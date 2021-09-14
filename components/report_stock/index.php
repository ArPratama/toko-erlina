<div class="float-left">
  <span class="text-xs text-gray-500 block">Filter by Incoming Date: </span>
  <input
    id="txtFilterStartOrderDate"
    name="txtFilterStartOrderDate"
    type="date"
    maxlength="50"
    class="border p-2 rounded w-30 text-sm text-gray-500 form-input focus:border-gray-400 focus:outline-none focus:shadow-outline-gray"
    placeholder="Type here"
    onchange="doReloadTable()"
  />
  <input
    id="txtFilterEndOrderDate"
    name="txtFilterEndOrderDate"
    type="date"
    maxlength="50"
    class="border p-2 rounded w-30 sm:mr-2 text-sm text-gray-500 form-input focus:border-gray-400 focus:outline-none focus:shadow-outline-gray"
    placeholder="Type here"
    onchange="doReloadTable()"
  />
</div>
<div class="txtSearch_tblreports_class relative text-gray-600 w-52 mb-6 float-left mr-5 mt-4">
  <input
    id="txtSearch_tblreports"
    placeholder="Search"
    class="bg-white h-10 px-5 pr-10 rounded-lg text-sm focus:outline-none border"
    onkeyup="doSearchTable()">
  <button type="submit" class="absolute right-0 top-0 mt-3 mr-4">
  <svg class="h-4 w-4 fill-current" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" viewBox="0 0 56.966 56.966" xml:space="preserve" width="512px" height="512px">
    <path d="M55.146,51.887L41.588,37.786c3.486-4.144,5.396-9.358,5.396-14.786c0-12.682-10.318-23-23-23s-23,10.318-23,23  s10.318,23,23,23c4.761,0,9.298-1.436,13.177-4.162l13.661,14.208c0.571,0.593,1.339,0.92,2.162,0.92  c0.779,0,1.518-0.297,2.079-0.837C56.255,54.982,56.293,53.08,55.146,51.887z M23.984,6c9.374,0,17,7.626,17,17s-7.626,17-17,17  s-17-7.626-17-17S14.61,6,23.984,6z"/>
  </svg>
  </button>
</div>
<div class="relative text-gray-600 w-54 mb-6 float-left mt-4">
<button
  id="btnShowAll"
  onCLick="ShowAllData()"
  class="w-40 hidden sm:block px-4 py-2 text-sm text-white bg-blue-600 border border-transparent rounded-lg active:bg-blue-600 hover:bg-blue-700 focus:outline-none focus:shadow-outline-blue">
  Show All
</button>
</div>

<button
  id="btnFrmExport"
  onclick="downloadExcel()"
  class="w-40 float-right mr-2 mt-4 hidden sm:block px-4 py-2 text-sm text-white bg-blue-600 border border-transparent rounded-lg active:bg-blue-600 hover:bg-blue-700 focus:outline-none focus:shadow-outline-blue"
>
  Export to Excel
</button>

<table id="tblreports" class="w-full whitespace-nowrap">
  <thead>
    <tr class="font-semibold tracking-wide text-left text-gray-500 bg-gray-100 uppercase border-b">
      <th class="px-4 py-3">Product Name</th>
      <th class="px-4 py-3">Amount</th>
      <th class="px-4 py-3">Status</th>
      <th class="px-4 py-3">Source</th>
      <th class="px-4 py-3">Incoming Date</th>
    </tr>
  </thead>
</table>

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
  Pace.restart();
  $('#tblreports').hide();
  $('.txtSearch_tblreports_class').hide();

  $('#btnFrmExport').hide();
  if (getCookie(MSG['cookiePrefix']+'GLOBAL-ACCOUNTTYPE')=='3' || getCookie(MSG['cookiePrefix']+'GLOBAL-ACCOUNTTYPE')=='1') {
        $('#btnFrmExport').show();
  }

  function initDataTable() {
      $('#tblreports').DataTable().clear();
      $('#tblreports').DataTable().destroy();
      $('#tblreports').DataTable( {
        ajax: {
          url: apiUrl+'/reports/getStock',
          data: function(d) {
            d.productName = $('#txtFrmName').val();
            d.startIncomingDate = $('#txtFilterStartOrderDate').val();
            d.endIncomingDate = $('#txtFilterEndOrderDate').val();
            d._s = getCookie(MSG['cookiePrefix']+'AUTH-TOKEN');
      },
        },
        "paging": false,
        "ordering": false,
        columns: [
          { data:'ProductName', className:'px-4 py-3 text-sm' },
          { data:'Amount', className:'px-4 py-3 text-sm' },
          {
            data:'Status',
            className:'px-4 py-3 text-sm',
            render: function (data, type, full, meta) {
              var html = data==1 ? '<span class="px-2 py-1 font-semibold leading-tight text-green-700 bg-green-100 rounded-full">Display</span>' : '<span class="px-2 py-1 font-semibold leading-tight text-white bg-red-400 rounded-full">On Storage</span>';
              return html
            },
          },
          { data:'Source', className:'px-4 py-3 text-sm' },
          { data:'IncomingDate', className:'px-4 py-3 text-sm' }
        ],
        'dom': '<"w-full overflow-x-auto rounded-lg"t<"grid px-4 py-3 text-xs tracking-wide text-gray-500 border-t bg-gray-50 sm:grid-cols-9"<"flex items-center col-span-3"i><"col-span-2"><"flex col-span-4 mt-2 sm:mt-auto sm:justify-end"<"inline-flex items-center"p>>>>'
      });
       $('.txtSearch_tblreports_class').show();
      }

  function doSearchTable() {
    $('#tblreports').DataTable().search( $('#txtSearch_tblreports').val() ).draw();
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

  function doReloadTable() {
     initDataTable();
     $('#tblreports').show();
  }

  function ShowAllData() {
     $('#imeiInput').val("");
     $('#txtFilterStartOrderDate').val("");
     $('#txtFilterEndOrderDate').val("");
     $('#tblreports').show();
     initDataTable();
    }

  function downloadExcel() {
    window.location=apiUrl+'/reports/getStock?_export=true&' + 'imei=' + $('#txtFrmName').val() + '&startIncomingDate='+$('#txtFilterStartOrderDate').val() + '&endIncomingDate='+$('#txtFilterEndOrderDate').val() + '&_s='+getCookie(MSG['cookiePrefix']+'AUTH-TOKEN');
  }
</script>