require('./bootstrap');

require('./datatables');

var moment = require('moment');

/*
* Target: Negative Lists List
*/
var _table = $('#table');
var _table_no_lastname = $('#table_no_lastname');
var _table_lastname = $('#table_lastname');
var _idSelected = 0;

var _loading = $('#loading-table');
var _loading_no_lastname = $('#loading-table-no-lastname');
var _loading_lastname = $('#loading-table-lastname');

var _isHome = true;
var _totalSearchedNoLastname = -1;
var _totalSearchedLastname = -1;

var _name = '';
var _lastname = '';
var _ruc = '';
var _userId;

init();

$('#table_paginate').hide();
$('.custom-pagination').hide();

$('#div_table').show();
$('#div_table_no_lastname').hide();
$('#div_table_lastname').hide();
$('#div_table_empty').hide();

function init() {
  _userId = $('#userId').text();
  listsNegativeLists();
  lnLists_search(_table_no_lastname, 'not_lastname');
  lnLists_search(_table_lastname, 'same_lastname');
}

function loading(flag) {
  if (flag) {
    $('#loadingOpen').trigger('click');
  } else {
    $('#loadingClose').trigger('click');
  }
}

/* BEGIN - TABLE LIST NEGATIVE LISTS */
function listsNegativeLists() {
  _table.DataTable({
    serverSide: true,
    processing: false,
    destroy: true,
    ajax: {
      type: 'POST', // 'POST'
      url: `/api/v1/negativelists/list-datatable`,
      data: {
        userId: $('#userId').text(),
      },
      dataSrc: function (json) {
        return json.data;
      },
    },
    order: [[0, 'desc']],
    columns: [
      { data: 'id', width: '5%' },
      { data: 'fullname', width: '50%' },
      { data: 'document', width: '15%' },
      {
        data: 'type_color',
        render: function (data, type) {
          if (type === 'display') {
            var [color, label] = data.split('|');
            return `<span style="color: ${color}; font-weight: bold;">${label}</span>`;
          }
          return data;
        },
        width: '20%',
      },
      {
        data: 'created_at',
        render: function (data, type) {
          if (type === 'display') {
            return moment(data).format('DD/MM/YYYY hh:mm:ss');
          }
          return data;
        },
        width: '5%',
      },
      {
        data: 'actions',
        render: function(data, type) {
          if (type === 'display') {
            var details = `
              <svg id="SVG-show-${data}" class="actions h-6 w-6" style="color: #00D5FB" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path id="PATH-show-${data}" class="actions" stroke-linecap="round" stroke-linejoin="round" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            `;
            var assign = `<svg id="SVG-pass-${data}" xmlns="http://www.w3.org/2000/svg" class="actions h-6 w-6 text-conoce-green" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path id="PATH-pass-${data}" class="actions" stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
            </svg>`;
            return `<div class="flex justify-center gap-3">${details}${assign}</div>`;
          }
          return data;
        },
        width: '5%',
      },
    ],
    language: {
      lengthMenu: 'Mostrar _MENU_ reg/pág',
      zeroRecords: 'No hay datos disponibles',
      info: 'Registro _START_ de _END_ de _TOTAL_',
      infoEmpty: 'No hay datos disponibles',
      search: 'Buscar: ',
      select: {
        rows: '- %d registros seleccionados',
      },
      infoFiltered: '(Filtrado de _MAX_ registros)',
    },
    scrollY: "53vh",
    scrollX: true, // <--- Important: Header scrolled
    scrollCollapse: true,
    autoWidth: false,
    paging: true,
    info: false,
    ordering: true,
    lengthChange: false,
    searching: true, // <-- Important: For search third button
    columnDefs: [
      { className: "text-center", targets: [0, 2, 3, 4] }, // targets: "_all",
      { className: "dt-head-center", targets: "_all" },
      { orderable: false, targets: [1, 5] },
      {
        render: function (data, type, full, meta) {
            return "<div style='white-space:normal;'>" + data + "</div>";
        },
        targets: '_all'
      },
    ],
    initComplete: function() {
      setTimeout(function() {
        _table.DataTable().columns.adjust();
        $('.custom-pagination').show();

        var urlParams = new URLSearchParams(window.location.search);
        var regexUser = urlParams.get('regexUser');
        if (regexUser) {
          $('#search').val(regexUser);
          _table.DataTable().ajax.reload(null, false);
          _table.DataTable().search(regexUser).draw();
        }
      }, 500);
    },
    drawCallback: function() {
      var page_info = _table.DataTable().page.info();

      $('.totalpages').text(page_info.pages);
      var html = '';
      var start = 0;
      var length = page_info.length;
      for(var count = 1; count <= page_info.pages; count++) {
        var page_number = count - 1;
        html += `<option value="${page_number}" data-start="${start}" data-length="${length}">
                  ${count}
                </option>`;
        start = start + page_info.length;
      }
      var currPage = page_info.page;
      $('.perpage').val(length);
      $('.pagelist').html(html);
      $('.pagelist').val(currPage);
      if (currPage > 0) {
        $('.page-first').show();
        $('.page-back').show();
      } else {
        $('.page-first').hide();
        $('.page-back').hide();
      }
      if(currPage + 1 === page_info.pages) {
        $('.page-next').hide();
        $('.page-last').hide();
      } else {
        $('.page-next').show();
        $('.page-last').show();
      }

      $('#regStart').text(page_info.start + 1);
      $('#regEnd').text(page_info.end + 1);
      $('#regTotal').text(page_info.recordsTotal);
    }
  });
}

_table.on('processing.dt', function (e, settings, processing) {
  $('#processingIndicator').css('display', 'none');
  if (processing) _loading.show();
  else _loading.hide();
});

$('.dataTables_paginate .paginate_button').on('click', function() {
  $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
});

$('.perpage').on('change', function() {
  var perpage = parseInt($('.perpage').val());
  _table.DataTable().ajax.reload(null, false);
  _table.DataTable().page.len(perpage).draw();
});

$('#pagelistHeader').on('change', function() {
  var page_number = parseInt($("#pagelistHeader option:selected").val());
  _table.DataTable().ajax.reload(null, false);
  _table.DataTable().page(page_number).draw('page');
});

$('#pagelistFooter').on('change', function() {
  var page_number = parseInt($("#pagelistFooter option:selected").val());
  _table.DataTable().ajax.reload(null, false);
  _table.DataTable().page(page_number).draw('page');
});

$('.page-first').on('click', function() {
  _table.DataTable().ajax.reload(null, false);
  _table.DataTable().page('first').draw('page');
});

$('.page-back').on('click', function() {
  var page_info = _table.DataTable().page.info();
  var backPage = page_info.page - 1;
  _table.DataTable().ajax.reload(null, false);
  _table.DataTable().page(backPage).draw('page');
});

$('.page-next').on('click', function() {
  var page_info = _table.DataTable().page.info();
  var nextPage = page_info.page + 1;
  _table.DataTable().ajax.reload(null, false);
  _table.DataTable().page(nextPage).draw('page');
});

$('.page-last').on('click', function() {
  _table.DataTable().ajax.reload(null, false);
  _table.DataTable().page('last').draw('page');
});

$('.neglst--tbody--own').on('click', 'tr', function (event) {
  var target = event.target;
  if (target.classList.contains('actions')) {
    var splitted = target.id.split('-');
    if (splitted.length > 2) {
      var [_, action, id] = splitted;
      _idSelected = id;
      if (action === 'show') showDetails(id, false);
      if (action === 'pass') showTeam();
    }
    return;
  }
});

$('.neglst--tbody--search').on('click', 'tr', function (event) {
  var target = event.target;
  if (target.classList.contains('actions')) {
    var splitted = target.id.split('-');
    if (splitted.length > 2) {
      var [_, action, id] = splitted;
      _idSelected = id;
      if (action === 'show') showDetails(id, true);
      if (action === 'pass') showTeam();
    }
    return;
  }
});

async function showDetails(id, isSearch) {
  loading(true);
  $('#neglst-details').trigger('click');
  await $.ajax({
    url: `/api/v1/negativelists/details/object/${_userId}/${id}/${isSearch}`,
    type: 'get',
    success: function (response) {
      loading(false);
      if (response) {
        if (isSearch) increaseSearches();

        var { id, alias, name, lastname, location, nation, link, passport, position, gender, ruc, tt, type, color, other, date_at } = response;
        
        $('#neglst_lastname').text(lastname);
        $('#neglst_name').text(name);
        $('#neglst_ruc').text(ruc);
        $('#neglst_passport').text(passport);
        $('#neglst_position').text(position);
        $('#neglst_date_at').text(date_at);
        $('#neglst_location').text(location);
        $('#neglst_alias').text(alias);
        $('#neglst_other').text(other);

        if(tt =='N') $('#neglst_tt').text('Individual');
        else $('#neglst_tt').text('Entidad');

        $("#neglst_type").html(`<span style="color: ${color}; font-weight: 500;">${type}</span>`);
        $("#neglst_link").html(`<a href="${link}" target="_blank" style="color: #5500FF; font-weight: 500;">${link}</a>`);
      }
    },
    error: function (XMLHttpRequest, textStatus, errorThrown) {
      loading(false);
      alert("Status: " + textStatus); alert("Error: " + errorThrown);
    }
  });
}

$('#modalBtnPrintNegLst').on('click', async function (event) {
  event.preventDefault();
  var link = document.createElement('a');
  link.target = '_self';
  link.href = `/api/v1/negativelists/details/pdf/${_userId}/${_idSelected}`;
  // link.download = "pruebitas.pdf";
  link.click();
});
/* END - TABLE LIST NEGATIVE LISTS */

/* BEGIN - TABLE LIST NEGATIVE LISTS WITHOUT LASTNAME */
function lnLists_search(table, typeSearch) {
  table.DataTable({
    serverSide: true,
    processing: false,
    destroy: true,
    ajax: {
      type: 'POST',
      url: '/api/v1/negativelists/list-datatable-search',
      data: (d) => {
        d.typeSearch = typeSearch;
        d.userId = $('#userId').text();
        d.name = _name;
        d.lastname = _lastname;
        d.ruc = _ruc;
      },
      dataSrc: function (json) {
        return json.data;
      },
    },
    // order: [[0, 'desc']],
    columns: [
      { data: 'id' },
      { data: 'fullname' },
      { data: 'ruc' },
      { data: 'type_color' },
      {
        data: 'tt',
        render: function (data, type) {
          if (type === 'display') {
            if (data === 'N') return 'Individual'
            else if (data === 'J') return 'Jurídico'
            else return ''
          }
          return data;
        }
      },
      {
        data: 'actions',
        render: function(data, type) {
          if (type === 'display') {
            var details = `
              <svg id="SVG-show-${data}" class="actions h-6 w-6" style="color: #00D5FB" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path id="PATH-show-${data}" class="actions" stroke-linecap="round" stroke-linejoin="round" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            `;
            var assign = `<svg id="SVG-pass-${data}" xmlns="http://www.w3.org/2000/svg" class="actions h-6 w-6 text-conoce-green" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path id="PATH-pass-${data}" class="actions" stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
            </svg>`;
            return `<div class="flex justify-center gap-3">${details}${assign}</div>`;
          }
          return data;
        }
      },
    ],
    language: {
      lengthMenu: 'Mostrar _MENU_ reg/pág',
      zeroRecords: 'No hay datos disponibles',
      info: 'Registro _START_ de _END_ de _TOTAL_',
      infoEmpty: 'No hay datos disponibles',
      search: 'Buscar: ',
      select: {
        rows: '- %d registros seleccionados',
      },
      infoFiltered: '(Filtrado de _MAX_ registros)',
      paginate: {
        previous: "<",
        next: ">",
      }
    },
    scrollY: "53vh",
    scrollX: true, // <--- Important: Header scrolled
    scrollCollapse: true,
    autoWidth: false,
    paging: true,
    info: false,
    ordering: true,
    lengthChange: false,
    searching: true, // <-- Important: For search third button
    columnDefs: [
      { className: "text-center", targets: [0] }, // targets: "_all",
      { className: "dt-head-center", targets: "_all" },
      { orderable: false, targets: [1, 5] },
    ],
    initComplete: function() {
      setTimeout(function() {
        table.DataTable().columns.adjust();
      }, 500);
    },
    drawCallback: function () {
      if (_isHome) return;

      var page_info = table.DataTable().page.info();
      var total = page_info.recordsTotal;

      if (typeSearch === 'not_lastname') {
        $('#res_table_not_lastname').text(total);
        _totalSearchedNoLastname = total;
      }
      if (typeSearch === 'same_lastname') {
        $('#res_table_lastname').text(total);
        _totalSearchedLastname = total;
      }

      if (_totalSearchedNoLastname === 0 && _totalSearchedLastname === 0) {
        $('#div_table_empty').show();
        $('#div_table_no_lastname').hide();
        $('#div_table_lastname').hide();
      } else {
        if (_totalSearchedNoLastname === 0) {
          $('#div_table_empty').show();
          $('#div_table_no_lastname').hide();
          $('#div_table_lastname').show();
        } else {
          $('#div_table_empty').hide();
          $('#div_table_no_lastname').show();
          $('#div_table_lastname').show();
        }
      }
    },
  });
}

_table_no_lastname.on('processing.dt', function (e, settings, processing) {
  $('#processingIndicator').css('display', 'none');
  if (processing) _loading_no_lastname.show();
  else _loading_no_lastname.hide();
});

_table_lastname.on('processing.dt', function (e, settings, processing) {
  $('#processingIndicator').css('display', 'none');
  if (processing) _loading_lastname.show();
  else _loading_lastname.hide();
});

function increaseSearches() {
  $('#counter').text(Number($('#counter').text() ?? '0') + 1);
}

function isEmptyInputsSearch() {
  var isEmpty = !$('#name').val() && !$('#lastname').val() && !$('#ruc').val();
  if (isEmpty) $("#btnSearch").attr("disabled", true);
  else $("#btnSearch").removeAttr("disabled");
}

$("#name").on('input', isEmptyInputsSearch);
$("#lastname").on('input', isEmptyInputsSearch);
$("#ruc").on('input', isEmptyInputsSearch);
$('#btnSearch').on('click', function (event) {
  event.preventDefault();

  _name = $('#name').val();
  _lastname = $('#lastname').val();
  _ruc = $('#ruc').val();

  if (!_name && !_lastname && !_ruc) {
    return;
  }

  _isHome = false;
  _totalSearchedNoLastname = -1;
  _totalSearchedLastname = -1;

  // increaseSearches();

  $('#div_table').hide();
  $('#div_table_no_lastname').show();
  // _table.DataTable().ajax.reload(null, false);
  _table_no_lastname.DataTable().draw(); //.search(text).draw();

  if ($('#search_same_lastname').is(':checked')) {
    $('#div_table_lastname').show();
    _table_lastname.DataTable().draw();
  } else {
    $('#div_table_lastname').hide();
  }
});

$('.btn-back-home').on('click', function () {
  _isHome = false;
  _totalSearchedNoLastname = -1;
  _totalSearchedLastname = -1;

  $('#div_table').show();
  $('#div_table_no_lastname').hide();
  $('#div_table_lastname').hide();
  $('#div_table_empty').hide();
});
/* END - TABLE LIST NEGATIVE LISTS WITHOUT LASTNAME */

$('#btnShowModalMassive').on('click', function () {
  $('#fileMassive').val('');
  $('#btnUploadMassive').show();
  $('#previewMassive').hide();
  $('#neglst-massive').trigger('click');
});

$('#btnUploadMassive').on('click', function () {
  $('#lbFileMassive').trigger('click');
});

$('#fileMassive').on('change', function () {
  var files = $('#fileMassive')[0].files;
  if (files.length) {
    var file = files[0];
    var reader = new FileReader();
    reader.onload = function () {
      // $('#previewFileMassive').attr("src", reader.result);
      $('#previewFileMassive').text(file.name);
    }
    reader.readAsDataURL(file);
    $('#btnUploadMassive').hide();
    $('#previewMassive').show();
  }
});

$('#btnMassiveExcel').on('click', function () {
  // massive('xlsx');
  $('#neglstMassiveFailed').hide();
  $('#typeMassive').val('xlsx');
  increaseSearches();
});

$('#btnMassivePDF').on('click', function () {
  // massive('pdf');
  $('#neglstMassiveFailed').hide();
  $('#typeMassive').val('pdf');
  increaseSearches();
});

$('#btnPrintEmpty').on('click', function () {
  event.preventDefault();
  var search = $('#name').val() + ' ' + $('#lastname').val() + ' ' + $('#ruc').val();
  var link = document.createElement('a');
  link.target = '_self';
  link.href = `/api/v1/negativelists/details/pdf-empty/${_userId}/${search}`;
  // link.download = "pruebitas.pdf";
  link.click();
});

/*async function massive(type) {
  loading(true);
  var files = $('#fileMassive')[0].files;
  if (!files.length) return
  for (var i = 0; i < files.length; i++) {
    var formData = new FormData()
    formData.append('type', type)
    formData.append('file', files[i])
    await $.ajax({
      type: 'POST',
      url: 'api/v1/negativelists/massive',
      data: formData,
      cache: false,
      processData: false,
      contentType: false,
      xhr: function () {
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function () {
            if (xhr.readyState == 2) {
                if (xhr.status == 200) {
                    xhr.responseType = "blob";
                } else {
                    xhr.responseType = "text";
                }
            }
        };
        return xhr;
      },
      success: function (data) {
        loading(false);
        //Convert the Byte Data to BLOB object.
        var blob = new Blob([data], { type: "application/octetstream" });

        //Check the Browser type and download the File.
        var isIE = false || !!document.documentMode;
        if (isIE) {
          window.navigator.msSaveBlob(blob, fileName);
        } else {
          var url = window.URL || window.webkitURL;
          link = url.createObjectURL(blob);
          var a = $("<a />");
          a.attr("download", fileName);
          a.attr("href", link);
          $("body").append(a);
          a[0].click();
          $("body").remove(a);
        }
      },
      error: function (XMLHttpRequest, textStatus, errorThrown) {
        loading(false);
        alert("Status: " + textStatus); alert("Error: " + errorThrown);
      }
    });
  }
}*/

async function showTeam() {
  loading(true);
  $('#custom-team').trigger('click');
  await $.ajax({
    url: `/api/v1/company/users/${_userId}`,
    type: 'get',
    success: function (response) {
      loading(false);
      if (response) {
        $('#customTeam').html('');
        var htmlTeam = '';
        response.forEach((res) => {
          htmlTeam += `<button class="btn btn-link btn-xs" onclick="assign(${res.userId}, ${_idSelected})">
            ${res.fullname}
          </button>`;
        })
        $('#customTeam').html(htmlTeam);
      }
    },
    error: function (XMLHttpRequest, textStatus, errorThrown) {
      loading(false);
      alert("Status: " + textStatus); alert("Error: " + errorThrown);
    }
  });
}
