require('./bootstrap');

require('./datatables');

var moment = require('moment');

var _userId;
var _people = [];
var _modal_inputs = [
  'lastname', 'name', 'birthday', 'nationality', 'ocupation', 'cellphone',
  'postalcode', 'province', 'department', 'country', 'address', 'dni',
  'rut', 'lm', 'ci', 'ce', 'passport', 'emittedAt', 'ruc', 'other'
];

window.addEventListener("load", function(event) {
  _userId = $('#userId').text();
}, false);

function loading(flag) {
  if (flag) {
    $('#loadingOpen').trigger('click');
  } else {
    $('#loadingClose').trigger('click');
  }
}

$('#reportBtnSavePeople').on('click', function () {
  var item = {}
  _modal_inputs.forEach((inp) => (item[inp] = $(`input[name='${inp}__']`).val()))

  var html = `
    <tr id="operation__people__row__${_people.length}">
      <td>${_people.length + 1}</td>
      <td>${item.lastname}</td>
      <td>${item.name}</td>
      <td>${item.birthday}</td>
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

  _people.push(item);
  $('#operation-table-people').append(html);

  _modal_inputs.forEach((inp) => ($(`input[name='${inp}__']`).val('')));

  $('#modal-operation-people').trigger('click');
});

$(".modal-button").on('click', function () {
  _modal_inputs.forEach((inp) => ($(`input[name='${inp}__']`).val('')));
});

$(document).on('click', '.table__actions', function () {
  var idrow  = $(this).closest('tr').attr('id');
  var index = Number(idrow.replace('operation__people__row__', ''));
  var action = $(this).attr('class');

  if (action.includes('edit')) {
    var people = _people[index];
    _modal_inputs.forEach((inp) => ($(`input[name='${inp}__']`).val(people[inp])));
    $('#modal-operation-people').trigger('click');
  }

  if (action.includes('remove')) {
    $(`#${idrow}`).remove();
    _people.splice(index, 1);
  }
});

$('#btnOperationSave').on('click', () => create(0));

$('#btnOperationRegister').on('click', () => create(1));

async function create(status) {
  var s1 = {
    company: $("input[name='company']").val(),
    code: $("input[name='code']").val(),
    office: $("input[name='office']").val(),
    registeredAt: $("input[name='registeredAt']").val(),
  }

  var s2 = {
    lastname: $("input[name='lastname1']").val(),
    name: $("input[name='name1']").val(),
    birthday: $("input[name='birthday1']").val(),
    nationality: $("input[name='nationality1']").val(),
    ocupation: $("input[name='ocupation1']").val(),
    address: $("input[name='address1']").val(),
    postalcode: $("input[name='postalcode1']").val(),
    province: $("input[name='province1']").val(),
    department: $("input[name='department1']").val(),
    country: $("input[name='country1']").val(),
    cellphone: $("input[name='cellphone1']").val(),
    dni: $("input[name='dni1']").val(),
    rut: $("input[name='rut1']").val(),
    lm: $("input[name='lm1']").val(),
    ci: $("input[name='ci1']").val(),
    ce: $("input[name='ce1']").val(),
    passport: $("input[name='passport1']").val(),
    emittedAt: $("input[name='emittedAt1']").val(),
    ruc: $("input[name='ruc1']").val(),
    other: $("input[name='other1']").val(),
  }

  var s3 = {
    lastname: $("input[name='lastname2']").val(),
    name: $("input[name='name2']").val(),
    birthday: $("input[name='birthday2']").val(),
    nationality: $("input[name='nationality2']").val(),
    ocupation: $("input[name='ocupation2']").val(),
    address: $("input[name='address2']").val(),
    postalcode: $("input[name='postalcode2']").val(),
    province: $("input[name='province2']").val(),
    department: $("input[name='department2']").val(),
    country: $("input[name='country2']").val(),
    cellphone: $("input[name='cellphone2']").val(),
    dni: $("input[name='dni2']").val(),
    rut: $("input[name='rut2']").val(),
    lm: $("input[name='lm2']").val(),
    ci: $("input[name='ci2']").val(),
    ce: $("input[name='ce2']").val(),
    passport: $("input[name='passport2']").val(),
    emittedAt: $("input[name='emittedAt2']").val(),
    ruc: $("input[name='ruc2']").val(),
    other: $("input[name='other2']").val(),
  }

  var s4 = {
    beneficiary: $("input[name='beneficiary']").val(),
    lastname: $("input[name='lastname3']").val(),
    name: $("input[name='name3']").val(),
    birthday: $("input[name='birthday3']").val(),
    nationality: $("input[name='nationality3']").val(),
    ocupation: $("input[name='ocupation3']").val(),
    address: $("input[name='address3']").val(),
    postalcode: $("input[name='postalcode3']").val(),
    province: $("input[name='province3']").val(),
    department: $("input[name='department3']").val(),
    country: $("input[name='country3']").val(),
    cellphone: $("input[name='cellphone3']").val(),
    dni: $("input[name='dni3']").val(),
    rut: $("input[name='rut3']").val(),
    lm: $("input[name='lm3']").val(),
    ci: $("input[name='ci3']").val(),
    ce: $("input[name='ce3']").val(),
    passport: $("input[name='passport3']").val(),
    emittedAt: $("input[name='emittedAt3']").val(),
    ruc: $("input[name='ruc3']").val(),
    other: $("input[name='other3']").val(),
  }

  var s5 = {
    amount: $("input[name='amount']").val(),
    date: $("input[name='date']").val(),
    location: $("input[name='location']").val(),

    nationalcurrency: $("input[name='nationalcurrency']").val(),
    foreigncurrency: $("input[name='foreigncurrency']").val(),
    foreigncurrencyDetails: $("input[name='foreigncurrencyDetails']").val(),
    cashierscheck: $("input[name='cashierscheck']").val(),
    travelerscheck: $("input[name='travelerscheck']").val(),
    paymentorder: $("input[name='paymentorder']").val(),
    otherP: $("input[name='otherp']").val(),
    otherDetailsP: $("input[name='otherDetailsp']").val(),

    buy: $("input[name='buy']").val(),
    sell: $("input[name='sell']").val(),
    consultancies: $("input[name='consultancies']").val(),
    primaryplacements: $("input[name='primaryplacements']").val(),
    portfoliomanagement: $("input[name='portfoliomanagement']").val(),
    custody: $("input[name='custody']").val(),
    mutualmoney: $("input[name='mutualmoney']").val(),
    loan: $("input[name='loan']").val(),
    mutualfunds: $("input[name='mutualfunds']").val(),
    investmentfunds: $("input[name='investmentfunds']").val(),
    derivatives: $("input[name='derivatives']").val(),
    collectivefunds: $("input[name='collectivefunds']").val(),
    otherQ: $("input[name='otherq']").val(),
    otherDetailsQ: $("input[name='otherDetailsq']").val(),

    account1: $("input[name='account1']").val(),
    account2: $("input[name='account2']").val(),
    account3: $("input[name='account3']").val(),
  }

  var data = {
    userId: _userId,
    status,
    s1,
    s2,
    s3,
    s4,
    s5
  }

  loading(true);
  var operationId = 0;
  await $.ajax({
    url: '/api/v1/operation/create',
    type: 'POST',
    contentType:'application/json',
    data: JSON.stringify(data),
    // dataType: 'json',
    processData: false,
    success: function(res) {
      operationId = res.regoperacionid;
    },
    error: function (XMLHttpRequest, textStatus, errorThrown) {
      loading(false);
      alert(XMLHttpRequest.responseJSON?.message)
    }
  });

  if (operationId <= 0) {
    alert('¡Ocurrió un error al crear el registro de operación!');
    return;
  }

  if (!_people.length) window.location.replace('/operations');

  var obj = {
    operationId,
    persons: _people.map((people) => ({
      lastname: people.lastname,
      name: people.name,
      birthday: people.birthday,
      nationality: people.nationality,
      ocupation: people.ocupation,
      postalcode: people.postalcode,
      country: people.country,
      department: people.department,
      province: people.province,
      cellphone: people.cellphone,
      address: people.address,
      dni: people.dni,
      rut: people.rut,
      lm: people.lm,
      ci: people.ci,
      ce: people.ce,
      passport: people.passport,
      emittedAt: people.emittedAt,
      ruc: people.ruc,
      other: people.other,
    }))
  }
  await $.ajax({
    url: '/api/v1/operation/create-list-beneficiaries',
    type: 'POST',
    contentType:'application/json',
    data: JSON.stringify(obj),
    // dataType: 'json',
    processData: false,
    success: function(res) {
      window.location.replace('/operations');
    },
    error: function (XMLHttpRequest, textStatus, errorThrown) {
      loading(false);
      alert(XMLHttpRequest.responseJSON?.message)
    }
  });
}

$('#btnShowModalMassive').on('click', function () {
  $('#fileMassive').val('');
  $('#btnUploadMassive').show();
  $('#previewMassive').hide();
  $('#operation-massive').trigger('click');
});

$('#btnUploadMassive').on('click', () => $('#lbFileMassive').trigger('click'));

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

$('#btnMassiveExcel').on('click', () => $('#operationMassiveFailed').hide());
