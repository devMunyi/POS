function crudaction(jsonbody, url, method = "POST", callback) {
  let server_ = $("#server_").val();
  let cleanJson = JSON.stringify(jsonbody);
  $.ajax({
    url: server_ + url,
    type: method,
    timeout: 0,
    headers: {
      "Content-Type": "application/json",
    },
    dataType: "json",
    data: cleanJson,
    beforeSend: function () {
      $("#processing").show();
    },

    complete: function () {
      $("#processing").hide();
    },
    success: function (feedback) {
      callback(feedback);
    },
    cache: false,
    contentType: false,
    processData: false,
    error: function (err) {
      callback(err);
    },
  });
}

function feedback(
  mtype = "NOTICE",
  dtype = "TOAST",
  target = ".feedback",
  message,
  secs = 1
) {
  let fmessage = "";
  $(target).text("");
  if (mtype === "NOTICE") {
    fmessage = "<span class='info notice'>" + message + "</span>";
  } else if (mtype === "ERROR") {
    fmessage = "<span class='info error'>" + message + "</span>";
  } else if (mtype === "SUCCESS") {
    fmessage = "<span class='info success'>" + message + "</span>";
  }
  if (dtype === "TOAST") {
    $("#standardnotif").html("").css("display", "none").removeClass(mtype);
    $("#standardnotif")
      .html(message)
      .fadeIn("fast")
      .addClass(mtype + "x");
    setTimeout(function () {
      $("#standardnotif").html("").fadeOut("fast").removeClass(mtype);
      $(target).html("");
    }, 1000 * secs);
  } else if (dtype === "INLINE") {
    $(target).html(fmessage);
    setTimeout(function () {
      $(target).html("");
      $(target).html("");
    }, 1000 * secs);
  }
}

function submitBtn(fun) {
  $(".submitbtn").html(
    '<button type="submit" onclick="' +
      fun +
      '"class="btn btn-success btn-md">Submit </button>'
  );
}

function disabledBtn() {
  $(".submitbtn").html(
    '<button class="btn btn-success btn-md" type="button" disabled>' +
      '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>' +
      "submitting..." +
      "</button>"
  );
}

///////--------------------Begin next na previous button handler
function next() {
  let current_offset = parseInt($("#_offset_").val());
  let current_rpp = parseInt($("#_rpp_").val());
  let func = $("#_func_").val();
  let current_page = parseInt($("#_page_no_").val());
  let nex = current_offset + current_rpp;
  let next_page = current_page + 1;
  if (nex < 0) {
    nex = 0;
  }
  $("#_offset_").val(nex);
  $("#_page_no_").val(next_page);
  var fn = eval(func);
}

function prev() {
  let current_offset = parseInt($("#_offset_").val());
  let current_rpp = parseInt($("#_rpp_").val());
  let current_page = parseInt($("#_page_no_").val());
  let func = $("#_func_").val();
  let prev = current_offset - current_rpp;
  let prev_page = current_page - 1;
  if (prev < 0) {
    prev = 0;
  }
  $("#_offset_").val(prev);
  $("#_page_no_").val(prev_page);
  var fn = eval(func);
}

/////-----------------------End next and previous button handler

///----------Begin form with file upload handler
function formready(formid) {
  formhandler("#" + formid);
}

function formhandler(formid) {
  var options = {
    beforeSend: function () {
      $("#progress").show();
      //clear everything
      $("#bar").width("0%");
      $("#message").html("");
      $("#percent").html("0%");
    },
    uploadProgress: function (event, position, total, percentComplete) {
      $("#bar").width(percentComplete + "%");
      $("#percent").html(percentComplete + "%");
    },
    success: function () {
      $("#bar").width("100%");
      $("#percent").html("100%");
    },
    complete: function (response) {
      $("#message").html(
        "<font color='green'>" + response.responseText + "</font>"
      );
      ///if success, refresh form
      var res = response.responseText;
      var suc = res.search("ucces");
      if (suc >= 0) {
        $(formid)[0].reset();
      }
    },
    error: function () {
      $("#message").html(
        "<font color='red'> ERROR: unable to upload files</font>"
      );
    },
  };

  $(formid).ajaxForm(options);
}

///////Enf form with file upload handler

////////--------------- footer date
function footer_date() {
  const d = new Date();
  let year = d.getFullYear();
  $("#admfooter-date").html(year);
}

//Begin search local storage helper functions
function trimString(str) {
  var i = 0;
  strlen = str.length - 1;
  while (i < str.length && str[i] == " ") i++;
  while (strlen > i && str[strlen] == " ") strlen -= 1;
  var strResult = str.substring(i, strlen + 1);
  return strResult.toLowerCase();
}

function compareObjects(obj1, obj2) {
  var k;
  for (k in obj1) if (obj1[k] != obj2[k]) return false;
  for (k in obj2) if (obj1[k] != obj2[k]) return false;
  return true;
}

function itemExists(haystack, needle) {
  for (var i = 0; i < haystack.length; i++)
    if (compareObjects(haystack[i], needle)) return true;
  return false;
}

function searchFor(objects, searchTerm) {
  searchTerm = searchTerm.toLowerCase();
  var results = [];
  for (var i = 0; i < objects.length; i++) {
    for (var key in objects[i]) {
      if (objects[i][key].toString().indexOf(searchTerm) != -1) {
        if (!itemExists(results, objects[i])) results.push(objects[i]);
      }
    }
  }
  return results;
}

//End search local storage helper functions

function feedback(
  mtype = "NOTICE",
  dtype = "TOAST",
  target = ".feedback",
  message,
  secs = 1
) {
  let fmessage = "";
  $(target).text("");
  if (mtype === "NOTICE") {
    fmessage = "<span class='info notice'>" + message + "</span>";
  } else if (mtype === "ERROR") {
    fmessage = "<span class='info error'>" + message + "</span>";
  } else if (mtype === "SUCCESS") {
    fmessage = "<span class='info success'>" + message + "</span>";
  }
  if (dtype === "TOAST") {
    $("#standardnotif").html("").css("display", "none").removeClass(mtype);
    $("#standardnotif")
      .html(message)
      .fadeIn("fast")
      .addClass(mtype + "x");
    setTimeout(function () {
      $("#standardnotif").html("").fadeOut("fast").removeClass(mtype);
      $(target).html("");
    }, 1000 * secs);
  } else if (dtype === "INLINE") {
    $(target).html(fmessage);
    setTimeout(function () {
      $(target).html("");
      $(target).html("");
    }, 1000 * secs);
  }
}

function load_std(resource, targetdiv, params) {
  let fields = params;
  let thislocation = $("#server_").val();

  $.ajax({
    method: "GET",
    url: thislocation + resource,
    data: fields,
    beforeSend: function () {
      $("#processing").show();
    },

    complete: function () {
      $("#processing").show();
    },
    success: function (feedback) {
      $(targetdiv).html(feedback);
    },
  });
}

function reload() {
  location.reload();
}

function gotourl(url) {
  window.location.href = url;
}

function pager(tableid) {
  $(
    "<div id='pager_header' class='row page-header pb-2'>\n" +
      "                        <div class='col-sm-6'><span style=\"font-family:sans-serif\" class='font-18 font-italic text-black text-mute'></div>\n" +
      "                        <div class='col-sm-6'><input type='text' class='form-control' id='search_' onkeyup='search();' placeholder='Search by service title, company name, unit or frequency...'></div>\n" +
      "                    </div>\n"
  ).insertBefore(tableid);

  $(
    "<div class='pager pt-2' id='pager_foot'>\n" +
      "                                 <nav class='Page navigation example'>\n" +
      "                                   <ul class='pagination justify-content-center'>\n" +
      "                                     <li class='page-item'><a href='javascript:void(0)' class='page-link btn btn-sm bg-blue text-sm' id='prev_' onclick='prev()'><i class='fa fa-arrow-left'></i>&nbsp;previous</a></li>&nbsp;&nbsp;\n" +
      "                                      <li class='page-item pt-1'><i>Page <span id='page_no'>1</span></i></li>&nbsp;&nbsp;\n" +
      "                                      <li class='page-item'><a href='javascript:void(0)' class='page-link btn btn-sm bg-blue text-sm' id='next_' onclick='next()'>&nbsp;next <i class='fa fa-arrow-right'></i></a></li>\n" +
      "                                   </ul>\n" +
      "                                 </nav>\n" +
      "                    </div>"
  ).insertAfter(tableid);
}

{
  /* <nav aria-label="Page navigation example">
    <ul class="pagination justify-content-center">
      <li class="page-item disabled">
        <a class="page-link">Previous</a>
      </li>
      <li class="page-item">
        <a class="page-link" href="#">
          1
        </a>
      </li>
      <li class="page-item">
        <a class="page-link" href="#">
          2
        </a>
      </li>
      <li class="page-item">
        <a class="page-link" href="#">
          3
        </a>
      </li>
      <li class="page-item">
        <a class="page-link" href="#">
          Next
        </a>
      </li>
    </ul>
  </nav>; */
}

function next() {
  let current_offset = parseInt($("#_offset_").val());
  let current_rpp = parseInt($("#_rpp_").val());
  let func = $("#_func_").val();
  let current_page = parseInt($("#_page_no_").val());
  let nex = current_offset + current_rpp;
  let next_page = current_page + 1;
  if (nex < 0) {
    nex = 0;
  }
  $("#_offset_").val(nex);
  $("#_page_no_").val(next_page);
  //console.log("Next page is =>", next_page);
  var fn = eval(func);
}

function prev() {
  let current_offset = parseInt($("#_offset_").val());
  let current_rpp = parseInt($("#_rpp_").val());
  let current_page = parseInt($("#_page_no_").val());
  let func = $("#_func_").val();
  let prev = current_offset - current_rpp;
  let prev_page = current_page - 1;
  if (prev < 0) {
    prev = 0;
  }
  $("#_offset_").val(prev);
  $("#_page_no_").val(prev_page);
  var fn = eval(func);
}

function search() {
  let search_ = $("#search_").val().trim();
  if (search_) {
    $("#_search_").val(search_);
    $("#_offset_").val(0);
    $("#_page_no_").val(1);
    let func = $("#_func_").val();
    var fn = eval(func);
    setTimeout(function () {
      var html = $(".table").html();
      // $('.table').html(html.replace(/mercy/gi, '<strong>$&</strong>'));
    }, 100);
  } else {
    pager_home();
  }
}

function orderby(fld, dir) {
  $("#_orderby_").val(fld);
  $("#_dir_").val(dir);

  $("#_offset_").val(0);
  let func = $("#_func_").val();
  var fn = eval(func);
  /*setTimeout(function () {
          var html = $('.table').html();
          // $('.table').html(html.replace(/mercy/gi, '<strong>$&</strong>'));
      },100);*/
}

function pager_home() {
  $("#_offset_").val(0);
  $("#_page_no_").val(1);
  //$('#_search_').val("");
  let func = $("#_func_").val();
  var fn = eval(func);
}

function pager_refactor() {
  let current_offset = parseInt($("#_offset_").val());
  let current_rpp = parseInt($("#_rpp_").val());
  let total_records = parseInt($("#_alltotal_").val());
  //$('#total_results_').html(total_records);
  //$('#approvals').html(total_records);
  //let total_uploads = parseInt($('#uploads_count').val());
  //$('#total_docs').html(total_uploads);

  let current_page = parseInt($("#_page_no_").val());
  $("#page_no").html(current_page);

  if (current_offset > 0) {
    $("#prev_").removeClass("disabled");
  } else {
    $("#prev_").addClass("disabled");
  }

  if (current_rpp + current_offset >= total_records) {
    $("#next_").addClass("disabled");
  } else {
    $("#next_").removeClass("disabled");
  }
}
