require('./bootstrap');

var _userId;
var _isPEP = false;
var _riskTotal = 0;
var _translation = {
  birthday: 'Fecha de constitución',
  type: 'Tipo de persona jurídica',
  size: 'Tamaño de empresa',
  obligation: 'Condición de sujeto Obligado',
  ciu: 'CIU',
  composition: 'Composición accionaria',
  pep: 'PEP',
  transaction: 'Transacción estimada',
  country: 'Nacional',
  residence: 'Residencia',
  office: 'Oficina de atención',
  product: 'Producto / Servicio',
  currency: 'Moneda',
  funding: 'Origen de fondos',
}

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

async function listCommons() {
  await $.ajax({
    url: `/api/v1/common/list-scoring-company-commons`,
    type: 'get',
    dataType: 'json',
    success: function(res) {
      res.forEach(({ name, data }) => distributeCommons(name, data))
    }
  });
}

function distributeCommons(inpSelectName, data) {
  var opts = '<option selected value="0">Seleccione...</option>';
  if (inpSelectName === 'ciu') {
    data.forEach(({ id, code, name, risk_val, weight_dni, weight_ruc }) => (opts += `<option value="${code}" data-foo="${risk_val}|${weight_dni}|${weight_ruc}">${name}</option>`));
  } else {
    data.forEach(({ id, name, risk_val, weight }) => (opts += `<option value="${id}" data-foo="${risk_val}|${weight}">${name}</option>`));
  }
  $(`select[name='${inpSelectName}']`).html(opts);
}

async function listTypes() {
  await $.ajax({
    url: `/api/v1/common/list-companytypes`,
    type: 'get',
    dataType: 'json',
    success: function(res) {
      var opts = '<option selected value="0">Seleccione...</option>';
      res.forEach(({ id, name, risk_val, weight }) => (opts += `<option value="${id}" data-foo="${risk_val}|${weight}">${name}</option>`));
      $("select[name='type']").html(opts);
    }
  });
}

async function listSizes() {
  await $.ajax({
    url: `/api/v1/common/list-companysizes`,
    type: 'get',
    dataType: 'json',
    success: function(res) {
      var opts = '<option selected value="0">Seleccione...</option>';
      res.forEach(({ id, name, risk_val, weight }) => (opts += `<option value="${id}" data-foo="${risk_val}|${weight}">${name}</option>`));
      $("select[name='size']").html(opts);
    }
  });
}

async function listObligations() {
  await $.ajax({
    url: `/api/v1/common/list-obligations`,
    type: 'get',
    dataType: 'json',
    success: function(res) {
      var opts = '<option selected value="0">Seleccione...</option>';
      res.forEach(({ id, name, risk_val, weight }) => (opts += `<option value="${id}" data-foo="${risk_val}|${weight}">${name}</option>`));
      $("select[name='obligation']").html(opts);
    }
  });
}

async function listCius() {
  await $.ajax({
    url: `/api/v1/common/list-cius`,
    type: 'get',
    dataType: 'json',
    success: function(res) {
      var opts = '<option selected value="0">Seleccione...</option>';
      res.forEach(({ id, code, name, risk_val, weight_dni, weight_ruc }) => (opts += `<option value="${code}" data-foo="${risk_val}|${weight_ruc}|${weight_dni}">${name}</option>`));
      $("select[name='ciu']").html(opts);
    }
  });
}

async function listCompositions() {
  await $.ajax({
    url: `/api/v1/common/list-compositions`,
    type: 'get',
    dataType: 'json',
    success: function(res) {
      var opts = '<option selected value="0">Seleccione...</option>';
      res.forEach(({ id, name, risk_val, weight }) => (opts += `<option value="${id}" data-foo="${risk_val}|${weight}">${name}</option>`));
      $("select[name='composition']").html(opts);
    }
  });
}

async function listPeps() {
  await $.ajax({
    url: `/api/v1/common/list-peps`,
    type: 'get',
    dataType: 'json',
    success: function(res) {
      var opts = '<option selected value="0">Seleccione...</option>';
      res.forEach(({ id, name, risk_val, weight }) => (opts += `<option value="${id}" data-foo="${risk_val}|${weight}">${name}</option>`));
      $("select[name='pep']").html(opts);
    }
  });
}

async function listCountries() {
  await $.ajax({
    url: `/api/v1/common/list-countries`,
    type: 'get',
    dataType: 'json',
    success: function(res) {
      var opts = '<option selected value="0">Seleccione...</option>';
      res.forEach(({ id, name, risk_val, weight }) => (opts += `<option value="${id}" data-foo="${risk_val}|${weight}">${name}</option>`));
      $("select[name='country']").html(opts);
      $("select[name='residence']").html(opts);
    }
  });
}

async function listOffices() {
  await $.ajax({
    url: `/api/v1/common/list-offices`,
    type: 'get',
    dataType: 'json',
    success: function(res) {
      var opts = '<option selected value="0">Seleccione...</option>';
      res.forEach(({ id, name, risk_val, weight }) => (opts += `<option value="${id}" data-foo="${risk_val}|${weight}">${name}</option>`));
      $("select[name='office']").html(opts);
    }
  });
}

async function listProducts() {
  await $.ajax({
    url: `/api/v1/common/list-products`,
    type: 'get',
    dataType: 'json',
    success: function(res) {
      var opts = '<option selected value="0">Seleccione...</option>';
      res.forEach(({ id, name, risk_val, weight }) => (opts += `<option value="${id}" data-foo="${risk_val}|${weight}">${name}</option>`));
      $("select[name='product']").html(opts);
    }
  });
}

async function listCurrencies() {
  await $.ajax({
    url: `/api/v1/common/list-currencies`,
    type: 'get',
    dataType: 'json',
    success: function(res) {
      var opts = '<option selected value="0">Seleccione...</option>';
      res.forEach(({ id, name, risk_val, weight }) => (opts += `<option value="${id}" data-foo="${risk_val}|${weight}">${name}</option>`));
      $("select[name='currency']").html(opts);
    }
  });
}

async function listFundings() {
  await $.ajax({
    url: `/api/v1/common/list-fundings`,
    type: 'get',
    dataType: 'json',
    success: function(res) {
      var opts = '<option selected value="0">Seleccione...</option>';
      res.forEach(({ id, name, risk_val, weight }) => (opts += `<option value="${id}" data-foo="${risk_val}|${weight}">${name}</option>`));
      $("select[name='funding']").html(opts);
    }
  });
}

$("select[name='composition']").on('change', function(){
  var composition = $(this).children("option:selected").text();
  _isPEP = composition === 'PEP';
  if (_isPEP) $('#divPep').show();
  else $('#divPep').hide();
});

function setYears(birthday) {
  var ageDifMs = Date.now() - birthday;
  var ageDate = new Date(ageDifMs);
  return Math.abs(ageDate.getUTCFullYear() - 1970);
}

function calAge(age) {
  var risk = 0;
  if (age >= 0 && age <= 1) {
    risk = 4;
  } else if (age > 1 && age <= 3) {
    risk = 3;
  } else if (age > 3 && age <= 5) {
    risk = 2;
  } else if (age > 5 && age <= 120) {
    risk = 1;
  } else {

  }
  return risk*0.05;
}

function calcAmount(amount) {
  var risk = 0;
  if (amount >= 0 && amount <= 14520) {
    risk = 1;
  } else if (amount > 14520 && amount <= 971637) {
    risk = 2;
  } else if (amount > 971637 && amount <= 12896139) {
    risk = 3;
  } else if (amount > 12896139 && amount <= 24820642) {
    risk = 4;
  } else if (amount > 24820642 && amount <= 1000000000) {
    risk = 5;
  } else {

  }
  return risk*0.05;
}

function calc(str) {
  var splitted = str.split('|');
  var val = 0;
  if (splitted.length >= 2) {
    val = Number(splitted[0])*Number(splitted[1]);
  }
  return val;
}

function setRisk(val) {
  var risk = '';
  var color = '';
  if (val >= 0 && val <= 1.8) {
    risk = 'Mínimo';
    color = '#469BE7';
  }
  if (val > 1.8 && val <= 2.6) {
    risk = 'Leve';
    color = '#429A46';
  }
  if (val > 2.6 && val <= 3.4) {
    risk = 'Moderado';
    color = '#FFFD0A';
  }
  if (val > 3.4 && val <= 4.2) {
    risk = 'Alto';
    color = '#FF8A00';
  }
  if (val > 4.2 && val <= 5) {
    risk = 'Muy Alto';
    color = '#FF0000';
  }
  return { color, risk };
}

$('#calculate').on('click', function () {
  calculate();
});

function calculate() {
  var birthday = $("input[name='birthday']").val();
  if (!birthday) {
    alert(`¡Completar el campo ${_translation.birthday} para realizar el cálculo!`);
    return;
  }

  var years = setYears(new Date(birthday));
  var transaction = $("input[name='transaction']").val();

  var risks = {
    client: { zTotal: 0 },
    location: { zTotal: 0 },
    other: { zTotal: 0 },
    zTotal: 0,
  };
  var keys;

  if ($("input[name='transaction']").val() === '') {
    alert(`¡Completar el campo ${_translation.transaction} para realizar el cálculo!`);
    return;
  }

  /* Begin - Client */
  keys = ['type', 'size', 'obligation', 'transaction', 'birthday', 'ciu', 'composition'];
  if (_isPEP) keys.push('pep')
  try {
    keys.forEach((key) => {
      var calculated = 0;
      if (key === 'transaction') {
        calculated = calcAmount(Number(transaction));
        risks.client.transaction = calculated;
      } else if (key === 'birthday') {
        calculated = calAge(Number(years));
        risks.client.birthday = calculated;
      } else {
        var valweight = $(`select[name='${key}']`).children("option:selected").data("foo");
        if (!valweight) throw new Error(key);
        calculated = calc(valweight);
        risks.client[key] = calculated;
      }
      risks.client.zTotal += calculated;
    })
  } catch (err) {
    var key = err.message;
    alert(`¡Completar el campo ${_translation[key]} para realizar el cálculo!`);
    return;
  }
  risks.zTotal += risks.client.zTotal;
  /* End - Client */

  /* Begin - Location */
  keys = ['country', 'office', 'residence'];
  try {
    keys.forEach((key) => {
      var valweight = $(`select[name='${key}']`).children("option:selected").data("foo");
      if (!valweight) throw new Error(key);
      var calculated = calc(valweight);
      risks.location[key] = calculated;
      risks.location.zTotal += calculated;
    })
  } catch (err) {
    var key = err.message;
    alert(`¡Completar el campo ${_translation[key]} para realizar el cálculo!`);
    return;
  }
  risks.zTotal += risks.location.zTotal;
  /* End - Location */

  /* Begin - Other */
  keys = ['product', 'currency', 'funding'];
  try {
    keys.forEach((key) => {
      var valweight = $(`select[name='${key}']`).children("option:selected").data("foo");
      if (!valweight) throw new Error(key);
      var calculated = calc(valweight);
      risks.other[key] = calculated;
      risks.other.zTotal += calculated;
    })
  } catch (err) {
    var key = err.message;
    alert(`¡Completar el campo ${_translation[key]} para realizar el cálculo!`);
    return;
  }
  risks.zTotal += risks.other.zTotal;
  /* End - Other */

  console.log(risks)

  var { color, risk } = setRisk(risks.zTotal);
  _riskTotal = risks.zTotal.toFixed(2);
  $('#risk_total_dot').css('background-color', color);
  $('#risk_total').text(`Riesgo ${risk}: ${_riskTotal}`);
}

$('#btnRiskCancel').on('click', function () {
  window.location.replace('/scoring');
});

$('#btnRiskSave').on('click', function () {
  create('save');
});

$('#btnRiskRegister').on('click', function () {
  calculate();
  var { isValid, error } = validationIdentification();
  if (!isValid) { alert(error); return; }
  if ($("input[name='fullname']").val() === '') { alert('¡Nombre no válido!'); return; }

  create('register');
});

function validationIdentification() {
  var identification = $("input[name='identification']").val();
  /*var re = new RegExp(/^[0-9]{8,8}$/g); // DNI
  if (_hasCompany) re = new RegExp(/^([0-9]{11})$/g); // RUC

  var isValid = re.test(identification);
  var error = _hasCompany ? '¡RUC no válido!' : '¡DNI no válido!';*/
  var isValid = Boolean(identification);
  var error = isValid ? '' : '¡Identificación no válida!';

  return { isValid, error };
}

async function create(type) {
  var data = {
    userId: _userId,
    name: $("input[name='fullname']").val(),
    dni: $("input[name='identification']").val(),
    birthday: $("input[name='birthday']").val(),
    typeId: $("select[name='type']").val(),
    sizeId: $("select[name='size']").val(),
    obligationId: $("select[name='obligation']").val(),
    ciuId: $("select[name='ciu']").val(),
    compositionId: $("select[name='composition']").val(),
    transaction: $("input[name='transaction']").val(),
    countryId: $("select[name='country']").val(),
    residenceId: $("select[name='residence']").val(),
    officeId: $("select[name='office']").val(),
    productId: $("select[name='product']").val(),
    currencyId: $("select[name='currency']").val(),
    fundingId: $("select[name='funding']").val(),
    risk_val: _riskTotal,
    obs: $("textarea[name='obs']").val(),
    type: type,
  }

  if (_isPEP) data.pepId = $("select[name='pep']").val();

  loading(true);
  await $.ajax({
    url: '/api/v1/scoring/create-company',
    type: 'POST',
    contentType:'application/json',
    data: JSON.stringify(data),
    // dataType: 'json',
    processData: false,
    success: function(res) {
      window.location.replace('/scoring');
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
  $('#scoring-massive').trigger('click');
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
  $('#scoringMassiveFailed').hide();
});