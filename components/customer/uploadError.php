<div class="py-3 px-5 mb-4 bg-yellow-100 text-yellow-900 text-sm rounded-md border border-yellow-200 flex items-center" role="alert">
  <div class="w-4 mr-2">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
    </svg>
  </div>
  <span>All task has been aborted, please correct your data and retry upload</span>
</div>

<table id="tblError" class="w-full whitespace-nowrap">
  <thead>
    <tr id="tblErrorHeader" class="font-semibold tracking-wide text-left text-xs text-gray-500 bg-gray-100 uppercase border-b"></tr>
  </thead>
  <tbody id="tblErrorData"></tbody>
</table>

<script>
  function onLoad(dataSource,isReload=false) {
    modal.close();
    var html = $('#lblBreadcrumb').html();
    if (!isReload) {
      html += ' / <a href="#" onclick="loadPage(\'products/uploadError\',\'onLoad(`'+dataSource+'`,true)\')">Upload Error</a>';
    }
    $('#lblBreadcrumb').html(html);
    dataSource = JSON.parse(decodeURIComponent(dataSource));
    Pace.restart();
    var html = '';
    for (var i=0; i<dataSource['header'].length; i++) {
      html += '<th class="px-4 py-3">'+dataSource['header'][i]+'</th>';
    }
    $('#tblErrorHeader').html(html);
    var html = '';
    for (var index=0; index<dataSource['data'].length; index++) {
      html += '<tr>';
      for (var i=0; i<dataSource['data'][index].length; i++) {
        html += '<td class="px-2 py-1 border-b text-xs '+(i==0 || i==1 ? 'bg-yellow-50 text-yellow-900' : '')+'">'+dataSource['data'][index][i]+'</td>';
      }
      html += '</tr>';
    }
    $('#tblErrorData').html(html);
    $('#tblError').DataTable({
      "paging": false,
      "ordering": false,
      'dom': '<"w-full overflow-x-auto rounded-lg"t<"grid px-4 py-3 text-xs tracking-wide text-gray-500 border-t bg-gray-50 sm:grid-cols-9"<"flex items-center col-span-3"i><"col-span-2"><"flex col-span-4 mt-2 sm:mt-auto sm:justify-end"<"inline-flex items-center"p>>>>'
    });
  }
</script>