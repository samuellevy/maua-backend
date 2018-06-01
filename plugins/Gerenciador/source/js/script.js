$(document).ready(function(){
  $(".BoxEvent").hide();

  //responder chamado
  $(".NewEventBtn").click(function(){
    var action = $(this).attr('data-action');
    $(".BoxEvent").hide();
    $(".BoxEvent[data-action="+action+"]").show();
  });

  //upload de imagens
  $('.form-img').click(function(){
    $(this).next().click();
  });

  $('input[type=file]').change(function () {
    readURL(this, $(this).prev());
  });

  //mostra imagem local
  function readURL(input, target) {
    if (input.files && input.files[0]) {
      var reader = new FileReader();

      reader.onload = function (e) {
        target.attr('src', e.target.result);
      }
      reader.readAsDataURL(input.files[0]);
    }
  }

  $('.remove').click(function(){
    var uid = $(this).attr('data-uid');

    removeFileFromServer(uid);
    $('.form-img[data-uid='+uid+']').attr('src','http://via.placeholder.com/270x270');
    $('.remove[data-uid='+uid+']').hide();
    $('.form-file[data-uid='+uid+']').val('');
  });
});

function removeFileFromServer(id){
  var responder = "";
  $.ajax({
    url: "./public/delete_file/"+id,
    cache: false
  })
  .done(function( html ) {
    responder = html;
  });
}

function changeStatus(id){
  var responder = "";
  $.ajax({
    url: "./menu/changeStatus/"+id,
    cache: false,
    data: {status: "toggle"},
    method: 'POST'
  })
  .done(function( html ) {
    responder = html;
  });
}

$('.sidebar-dropdown a').click(function(){
  var id = $(this).parent('.sidebar-dropdown').attr('data-id');
  $('.sidebar-dropdown[data-id='+id+'] ul').toggleClass("show-sidebar-dropdown");
  $('.sidebar-dropdown[data-id='+id+'] .i-absolute').toggleClass("i-absolute-transform");
});
