require("./bootstrap");

require("./datatables");

window.moment = require('moment');

var _userId;

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

$('.neglst--tbody').on('click', 'tr', function (event) {
  var target = event.target;
  if (target.classList.contains('actions')) {
    var splitted = target.id.split('-');
    if (splitted.length > 2) {
      var [_, action, id] = splitted;
      _idSelected = id;
      if (action === 'show') showDetails(id, true);
    }
    return;
  }
});

async function showDetails(id) {
  loading(true);
  $('#neglst-details').trigger('click');
  await $.ajax({
    url: `/api/v1/negativelists/details/object/${_userId}/${id}/false`,
    type: 'get',
    success: function (response) {
      loading(false);
      if (response) {
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
        $("#neglst_link").html(`<a href="${link}" target="_blank" style="color: #00D5FB; font-weight: 500;">${link}</a>`);
      }
    },
    error: function (XMLHttpRequest, textStatus, errorThrown) {
      loading(false);
      alert("Status: " + textStatus); alert("Error: " + errorThrown);
    }
  });
}