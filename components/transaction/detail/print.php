<style>
  @media print {
    #sidebarWrapper, #headerWrapper, #btnFrmBack, #btnFrmPrint, #lblPrintTransaction {
      display:none !important;
    }
    body,html,#contentWrapper {
        margin-top:0%;
        display:block;
        height:100%;
        width:100%;
    }
      #mainTable { page-break-after:always }
      #mainTable.tr    { page-break-inside:avoid; page-break-after:always }
      #mainTable.td    { page-break-inside:avoid; page-break-after:always }
      #mainTable.thead { display:table-header-group }
      #mainTable.tfoot { display:table-footer-group }
  }
</style>

<button
  id="btnFrmBack"
  onclick="previousPage()"
  class="w-40 mt-2 mb-6 mr-2 px-4 py-2 text-sm text-white bg-blue-600 border border-transparent rounded-lg active:bg-blue-600 hover:bg-blue-700 focus:outline-none focus:shadow-outline-blue"
>
  Back
</button>

<button
  id="btnFrmPrint"
  onclick="printPage()"
  class="w-40 mt-2 mb-6 mr-2 px-4 py-2 text-sm text-white bg-blue-600 border border-transparent rounded-lg active:bg-blue-600 hover:bg-blue-700 focus:outline-none focus:shadow-outline-blue"
>
  Print
</button>

<h2 id = "lblPrintTransaction" class="lblPrintTransaction text-4xl font-semibold text-gray-700 mb-10">Print Transaction</h2>

<table>
  <thead>
    <tr>
      <th id="currentDate" class="px-5 py-5"></th>
      <th class="px-5 py-5">&nbsp;</th>
      <th class="px-5 py-5">&nbsp;</th>
      <th class="px-5 py-5">SRC Erlina</th>
    </tr>
  </thead>
</table>

<table class="mainTable">
  <thead>
    <tr>
      <th class="px-5 py-5">No</th>
      <th class="px-5 py-5">Product Name</th>
      <th class="px-5 py-5">Price</th>
      <th class="px-5 py-5">Qty</th>
      <th class="px-5 py-5">Total</th>
    </tr>
  </thead>
  <tbody id="tblItems">
  </tbody>
</table>
<input type="hidden" name="hdnFrmParentID" id="hdnFrmParentID" />
<script>

  Pace.restart();
  $('#lblBreadcrumb').hide();
  $('#bgWrapper').hide();

  n =  new Date();
  y = n.getFullYear();
  m = n.getMonth() + 1;
  d = n.getDate()

  dateFormat = m + "/" + d + "/" + y;
  $('#currentDate').html(dateFormat);

  function onDetailForm(ID) {
    modal.close();
    $('#hdnFrmParentID').val(ID);
    doFetch('transaction/get','_i='+ID);
    doFetch('transaction/detail/get','print=1&_i='+ID);
  }
  function onCompleteFetch(data) {
    Swal.close();
    $('#lblFrmAccount').html(data.AccountDesc);
    $('#lblFrmStore').html(data.Store);
    $('#lblFrmContactPhone').html(data.ContactPhone);
    $('#lblFrmSalesman').html(data.SalesmanDesc);
    $('#lblFrmPurchaseOrderID').html(data.PurchaseOrderID);
    $('#lblFrmDeliveryOrderID').html(data.ID);
    $('#lblFrmSentDate').html(data.SentDate);
  }
  function onCompleteFetchList(data) {
    Swal.close();
    items = new Array();
    var total = parseFloat(0);
    var html = '';
    var no = 0;
    var total = parseFloat(0);
    jQuery.each(data, function(index, value) {
        html = '';
        no++;
        html += '<tr>';
        html += '<td class="px-5 py-5">'+no+'</td>';
        html += '<td class="px-5 py-5">'+value['ProductName']+'</td>';
        html += '<td class="px-5 py-5">'+doFormatNumber(value['Price'])+'</td>';
        html += '<td class="px-5 py-5">'+value['Amount']+'</td>';
        html += '<td class="px-5 py-5">'+doFormatNumber(value['Total'])+'</td>';
        html += '</tr>';
        $('#tblItems').append(html);
        total += parseFloat(value['Total']);
    });
    html = '';
    html += '<tr>';
    html += '<td class="px-4 py-3 text-sm">&nbsp;</td>';
    html += '<td class="px-4 py-3 text-sm">&nbsp;</td>';
    html += '<td class="px-4 py-3 text-sm">&nbsp;</td>';
    html += '<td class="px-4 py-3 text-sm font-semibold">Grand Total</td>';
    html += '<td class="px-4 py-3 text-sm font-semibold">'+doFormatNumber(total)+'</td>';
    html += '</tr>';
    $('#tblItems').append(html);
  }
  
  function printPage() {
    window.print();
  }

  function previousPage() {
    loadPage('transaction/detail/index','onDetailForm(\''+$('#hdnFrmParentID').val()+'\',true)');
  }
</script>