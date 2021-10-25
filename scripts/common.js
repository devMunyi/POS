function dbaction(resource,params, callback){
    let server_ = $('#server_').val();
    let fields=params;
    $.ajax({
        method:'POST',
        url:server_+resource,
        data:fields,
        beforeSend:function()
        {
            $("#processing").show();
        },

        complete:function ()
        {
            $("#processing").hide();
        },
        success: function(feedback)
        {
            callback(feedback);
        },
        error: function (err) {
            callback(err);
        }
    });
}

function feedback(mtype = 'NOTICE', dtype = 'TOAST', target = '.feedback', message, secs = 1) {
    let fmessage = "";
    $(target).text("");
    if (mtype === 'NOTICE') {
        fmessage = "<span class='info notice'>" + message + "</span>";
    } else if (mtype === 'ERROR') {
        fmessage = "<span class='info error'>" + message + "</span>";
    } else if (mtype === 'SUCCESS') {
        fmessage = "<span class='info success'>" + message + "</span>";
    }
    if (dtype === 'TOAST') {
        $('#standardnotif').html("").css("display", "none").removeClass(mtype);
        $('#standardnotif').html(message).fadeIn("fast").addClass(mtype + "x");
        setTimeout(function () {
            $('#standardnotif').html("").fadeOut('fast').removeClass(mtype);
            $(target).html("");
        }, 1000 * secs);
    } else if (dtype === 'INLINE') {
        $(target).html(fmessage);
        setTimeout(function () {
            $(target).html("");
            $(target).html("");
        }, 1000 * secs);
    }
}


function load_std(resource,targetdiv,params) {
    let fields = params;
    let thislocation = $('#server_').val();

    $.ajax({
        method:'GET',
        url:thislocation+resource,
        data:fields,
        beforeSend:function()
        {
            $("#processing").show();
        },

        complete:function ()
        {
            $("#processing").show();
        },
        success: function(feedback)
        {
            $(targetdiv).html(feedback);

        }

    });
}

function reload(){
    location.reload();
}


function gotourl(url){
    window.location.href = url;
}


function pager(tableid) {

    $("<div id='pager_header' class='row page-header'>\n" +
        "                        <div class='col-sm-6'><span style=\"font-family:sans-serif\" class='font-18 font-italic text-black text-mute'><span class = \"badge font-16\" id='total_results_'>0</span> Record(s) Found</span></div>\n" +
        "                        <div class='col-sm-6'><input type='text' class='form-control' id='search_' onkeyup='search();' placeholder='Search'></div>\n" +
        "                    </div>\n").insertBefore(tableid);

    $("<div class='pager' id='pager_foot'>\n" +
        "                        <nav aria-label='Pager'>\n" +
        "                            <ul class='list-group'>\n" +
        "                                <li  class='page-item'>\n" +
        "                                    <a class='previous page-link btn  bg-blue text-bold' id='prev_' href='#' onclick='prev()' tabindex='-1'><i class='fa fa-arrow-left'></i> Previous</a>\n" +
        "                                </li>\n" +

        "                                <li class='page-item'><i>Page <span id='page_no'>1</span></i></li>\n" +
        "                                <li  class='page-item'>\n" +
        "                                    <a class=' next page-link btn  bg-blue text-bold' id='next_' onclick='next()' href='#'>Next <i class='fa fa-arrow-right'></i></a>\n" +
        "                                </li>\n" +
        "                            </ul>\n" +
        "                        </nav>\n" +
        "                    </div>").insertAfter(tableid);

}
function next() {
    let current_offset = parseInt($('#_offset_').val());
    let current_rpp = parseInt($('#_rpp_').val());
    let func = $('#_func_').val();
    let current_page = parseInt($('#_page_no_').val());
    let nex = current_offset + current_rpp;
    let next_page = current_page + 1;
    if(nex < 0){
        nex = 0;
    }
    $('#_offset_').val(nex);
    $('#_page_no_').val(next_page);
    var fn = eval(func);
}


function prev() {
    let current_offset = parseInt($('#_offset_').val());
    let current_rpp = parseInt($('#_rpp_').val());
    let current_page = parseInt($('#_page_no_').val());
    let func = $('#_func_').val();
    let prev = current_offset - current_rpp;
    let prev_page = current_page - 1;
    if(prev < 0){
        prev = 0;
    }
    $('#_offset_').val(prev);
    $('#_page_no_').val(prev_page);
    var fn = eval(func);
}

function search() {
    let search_ = $('#search_').val().trim();
    let camp_id = $('#_camp_id_').val();
    if(search_) {
        $('#_camp_id_').val(camp_id);
        $('#_search_').val(search_);
        $('#_offset_').val(0);
        $('#_page_no_').val(1);
        let func = $('#_func_').val();
        var fn = eval(func);
        setTimeout(function () {
            var html = $('.table').html();
           // $('.table').html(html.replace(/mercy/gi, '<strong>$&</strong>'));
        },100);
    }
    else{
        pager_home();
    }
}

function orderby(fld, dir){
    $('#_orderby_').val(fld);
    $('#_dir_').val(dir);

    $('#_offset_').val(0);
    let func = $('#_func_').val();
    var fn = eval(func);
    /*setTimeout(function () {
        var html = $('.table').html();
        // $('.table').html(html.replace(/mercy/gi, '<strong>$&</strong>'));
    },100);*/
}

function pager_home() {
    $('#_offset_').val(0);
    $('#_page_no_').val(1);
    //$('#_search_').val("");
    let func = $('#_func_').val();
    var fn = eval(func);
}


function pager_refactor() {
    let current_offset = parseInt($('#_offset_').val());
    let current_rpp = parseInt($('#_rpp_').val());
    let total_records = parseInt($('#_alltotal_').val());
    $('#total_results_').html(total_records);
    $('#approvals').html(total_records);
    let total_uploads = parseInt($('#uploads_count').val());
    $('#total_docs').html(total_uploads);

    let current_page = parseInt($('#_pageno_').val());
    $('#page_no').html(current_page);

   if(((current_offset)) > 0){
       $("#prev_").removeClass("disabled");
   }
   else{
       $("#prev_").addClass("disabled");
   }

   if((current_rpp+current_offset) >= total_records){
       $("#next_").addClass("disabled");

   }
   else{
       $("#next_").removeClass("disabled");
   }

}


function list_items_count(item){
    let num = $('#')
}