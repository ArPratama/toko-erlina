<div class="float-left">
  <span class="text-xs text-gray-500 block">Filter by Last Order: </span>
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
<div class="relative text-gray-600 w-52 mb-6 float-left mt-4">
  <input id="imeiInput"
   placeholder="Loading..."
   class=" imeiSelClass bg-white h-10 px-5 rounded-lg w-30 text-sm focus:outline-none border filter grayscale blur-md contrast-200">
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
      <th class="px-4 py-3">SKU</th>
      <th class="px-4 py-3">IMEI</th>
      <th class="px-4 py-3">Product Name</th>
      <th class="px-4 py-3">Description</th>
      <th class="px-4 py-3">Color</th>
      <th class="px-4 py-3">Capacity</th>
      <th class="px-4 py-3 text-right">Item Sold</th>
      <th class="px-4 py-3">Last Order</th>
    </tr>
  </thead>
</table>

<style>
.ui-autocomplete {
    position: absolute;
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
  text-align: center;
}

.ui-helper-hidden-accessible { display:none; }

</style>

<script>
  Pace.restart();
  $('#tblreports').hide();
  fetchIMEI();
  $('.imeiSelClass').prop("disabled",true);
  $('.txtSearch_tblreports_class').hide();

  function initDataTable() {
      $('#tblreports').DataTable().clear();
      $('#tblreports').DataTable().destroy();
      $('#tblreports').DataTable( {
        ajax: {
          url: apiUrl+'/reports/getSalesByModel',
          data: function(d) {
            d.imei = $('#imeiInput').val();
            d.startOrderDate = $('#txtFilterStartOrderDate').val();
            d.endOrderDate = $('#txtFilterEndOrderDate').val();
            d._s = getCookie(MSG['cookiePrefix']+'AUTH-TOKEN');
      },
        },
        "paging": false,
        "ordering": false,
        columns: [
          { data:'SKU', className:'px-4 py-3 text-sm' },
          { data:'IMEI', className:'px-4 py-3 text-sm' },
          { data:'Product', className:'px-4 py-3 text-sm' },
          { data:'Description', className:'px-4 py-3 text-sm' },
          { data:'Color', className:'px-4 py-3 text-sm' },
          { data:'Capacity', className:'px-4 py-3 text-sm' },
          { data:'Total', className:'px-4 py-3 text-sm text-right' },
          { data:'LastBook', className:'px-4 py-3 text-sm' },
        ],
        'dom': '<"w-full overflow-x-auto rounded-lg"t<"grid px-4 py-3 text-xs tracking-wide text-gray-500 border-t bg-gray-50 sm:grid-cols-9"<"flex items-center col-span-3"i><"col-span-2"><"flex col-span-4 mt-2 sm:mt-auto sm:justify-end"<"inline-flex items-center"p>>>>'
      });
       $('.txtSearch_tblreports_class').show();
      }

  function doSearchTable() {
    $('#tblreports').DataTable().search( $('#txtSearch_tblreports').val() ).draw();
  }

  function fetchIMEI() {
    doFetch('global/getIMEI','_cb=onCompleteFetchIMEI&type=SalesByModel&_p=',false);
  }

  function onCompleteFetchIMEI(data) {
    var html = '';
    var imeiArr = [];
    for (i=0;i<data.length;i++) {
      imeiArr.push(data[i].IMEI);
    }

    $('.imeiSelClass').attr('placeholder', 'Search IMEI')
    $('.imeiSelClass').prop("disabled",false);

    $( "#imeiInput" ).autocomplete({
      minLength: 3,
      source: function (request, response) {
        var results = $.ui.autocomplete.filter(imeiArr, request.term);
        response(results.slice(0, 10));
      }
    });
    }

    $('.imeiSelClass').keypress(function(event){
        var keycode = (event.keyCode ? event.keyCode : event.which);
        if(keycode == '13'){
            doReloadTable();
        }
    });

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
    window.location=apiUrl+'/reports/getSalesByModel?_export=true&' + 'imei=' + $('#imeiInput').val() + '&startOrderDate='+$('#txtFilterStartOrderDate').val() + '&endOrderDate='+$('#txtFilterEndOrderDate').val() + '&_s='+getCookie(MSG['cookiePrefix']+'AUTH-TOKEN');
  }
</script>