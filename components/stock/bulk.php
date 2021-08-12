<div class="mt-4 mb-6">
  <div id="lblMdlTitle" class="mb-2 text-lg font-semibold text-gray-700">Stock Management</div>
  <div class="text-sm text-gray-700">
    <p class="text-gray-400 mb-8">Here you can export all data</p>
    
    <button
      onclick="downloadExcel()"
      class="w-full px-4 py-2 text-sm text-white bg-blue-600 border border-transparent rounded-lg active:bg-blue-600 hover:bg-blue-700 focus:outline-none focus:shadow-outline-blue"
    >
      Export to Excel
    </button>

<script>
  function downloadTemplate() {
    window.location='components/stock/Product_Management_Template.xlsx';
  }

  function beforeUpload() {
    doUpload('stock/doUpload','inpFile');
  }

  function uploadError(data) {
    loadPage('stock/uploadError','onLoad(\''+encodeURIComponent(JSON.stringify(data))+'\')');
  }

  if (getCookie(MSG['cookiePrefix']+'GLOBAL-ACCOUNTTYPE')=='1') {
    $("#wrapperAccessUpload").show();
  } else {
    $("#lblNotAccessUpload").show();
  }
</script>