require('./bootstrap');

require('./datatables');

var moment = require('moment');

var _userId;
var _people = [];
var _modal_selects = [
  'henry', 'peopletype', 'condition', 'nationality', 'pep', 'documenttype',
  'ocupation', 'country', 'department', 'province', 'district'
];
var _modal_inputs = [
  'ruc', 'company', 'lastname1', 'lastname2', 'name', 'birthday', 'documentnumber',
  'cellphone', 'email', 'address'
];
var _provinces = [];
var _districts = [];
var _signals = [];
var _documenttypes = [];

var _peopleSelected = -1;

window.addEventListener("load", function(event) {
  _userId = $('#userId').text();
  listCommons();
}, false);

function loading(flag) {
  if (flag) {
    $('#loadingOpen').trigger('click');
  } else {
    $('#loadingClose').trigger('click');
  }
}

function distributeCommons(inpSelectName, data) {
  var opts = '<option value="0" selected>Seleccione...</option>';
  data.forEach(({ id, name }) => (opts += `<option value="${id}">${name}</option>`));
  $(`select[name='${inpSelectName}']`).html(opts);
}

async function listCommons() {
  await $.ajax({
    url: `/api/v1/common/list-report-commons`,
    type: 'get',
    dataType: 'json',
    success: function(res) {
      res.forEach(({ name, data }) => {
        if (name === 'province' || name === 'province__') _provinces = data;
        else if (name === 'district' || name === 'district__') _districts = data;
        else if (name === 'signal') _signals = data;
        else {
          if (name === 'documenttype__') _documenttypes = data;
          distributeCommons(name, data);
        }
      });
    }
  });
}

$("select[name='type']").on('change', () => {
  var typeId = $("select[name='type']").val();
  if (typeId) {
    var signalTypeId = $("select[name='signaltype']").val();
    var signals = _signals.filter((signal) => Number(signal.typeId) === Number(typeId) && Number(signal.signalTypeId) === Number(signalTypeId));

    var opts = '<option value="0" selected>Seleccione...</option>';
    signals.forEach(({ id, name }) => (opts += `<option value="${id}">${name}</option>`));
    $(`select[name='signal']`).html(opts);
  } else {
    $(`select[name='signal']`).html('<option value="0" selected>Seleccione...</option>');
  }
});

$("select[name='signaltype']").on('change', () => {
  var signalTypeId = $("select[name='signaltype']").val();
  if (signalTypeId) {
    var typeId = $("select[name='type']").val();
    var signals = _signals.filter((signal) => Number(signal.typeId) === Number(typeId) && Number(signal.signalTypeId) === Number(signalTypeId));

    var opts = '<option value="0" selected>Seleccione...</option>';
    signals.forEach(({ id, name }) => (opts += `<option value="${id}">${name}</option>`));
    $(`select[name='signal']`).html(opts);
  } else {
    $(`select[name='signal']`).html('<option value="0" selected>Seleccione...</option>');
  }
});

$("input[name='company__']").on('change', () => {
  var company = $("input[name='company__']").val();
  $("input[name='name__']").val(company);
});

$('#reportBtnSavePeople').on('click', function () {
  var item = {}
  _modal_selects.forEach((sel) => (item[sel] = $(`select[name='${sel}__']`).val()));
  _modal_inputs.forEach((inp) => (item[inp] = $(`input[name='${inp}__']`).val()))

  var html = `
    <tr id="report__people__row__${_peopleSelected >= 0 ? _peopleSelected : _people.length}">
      <td>${item.henry}</td>
      <td>${item.name}</td>
      <td>${detectDocType(item.documenttype)}</td>
      <td>${item.documentnumber}</td>
      <td>
        <button type="button" class="table__actions edit">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
          </svg>
        </button>
        <button type="button" class="table__actions remove">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
          </svg>
        </button>
      </td>
    </tr>
  `;

  if (_peopleSelected >= 0) {
    $(`#report__people__row__${_peopleSelected}`).replaceWith(html);
    _people[_peopleSelected] = item;
  } else {
    _people.push(item);
    $('#report-table-people').append(html);
  }

  _modal_selects.forEach((sel) => ($(`select[name='${sel}__']`).val('')));
  _modal_inputs.forEach((inp) => ($(`input[name='${inp}__']`).val('')));
  _peopleSelected = -1;
  $('#reportModalCompany').css('display', 'none');

  $('#modal-report-people').trigger('click');
});

$(".modal-button").on('click', function () {
  _modal_selects.forEach((sel) => ($(`select[name='${sel}__']`).val('')));
  _modal_inputs.forEach((inp) => ($(`input[name='${inp}__']`).val('')));
});

$(document).on('click', '.table__actions', function () {
  var idrow  = $(this).closest('tr').attr('id');
  var index = Number(idrow.replace('report__people__row__', ''));
  var action = $(this).attr('class');

  var people = _people[index];

  if (people.department) {
    var provs = _provinces.filter((province) => `${province.departmentId}` === `${people.department}`)

    var opts = '<option value="0" selected>Seleccione...</option>';
    provs.forEach(({ id, name }) => (opts += `<option value="${id}">${name}</option>`));
    $(`select[name='province__']`).html(opts);
    $(`select[name='district__']`).html('<option value="0" selected>Seleccione...</option>');
  }

  if (people.province) {
    var dists = _districts.filter((district) => `${district.provinceId}` === `${people.province}`)

    var opts = '<option value="0" selected>Seleccione...</option>';
    dists.forEach(({ id, name }) => (opts += `<option value="${id}">${name}</option>`));
    $(`select[name='district__']`).html(opts);
  }

  if (action.includes('edit')) {
    _peopleSelected = index;
    _modal_selects.forEach((sel) => ($(`select[name='${sel}__']`).val(people[sel])));
    _modal_inputs.forEach((inp) => ($(`input[name='${inp}__']`).val(people[inp])));
    $('#reportModalCompany').css('display', $("select[name='henry__']").val() === 'juridica' ? 'flex' : 'none');
    $('#modal-report-people').trigger('click');
  }

  if (action.includes('remove')) {
    $(`#${idrow}`).css('display', 'none'); // $(`#${idrow}`).remove();
    _people[index] = null; // _people.splice(index, 1);
  }
});

$("select[name='henry__']").on('change', () => {
  $('#reportModalCompany').css('display', $("select[name='henry__']").val() === 'juridica' ? 'flex' : 'none');
});

$("select[name='department']").on('change', () => {
  var departmentId = $("select[name='department']").val();
  if (departmentId) {
    var provs = _provinces.filter((province) => province.departmentId === departmentId)

    var opts = '<option value="0" selected>Seleccione...</option>';
    provs.forEach(({ id, name }) => (opts += `<option value="${id}">${name}</option>`));
    $(`select[name='province']`).html(opts);

    $(`select[name='district']`).html('<option value="0" selected>Seleccione...</option>');
  } else {
    $(`select[name='province']`).html('<option value="0" selected>Seleccione...</option>');
  }
});

$("select[name='province']").on('change', () => {
  var provinceId = $("select[name='province']").val();
  if (provinceId) {
    var provs = _districts.filter((district) => district.provinceId === provinceId)

    var opts = '<option value="0" selected>Seleccione...</option>';
    provs.forEach(({ id, name }) => (opts += `<option value="${id}">${name}</option>`));
    $(`select[name='district']`).html(opts);
  } else {
    $(`select[name='district']`).html('<option value="0" selected>Seleccione...</option>');
  }
});

$("select[name='department__']").on('change', () => {
  var departmentId = $("select[name='department__']").val();
  if (departmentId) {
    var provs = _provinces.filter((province) => province.departmentId === departmentId)

    var opts = '<option value="0" selected>Seleccione...</option>';
    provs.forEach(({ id, name }) => (opts += `<option value="${id}">${name}</option>`));
    $(`select[name='province__']`).html(opts);

    $(`select[name='district__']`).html('<option value="0" selected>Seleccione...</option>');
  } else {
    $(`select[name='province__']`).html('<option value="0" selected>Seleccione...</option>');
  }
});

$("select[name='province__']").on('change', () => {
  var provinceId = $("select[name='province__']").val();
  if (provinceId) {
    var provs = _districts.filter((district) => district.provinceId === provinceId)

    var opts = '<option value="0" selected>Seleccione...</option>';
    provs.forEach(({ id, name }) => (opts += `<option value="${id}">${name}</option>`));
    $(`select[name='district__']`).html(opts);
  } else {
    $(`select[name='district__']`).html('<option value="0" selected>Seleccione...</option>');
  }
});

$('#btnReportSave').on('click', () => create(0));

$('#btnReportRegister').on('click', () => create(1));

async function create(status) {
  var data = {
    userId: _userId,
    status,
    typeId: $("select[name='type']").val(),
    urgencyId: $("select[name='urgency']").val(),
    office: $("input[name='office']").val(),
    address: $("input[name='address']").val(),
    departmentId: $("select[name='department']").val(),
    provinceId: $("select[name='province']").val(),
    districtId: $("select[name='district']").val(),
    signaltypeId: $("select[name='signaltype']").val(),
    signalIds: $("select[name='signal']").val(),
    crimeId: $("select[name='crime']").val(),
    crimetypeIds: $("select[name='crimetype']").val(),
    activityId: $("select[name='activity']").val(),
    details: $("textarea[name='details']").val(),
    product: $("textarea[name='product']").val(),
    amount: $("input[name='amount']").val(),
    currencyId: $("select[name='currency']").val(),
    startedAt: $("input[name='startedAt']").val(),
    finishedAt: $("input[name='finishedAt']").val(),
    extra: $("textarea[name='extra']").val(),
  }

  loading(true);
  var reportId = 0;
  await $.ajax({
    url: '/api/v1/report/create',
    type: 'POST',
    contentType:'application/json',
    data: JSON.stringify(data),
    // dataType: 'json',
    processData: false,
    success: function(res) {
      reportId = res.operacionid;
    },
    error: function (XMLHttpRequest, textStatus, errorThrown) {
      loading(false);
      alert(XMLHttpRequest.responseJSON?.message)
    }
  });

  if (reportId <= 0) {
    alert('¡Ocurrió un error al crear el reporte de operación!');
    return;
  }

  var arrPeople = _people.filter((peop) => peop)
  if (!arrPeople.length) window.location.replace('/reports');

  var obj = {
    reportId,
    persons: arrPeople.map((people) => ({
      henry: people.henry,
      peopletypeId: people.peopletype,
      conditionId: people.condition,
      company: people.company,
      ruc: people.ruc,
      lastname1: people.lastname1,
      lastname2: people.lastname2,
      name: people.name,
      birthday: people.birthday,
      nationality: people.nationality,
      pepId: people.pep,
      documenttypeId: people.documenttype,
      documentnumber: people.documentnumber,
      ocupationId: people.ocupation,
      cellphone: people.cellphone,
      email: people.email,
      address: people.address,
      countryId: people.country,
      departmentId: people.department,
      provinceId: people.province,
      districtId: people.district,
    })),
  }
  await $.ajax({
    url: '/api/v1/report/create-list-people',
    type: 'POST',
    contentType:'application/json',
    data: JSON.stringify(obj),
    // dataType: 'json',
    processData: false,
    success: function(res) {
      window.location.replace('/reports');
    },
    error: function (XMLHttpRequest, textStatus, errorThrown) {
      loading(false);
      alert(XMLHttpRequest.responseJSON?.message)
    }
  });
}

function detectDocType(val) {
  var doctype = "";
  var dt = _documenttypes.find(({ id }) => Number(id) === Number(val))
  if (dt) return dt.name
  return doctype
}
