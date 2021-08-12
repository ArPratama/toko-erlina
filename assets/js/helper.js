// Alpine JS
function data() {
  return {
    isLoginPage: true,
    isSideMenuOpen: false,
    isProfileMenuOpen: false,
    isModalOpen: false,
    openForgot() {
      this.isLoginPage = false
    },
    openLogin() {
      this.isLoginPage = true
    },
    toggleSideMenu() {
      this.isSideMenuOpen = !this.isSideMenuOpen
    },
    closeSideMenu() {
      this.isSideMenuOpen = false
    },
    toggleProfileMenu() {
      this.isProfileMenuOpen = !this.isProfileMenuOpen
    },
    closeProfileMenu() {
      this.isProfileMenuOpen = false
    },
    openModal() {
      this.isModalOpen = true
    },
    closeModal() {
      this.isModalOpen = false
    },
  }
}

// Global Setting
var maxTimeout = 540000; //9 minutes
var apiUrl = 'api/public';
var MSG = {
  cookiePrefix: 'DAMS-',
  onLoading: 'Please wait...',
  disconnected: 'Please check your internet connection',
  UrlNotFound: 'Page not found'
};
$.fn.dataTable.ext.classes.sPageButton = 'px-3 py-1 rounded-md cursor-pointer';
$.fn.dataTable.ext.classes.sPageButtonActive = 'px-3 py-1 bg-blue-600 text-white rounded-md cursor-pointer';

// Global Function
function setCookie(cname,cvalue) {
  var d = new Date();
  d.setTime(d.getTime() + ((30*12)*24*60*60*1000));
  var expires = 'expires=' + d.toGMTString();
  document.cookie = cname + '=' + cvalue + ';' + expires + ';path=/';
}
function getCookie(cname) {
  var name = cname + '=';
  var decodedCookie = decodeURIComponent(document.cookie);
  var ca = decodedCookie.split(';');
  for(var i = 0; i < ca.length; i++) {
    var c = ca[i];
    while (c.charAt(0) == ' ') {
      c = c.substring(1);
    }
    if (c.indexOf(name) == 0) {
      return c.substring(name.length, c.length);
    }
  }
  return '';
}
function doStripTags(str) { 
  if ((str===null) || (str==='')) {
    return false; 
  } else {
    str = str.toString(); 
  }
  return str.replace( /(<([^>]+)>)/ig, ''); 
} 
function doFormatNumber(str,limit=0) {
  if (str != null) {
    if (limit==0) { 
      if (parseFloat(str)!=0) {
        if (str.toString().includes('.')) { return parseFloat(str).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'); }
        else { return str.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","); }
      } else {
        return str; 
      }
    }
    else { return parseFloat(str).toFixed(limit).replace(/\d(?=(\d{3})+\.)/g, '$&,'); }
  } else {
    return "0";
  }
}
function doFormatID(str) {
  return str.replace(/\s+/g, '').toUpperCase();
}
function doBeforeSend(loading=true) {
  Pace.restart(); 
  if (loading) { Swal.fire({html:MSG['onLoading'],width:'300px',allowOutsideClick:false,allowEscapeKey:false,allowEnterKey:false,showConfirmButton:false}); }
}
function doHandleSuccess(e) {
  if (!e.status) {
    if (e.callback && e.approval) {
        Swal.fire({
            title: 'Info',
            text: e.message,
            icon: 'warning',
            showCancelButton: false,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'OK'
          }).then((result) => {
            if (result.isConfirmed) {
                eval(e.callback);
            }
        })
    } else {
        Swal.fire({title:'Error', text:e.message, icon:'error'});
        if (e.callback) { eval(e.callback); }
    }
  } else {
    if (e.message) { Swal.fire({title:'Success', text:e.message, icon:'success'});}
    if (e.callback) { eval(e.callback); }
  }
}
function doHandleError(e, t, i) {
  var message = '';
  if (i == 'timeout' || i == '') { message = MSG['disconnected']; }
  if (i == 'Not Found') { message = MSG['UrlNotFound']; }
  Swal.fire({title:'Error', text: message == '' ? i : message, icon:'error'});
  if (e.callback) { eval(e.callback); }
  console.log(e, t, i);
}
function doSubmitForm(event,postUrl,formID) {
  event.preventDefault();
  $.ajax({
    url: apiUrl+'/'+postUrl,
    type: 'post',
    data: $('#'+formID).serialize()+'&_s='+getCookie(MSG['cookiePrefix']+'AUTH-TOKEN'),
    beforeSend: function(e, t, i) { doBeforeSend(); },
    success: function(e) { doHandleSuccess(e); },
    error: function(e, t, i) { doHandleError(e, t, i) },
    timeout: maxTimeout
  });
}
function doSubmit(postUrl,arrData) {
  $.ajax({
    url: apiUrl+'/'+postUrl,
    type: 'post',
    data: arrData+'&_s='+getCookie(MSG['cookiePrefix']+'AUTH-TOKEN'),
    beforeSend: function(e, t, i) { doBeforeSend(); },
    success: function(e) { doHandleSuccess(e); },
    error: function(e, t, i) { doHandleError(e, t, i) },
    timeout: maxTimeout
  });
}
function doFetch(postUrl,params,loading=true) {
  $.ajax({
    url: apiUrl+'/'+postUrl+'?'+params+'&_s='+getCookie(MSG['cookiePrefix']+'AUTH-TOKEN'),
    type: 'get',
    beforeSend: function(e, t, i) { doBeforeSend(loading); },
    success: function(e) { doHandleSuccess(e); },
    error: function(e, t, i) { doHandleError(e, t, i) },
    timeout: maxTimeout
  });
}
function doUpload(postUrl,fileID) {
  var formData = new FormData();
  formData.append('_s',getCookie(MSG['cookiePrefix']+'AUTH-TOKEN'));
  formData.append(fileID,$('#'+fileID)[0].files[0]);
  $.ajax({
    url: apiUrl+'/'+postUrl,
    type: 'post',
    data: formData,
    contentType: false,
    processData: false,
    beforeSend: function(e, t, i) { doBeforeSend(); },
    success: function(e) { doHandleSuccess(e); },
    error: function(e, t, i) { doHandleError(e, t, i) },
    timeout: maxTimeout
  });
}
function loadMenu(url,name) {
  if (url != '#') {
    $.ajax({
      url: 'components/'+url+'/index.php',
      type: 'get',
      beforeSend: function(e, t, i) { doBeforeSend(); },
      success: function(e) { 
        $('.tagMenu').removeClass('bg-blue-50 rounded-full');
        $('.tagMenu_'+url).addClass('bg-blue-50 rounded-full');
        $('#lblBreadcrumb').show();
        $('#bgWrapper').show();
        $('#lblHero').html(name);
        $('#lblBreadcrumb').html('<a href="#" onclick="loadMenu(\''+url+'\',\''+name+'\')">'+name+'</a>');
        $('#contentWrapper').html(e);
        Swal.close();
      },
      error: function(e, t, i) { doHandleError(e, t, i) },
      timeout: maxTimeout
    });
  }
}
function loadPage(url,init='') {
  if (url != '#') {
    $.ajax({
      url: 'components/'+url+'.php',
      type: 'get',
      beforeSend: function(e, t, i) { doBeforeSend(); },
      success: function(e) { 
        $('#contentWrapper').html(e);
        Swal.close();
        if (init != '') { eval(init); }
      },
      error: function(e, t, i) { doHandleError(e, t, i) },
      timeout: maxTimeout
    });
  }
}
function loadModal(url,init='') {
  if (url != '#') {
    $.ajax({
      url: 'components/'+url+'.php',
      type: 'get',
      beforeSend: function(e, t, i) { doBeforeSend(); },
      success: function(e) { 
        $('#modalContentWrapper').html(e);
        Swal.close();
        modal.show();
        if (init != '') { eval(init); }
      },
      error: function(e, t, i) { doHandleError(e, t, i) },
      timeout: maxTimeout
    });
  }
}

var modal = {
  show: function() {
    $('#modalWrapper').show();
    $('#modalBody').show(300);
  },
  close: function() {
    $('#modalBody').hide();
    $('#modalWrapper').hide();
  }
};