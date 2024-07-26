require('./bootstrap');

var _UIT = 4600;
var _companyTypeIds = {
  "Microempresa": 1,
  "Pequeña Empresa": 2,
  "Mediana Empresa": 3,
  "Gran Empresa": 4,
}
var _probs = ['Muy baja', 'Baja', 'Media', 'Alta', 'Muy alta'];
var _impacts = ['Insignificante', 'Menor', 'Moderado', 'Mayor', 'Catastrófico'];
var _probsMap = [
  [0.01, 4.99],
  [5.00, 9.99],
  [10.00, 29.99],
  [30.00, 99.90],
  [99.91, 100.00]
]
var _impactsMap = {
  "0": [[0, 1], [ 1, 10], [10, 20], [20, 40], [40, 150]],
  "1": [[0, 1], [ 1, 50], [50, 100], [100, 200], [200, 1700]],
  "2": [[0, 1], [ 1, 250], [250, 300], [300, 450], [450, 2300]],
  "3": [[0, 1], [ 1, 250], [250, 300], [300, 450], [450, 2400]],
}
var _status = {
  save: 1,
  register: 2,
  close: 3,
}

var _companyType;
var _idxProbInher;
var _idxImpInher;
var _idxProbRes;
var _idxImpRes;
var _xy;

window.addEventListener("load", function(event) {
  boxElement = document.querySelector("#userId");
  var userId = $('#userId').text();
  init(userId)
}, false);

function loading(flag) {
  if (flag) {
    $('#loadingOpen').trigger('click');
  } else {
    $('#loadingClose').trigger('click');
  }
}

$("select[name='companyType']").on('change', function(){
  _companyType = _companyTypeIds[$("select[name='companyType']").val()];

  /* Begin - Trigger */
  var calculate = $("input[name='companyImpEstim']").val()/_UIT;
  var ranges = _impactsMap[_companyType - 1];
  _idxImpInher = ranges.findIndex((range) => inRange(calculate, range[0], range[1]));
  if (_idxImpInher === -1) _idxImpInher = _impacts.length - 1
  $('#companyImpEstimText').text(_impacts[_idxImpInher]);
  /* End - Trigger */
});

$("select[name='companyArea']").on('change', function(){
  var areaId = $(this).children("option:selected").val();
  listProcessesByArea(areaId);
});

$("select[name='companyProb']").on('change', function(){
  var probId = $(this).children("option:selected").val() ?? 0;
  if (probId > 0) {
    _idxProbInher = probId - 1;
    $('#companyProbText').text(_probs[_idxProbInher]);
    $('#companyProbText').show();
  } else {
    $('#companyProbText').hide();
  }
});

$("input[name='companyImpEstim']").on('input', function() {
  var calculate = $("input[name='companyImpEstim']").val()/_UIT;
  var ranges = _impactsMap[_companyType - 1];
  _idxImpInher = ranges.findIndex((range) => inRange(calculate, range[0], range[1]));
  if (_idxImpInher === -1) _idxImpInher = _impacts.length - 1
  $('#companyImpEstimText').text(_impacts[_idxImpInher]);
});

$('#btnRiskInher').on('click', function () {
  $(`#mp${_xy}`).html('');
  _xy = `${_probs.length - _idxProbInher}${_idxImpInher + 1}`
  $(`#mp${_xy}`).html(`
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-purple-500" viewBox="0 0 20 20" fill="currentColor" style="width: 100% !important">
      <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z" />
    </svg>
  `);
});

$('#btnRiskRes').on('click', function () {
  // Prob
  var lst1 = [
    $("select[name='controlPeriod']").val(),
    $("select[name='controlOper']").val(),
    $("select[name='controlType']").val(),
    $("select[name='controlSuper']").val(),
  ];
  var lst2 = [
    $("select[name='controlFreq']").val(),
    $("select[name='controlFollow']").val()
  ];
  var sum = sumWeights(lst1)*sumWeights(lst2);

  var valProb = _probsMap[_idxProbInher];
  var valProbCeil = valProb[1]; // [0, 99] --> 99

  var rdxProb = (1 - sum)*valProbCeil;
  _idxProbRes = _probsMap.findIndex((range) => inRange(rdxProb, range[0], range[1]));
  var prob = _probs[_idxProbRes];

  // Impact
  var rdxImp = (1 - sum)*($("input[name='companyImpEstim']").val()/_UIT);
  var ranges = _impactsMap[_companyType - 1];
  _idxImpRes = ranges.findIndex((range) => inRange(rdxImp, range[0], range[1]));
  var imp = _impacts[_idxImpRes];

  // Graphic
  $('#sumText').text(sum);
  $('#rdxProbText').text(prob);
  $('#rdxImpText').text(imp);

  $("span[id^='_mp']").each(function (i, el) { // Finding id starts with...
    $(`#${this.id}`).html('');
  });

  var xy = `${_probs.length - _idxProbRes}${_idxImpRes + 1}`
  $(`#_mp${xy}`).html(`
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-purple-500" viewBox="0 0 20 20" fill="currentColor" style="width: 100% !important">
      <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z" />
    </svg>
  `);
});

$('#btnRiskCancel').on('click', function () {
  if (window.risk.status === 1) window.location.replace('/risks');
});

$('#btnRiskSave').on('click', function () {
  if (window.risk.status === 1) modify('save');
});

$('#btnRiskRegister').on('click', function () {
  if (window.risk.status === 1) modify('register');
});

$('#btnRiskClose').on('click', function () {
  modify('close');
});

function getVal(val) {
  if (!val) return null;
  var aux = val.split('T');
  if (aux.length > 1) return Number(aux[0]);
  return NaN;
}

function setVal(val) {
  if (val === null) return '-.-';
  var lnVal = val.toString().length;
  if (lnVal === 1) return `${val}.0`;
  return val;
}

function inRange(x, min, max) {
  return x >= min && x < max;
}

function sumWeights(lst) {
  var sum = 0;
  lst.forEach((item) => {
    var [value, weight] = item.split('T');
    sum += value * weight;
  });
  return sum;
}

async function listAreasByUser(userId) {
  await $.ajax({
    url: `/api/v1/common/list-areas-by-user/${userId}`,
    type: 'get',
    dataType: 'json',
    success: function(res) {
      var optAreas = '';
      optAreas += '<option selected value="0">Seleccione una área</option>';
      res.forEach((item) => {
        optAreas += `<option value="${item.areaId}">${item.area}</option>`;
      });
      /* Begin - For areas deleted before */
      var wrCompAreId = isNaN(window.risk.companyAreaId) ? 0 : Number(window.risk.companyAreaId);
      var wrCompAreName = window.risk.companyAreaName;
      if (wrCompAreId > 0) {
        const found = res.find((item) => item.areaId === wrCompAreId)
        if (!found) optAreas += `<option value="${wrCompAreId}">${wrCompAreName}</option>`;
      }

      var wrCtrlAreId = isNaN(window.risk.ctrlAreaId) ? 0 : Number(window.risk.ctrlAreaId);
      var wrCtrlAreName = window.risk.ctrlAreaName;
      if (wrCtrlAreId > 0) {
        const found = res.find((item) => item.areaId === wrCtrlAreId)
        if (!found) optAreas += `<option value="${wrCtrlAreId}">${wrCtrlAreName}</option>`;
      }

      var wrPlanAreId = isNaN(window.risk.planAreaId) ? 0 : Number(window.risk.planAreaId);
      var wrPlanAreName = window.risk.planAreaName;
      if (wrPlanAreId > 0) {
        const found = res.find((item) => item.areaId === wrPlanAreId)
        if (!found) optAreas += `<option value="${wrPlanAreId}">${wrPlanAreName}</option>`;
      }
      /* End - For areas deleted before */
      $("select[name='companyArea']").html(optAreas);
      $("select[name='controlArea']").html(optAreas);
      $("select[name='planArea']").html(optAreas);
    }
  });
}

async function listProcessesByArea(areaId) {
  await $.ajax({
    url: `/api/v1/common/list-processes-by-area/${areaId}`,
    type: 'get',
    dataType: 'json',
    success: function(res) {
      var optProcesses = '';
      optProcesses += '<option selected value="0">Seleccione un proceso</option>';
      res.forEach((item) => {
        optProcesses += `<option value="${item.processId}">${item.process}</option>`;
      });
      /* Begin - For processes deleted before */
      var wrCompProcId = isNaN(window.risk.companyProcessId) ? 0 : Number(window.risk.companyProcessId);
      var wrCompProcName = window.risk.companyProcessName;
      if (wrCompProcId > 0) {
        const found = res.find((item) => item.processId === wrCompProcId)
        if (!found) optProcesses += `<option value="${wrCompProcId}">${wrCompProcName}</option>`;
      }
      /* End - For processes deleted before */
      $("select[name='companyProcess']").html(optProcesses);
    }
  });
}

async function modify(type) {
  var idRisk = $('#idRisk').text();
  var files = $("input[name='file']").length ? $("input[name='file']")[0].files : [];

  if (type === 'close') {
    if (!files.length) {
      alert('¡Archivo requerido!');
      return;
    }
    await uploadFile(idRisk, files[0]);
  }

  var data = {
    idRisk: $('#idRisk').text(),
    companyTypeId: _companyType,
    companyTypeName: $("select[name='companyType']").val(),
    title: $("textarea[name='companyTitle']").val(),
    companyAreaId: $("select[name='companyArea']").val(),
    companyAreaName: $("select[name='companyArea'] option:selected").text(),
    companyProcessId: $("select[name='companyProcess']").val(),
    companyProcessName: $("select[name='companyProcess'] option:selected").text(),
    details: $("textarea[name='companyRiskDetails']").val(),
    factorId: $("select[name='companyFactor']").val(),
    factorName: $("select[name='companyFactor'] option:selected").text(),
    probId: $("select[name='companyProb']").val(),
    probName: $("select[name='companyProb'] option:selected").text(),
    probInher: _idxProbInher >= 0 ? _probs[_idxProbInher] : undefined,
    probInherId: _idxProbInher >= 0 ? _probs.length - _idxProbInher : undefined,
    impEstim: $("input[name='companyImpEstim']").val(),
    impInher: _idxImpInher >= 0 ? _impacts[_idxImpInher] : undefined,
    impInherId: _idxImpInher >= 0 ? _idxImpInher + 1 : undefined,
    ctrlDescription: $("textarea[name='controlDescription']").val(),
    ctrlDocument: $("textarea[name='controlDocument']").val(),
    ctrlAreaId: $("select[name='controlArea']").val(),
    ctrlAreaName: $("select[name='controlArea'] option:selected").text(),
    ctrlPeriodId: getVal($("select[name='controlPeriod']").val()), // <-- split
    ctrlPeriodName: $("select[name='controlPeriod'] option:selected").text(),
    ctrlOperId: getVal($("select[name='controlOper']").val()), // <-- split
    ctrlOperName: $("select[name='controlOper'] option:selected").text(),
    ctrlTypeId: getVal($("select[name='controlType']").val()), // <-- split
    ctrlTypeName: $("select[name='controlType'] option:selected").text(),
    ctrlSuperId: getVal($("select[name='controlSuper']").val()), // <-- split
    ctrlSuperName: $("select[name='controlSuper'] option:selected").text(),
    ctrlFreqId: getVal($("select[name='controlFreq']").val()), // <-- split
    ctrlFreqName: $("select[name='controlFreq'] option:selected").text(),
    ctrlFollId: getVal($("select[name='controlFollow']").val()), // <-- split
    ctrlFollName: $("select[name='controlFollow'] option:selected").text(),
    probRes: _idxProbRes >= 0 ? _probs[_idxProbRes] : undefined,
    probResId: _idxProbRes >= 0 ? _probs.length - _idxProbRes : undefined,
    impRes: _idxImpRes >= 0 ? _impacts[_idxImpRes] : undefined,
    impResId: _idxImpRes >= 0 ? _idxImpRes + 1 : undefined,
    planDescr: $("textarea[name='planDescr']").val(),
    planAreaId: $("select[name='planArea']").val(),
    planAreaName: $("select[name='planArea'] option:selected").text(),
    fecStart: $("input[name='planFecStart']").val(),
    fecEnd: $("input[name='planFecEnd']").val(),
    status: _status[type] ?? 1,
    type: type,
    // userId: $('#userId').text(),
  }
  loading(true);
  await $.ajax({
    url: `/api/v1/risk/modify`,
    type: 'POST',
    contentType:'application/json',
    data: JSON.stringify(data),
    processData: false,
    success: function(res) {
      window.location.replace('/risks');
    },
    error: function (XMLHttpRequest, textStatus, errorThrown) {
      loading(false);
      alert(XMLHttpRequest.responseJSON?.message);
    }
  });
}

async function uploadFile(idRisk, file) {
  var formData = new FormData();
  formData.append('idRisk', idRisk);
  formData.append('file', file);
  await $.ajax({
    type: 'POST',
    url: '/api/v1/risk/upload-file',
    data: formData,
    cache: false,
    processData: false,
    contentType: false,
    success: function (res) {
    },
    error: function (XMLHttpRequest, textStatus, errorThrown) {
      alert(XMLHttpRequest.responseJSON?.message);
    }
  });
}

async function init(userId) {
  _companyType = window.risk.companyTypeId;
  await listAreasByUser(userId);
  await listProcessesByArea(window.risk.companyAreaId);
  if (window.risk.status === 1) {
    ['company', 'control', 'plan'].forEach((section) => {
      $(`input[name^='${section}']`).each((idx, el) => $(`input[name='${el.name}']`).removeAttr('disabled'));
      $(`textarea[name^='${section}']`).each((idx, el) => $(`textarea[name='${el.name}']`).removeAttr('disabled'));
      $(`select[name^='${section}']`).each((idx, el) => $(`select[name='${el.name}']`).removeAttr('disabled'));
    })
  }

  var lst = {
    inher: ['companyAreaId', 'companyProcessId', 'factorId', 'probId'],
    res: ['ctrlPeriodId', 'ctrlOperId', 'ctrlTypeId', 'ctrlFreqId', 'ctrlFollId'],
  }
  var isFromClientConoce = window.risk.origin && window.risk.origin === 'client-conoce';

  /* ************************ */
  /* ********* Inher ******** */
  /* ************************ */
  $("select[name='companyArea']").val(window.risk.companyAreaId);
  $("select[name='companyProcess']").val(window.risk.companyProcessId);
  $("select[name='companyFactor']").val(window.risk.factorId);

  if (window.risk.probId) {
    $("select[name='companyProb']").val(window.risk.probId);
    var probId = $("select[name='companyProb']").children("option:selected").val() ?? 0;
    if (probId > 0) {
      _idxProbInher = probId - 1;
      $('#companyProbText').text(_probs[_idxProbInher]);
      $('#companyProbText').show();
    } else {
      $('#companyProbText').hide();
    }
  }

  if (window.risk.impEstim) {
    $("input[name='companyImpEstim']").val(window.risk.impEstim);
    var calculate = $("input[name='companyImpEstim']").val()/_UIT;
    var ranges = _impactsMap[_companyType - 1];
    _idxImpInher = ranges.findIndex((range) => inRange(calculate, range[0], range[1]));
    if (_idxImpInher === -1) _idxImpInher = _impacts.length - 1
    $('#companyImpEstimText').text(_impacts[_idxImpInher]);
  }

  var flag = true;
  lst['inher'].forEach((key) => { if (!window.risk[key] && window.risk[key] !== 0) flag = false; });
  if (flag) {
    $(`#mp${_xy}`).html('');
    _xy = `${_probs.length - _idxProbInher}${_idxImpInher + 1}`
    $(`#mp${_xy}`).html(`
      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-purple-500" viewBox="0 0 20 20" fill="currentColor" style="width: 100% !important">
        <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z" />
      </svg>
    `);
  }

  /* ************************ */
  /* ********* Res ******** */
  /* ************************ */
  $("select[name='controlArea']").val(window.risk.ctrlAreaId);
  $("select[name='controlPeriod']").val(`${setVal(window.risk.ctrlPeriodId)}T0.2`);
  $("select[name='controlOper']").val(`${setVal(window.risk.ctrlOperId)}T0.2`);
  $("select[name='controlType']").val(`${setVal(window.risk.ctrlTypeId)}T0.2`);
  $("select[name='controlSuper']").val(`${setVal(window.risk.ctrlSuperId)}T0.2`);
  $("select[name='controlFreq']").val(`${setVal(window.risk.ctrlFreqId)}T0.5`);
  $("select[name='controlFollow']").val(`${setVal(window.risk.ctrlFollId)}T0.5`);
  var flag = true;
  if (isFromClientConoce) lst['res'].push('ctrlSuperId');
  lst['res'].forEach((key) => { if (!window.risk[key] && window.risk[key] !== 0) flag = false; });
  if (flag) {
    var lst1 = [
      $("select[name='controlPeriod']").val(),
      $("select[name='controlOper']").val(),
      $("select[name='controlType']").val(),
      // $("select[name='controlSuper']").val(),
    ];
    var lst2 = [
      $("select[name='controlFreq']").val(),
      $("select[name='controlFollow']").val()
    ];
    if (isFromClientConoce) lst1.push($("select[name='controlSuper']").val());
    var sum = sumWeights(lst1)*sumWeights(lst2);
  
    var valProb = _probsMap[_idxProbInher];
    var valProbCeil = valProb[1]; // [0, 99] --> 99
  
    var rdxProb = (1 - sum)*valProbCeil;
    _idxProbRes = _probsMap.findIndex((range) => inRange(rdxProb, range[0], range[1]));
    var prob = _probs[_idxProbRes];
  
    // Impact
    var rdxImp = (1 - sum)*($("input[name='companyImpEstim']").val()/_UIT);
    var ranges = _impactsMap[_companyType - 1];
    _idxImpRes = ranges.findIndex((range) => inRange(rdxImp, range[0], range[1]));
    var imp = _impacts[_idxImpRes];
  
    // Graphic
    $('#sumText').text(sum);
    $('#rdxProbText').text(prob);
    $('#rdxImpText').text(imp);
  
    $("span[id^='_mp']").each(function (i, el) { // Finding id starts with...
      $(`#${this.id}`).html('');
    });
  
    var xy = `${_probs.length - _idxProbRes}${_idxImpRes + 1}`;
    $(`#_mp${xy}`).html(`
      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-purple-500" viewBox="0 0 20 20" fill="currentColor" style="width: 100% !important">
        <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z" />
      </svg>
    `);
  }

  /* ************************ */
  /* ********* Control ******** */
  /* ************************ */
  $("select[name='planArea']").val(window.risk.planAreaId);
}
