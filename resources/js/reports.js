require('./bootstrap');

require('./datatables');

var moment = require('moment');

/*
* Target: Operation List
*/
var _table = $('#table-reports');
var _loading = $('#loading');
var _loading_table = $('#loading-table');
var _idSelected = 0;
var _userId;
var _people = [];

window.addEventListener("load", function(event) {
  boxElement = document.querySelector("#userId");
  _userId = $('#userId').text();
  reportLists();
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

function reportLists() {
  _table.DataTable({
    serverSide: true,
    processing: false,
    destroy: true,
    ajax: {
      type: 'POST',
      url: `/api/v1/report/list-datatable`,
      data: {
        userId: $('#userId').text(),
      },
      dataSrc: function (json) {
        return json.data;
      },
    },
    order: [[0, 'desc']],
    columns: [
      { data: 'id' },
      { data: 'type' },
      {
        data: 'signal',
        render: function(data, type) {
          if (type === 'display') {
            if (data.length > 150) return `${data.slice(0, 140)}...`
            return data;
          }
          return data;
        }
      },
      { data: 'delit' },
      { data: 'office' },
      { data: 'created_at' },
      {
        data: 'actions',
        render: function(data, type) {
          if (type === 'display') {
            var { id, status } = data;
            var state = status ? Number(status) : 0;
            switch (state) {
              case  0:
                var edit = `<svg id="SVG-show-${id}" xmlns="http://www.w3.org/2000/svg" class="actions w-6 h-6 text-conoce-green" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                  <path id="PATH-show-${id}" class="actions" stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                </svg>`;
                return `<div class="flex justify-center gap-3">${edit}</div>`;
              case 1:
                var details = `<svg id="SVG-show-${id}" xmlns="http://www.w3.org/2000/svg" class="actions h-6 w-6 text-conoce-green" viewBox="0 0 20 20" fill="currentColor">
                    <path id="PATH-show-${id}-" class="actions" d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                    <path id="PATH-show-${id}" class="actions" fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                  </svg>`;
                var print = `<svg id="SVG-print-${id}" xmlns="http://www.w3.org/2000/svg" class="actions h-6 w-6 text-conoce-green" viewBox="0 0 20 20" fill="currentColor">
                    <path id="PATH-print-${id}" class="actions" fill-rule="evenodd" d="M5 4v3H4a2 2 0 00-2 2v3a2 2 0 002 2h1v2a2 2 0 002 2h6a2 2 0 002-2v-2h1a2 2 0 002-2V9a2 2 0 00-2-2h-1V4a2 2 0 00-2-2H7a2 2 0 00-2 2zm8 0H7v3h6V4zm0 8H7v4h6v-4z" clip-rule="evenodd" />
                  </svg>`;
                var assign = `<svg id="SVG-pass-${id}" xmlns="http://www.w3.org/2000/svg" class="actions h-6 w-6 text-conoce-green" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path id="PATH-pass-${id}" class="actions" stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                  </svg>`;
                return `<div class="flex justify-center gap-3">${details}${print}${assign}</div>`;
              default:
                break;
            }
          }
          return data;
        },
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
      { className: "text-center", targets: [0, 2, 3, 4, 5] }, // targets: "_all",
      { className: "dt-head-center", targets: "_all" },
      { orderable: false, targets: [5] },
      { className: "report-col-1", targets: [1] },
      { className: "report-col-2", targets: [2] },
      { className: "report-col-3", targets: [3] },
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
      _idSelected = id;
      $('#reportId').text(_idSelected);
      if (action === 'show') {
        window.location.href = `/reports/details/${id}`;
      }
      if (action === 'print') {
        var link = document.createElement('a');
        link.target = '_self';
        link.href = `/api/v1/report/details/excel/${_idSelected}`;
        // link.download = "pruebitas.pdf";
        link.click();
      }
      if (action === 'pass') {
        showTeam();
      }
    }
    return;
  }
});

$('#reportBtnSavePeople').on('click', () => {
  $('#report-people').html('');
  var html = '';
  _people.push({
    type: '',
    name: '',
    documentType: '',
    document: '',
  });
  _people.forEach((person) => {
    html += `
      <tr>
        <td>${person.type}</td>
        <td>${person.name}</td>
        <td>${person.documentType}</td>
        <td>${person.document}</td>
        <td></td>
      </tr>
    `;
  })
  $('#report-table-people').html(html);
  $('#report-people').trigger('click');
})

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
