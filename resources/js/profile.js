require('./bootstrap');

var eyeVisible1 = $('#eye-visible-1');
var eyeHidden1 = $('#eye-hidden-1');
var eyeVisible2 = $('#eye-visible-2');
var eyeHidden2 = $('#eye-hidden-2');
var eyeVisible3 = $('#eye-visible-3');
var eyeHidden3 = $('#eye-hidden-3');

function loading(flag) {
  if (flag) {
    $('#loadingOpen').trigger('click');
  } else {
    $('#loadingClose').trigger('click');
  }
}

eyeVisible1.on('click', () => {
  eyeVisible1.hide();
  eyeHidden1.show();
  $("input[name='password']").attr('type', 'text');
});

eyeHidden1.on('click', () => {
  eyeVisible1.show();
  eyeHidden1.hide();
  $("input[name='password']").attr('type', 'password');
});

eyeVisible2.on('click', () => {
  eyeVisible2.hide();
  eyeHidden2.show();
  $("input[name='newPassword']").attr('type', 'text');
});

eyeHidden2.on('click', () => {
  eyeVisible2.show();
  eyeHidden2.hide();
  $("input[name='newPassword']").attr('type', 'password');
});

eyeVisible3.on('click', () => {
  eyeVisible3.hide();
  eyeHidden3.show();
  $("input[name='rePassword']").attr('type', 'text');
});

eyeHidden3.on('click', () => {
  eyeVisible3.show();
  eyeHidden3.hide();
  $("input[name='rePassword']").attr('type', 'password');
});

$('#btnEditShow').on('click', function () {
  $('#divEditShow').hide();
  $('#divEditSend').show();
});

$('#btnEditCancel').on('click', function () {
  $('#form-user')[0].reset();
  $('#divEditShow').show();
  $('#divEditSend').hide();
});

$('#triggerUpload').on('click', function() {
  $('#upload').trigger('click');
});

$('#upload').on('change', function () {
  var files = $('#upload')[0].files;
  if (files.length) {
    var file = files[0];
    var reader = new FileReader();
    reader.onload = function () {
      $('#avatar').attr("src", reader.result);
    }
    reader.readAsDataURL(file);
  }
});

$('.btnmodify').on('click', async function() {
  var [_, idUser] = this.id.split('-');
  var files = $('#upload')[0].files;
  loading(true);
  await $.ajax({
    url:   `/api/v1/profile/modify-password`,
    type:  'put',
    dataType: "json",
    data:  {
      id: idUser,
      password: $("input[name='password']").val(),
      newPassword: $("input[name='newPassword']").val(),
      rePassword: $("input[name='rePassword']").val(),
    },
    success: function (response) {
      if (!files.length) {
        loading(false);
        $('#btnEditCancel').trigger('click');
      }
    },
    error: function (XMLHttpRequest, textStatus, errorThrown) {
      loading(false);
      var error = XMLHttpRequest.responseJSON;
      $('#errorMessage').text(error.error);
      $('#errorOpen').trigger('click');
    }
  });

  if (files.length) {
    var formData = new FormData();
    formData.append('id', idUser);
    formData.append('file', files[0]);
    await $.ajax({
      type: 'POST',
      url:   `/api/v1/profile/upload-avatar`,
      data: formData,
      cache: false,
      processData: false,
      contentType: false,
      success: function (response) {
        loading(false);
        $('#btnEditCancel').trigger('click');
      },
      error: function (XMLHttpRequest, textStatus, errorThrown) {
        loading(false);
        alert("Status: " + textStatus); alert("Error: " + errorThrown);
      }
    })
  }
});
