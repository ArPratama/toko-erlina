<!--
  <div class="relative text-gray-600 w-52 mb-6 float-left">
  <input 
    id="txtSearch_tblStock"
    placeholder="Search" 
    class="bg-white h-10 px-5 pr-10 rounded-lg text-sm focus:outline-none border"
    onkeyup="doSearchTable()">
  <button type="submit" class="absolute right-0 top-0 mt-3 mr-4">
  <svg class="h-4 w-4 fill-current" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" viewBox="0 0 56.966 56.966" xml:space="preserve" width="512px" height="512px">
    <path d="M55.146,51.887L41.588,37.786c3.486-4.144,5.396-9.358,5.396-14.786c0-12.682-10.318-23-23-23s-23,10.318-23,23  s10.318,23,23,23c4.761,0,9.298-1.436,13.177-4.162l13.661,14.208c0.571,0.593,1.339,0.92,2.162,0.92  c0.779,0,1.518-0.297,2.079-0.837C56.255,54.982,56.293,53.08,55.146,51.887z M23.984,6c9.374,0,17,7.626,17,17s-7.626,17-17,17  s-17-7.626-17-17S14.61,6,23.984,6z"/>
  </svg>
  </button>
</div>
-->

<button
  id="btnFrmAdd"
  onclick="loadModal('stock/form')"
  class="w-32 float-right mb-6 px-4 py-2 text-sm text-white bg-blue-600 border border-transparent rounded-lg active:bg-blue-600 hover:bg-blue-700 focus:outline-none focus:shadow-outline-blue"
  style="display:none"
>
  Add New
</button>

<button
  id="btnFrmBulk"
  onclick="loadModal('stock/bulk')"
  class="w-38 float-right mb-6 mr-2 hidden sm:block px-4 py-2 text-sm text-white bg-blue-600 border border-transparent rounded-lg active:bg-blue-600 hover:bg-blue-700 focus:outline-none focus:shadow-outline-blue"
  style="display:none"
>
  Bulk Operation
</button>

<button
  id="btnFrmExport"
  onclick="downloadExcel()"
  class="w-40 float-right mb-6 mr-2 mt-4 hidden sm:block px-4 py-2 text-sm text-white bg-blue-600 border border-transparent rounded-lg active:bg-blue-600 hover:bg-blue-700 focus:outline-none focus:shadow-outline-blue"
  style="display:none"
>
  Export to Excel
</button>

<table id="tblstock" class="w-full whitespace-nowrap">
  <thead>
    <tr class="font-semibold tracking-wide text-left text-gray-500 bg-gray-100 uppercase border-b">
      <th class="px-4 py-3">
        Item ID<br />
        <input
          id="txtFilterItemID"
          name="txtFilterItemID"
          type="text"
          class="border p-2 rounded w-full mt-1 text-sm form-input focus:border-gray-400 focus:outline-none focus:shadow-outline-gray"
          placeholder="Type here"
          onkeyup="doSearchTableColumn(0,$('#txtFilterItemID').val())"
        />
      </th>
      <th class="px-4 py-3">
        Product Name<br />
        <input
          id="txtFilterProduct"
          name="txtFilterProduct"
          type="text"
          class="border p-2 rounded w-full mt-1 text-sm form-input focus:border-gray-400 focus:outline-none focus:shadow-outline-gray"
          placeholder="Type here"
          onkeyup="doSearchTableColumn(1,$('#txtFilterProduct').val())"
        />
      </th>
      <th class="px-4 py-3">
        Amount<br />
        <input
          id="txtFilterAmount"
          name="txtFilterAmount"
          type="text"
          class="bg-gray-100 border p-2 rounded w-full mt-1 text-sm form-input focus:border-gray-400 focus:outline-none focus:shadow-outline-gray"
          placeholder="Type here"
          disabled
        />
      </th>
      <th class="px-4 py-3">
        Status<br />
        <input
          id="txtFilterStatus"
          name="txtFilterStatus"
          type="text"
          class="bg-gray-100 border p-2 rounded w-full mt-1 text-sm form-input focus:border-gray-400 focus:outline-none focus:shadow-outline-gray"
          placeholder="Type here"
          onkeyup="doSearchTableColumn(2,$('#txtFilterStatus').val())"
        />
      </th>
      <th class="px-4 py-3">
        Source<br />
        <input
          id="txtFilterSource"
          name="txtFilterSource"
          type="text"
          class="bg-gray-100 border p-2 rounded w-full mt-1 text-sm form-input focus:border-gray-400 focus:outline-none focus:shadow-outline-gray"
          placeholder="Type here"
          disabled
        />
      </th>
      <th class="px-4 py-3">
        Incoming Date<br />
        <input
          id="txtFilterIncomingDate"
          name="txtFilterIncomingDate"
          type="text"
          class="bg-gray-100 border p-2 rounded w-full mt-1 text-sm form-input focus:border-gray-400 focus:outline-none focus:shadow-outline-gray"
          placeholder="Type here"
          disabled
        />
      </th>
      <th class="px-4 py-3">&nbsp;</th>
    </tr>
  </thead>
</table>

<script>
  Pace.restart();
  $('#tblstock').DataTable( {
    ajax: {
      url: apiUrl+'/stock/get?_s='+getCookie(MSG['cookiePrefix']+'AUTH-TOKEN'),
      beforeSend: function(e, t, i) { doBeforeSend(); },
      error: function(e, t, i) { doHandleError(e, t, i) },
      complete: function (result) {
        if (parseInt(result.responseJSON.recordsTotal) == 0) {
          $('#tblstock tbody').html('');
          $('#tblstock_info').html('Showing 0 to 0 of 0 entries')
          $('#tblstock_paginate').html('<div><a class="px-3 py-1 rounded-md cursor-pointer previous disabled">Previous</a><span></span><a class="px-3 py-1 rounded-md cursor-pointer next disabled">Next</a></div>');
        } 
        Swal.close(); 
      },
      timeout: maxTimeout
    },
    "drawCallback": function( settings ) {
      Swal.close();
    },
    "processing": true,
    "serverSide": true,
    "ordering": false,
    columns: [
      { data:'ID', className:'px-4 py-3 text-sm' },
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
      { data:'IncomingDate', className:'px-4 py-3 text-sm' },
      {
        data:'ID',
        render: function (data, type, full, meta) {
          if (getCookie(MSG['cookiePrefix']+'GLOBAL-ACCOUNTTYPE')=='1') {
            var html = '<div class="flex item-center justify-center">' +
                        '<div onclick="showDetailView(\''+full['ID']+'\')" class="w-4 mr-2 transform hover:text-blue-500 hover:scale-110 cursor-pointer">' +
                          '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">' +
                            '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />' +
                            '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />' +
                          '</svg>' +
                        '</div>' +
                      '</div>';
          } else {
            var html = '<div class="flex item-center justify-center">' +
                        '<div onclick="showDetailView(\''+full['ID']+'\')" class="w-4 mr-2 transform hover:text-blue-500 hover:scale-110 cursor-pointer">' +
                          '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">' +
                            '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />' +
                            '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />' +
                          '</svg>' +
                        '</div>' +
                      '</div>';
          }
          return html
        },
      },
    ],
    'dom': '<"w-full overflow-x-auto rounded-lg"t<"grid px-4 py-3 text-xs tracking-wide text-gray-500 border-t bg-gray-50 sm:grid-cols-9"<"flex items-center col-span-3"i><"col-span-2"><"flex col-span-4 mt-2 sm:mt-auto sm:justify-end"<"inline-flex items-center"p>>>>'
  });

  function doReloadTable() {
    Pace.restart();
    $('#tblstock').DataTable().ajax.reload();
    modal.close();
  }

  function doSearchTable() {
    $('#tblstock').DataTable().search( $('#txtSearch_tblstock').val() ).draw();
  }

  function doSearchTableColumn(column,value) {
    value = value.trim();
    if (value.length >= 0) {
      setTimeout(function(){  
        $('#tblstock').DataTable().columns( column ).search( value ).draw();
      }, 1000);
    }
  }

  function showDetailView(ID) {
    loadModal('stock/view','onDetailForm(\''+ID+'\')');
  }

  function showDetailForm(ID) {
    loadModal('stock/form','onDetailForm(\''+ID+'\')');
  }

  function downloadExcel() {
    window.location=apiUrl+'/stock/get?_export=true&_s='+getCookie(MSG['cookiePrefix']+'AUTH-TOKEN');
  }

  function showDeleteConfirm(ID,Label) {
    Swal.fire({html:'Do you want to delete <strong>'+Label+'</strong> ?', icon:'warning', showCancelButton:true, confirmButtonText: 'Yes', cancelButtonText: 'No'})
    .then((result) => {
      if (result.isConfirmed) {
        var param = '_i='+ID;
        doSubmit('stock/doDelete',param);
      }
    });
  }

  if (getCookie(MSG['cookiePrefix']+'GLOBAL-ACCOUNTTYPE')=='1') {
    $("#btnFrmAdd").show();
    $("#btnFrmBulk").show();
  } else {
    $("#btnFrmExport").show();
  }
</script>