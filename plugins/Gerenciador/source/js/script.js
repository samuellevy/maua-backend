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
  rest.get('/public/review').then((rest)=>{
    console.log(rest);
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

var demo = {
  initPickColor: function(){
    $('.pick-class-label').click(function(){
      var new_class = $(this).attr('new-class');  
      var old_class = $('#display-buttons').attr('data-class');
      var display_div = $('#display-buttons');
      if(display_div.length) {
        var display_buttons = display_div.find('.btn');
        display_buttons.removeClass(old_class);
        display_buttons.addClass(new_class);
        display_div.attr('data-class', new_class);
      }
    });
  },
  
  initChartist: function(){    
    var dataSales = {
      labels: ['9:00AM', '12:00AM', '3:00PM', '6:00PM', '9:00PM', '12:00PM', '3:00AM', '6:00AM'],
      series: [
        [287, 385, 490, 492, 554, 586, 698, 695, 752, 788, 846, 944],
        [67, 152, 143, 240, 287, 335, 435, 437, 539, 542, 544, 647],
        [23, 113, 67, 108, 190, 239, 307, 308, 439, 410, 410, 509]
      ]
    };
    
    var optionsSales = {
      lineSmooth: false,
      low: 0,
      high: 800,
      showArea: true,
      height: "245px",
      axisX: {
        showGrid: false,
      },
      lineSmooth: Chartist.Interpolation.simple({
        divisor: 3
      }),
      showLine: false,
      showPoint: false,
    };
    
    var responsiveSales = [
      ['screen and (max-width: 640px)', {
        axisX: {
          labelInterpolationFnc: function (value) {
            return value[0];
          }
        }
      }]
    ];
    
    Chartist.Line('#chartHours', dataSales, optionsSales, responsiveSales);
    
    // 

    rest.get('/public/review/module_user').then((rest)=>{
      
      var data = {
        labels: [
          'Amarelo ' + Math.round((rest.amarelo*100)/rest.amarelo_all)+'%',
          'Verde ' + Math.round((rest.verde*100)/rest.verde_all)+'%',
          'Preto ' + Math.round((rest.preto*100)/rest.preto_all)+'%'],
        series: [
          [rest.amarelo_all, rest.verde_all, rest.preto_all],
          [rest.amarelo, rest.verde, rest.preto],
        ]
      };
      
      var options = {
        seriesBarDistance: 10,
        axisX: {
          showGrid: false
        },
        height: "245px"
      };
      
      var responsiveOptions = [
        ['screen and (max-width: 640px)', {
          seriesBarDistance: 5,
          axisX: {
            labelInterpolationFnc: function (value) {
              return value[0];
            }
          }
        }]
      ];
      
      Chartist.Bar('#chartActivity', data, options, responsiveOptions);
    });

    
    
    //
    var dataPreferences = {
      series: [
        [25, 30, 20, 25]
      ]
    };
    
    var optionsPreferences = {
      donut: true,
      donutWidth: 40,
      startAngle: 0,
      total: 100,
      showLabel: false,
      axisX: {
        showGrid: false
      }
    };
    
    Chartist.Pie('#chartPreferences', dataPreferences, optionsPreferences);
    rest.get('/public/review/enabled_stores').then((rest)=>{
      let enabled = (rest.enabled_stores*100)/rest.all_stores;
      Chartist.Pie('#chartPreferences', {
        labels: [Math.round(enabled)+'%',(100-Math.round(enabled))],
        series: [Math.round(enabled), (100-Math.round(enabled))]
      });
    });
    
  },
  
  initGoogleMaps: function(){
    var myLatlng = new google.maps.LatLng(40.748817, -73.985428);
    var mapOptions = {
      zoom: 13,
      center: myLatlng,
      scrollwheel: false, //we disable de scroll over the map, it is a really annoing when you scroll through page
      styles: [{"featureType":"water","stylers":[{"saturation":43},{"lightness":-11},{"hue":"#0088ff"}]},{"featureType":"road","elementType":"geometry.fill","stylers":[{"hue":"#ff0000"},{"saturation":-100},{"lightness":99}]},{"featureType":"road","elementType":"geometry.stroke","stylers":[{"color":"#808080"},{"lightness":54}]},{"featureType":"landscape.man_made","elementType":"geometry.fill","stylers":[{"color":"#ece2d9"}]},{"featureType":"poi.park","elementType":"geometry.fill","stylers":[{"color":"#ccdca1"}]},{"featureType":"road","elementType":"labels.text.fill","stylers":[{"color":"#767676"}]},{"featureType":"road","elementType":"labels.text.stroke","stylers":[{"color":"#ffffff"}]},{"featureType":"poi","stylers":[{"visibility":"off"}]},{"featureType":"landscape.natural","elementType":"geometry.fill","stylers":[{"visibility":"on"},{"color":"#b8cb93"}]},{"featureType":"poi.park","stylers":[{"visibility":"on"}]},{"featureType":"poi.sports_complex","stylers":[{"visibility":"on"}]},{"featureType":"poi.medical","stylers":[{"visibility":"on"}]},{"featureType":"poi.business","stylers":[{"visibility":"simplified"}]}]
      
    }
    var map = new google.maps.Map(document.getElementById("map"), mapOptions);
    
    var marker = new google.maps.Marker({
      position: myLatlng,
      title:"Hello World!"
    });
    
    // To add the marker to the map, call setMap();
    marker.setMap(map);
  },
  
  showNotification: function(from, align){
    color = Math.floor((Math.random() * 4) + 1);
    
    $.notify({
      icon: "pe-7s-gift",
      message: "Welcome to <b>Light Bootstrap Dashboard</b> - a beautiful freebie for every web developer."
      
    },{
      type: type[color],
      timer: 4000,
      placement: {
        from: from,
        align: align
      }
    });
  }
}
__DEV__ = false;

var rest = {
  url: __DEV__?'http://192.168.2.71/lafarge-backend/api':'http://dev2.3aww.com.br/lafargemaua/api',
  get(action){
    var url = this.url + action;
    var options = {
      method: 'GET',
      headers: {
        Accept: 'application/json',
        'Content-Type': 'application/json',
      }
    };
    return fetch(url, options).then((res) => res.json());
  },
};