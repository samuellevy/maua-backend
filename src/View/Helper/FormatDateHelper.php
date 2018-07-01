<?php
namespace App\View\Helper;

use Cake\View\Helper;

class FormatDateHelper extends Helper {
  public function teste(){
    return "teste";
  }

  public function formatDate($dateTime, $tipo=null){
    //Para usar direto em interação com models.
    //$select = $this->find("first", array('conditions'=>array($this->alias.".id"=>$id)));
    //$dateTime = $select[$this->alias]['created'];
    $date = substr($dateTime, 0, 10);
    $time = substr($dateTime, 11, 10);

    $diaSemana = date("w", strtotime($date));
    $semana = array(
      0=>'Domingo',
      1=>'Segunda-feira',
      2=>'Terça-feira',
      3=>'Quarta-feira',
      4=>'Quinta-feira',
      5=>'Sexta-feira',
      6=>'Sábado',
    );
    $mes = array(
      "01"=>'Janeiro',
      "02"=>'Fevereiro',
      "03"=>'Março',
      "04"=>'Abril',
      "05"=>'Maio',
      "06"=>'Junho',
      "07"=>'Julho',
      "08"=>'Agosto',
      "09"=>'Setembro',
      "10"=>'Outubro',
      "11"=>'Novembro',
      "12"=>'Dezembro',
    );
    $mesAbr = array(
      "01"=>'Jan',
      "02"=>'Fev',
      "03"=>'Mar',
      "04"=>'Abr',
      "05"=>'Mai',
      "06"=>'Jun',
      "07"=>'Jul',
      "08"=>'Ago',
      "09"=>'Set',
      "10"=>'Out',
      "11"=>'Nov',
      "12"=>'Dez',
    );

    switch($tipo){
      default:
      $return = $semana[$diaSemana].", ".date("d", strtotime($date))." de ".$mes[date("m", strtotime($date))]." de ".date("Y", strtotime($date));
      break;
      case "pt-numbers":
      $return = date("d", strtotime($date))."/".date("m", strtotime($date))."/".date("Y", strtotime($date));
      break;
      case "pt-numbers-hours":
      $return = date("d", strtotime($date))."/".date("m", strtotime($date))."/".date("Y", strtotime($date))." ".date("H", strtotime($time)).":".date("i", strtotime($time));
      break;
      case "just_day":
      $return = date("d", strtotime($date));
      break;
      case "de_mes_ano":
      $return = "de ".$mesAbr[date("m", strtotime($date))]." ".date("Y", strtotime($date));
      break;
      case "year":
      $return = date("Y", strtotime($date));
      break;
      case "month-numbers":
      $return = date("m", strtotime($date));
      break;
      case "full-date":
      $return = date("Y", strtotime($date)).'-'.date("m", strtotime($date)).'-'.date("d", strtotime($date));
      break;
    }

    return $return;
  }

}
?>
