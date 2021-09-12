<label class="block mt-4 text-xs">
  <span class="block text-gray-500">Transaction Date:</span>
  <span id="lblFrmTransactionDate" class="block"></span>
</label>
<label class="block mt-4 text-xs">
  <span class="block text-gray-500">Total Item:</span>
  <span id="lblFrmTotalItem" class="block"></span>
</label>
<label class="block mt-4 text-xs">
  <span class="block text-gray-500">Total Price:</span>
  <span id="lblFrmTotalPrice" class="block"></span>
</label>


<div class="relative text-gray-600 w-52 mt-6 mb-6 float-left">
  <input 
    id="txtSearch_tbltransaction"
    placeholder="Search" 
    class="bg-white h-10 px-5 pr-10 rounded-lg text-sm focus:outline-none border"
    onkeyup="doSearchTable()">
  <button type="submit" class="absolute right-0 top-0 mt-3 mr-4">
  <svg class="h-4 w-4 fill-current" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" viewBox="0 0 56.966 56.966" xml:space="preserve" width="512px" height="512px">
    <path d="M55.146,51.887L41.588,37.786c3.486-4.144,5.396-9.358,5.396-14.786c0-12.682-10.318-23-23-23s-23,10.318-23,23  s10.318,23,23,23c4.761,0,9.298-1.436,13.177-4.162l13.661,14.208c0.571,0.593,1.339,0.92,2.162,0.92  c0.779,0,1.518-0.297,2.079-0.837C56.255,54.982,56.293,53.08,55.146,51.887z M23.984,6c9.374,0,17,7.626,17,17s-7.626,17-17,17  s-17-7.626-17-17S14.61,6,23.984,6z"/>
  </svg>
  </button>
</div>

<table id="tbltransaction" class="w-full whitespace-nowrap">
  <thead>
    <tr class="font-semibold tracking-wide text-left text-gray-500 bg-gray-100 uppercase border-b">
      <th class="px-4 py-3">Product Name</th>
      <th class="px-4 py-3">Price</th>
      <th class="px-4 py-3">Amount</th>
      <th class="px-4 py-3">Total</th>
      <th class="px-4 py-3">Status</th>
    </tr>
  </thead>
</table>
<input type="hidden" name="hdnFrmParentID" id="hdnFrmParentID" />
<script>
  Pace.restart();
  function onDetailForm(ID,isReload=false) {
    modal.close();
    var html = $('#lblBreadcrumb').html();
    if (!isReload) {
      html += ' / <a href="#" onclick="loadPage(\'report_purchasing/detail/index\',\'onDetailForm(`'+ID+'`,true)\')">Detail</a>';
    }
    $('#lblBreadcrumb').html(html);
    $('#hdnFrmParentID').val(ID);
    doFetch('transaction/get','_i='+ID);
    
    $('#tbltransaction').DataTable( {
      ajax: {
        url: apiUrl+'/transaction/detail/get?_i='+ID+'&_s='+getCookie(MSG['cookiePrefix']+'AUTH-TOKEN'),
      },
      "ordering": false,
      columns: [
        { data:'ProductName', className:'px-4 py-3 text-sm' },
        {
          data:'Price',
          className:'px-4 py-3 text-sm text-right',
          render: function (data, type, full, meta) {
            var html = doFormatNumber(data);
            return html
          },
        },
        { data:'Amount', className:'px-4 py-3 text-sm' },
        {
          data:'Total',
          className:'px-4 py-3 text-sm text-right',
          render: function (data, type, full, meta) {
            var html = doFormatNumber(data);
            return html
          },
        },
        { data:'Status', className:'px-4 py-3 text-sm' }
      ],
      'dom': '<"w-full overflow-x-auto rounded-lg"t<"grid px-4 py-3 text-xs tracking-wide text-gray-500 border-t bg-gray-50 sm:grid-cols-9"<"flex items-center col-span-3"i><"col-span-2"><"flex col-span-4 mt-2 sm:mt-auto sm:justify-end"<"inline-flex items-center"p>>>>'
    });
  }
  function onCompleteFetch(data) {
    Swal.close();
    $('#lblFrmTransactionDate').html(data.TransactionDate);
    $('#lblFrmTotalItem').html(data.TotalItem);
    $('#lblFrmTotalPrice').html(doFormatNumber(data.TotalPrice));
  }
  
  function doReloadTable() {
    Pace.restart();
    $('#tbltransaction').DataTable().ajax.reload();
    modal.close();
  }

  function doSearchTable() {
    $('#tbltransaction').DataTable().search( $('#txtSearch_tbltransaction').val() ).draw();
  }

  function showDeleteConfirm(ID,Label) {
    Swal.fire({html:'Do you want to delete <strong>'+Label+'</strong> ?<br /><br />', icon:'warning', showCancelButton:true, confirmButtonText: 'Yes', cancelButtonText: 'No'})
    .then((result) => {
      if (result.isConfirmed) {
        var param = '_i='+ID;
        param += '&transactionID='+$('#hdnFrmParentID').val();
        doSubmit('transaction/detail/doDelete',param);
      }
    });
  }

  if (getCookie(MSG['cookiePrefix']+'GLOBAL-ACCOUNTTYPE')=='2') {
    $('#btnFrmAdd').show();
  }
</script>