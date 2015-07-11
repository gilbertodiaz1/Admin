<?php
function lang_fullcalendar($lang='en'){
	
switch ($lang) {
case 'en':
	$content="monthNames: ['January','February','March','April','May','June','July','August','September','October','November','December'],
			dayNamesShort: ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'],";
	break;
case 'es':
	$content="monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
			dayNamesShort: ['Dom','Lun','Mar','Mi&eacute;','Juv','Vie','S&aacute;b'],";
	break;
case 'zh-CN':
	$content="monthNames: ['一月','二月','三月','四月','五月','六月',	'七月','八月','九月','十月','十一月','十二月'],
			dayNamesShort: ['周日','周一','周二','周三','周四','周五','周六'],";
	break;
case 'de':	
     $content="monthNames: ['Januar','Februar','März','April','Mai','Juni',
		     'Juli','August','September','Oktober','November','Dezember'],
		      dayNamesShort: ['So','Mo','Di','Mi','Do','Fr','Sa'],";
	break;
case 'el':	
      $content="monthNames: ['Ιανουάριος','Φεβρουάριος','Μάρτιος','Απρίλιος','Μάιος','Ιούνιος',
		       'Ιούλιος','Αύγουστος','Σεπτέμβριος','Οκτώβριος','Νοέμβριος','Δεκέμβριος'],
		        dayNamesShort: ['Κυρ','Δευ','Τρι','Τετ','Πεμ','Παρ','Σαβ'],";
	break;
case 'fr':
       $content="monthNames: ['Janvier','Février','Mars','Avril','Mai','Juin',
		        'Juillet','Août','Septembre','Octobre','Novembre','Décembre'],  
                 dayNamesShort: ['Dim.','Lun.','Mar.','Mer.','Jeu.','Ven.','Sam.'],";
				 break;
case 'id':
      $content="monthNames: ['Januari','Februari','Maret','April','Mei','Juni',
		       'Juli','Agustus','September','Oktober','Nopember','Desember'],
			    dayNamesShort: ['Min','Sen','Sel','Rab','kam','Jum','Sab'],";
				break;
case 'it':
       $content="monthNames: ['Gennaio','Febbraio','Marzo','Aprile','Maggio','Giugno',
			    'Luglio','Agosto','Settembre','Ottobre','Novembre','Dicembre'],
                dayNamesShort: ['Dom','Lun','Mar','Mer','Gio','Ven','Sab'],";
              break;
case 'ja':
       $content="monthNames: ['1月','2月','3月','4月','5月','6月',
		        '7月','8月','9月','10月','11月','12月'],
				dayNamesShort: ['日','月','火','水','木','金','土'],";
				break;
case 'nl':
      $content="monthNames: ['januari', 'februari', 'maart', 'april', 'mei', 'juni',
		       'juli', 'augustus', 'september', 'oktober', 'november', 'december'],
		       dayNamesShort: ['zon', 'maa', 'din', 'woe', 'don', 'vri', 'zat'],";
			   break;
case 'pl':
      $content="monthNames: ['Styczeń','Luty','Marzec','Kwiecień','Maj','Czerwiec',
		       'Lipiec','Sierpień','Wrzesień','Październik','Listopad','Grudzień'],
		        dayNamesShort: ['Nie','Pn','Wt','Śr','Czw','Pt','So'],";
				break;
case 'pt':
      $content="monthNames: ['Janeiro','Fevereiro','Mar&ccedil;o','Abril','Maio','Junho',
		      'Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
		       dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','S&aacute;b'],";
			   break;
case 'ro':
     $content="monthNames: ['Ianuarie','Februarie','Martie','Aprilie','Mai','Iunie',
		      'Iulie','August','Septembrie','Octombrie','Noiembrie','Decembrie'],
			  dayNamesShort: ['Dum', 'Lun', 'Mar', 'Mie', 'Joi', 'Vin', 'Sâm'],";
			  break;
case 'ru':
      $content="monthNames: ['Январь','Февраль','Март','Апрель','Май','Июнь',
		       'Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь'],
			   dayNamesShort: ['вск','пнд','втр','срд','чтв','птн','сбт'],";
			   break;
case 'th':
      $content="monthNames: ['มกราคม','กุมภาพันธ์','มีนาคม','เมษายน','พฤษภาคม','มิถุนายน',
		       'กรกฎาคม','สิงหาคม','กันยายน','ตุลาคม','พฤศจิกายน','ธันวาคม'],
			    dayNamesShort: ['อา.','จ.','อ.','พ.','พฤ.','ศ.','ส.'],";
				break;
case 'tr':
     $content="monthNames: ['Ocak','Şubat','Mart','Nisan','Mayıs','Haziran',
		     'Temmuz','Ağustos','Eylül','Ekim','Kasım','Aralık'],
		      dayNamesShort: ['Pz','Pt','Sa','Ça','Pe','Cu','Ct'],";
			  break;
}
	
	return $content;
}

?>