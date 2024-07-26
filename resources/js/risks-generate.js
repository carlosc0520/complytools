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
  _companyType = _companyTypeIds[$("select[name='companyType']").val()];
  listAreasByUser(userId)
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
  window.location.replace('/risks');
});

$('#btnRiskSave').on('click', function () {
  create('save');
});

$('#btnRiskRegister').on('click', function () {
  create('register');
});

$('#btnRiskClose').on('click', function () {
  create('close');
});

function getVal(val) {
  if (!val) return null;
  var aux = val.split('T');
  if (aux.length > 1) return Number(aux[0]);
  return NaN;
}

function setVal(val) {
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
      $("select[name='companyProcess']").html(optProcesses);
    }
  });
}

async function create(type) {
  var data = {
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
    userId: $('#userId').text(),
  }
  loading(true);
  await $.ajax({
    url: '/api/v1/risk/create',
    type: 'POST',
    contentType:'application/json',
    data: JSON.stringify(data),
    // dataType: 'json',
    processData: false,
    success: function(res) {
      /*$('#risksForm1')[0].reset();
      $('#risksForm2')[0].reset();
      $('#risksForm3')[0].reset();
      $('#companyProbText').text('');
      $('#companyImpEstimText').text('');
      $('#sumText').text('');
      $('#rdxProbText').text('');
      $('#rdxImpText').text('');*/
      window.location.replace('/risks');
    },
    error: function (XMLHttpRequest, textStatus, errorThrown) {
      loading(false);
      alert(XMLHttpRequest.responseJSON?.message)
    }
  });
}

var _area;
var _processes = [];

$(document).on('click', '.assoc--item.area', function (e) {
  var id = e.target.id;

  $(`#${_area}`).css('background-color', 'white');
  if (_area === id) {
    _area = null;
  } else {
    _area = id;
    $(`#${id}`).css('background-color', '#ededed');
    getProcessesByArea(id.replace('assoc--area--', ''))
  }
});

$(document).on('click', '.assoc--item.process', function (e) {
  var id = e.target.id;
  var color = '#ededed';

  var idx = _processes.findIndex((sel) => sel === id)
  if (idx >= 0) {
    color = 'white';
    _processes.splice(idx, 1);
  } else {
    _processes.push(id);
  }

  $(`#${id}`).css('background-color', color);
});

async function getProcessesByArea(areaId) {
  _processes.map((id) => $(`#${id}`).css('background-color', 'white'));
  loading(true);
  await $.ajax({
    url:   `/api/v1/risk/list-processes-by-area/${areaId}`,
    type:  'get',
    success: function (response) {
      loading(false);
      _processes = response.map((processId) => `assoc--process--${processId}`);
      _processes.map((id) => $(`#${id}`).css('background-color', '#ededed'));
    },
    error: function (XMLHttpRequest, textStatus, errorThrown) {
      loading(false);
      var error = XMLHttpRequest.responseJSON;
      $('#errorMessage').text(error.error);
      $('#errorOpen').trigger('click');
    }
  });
}

$("#btnModalAreaProcess").on('click', () => {
  $('#areaprocess').trigger('click');
});

$('#btnCreateProcess').on('click', async () => {
  if (!$("input[name='process']").val()) {
    alert('¡Campo obligatorio!');
    return;
  }

  loading(true);
  await $.ajax({
    url: `/api/v1/risk/create-process`,
    type: 'post',
    data: { process: $("input[name='process']").val() },
    success: function (response) {
      loading(false);
      $("input[name='process']").val('');
      var processId = response.proceso_id;
      var process = response.nombre;
      if (processId) {
        var html = $('#association--processes').html();
        html = `<span class="assoc--item process" id="assoc--process--${processId}">${process}</span>` + html;
        
        $('#association--processes').html(html);

		    // $('#association--processes').scrollTop($('#association--processes')[0].scrollHeight);
        alert('¡Item agregado correctamente!');
      }
    },
    error: function (XMLHttpRequest, textStatus, errorThrown) {
      loading(false);
      alert("Status: " + textStatus); alert("Error: " + errorThrown);
    }
  });
});

$('#btnCreateArea').on('click', async () => {
  if (!$("input[name='area']").val()) {
    alert('¡Campo obligatorio!');
    return;
  }

  loading(true);
  await $.ajax({
    url: `/api/v1/risk/create-area`,
    type: 'post',
    data: { area: $("input[name='area']").val(), userId: $('#userId').text() },
    success: function (response) {
      loading(false);
      $("input[name='area']").val('');
      var areaId = response.division_area_id;
      var area = response.nombre;
      if (areaId) {
        var html = $('#association--areas').html();
        html = `<span class="assoc--item area" id="assoc--area--${areaId}">${area}</span>` + html;
        
        $('#association--areas').html(html);

        // $('#association--areas').scrollTop($('#association--areas')[0].scrollHeight);
        alert('¡Item agregado correctamente!');
      }
    },
    error: function (XMLHttpRequest, textStatus, errorThrown) {
      loading(false);
      alert("Status: " + textStatus); alert("Error: " + errorThrown);
    }
  });
});

$('#btnAssoc').on('click', async () => {
  if (!_area) {
    alert('¡Debe seleccionar un área!');
    return;
  }
  if (!_processes?.length) {
    alert('¡Debe seleccionar al menos un proceso!');
    return;
  }

  var data = {
    areaId: _area?.replace('assoc--area--', ''),
    processIds: _processes.map((process) => `${process.replace('assoc--process--', '')}`),
  };
  loading(true);
  await $.ajax({
    url: `/api/v1/risk/create-areaprocesses`,
    type: 'post',
    contentType:'application/json',
    data: JSON.stringify(data),
    processData: false,
    success: function (response) {
      loading(false);
      alert('¡Procesos asociados al área correctamente!');
    },
    error: function (XMLHttpRequest, textStatus, errorThrown) {
      loading(false);
      alert("Status: " + textStatus); alert("Error: " + errorThrown);
    }
  });
});

var __contextMenu = document.getElementById('context-menu');
// var __scope = document.querySelector('body')
var __deleteType;
var __deleteId;

document.addEventListener("contextmenu", (e) => {
  e.preventDefault();
  var classes = e.target.classList.value;
  if (classes.includes('assoc--item')) {
    var { clientX: mouseX, clientY: mouseY } = e;
    __contextMenu.style.top = `${mouseY}px`;
    __contextMenu.style.left = `${mouseX}px`;
    if (classes.includes('area')) {
      __deleteType = 'area';
      __deleteId = e.target.id;
      __contextMenu.classList.remove("visible");
      setTimeout(() => __contextMenu.classList.add("visible"));
    } else if (classes.includes('process')) {
      __deleteType = 'process';
      __deleteId = e.target.id;
      __contextMenu.classList.remove("visible");
      setTimeout(() => __contextMenu.classList.add("visible"));
    } else {
      __contextMenu.classList.remove("visible");
    }
  } else {
    __contextMenu.classList.remove("visible");
  }
});

/*$('.assoc--item.area').on('contextmenu', (e) => {
  e.preventDefault();
  var { clientX: mouseX, clientY: mouseY } = e;
  contextMenu.style.top = `${mouseY}px`;
  contextMenu.style.left = `${mouseX}px`;

  __deleteType = 'area';
  __deleteId = e.target.id;

  contextMenu.classList.remove("visible");
  setTimeout(() => {
    contextMenu.classList.add("visible");
  });
});

$('.assoc--item.process').on('contextmenu', (e) => {
  e.preventDefault();
  var { clientX: mouseX, clientY: mouseY } = e;
  contextMenu.style.top = `${mouseY}px`;
  contextMenu.style.left = `${mouseX}px`;

  __deleteType = 'process';
  __deleteId = e.target.id;

  contextMenu.classList.remove("visible");
  setTimeout(() => {
    contextMenu.classList.add("visible");
  });
});

scope.addEventListener('click', (e) => {
  if (e.target.offsetParent != contextMenu) {
    contextMenu.classList.remove("visible");
  }
});*/

$('#removeItem').on('click', async (e) => {
  e.preventDefault();
  $('#custom-risk-confirm').trigger('click');
  __contextMenu.classList.remove("visible");
});

$('#btnConfirmDelete').on('click', async (e) => {
  loading(true);
  var id = __deleteId.replace('assoc--process--', '').replace('assoc--area--', '');
  var url = `/api/v1/risk/delete-${__deleteType}/${id}`;
  await $.ajax({
    url: url,
    type: 'delete',
    success: function (response) {
      loading(false);
      $('#custom-risk-confirm').trigger('click');
      $(`#${__deleteId}`).remove();
      if (__deleteType === 'area') {
        _processes.map((id) => $(`#${id}`).css('background-color', 'white'));
        _processes = [];
      }
      alert('¡Item eliminado correctamente!');
    },
    error: function (XMLHttpRequest, textStatus, errorThrown) {
      loading(false);
      alert("Status: " + textStatus); alert("Error: " + errorThrown);
    }
  });
});
