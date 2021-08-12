<div class="float-left">
  <span class="text-xs text-gray-500 block">Filter by Mutation Date: </span>
  <input
    id="txtFilterStartMutationDate"
    name="txtFilterStartMutationDate"
    type="date"
    maxlength="50"
    class="border p-2 rounded w-30 text-sm text-gray-500 form-input focus:border-gray-400 focus:outline-none focus:shadow-outline-gray"
    placeholder="Type here"
    onchange="doReloadTable()"
  />
  <input
    id="txtFilterEndMutationDate"
    name="txtFilterEndMutationDate"
    type="date"
    maxlength="50"
    class="border p-2 rounded w-30 sm:mr-2 text-sm text-gray-500 form-input focus:border-gray-400 focus:outline-none focus:shadow-outline-gray"
    placeholder="Type here"
    onchange="doReloadTable()"
  />
</div>
<div class="relative text-gray-600 w-52 mb-2 float-left mt-4">
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
      <th class="px-4 py-3">Mutation Date</th>
      <th class="px-4 py-3">Distributor</th>
      <th class="px-4 py-3">IMEI</th>
      <th class="px-4 py-3">SKU</th>
      <th class="px-4 py-3">Product Name</th>
      <th class="px-4 py-3">Description</th>
      <th class="px-4 py-3">Color</th>
      <th class="px-4 py-3">Capacity</th>
      <th class="px-4 py-3">Origin</th>
      <th class="px-4 py-3">Destination</th>
    </tr>
  </thead>
</table>

<script>
  Pace.restart();
  $('#tblreports').DataTable( {
    ajax: {
      url: apiUrl+'/reports/getMutation',
      data: function(d) {
        d.startMutationDate = $('#txtFilterStartMutationDate').val();
        d.endMutationDate = $('#txtFilterEndMutationDate').val();
        d._s = getCookie(MSG['cookiePrefix']+'AUTH-TOKEN');
      },
    },
    "paging": false,
    "ordering": false,
    columns: [
      { data:'MutationDate', className:'px-4 py-3 text-sm' },
      { data:'Distributor', className:'px-4 py-3 text-sm' },
      { data:'IMEI', className:'px-4 py-3 text-sm' },
      { data:'SKU', className:'px-4 py-3 text-sm' },
      { data:'Product', className:'px-4 py-3 text-sm' },
      { data:'Description', className:'px-4 py-3 text-sm' },
      { data:'Color', className:'px-4 py-3 text-sm' },
      { data:'Capacity', className:'px-4 py-3 text-sm' },
      { data:'Origin', className:'px-4 py-3 text-sm' },
      { data:'Destination', className:'px-4 py-3 text-sm' },
    ],
    'dom': '<"w-full overflow-x-auto rounded-lg"t<"grid px-4 py-3 text-xs tracking-wide text-gray-500 border-t bg-gray-50 sm:grid-cols-9"<"flex items-center col-span-3"i><"col-span-2"><"flex col-span-4 mt-2 sm:mt-auto sm:justify-end"<"inline-flex items-center"p>>>>'
  });

  function doReloadTable() {
    Pace.restart();
    $('#tblreports').DataTable().ajax.reload();
    modal.close();
  }

  function doSearchTable() {
    $('#tblreports').DataTable().search( $('#txtSearch_tblreports').val() ).draw();
  }

  function downloadExcel() {
    window.location=apiUrl+'/reports/getMutation?_export=true&startMutationDate='+$('#txtFilterStartMutationDate').val()+'&endMutationDate='+$('#txtFilterEndMutationDate').val()+'&_s='+getCookie(MSG['cookiePrefix']+'AUTH-TOKEN');
  }
</script>