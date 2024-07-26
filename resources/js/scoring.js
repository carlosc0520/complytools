require('./bootstrap');

require('./datatables');

var moment = require('moment');

/*
* Target: Scoring List
*/
var _table = $('#table');
var _loading = $('#loading');
var _loading_table = $('#loading-table');
var _idSelected = 0;
var _userId;

window.addEventListener("load", function(event) {
  boxElement = document.querySelector("#userId");
  _userId = $('#userId').text();
  list();
  scoringLists();
}, false);

$('#table_paginate').hide();
$('.custom-pagination').hide();

function loading(flag) {
  if (flag) {
    $('#loadingOpen').trigger('click');
  } else {
    $('#loadingClose').trigger('click');
  }
}

async function list() {
  _loading.show();
  await $.ajax({
    url: `/api/v1/scoring/list/${_userId}`,
    type: 'GET',
    dataType: 'json',
    success: function(res) {
      _loading.hide();

      $('#txtMin').text(res?.min?.y)
      $('#txtLow').text(res?.low?.y)
      $('#txtMid').text(res?.mid?.y)
      $('#txtHigh').text(res?.high?.y)
      $('#txtHyper').text(res?.hyper?.y)
    }
  });
}

function scoringLists() {
  _table.DataTable({
    serverSide: true,
    processing: false,
    destroy: true,
    ajax: {
      type: 'POST',
      url: `/api/v1/scoring/list-datatable`,
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
      { data: 'type', width: '5%' },
      { data: 'fullname', width: '55%' },
      { data: 'identification', width: '50%' },
      {
        data: 'created_at',
        render: function (data, type) {
          if (type === 'display') {
            return data ? moment(data).format('DD/MM/YYYY') : '';
          }
          return data;
        },
        width: '5%',
      },
      { data: 'colorRiskRes', width: '55%' },
      { data: 'status', width: '10%' },
      {
        data: 'actions',
        render: function(data, type) {
          if (type === 'display') {
            var { id, status, type } = data;
            var details = `<svg id="SVG-show-${id}-${type}" xmlns="http://www.w3.org/2000/svg" class="actions h-6 w-6 text-conoce-green" viewBox="0 0 20 20" fill="currentColor">
              <path id="PATH-show-${id}-${type}" class="actions" d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
              <path id="PATH-show-${id}-${type}" class="actions" fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
            </svg>`;
            var print = `<svg id="SVG-print-${id}-${type}" xmlns="http://www.w3.org/2000/svg" class="actions h-6 w-6 text-conoce-green" viewBox="0 0 20 20" fill="currentColor">
              <path id="PATH-print-${id}-${type}" class="actions" fill-rule="evenodd" d="M5 4v3H4a2 2 0 00-2 2v3a2 2 0 002 2h1v2a2 2 0 002 2h6a2 2 0 002-2v-2h1a2 2 0 002-2V9a2 2 0 00-2-2h-1V4a2 2 0 00-2-2H7a2 2 0 00-2 2zm8 0H7v3h6V4zm0 8H7v4h6v-4z" clip-rule="evenodd" />
            </svg>`;
            var assign = `<svg id="SVG-pass-${id}-${type}" xmlns="http://www.w3.org/2000/svg" class="actions h-6 w-6 text-conoce-green" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path id="PATH-pass-${id}-${type}" class="actions" stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
            </svg>`;
            var edit = `<svg id="SVG-show-${id}-${type}" xmlns="http://www.w3.org/2000/svg" class="actions h-6 w-6 text-conoce-green" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path id="PATH-show-${id}-${type}" class="actions" stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
            </svg>`;
            if (Number(status) === 1) return `<div class="flex justify-center gap-3">${edit}</div>`;
            return `<div class="flex justify-center gap-3">${details}${print}${assign}</div>`;
          }
          return data;
        },
        width: '10%',
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
    dom: "lfrti",
    columnDefs: [
      { className: "text-center", targets: [0, 1, 3, 4, 5, 6] }, // targets: "_all",
      { className: "dt-head-center", targets: "_all" },
      { orderable: false, targets: [7] },
      {
        targets: [5],
        createdCell: function (td) {
          $(td).css('padding', "0")
        }
      },
    ],
    initComplete: function() {
      setTimeout(function() {
        _table.DataTable().columns.adjust();
        $('.custom-pagination').show();
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

$('#btnListInher').on('click', function () {
  heatRisksChart('inher');
});

$('#btnListRes').on('click', function () {
  heatRisksChart('res');
});

$(".risk--type").on("click", function () {
  $(".dropdown-content").slideUp("fast");
});

_table.on('processing.dt', function (e, settings, processing) {
  $('#processingIndicator').css('display', 'none');
  if (processing) _loading_table.show();
  else _loading_table.hide();
});

$('.dataTables_paginate .paginate_button').on('click', function() {
  $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
});

$('#search').on('keydown', function(event) {
  if (event?.key == 'Enter') {
    var text = $('#search').val();
    _table.DataTable().ajax.reload(null, false);
    _table.DataTable().search(text).draw();
  }
});

$('#btnSearch').on('click', function () {
  var text = $('#search').val();
  _table.DataTable().ajax.reload(null, false);
  _table.DataTable().search(text).draw();
})

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

$('tbody').on('click', 'tr', function (event) {
  var target = event.target;
  if (target.classList.contains('actions')) {
    var splitted = target.id.split('-');
    if (splitted.length > 2) {
      var [_, action, id] = splitted;
      var type = splitted.length > 3 ? splitted[3] : 'N';
      var redir = type === 'N' ? 'natural' : 'company';
      _idSelected = id;
      if (action === 'show') {
        window.location.href = `/scoring/details/${redir}/${id}`;
      }
      if (action === 'print') {
        var link = document.createElement('a');
        link.target = '_blank';
        link.href = `/api/v1/scoring/details/pdf-${redir}/${_userId}/${_idSelected}`;
        link.click();
      }
      if (action === 'pass') {
        showTeam();
      }
    }
    return;
  }
});

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
