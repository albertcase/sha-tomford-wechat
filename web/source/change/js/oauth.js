var oauthjs = {
  ajaxloadlist: function(){
    popup.openloading();
    $.ajax({
      url: "/wechat/oauth/list",
      type:"post",
      dataType:'json',
      success: function(data){
        popup.closeloading();
        if(data.code == "10"){
          oauthjs.buildlist(data.list);
        }
        popup.openwarning(data.msg);
      },
      error: function(){
        popup.closeloading();
        popup.openwarning("unknow error");
      }
    });
  },
  buildlisttable: null,
  buildlist: function(data){
    if(this.buildlisttable)
     this.buildlisttable.destroy();
    $('#oauthlisttable tbody').empty();
    this.buildlisttable = $('#oauthlisttable').DataTable( {
      "data": data,
      "columns": [
        {
          "data": "name",
        },
        {
          "class": "oauthcss",
          "data": "callback_url"
        },
        {
          "class": "t-center",
          "data": null,
          "defaultContent": '<i class="fa fa-edit fa-lg"></i>'
        },
        {
          "class": "t-center",
          "data": null,
          "defaultContent": '<i class="fa fa-trash-o fa-lg"></i>'
        },
      ],
      "rowCallback": function( row, data ) {
          $(row).attr('oauthid',data.id);
      },
    } );
  },
  initadd: function(){
    $("#addoauth .oauthredirect").val('');
    $("#addoauth .oauthcallback").val('');
    $("#addoauth .oauthname").val('');
  },
  ajaxadd: function(){
    popup.openloading();
    $.ajax({
      url: "/wechat/oauth/add",
      type:"post",
      data: {
        // "redirect_url": $("#addoauth .oauthredirect").val(),
        "callback_url": $("#addoauth .oauthcallback").val(),
        name: $("#addoauth .oauthname").val(),
        scope: $("#addoauth .oauthscope").val(),
      },
      dataType:'json',
      success: function(data){
        popup.closeloading();
        if(data.code == "10"){
          oauthjs.ajaxloadlist();
          publicall.gotolist();
          oauthjs.initadd();
        }
        popup.openwarning(data.msg);
      },
      error: function(){
        popup.closeloading();
        popup.openwarning("unknow error");
      }
    });
  },
  ajaxeditid: null,
  ajaxinfo: function(){
    var self = this;
    popup.openloading();
    $.ajax({
      url: "/wechat/oauth/info",
      type:"post",
      data: {
        "id": self.ajaxeditid,
      },
      dataType:'json',
      success: function(data){
        popup.closeloading();
        if(data.code == "10"){
          // $("#editoauth .oauthredirect").val(data.info["redirect_url"]);
          $("#editoauth .oauthcallback").val(data.info["callback_url"]);
          $("#editoauth .oauthname").val(data.info["name"]);
          $("#editoauth .oauthscope").val(data.info["scope"]);
          publicall.gotoedit("editoauth");
        }
        popup.openwarning(data.msg);
      },
      error: function(){
        popup.closeloading();
        popup.openwarning("unknow error");
      }
    });
  },
  ajaxupdate: function(){
    var self = this;
    popup.openloading();
    $.ajax({
      url: "/wechat/oauth/update",
      type:"post",
      data: {
        id: self.ajaxeditid,
        // "redirect_url": $("#editoauth .oauthredirect").val(),
        "callback_url": $("#editoauth .oauthcallback").val(),
        name: $("#editoauth .oauthname").val(),
        scope: $("#editoauth .oauthscope").val(),
      },
      dataType:'json',
      success: function(data){
        popup.closeloading();
        if(data.code == "10"){
          oauthjs.ajaxloadlist();
          publicall.gotolist();
        }
        popup.openwarning(data.msg);
      },
      error: function(){
        popup.closeloading();
        popup.openwarning("unknow error");
      }
    });
  },
  ajaxdelete: function(id){
    popup.openloading();
    $.ajax({
      url: "/wechat/oauth/delete",
      type:"post",
      data: {
        "id": id,
      },
      dataType:'json',
      success: function(data){
        popup.closeloading();
        if(data.code == "10"){
          oauthjs.ajaxloadlist();
        }
        popup.openwarning(data.msg);
      },
      error: function(){
        popup.closeloading();
        popup.openwarning("unknow error");
      }
    });
  },
  onload: function(){
    var self = this;
    $("#addoauthsubmit").click(function(){
      self.ajaxadd();
    });
    $("#oauthlist").on("click", "#oauthlisttable .fa-edit", function(){
      self.ajaxeditid = $(this).parent().parent().attr("oauthid");
      self.ajaxinfo();
    });
    $("#oauthlist").on("click", "#oauthlisttable .fa-trash-o", function(){
      self.ajaxdelete($(this).parent().parent().attr("oauthid"));
    });
    $("#editoauth").on("click", "#editoauthsubmit", function(){
      self.ajaxupdate();
    });
    $("#editoauth").on("click", ".fa-mail-reply", function(){
      publicall.gotolist();
    })

  }
};


$(function(){
  oauthjs.onload();
});
