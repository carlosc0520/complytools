require('./bootstrap');

require('./datatables');

var moment = require('moment');

/*
* Target: Complaint List
*/
var _table = $('#table-complaints');
var _loading = $('#loading');
var _loading_table = $('#loading-table');
var _idSelected = 0;
var _userId;

window.addEventListener("load", function(event) {
  boxElement = document.querySelector("#userId");
  _userId = $('#userId').text();
  complaintLists();
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

function complaintLists() {
  _table.DataTable({
    serverSide: true,
    processing: false,
    destroy: true,
    ajax: {
      type: 'POST',
      url: `/api/v1/complaint/list-datatable`,
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
      { data: 'code' },
      { data: 'reason' },
      { data: 'relation' },
      {
        data: 'created_at',
        render: function (data, type) {
          if (type === 'display') {
            return data ? moment(data).format('DD/MM/YYYY') : '';
          }
          return data;
        },
      },
      {
        data: 'closed_at',
        render: function (data, type) {
          if (type === 'display') {
            return data ? moment(data).format('DD/MM/YYYY') : '';
          }
          return data;
        },
      },
      {
        data: 'status',
        render: function (data, type) {
          if (type === 'display') {
            var { code, name, closed_at, created_at } = data;
            if (closed_at) {
              if (Date.now() > new Date(closed_at).getTime()) {
                return `<span style="color: red; font-weight: bold;">Vencido</span>`
              }
              return name
            }
            return name
          }
          return data
        },
      },
      {
        data: 'actions',
        render: function(data, type) {
          if (type === 'display') {
            var { id, status } = data;
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
            var edit = `<svg id="SVG-show-${id}" xmlns="http://www.w3.org/2000/svg" class="actions h-6 w-6 text-conoce-green" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path id="PATH-show-${id}" class="actions" stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
            </svg>`;
            // if (Number(status) === 1) return `<div class="flex justify-center gap-3">${edit}</div>`;
            return `<div class="flex justify-center gap-3">${details}${print}${assign}</div>`;
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
      { className: "text-center", targets: [0, 2, 3, 4, 5, 6] }, // targets: "_all",
      { className: "dt-head-center", targets: "_all" },
      { orderable: false, targets: [7] },
      { className: "custom-width-col", targets: [2] },
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
      $('#complaintId').text(_idSelected);
      if (action === 'show') {
        showDetails(_idSelected);
      }
      if (action === 'print') {
        var link = document.createElement('a');
        link.target = '_self';
        link.href = `/api/v1/complaint/details/pdf/${_userId}/${_idSelected}`;
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

async function showDetails(id) {
  loading(true);
  $('#complaint-details').trigger('click');
  await $.ajax({
    url: `/api/v1/complaint/details/object/${id}`,
    type: 'get',
    success: function (response) {
      loading(false);
      if (response) {
        var { relations, files, historial, ...complaint } = response;
        $('.complaint--code').text(complaint.code);
        $('#reason').text(complaint.reason);
        $('#relation').text(complaint.relation);
        $('#description').text(complaint.description);

        $('#files').html('');
        htmlFiles = '';
        files.forEach(({ url }) => {
          htmlFiles += `<a class="text-conoce-green" href="${url}" target="_blank">Ver adjunto</a>`;
        })
        $('#files').html(htmlFiles);

        $('#relations').html('');
        var htmlRelations = '';
        relations.forEach((relation) => {
          htmlRelations += `<tr>
            <td>${relation.type}</td>
            <td>${relation.name}</td>
            <td>${relation.identification}</td>
            <td>${relation.code}</td>
            <td>${relation.rol}</td>
          </tr>`;
        })
        $('#relations').html(htmlRelations);

        $('#fullname').text(complaint.name);
        $('#identification').text(complaint.dni);
        $('#cellphone').text(complaint.cellphone);
        $('#email').text(complaint.email);

        $('#historial').html('');
        var htmlHist = '';
        historial.forEach((hist) => {
          htmlHist += '<hr>';
          if (hist.label) { // Automatic
            htmlHist += `<div class="text-sm my-1">${hist.label} - ${hist.createdAt}</div>`;
          } else {          // Manual
            if (hist.userId) {  // Admin
              htmlHist += `<div class="grid my-1">
                <span>${hist.message}</span>
                <div class="text-xs">
                  <span class="font-bold">${hist.fullname}<span>
                  <span>${hist.createdAt}</span>
                </div>
              </div>`;
            } else {            // User
              htmlHist += `<div class="grid my-1" style="margin-left: 20px">
                <span>${hist.message}</span>
                <div class="text-xs">
                  <span class="font-bold">Denunciante<span>
                  <span>${hist.createdAt}</span>
                </div>
              </div>`;
            }
          }
        })
        $('#historial').html(htmlHist);

        if (complaint.file) {
          $("input[name='file']").hide();
          $("#closeDocument").attr("href", complaint.file);
          $("#closeDocument").show();
        } else {
          $("input[name='file']").show();
          $("#closeDocument").hide();
        }

        if (Number(complaint.status) < 3) {
          $('#modalBtnCloseComplaint').removeClass('hidden');
          $('#modalBtnIncompleteComplaint').removeClass('hidden');
        } else {
          $('#modalBtnCloseComplaint').hide();
          $('#modalBtnIncompleteComplaint').hide();
        }

        if (complaint.closed_at) {
          $("input[name='expirationDate']").val(complaint.closed_at);
        }
      }
    },
    error: function (XMLHttpRequest, textStatus, errorThrown) {
      loading(false);
      alert("Status: " + textStatus); alert("Error: " + errorThrown);
    }
  });
}

$('#createMessage').on('click', async function(e) {
  e.preventDefault();
  loading(true);
  await $.ajax({
    url: `/api/v1/complaint/historial`,
    type: 'post',
    data: {
      message: $("textarea[name='message']").val(),
      userId: $('#userId').text(),
      complaintId: _idSelected,
    },
    success: function (response) {
      loading(false);
      if (response) {
        var context = `<div class="grid my-1">
          <span>${response.message}</span>
          <div class="text-xs">
            <span class="font-bold">${response.fullname}<span>
            <span>${response.createdAt}</span>
          </div>
        </div><hr>`;
        var htmlHist = context + htmlHist;
        $("textarea[name='message']").val('');
        $('#historial').html(htmlHist);
      }
    },
    error: function (XMLHttpRequest, textStatus, errorThrown) {
      loading(false);
      alert("Status: " + textStatus); alert("Error: " + errorThrown);
    }
  });
});

$('#modalBtnExpirationDateComplaint').on('click', async function(e) {
  e.preventDefault();
  const fec = $("input[name='expirationDate']").val();
  loading(true);
  if (!fec) return alert('¡Debe definir una fecha de cierre!');
  await $.ajax({
    url: `/api/v1/complaint/set-expiration-date`,
    type: 'post',
    data: {
      id: _idSelected,
      expirationDate: fec,
    },
    success: function (response) {
      loading(false);
    },
    error: function (XMLHttpRequest, textStatus, errorThrown) {
      loading(false);
      alert("Status: " + textStatus); alert("Error: " + errorThrown);
    }
  });
});

$('#modalBtnCloseComplaint').on('click', async function(e) {
  e.preventDefault();
  var files = $("input[name='file']")[0].files;
  if (files.length) {
    var formData = new FormData();
    formData.append('id', _idSelected);
    formData.append('file', files[0]);
    loading(true);
    await $.ajax({
      url: `/api/v1/complaint/close`,
      type: 'post',
      data: formData,
      cache: false,
      processData: false,
      contentType: false,
      success: function (response) {
        loading(false);
        if (response) {
          $("input[name='file']").hide();
          $("#closeDocument").attr("href", response.sustento);
          $("#closeDocument").show();

          if (Number(response.estado) < 3) {
            $('#modalBtnCloseComplaint').removeClass('hidden');
            $('#modalBtnIncompleteComplaint').removeClass('hidden');
          } else {
            $('#modalBtnCloseComplaint').hide();
            $('#modalBtnIncompleteComplaint').hide();
          }
        }
      },
      error: function (XMLHttpRequest, textStatus, errorThrown) {
        loading(false);
        alert("Status: " + textStatus); alert("Error: " + errorThrown);
      }
    });
  } else {
    alert("¡Debe subir un archivo para cerrar la denuncia!");
  }
});

$('#modalBtnIncompleteComplaint').on('click', async function(e) {
  e.preventDefault();
  loading(true);
  await $.ajax({
    url: `/api/v1/complaint/close-incomplete`,
    type: 'post',
    data: {
      id: _idSelected,
      ownerId: _userId,
    },
    success: function (response) {
      loading(false);
      if (response) {
        $("input[name='file']").hide();
        $("#closeDocument").attr("href", response.sustento);
        $("#closeDocument").show();

        if (Number(response.estado) < 3) {
          $('#modalBtnCloseComplaint').removeClass('hidden');
          $('#modalBtnIncompleteComplaint').removeClass('hidden');
        } else {
          $('#modalBtnCloseComplaint').hide();
          $('#modalBtnIncompleteComplaint').hide();
        }

        var htmlHist = $('#historial').html();
        var hist = response.historial;
        htmlHist += `
          <div class="grid my-1">
            <span>${hist.label}</span>
            <div class="text-xs">
              <span class="font-bold">${hist.fullname}<span>
              <span>${hist.createdAt}</span>
            </div>
          </div>`;
        $('#historial').html(htmlHist);
      }
    },
    error: function (XMLHttpRequest, textStatus, errorThrown) {
      loading(false);
      alert("Status: " + textStatus); alert("Error: " + errorThrown);
    }
  });
});

async function showTeam() {
  loading(true);
  $('#complaint-team').trigger('click');
  await $.ajax({
    url: `/api/v1/complaint/team/${_userId}/${_idSelected}`,
    type: 'get',
    success: function (response) {
      loading(false);
      if (response) {
        $('#complaintTeam').html('');
        var htmlTeam = '';
        response.forEach((res) => {
          htmlTeam += `<button class="btn btn-link btn-xs" onclick="assign(${res.userId}, ${_idSelected})">
            ${res.fullname}
          </button>`;
        })
        $('#complaintTeam').html(htmlTeam);
      }
    },
    error: function (XMLHttpRequest, textStatus, errorThrown) {
      loading(false);
      alert("Status: " + textStatus); alert("Error: " + errorThrown);
    }
  });
}
