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

  questions.init();
  users.init();
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

function changeStatus(model, field, id){
  var responder = "";
  $.ajax({
    url: "./"+model+"/changeStatus/"+id,
    cache: false,
    data: {status: "toggle", field: field},
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

var questions = {
  init: function(){
    this.options();
  },
  options: function(){
    var qtd = $('.option').length;    
    $('button[data-function=addNewOption]').click(function(){
      var element = '<div class="option" data-id="'+qtd+'"><input type="radio" name="value" value="'+qtd+'"/><input placeholder="Nova opção" class="form-control questionOption" name="options['+qtd+'][title]"/></div>';
      qtd+=1;
      $('.options').append(element);
    });
  }
}

var users = {
  init: function(){
    this.remove();
  },
  remove: function(){
    $('button.btn-remove').click(function(){
      var id = $(this).attr('model-id');
      var user_id = $(this).attr('data-id');

      $('.user_block[data-id='+user_id+']').remove();
      users.delete('stores',id,user_id);
    });
  },
  delete: function(model,id,user_id){
    $.ajax({
      url: "./"+model+"/deleteUser",
      cache: false,
      data: {id: id, user_id: user_id},
      method: 'POST'
    })
    .done(function( html ) {
      responder = html;
    });
  }
}