<?php

	require_once('src/lib/pdf/mpdf.php');
  function rus_date(){
    $month_arr = array(
      '01' => 'января', 
      '02' => 'февраля',
      '03' => 'марта',
      '04' => 'апреля',
      '05' => 'мая',
      '06' => 'июня',
      '07' => 'июля',
      '08' => 'августа',
      '09' => 'сентября',
      '10' => 'октября',
      '11' => 'ноября',
      '12' => 'декабря'
    );
    $month = strftime("%m"); 
    $year = strftime("%Y"); 
    $day = strftime("%d"); 
    $month = $month_arr[$month]; 
    return $day.' '.$month.' '.$year.' г.';
  }
  
  function remake_img_url($path){    
    if(stristr($path, 'https:') !== false){
      $url = str_replace('https:', '', $path);
    }else if(stristr($path, 'http:') !== false){
      $url = str_replace('http:', '', $path);
    }else{
      $url = $path;
    }    
    return $url;
  }
  
  $param = array();
   
  set_time_limit(512);
	$pdf = new mPDF('utf-8', 'A4', '10', 'Arial', 0, 0, 50, 50, 0, 0);
 	$pdf->charset_in = 'utf-8';

 	$css = file_get_contents('src/css/style.css');
 	$pdf->writeHTML($css, 1);
  
  //if(file_exists ('data.json')){
  if(file_exists ($filename)){
    //$param = json_decode(file_get_contents("data.json"), true);
    $param = json_decode(file_get_contents($filename), true);
  }
  
  $pdf->SetHTMLHeader('
    <div class="header">
      <table>					
        <tbody>						
          <tr>
            <td class="logo">
              <img src="src/img/logo.jpg" width="249">
            </td>
            <td class="adres">
              <div class="f">
                Санкт-Петербург, Невский пр., 84-78
              </div>
              <div>
                Телефон: <b>(812) 900-22-48</b>
              </div>
            </td>
            <td class="mail">
              <div class="e">
                ИП Бочков М.В.
              </div>
              <div class="r">
                <span>mvboch@byratino.info</span>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
      <div class="border"></div>
    </div>
  ');
  
  $pdf->SetHTMLFooter('
    <div class="footer">
      <div class="border"></div>
      <table width="100%" style="padding-top:20px;">
        <tr>
          <td width="33%">
            <div class="link" style="font-size: 20px; borser-bottom: 1px solid #000;"><b>www.byratino.info</b></div>
            <div class="small" style="font-size: 6px; margin-top:5px;">ИНФОРМАЦИОННО-АНАЛИТИЧЕСКАЯ СИСТЕМА ОБРАБОТКИ ИНФОРМАЦИИ ИЗ СЕТИ ИНТЕРНЕТ (ИАС «БУРАТИНО» )</div>
          </td>
          <td width="33%" style="padding-left: 25px; line-height: 25px;">Техническая поддержка:<b>boykovpn@byratino.info</b></td>
          <td width="33%" style="padding-left: 15px; line-height: 25px;">Организационные вопросы: <b>mvboch@byratino.info</b></td>
        </tr>
      </table>
    </div>
  ');
  
  $html = '<!DOCTYPE html><html><head></head><body><section>';
  $pdf->writeHTML($html, 2);           


  
  
  $fio = explode(" ", $param['info']['user_info']['fio']);
  $html = '<div class="first-page">
                  <div class="datetime"><table><tr><td><img src="src/img/date-icon.png" width="25" height="25"></td><td>Отчет сформирован '.rus_date().'</td></tr></table></div>
                  <div style="width:100%; background:url(src/img/bg-main.jpg) no-repeat;  background-size:70%; background-position:120px 100px;">
                    <div class="left" style="float:left;">
                      <div class="name" style="font-size:26px;  text-align:center;">'.$fio[0].'</div>
                      <div class="name" style="font-size:26px;  text-align:center;">'.$fio[1].'</div>
                      <div class="uid" style="font-size:10px;">Идентификатор пользователя '.$param['deep']['user_info']['uid'].'</div>
                      <div class="photo" style="text-align:center;"><img src="'.remake_img_url($param['deep']['user_info']['photo']).'" width="180" align="center"></div>
                    </div>
                    <div class="right" style="float:left;">
                      <table width="100%">';
                        for($i = 0; $i < count($param['deep']['user_info']['about']); $i++){
                          $key = key($param['deep']['user_info']['about']);
                            if($param['deep']['user_info']['about'][$key]){
                              $html .= '
                                <tr>
                                  <td style="padding-top:5px; width:150px;">'.$key.'</td>
                                  <td style="padding-top:5px; padding-bottom:3px;">'.$param['deep']['user_info']['about'][$key].'</td>
                                </tr>';
                            }                            
                          next($param['deep']['user_info']['about']);
                        }
                      $html .= '</table>
                    </div>
                  </div>
                </div>';
  $pdf->writeHTML($html, 2);
  
  
  
  $pdf->AddPage('','','1','i','on');
  $html = '
    <div class="ram">
      <div class="top-head">
        <table width="100%">
          <tr><td style="font-size:22px; color: rgb(96, 144, 184);">Фото</td><td style="text-align:right; font-size: 20px;">'.$param['info']['user_info']['fio'].'</td></tr>
          <tr><td></td><td style="text-align: right; padding-top:5px; padding-bottom:3px; font-size:10px; text-align:right;">Идентификатор пользователя - '.$param['info']['user_info']['uid'].'</td></tr>
          <tr><td></td><td style="text-align: right; border-bottom:1px solid #ccc; padding-bottom:5px;">
            <table>
              <tr>
                <td><img src="src/img/date-icon.png" width="25" height="25" style="float:left"></td>
                <td><span style="padding-left:5px; float:left">'.rus_date().'</span></td>
              </tr>
            </table>     
          </td></tr>
        </table>
      </div>      
      <div class="photo"><div style="width:100%; padding-top: 20px;">';        
      for($i = 0; $i < count($param['info']['photos']); $i++){
        if($i > 31) break;
        $html .= '<div style="float:left; width: 150px; height: 140px; margin-left:25px; padding-top:5px; text-align:center;"><img style="max-height:140px; max-width:150px;" src="'.remake_img_url($param['info']['photos'][$i]).'"></div>';
      }        
  $html .= '</div>
      </div>
    </div>  
  ';
  $pdf->writeHTML($html, 2);  
  
  
  
  function Counters($count){
    if($count){
      return $count;
    }else{
      return 0;
    }
  }
  
  function markers($val){
    return $val ? round(($val * 385) / 100) : 0;
  }
  
  if($param['deep']['stat']){
  $pdf->AddPage('','','1','i','on');
  $html = '
    <div class="second-page ram">
      <div class="top-head">
        <table width="100%">
          <tr><td style="font-size:22px; color: rgb(96, 144, 184);">Аналитический блок</td><td style="text-align:right; font-size: 20px;">'.$param['info']['user_info']['fio'].'</td></tr>
          <tr><td></td><td style="text-align: right; padding-top:5px; padding-bottom:3px; font-size:10px; text-align:right;">Идентификатор пользователя - '.$param['info']['user_info']['uid'].'</td></tr>
          <tr><td></td><td style="text-align: right; border-bottom:1px solid #ccc; padding-bottom:5px;">
            <table>
              <tr>
                <td><img src="src/img/date-icon.png" width="25" height="25" style="float:left"></td>
                <td><span style="padding-left:5px; float:left">'.rus_date().'</span></td>
              </tr>
            </table>     
          </td></tr>
        </table>
      </div>      
        <table class="counters ram" cellspasing="0" cellpadding="0" width="100%">
          <tr>
            <td align="center">
              <table width="100%">
                <tr><td align="center"><img src="src/img/repost-icon.png" width="25" height="25"></td></tr>
                <tr><td align="center">'.Counters($param['deep']['user_info']['counters']['repost']).'</td></tr>
                <tr><td align="center">Репостов</td></tr>
              </table>
            </td>          
            <td align="center">
              <table width="100%">
                <tr><td align="center"><img src="src/img/like-icon.png" width="25" height="25"></td></tr>
                <tr><td align="center">'.Counters($param['deep']['user_info']['counters']['like']).'</td></tr>
                <tr><td align="center">Лайков</td></tr>
              </table>
            </td>          
            <td align="center">
              <table width="100%">
                <tr><td align="center"><img src="src/img/album-icon.png" width="25" height="25"></td></tr>
                <tr><td align="center">'.Counters($param['deep']['user_info']['counters']['albums']).'</td></tr>
                <tr><td align="center">Альбомов</td></tr>
              </table>
            </td>          
            <td align="center">
              <table width="100%">
                <tr><td align="center"><img src="src/img/video-icon.png" width="25" height="25"></td></tr>
                <tr><td align="center">'.Counters($param['deep']['user_info']['counters']['videos']).'</td></tr>
                <tr><td align="center">Видео</td></tr>
              </table>
            </td>          
            <td align="center">
              <table width="100%">
                <tr><td align="center"><img src="src/img/audio-icon.png" width="25" height="25"></td></tr>
                <tr><td align="center">'.Counters($param['deep']['user_info']['counters']['audios']).'</td></tr>
                <tr><td align="center">Аудио</td></tr>
              </table>
            </td>          
            <td align="center">
              <table width="100%">
                <tr><td align="center"><img src="src/img/note-icon.png" width="25" height="25"></td></tr>
                <tr><td align="center">'.Counters($param['deep']['user_info']['counters']['notes']).'</td></tr>
                <tr><td align="center">Заметок</td></tr>
              </table>
            </td>          
            <td align="center">
              <table width="100%">
                <tr><td align="center"><img src="src/img/photo-icon.png" width="25" height="25"></td></tr>
                <tr><td align="center">'.Counters($param['deep']['user_info']['counters']['photos']).'</td></tr>
                <tr><td align="center">Фото</td></tr>
              </table>
            </td>          
            <td align="center">
             <table width="100%">
                <tr><td align="center"><img src="src/img/gift-icon.png" width="25" height="25"></td></tr>
                <tr><td align="center">'.Counters($param['deep']['user_info']['counters']['gifts']).'</td></tr>
                <tr><td align="center">Подарков</td></tr>
              </table>
            </td>          
            <td align="center">
              <table width="100%">
                <tr><td align="center"><img src="src/img/subscriber-icon.png" width="25" height="25"></td></tr>
                <tr><td align="center">'.Counters($param['deep']['user_info']['counters']['followers']).'</td></tr>
                <tr><td align="center">Подписчиков</td></tr>
              </table>
            </td>
          </tr>
        </table>      
        <div class="socio">
          <div class="bl block_1" style="border-bottom:1px solid #ccc; padding-bottom:10px;">
            <table width="100%">
              <tr>
                <td width="40%" style="text-transform: uppercase; font-size:14px;">Открытость</td>
                <td width="60%">
                  <table id="gh" width="100%" cellspacing="0" cellpadding="0">
                    <tr>
                      <td style="background: rgb(251, 190, 146); padding-top:2px; padding-bottom:2px;">Скрытый</td>
                      <td style="background: rgb(177, 198, 217); padding-top:2px; padding-bottom:2px;">Обычный</td>
                      <td style="background: rgb(211, 229, 155); padding-top:2px; padding-bottom:2px;">Доверчивый</td>
                    </td>                  
                  </table>
                  <table width="100%" style="margin-top:-3px;"><tr><td width="'.markers($param['deep']['stat']['pOpen']).'"></td><td width="30" height="15" style="background:url(src/img/trngl.jpg) no-repeat; background-size:100%;"></td></tr></table>                  
                </td>
              </tr>
            </table>
            <div class="text" style="font-size: 9px ; margin-top:2px; width:100%;">Показатель "ОТКРЫТОСТЬ" - отражает индивидуальную готовность участника социальной сети публиковать персональную информацию в общедоступных источниках. Большое значение показателя открытости является неоднозначным. С одной стороны высокая степень открытости может косвенно отражать честность человека и отсутсвие в его прошлом негативных поступков, фактов или событий, которые он должен скрывать. С другой стороны, большре значение показателя открытости может говорить о доверчивости человека, нести риск использования персональной информации тертьими лицами в противоправных целях, включая и причинения ущерба самому пользователю.</div>
          </div>       
          <div class="bl block_2" style="border-bottom:1px solid #ccc; margin-top:10px; padding-bottom:10px;">
            <table width="100%">
              <tr>
                <td width="40%" style="text-transform: uppercase; font-size:14px;">Информационная активность</td>
                <td width="60%">
                  <table id="gh" width="100%" cellspacing="0" cellpadding="0">
                    <tr>
                      <td style="background: rgb(251, 190, 146); padding-top:2px; padding-bottom:2px;">Пассивный</td>
                      <td style="background: rgb(177, 198, 217); padding-top:2px; padding-bottom:2px;">Активный</td>
                      <td style="background: rgb(211, 229, 155); padding-top:2px; padding-bottom:2px;">Гиперактивный</td>
                    </td>                  
                  </table>
                  <table width="100%" style="margin-top:-3px;"><tr><td width="'.markers($param['deep']['stat']['p_count']).'"></td><td width="30" height="15" style="background:url(src/img/trngl.jpg) no-repeat; background-size:100%;"></td></tr></table>                  
                </td>
              </tr>
            </table>
            <div class="text" style="font-size: 9px; margin-top:2px; width:100%;">Показатель "ИНФОРМАЦИОННАЯ АКТИВНОСТЬ" отражает интенсивность создания пользователем информации (контента) разного вида (сообщения, фото, альбомы, аудио, заметки). Большое значение показателя информационной активности говорит о стремлении к социальному общению, однако, несет риск разглашения критических данных.</div>
          </div>        
          <div class="bl block_3" style="border-bottom:1px solid #ccc; margin-top:10px; padding-bottom:10px;">
            <table width="100%">
              <tr>
                <td width="40%" style="text-transform: uppercase; font-size:14px;">Зрелость</td>
                <td width="60%">
                  <table id="gh" width="100%" cellspacing="0" cellpadding="0">
                    <tr>
                      <td style="background: rgb(251, 190, 146); padding-top:2px; padding-bottom:2px;">Новичок</td>
                      <td style="background: rgb(177, 198, 217); padding-top:2px; padding-bottom:2px;">Опытный</td>
                      <td style="background: rgb(211, 229, 155); padding-top:2px; padding-bottom:2px;">Профессионал</td>
                    </td>                  
                  </table>
                  <table width="100%" style="margin-top:-3px;"><tr><td width="'.markers($param['deep']['stat']['old_year']).'"></td><td width="30" height="15" style="background:url(src/img/trngl.jpg) no-repeat; background-size:100%;"></td></tr></table>                  
                </td>
              </tr>
            </table>
            <div class="text" style="font-size: 9px; margin-top:2px; width:100%;">Показатель "Зрелость" отражает общий стаж пребывания пользователя в социальной сети. Высокое значение показателя показывает опыт общения в сети и выступает катализатором (усилителем) других категорий риска.</div>
          </div>        
          <div class="bl block_4" style="border-bottom:1px solid #ccc; margin-top:10px; padding-bottom:10px;">
            <table width="100%">
              <tr>
                <td width="40%" style="text-transform: uppercase; font-size:14px;">Лидерство</td>
                <td width="60%">
                  <table id="gh" width="100%" cellspacing="0" cellpadding="0">
                    <tr>
                      <td style="background: rgb(251, 190, 146); padding-top:2px; padding-bottom:2px;">Новичок</td>
                      <td style="background: rgb(177, 198, 217); padding-top:2px; padding-bottom:2px;">Опытный</td>
                      <td style="background: rgb(211, 229, 155); padding-top:2px; padding-bottom:2px;">Профессионал</td>
                    </td>                  
                  </table>
                  <table width="100%" style="margin-top:-3px;"><tr><td width="'.markers($param['deep']['stat']['l_count']).'"></td><td width="30" height="15" style="background:url(src/img/trngl.jpg) no-repeat; background-size:100%;"></td></tr></table>          
                </td>
              </tr>
            </table>
            <div class="text" style="font-size: 9px; margin-top:2px; width:100%;">Показатель "ЛИДЕРСТВО" отражает способность заинтересовывать других пользователей публикуемым контентом и вовлеченностью их в социальное общение с данным человеком. Большое значение показателя "ЛИДЕРСТВО" показывает возможность формировани неформальных групп с самыми разнообразными интересами и намерениями.</div>
          </div>        
          <div class="bl block_5" style="margin-top:10px; padding-bottom:10px;">
            <table width="100%">
              <tr>
                <td width="40%" style="text-transform: uppercase; font-size:14px;">Интернет-активность</td>
                <td width="60%">
                  <table id="gh" width="100%" cellspacing="0" cellpadding="0">
                    <tr>
                      <td style="background: rgb(251, 190, 146); padding-top:2px; padding-bottom:2px;">Периодически</td>
                      <td style="background: rgb(177, 198, 217); padding-top:2px; padding-bottom:2px;">Системно</td>
                      <td style="background: rgb(211, 229, 155); padding-top:2px; padding-bottom:2px;">Постоянно</td>
                    </td>                  
                  </table>
                  <table width="100%" style="margin-top:-3px;"><tr><td width="'.markers($param['deep']['stat']['inDay']).'"></td><td width="30" height="15" style="background:url(src/img/trngl.jpg) no-repeat; background-size:100%;"></td></tr></table>                  
                </td>
              </tr>
            </table>
            <div class="text" style="font-size: 9px; margin-top:2px; width:100%;">Показатель "Интернет-активность" отражает интенсивность пребывания пользователя в социальной сети. Большое значение показателя "Интернет-активность" показывает потенциальный риск Интернет-зависимости и нецелевого использования доступа в Интернет.</div>
          </div>        
        </div>
      </div>
    ';
    $pdf->writeHTML($html, 2);
  }
  
  
  
  
  
  if($param['deep']['wordCloud']){
    $pdf->AddPage('','','1','i','on');
    $html = '<div class="ram">
      <div class="top-head">
          <table width="100%">
            <tr><td style="font-size:22px; color: rgb(96, 144, 184);">Ключевые слова</td><td style="text-align:right; font-size: 20px;">'.$param['info']['user_info']['fio'].'</td></tr>
            <tr><td></td><td style="text-align: right; padding-top:5px; padding-bottom:3px; font-size:10px; text-align:right;">Идентификатор пользователя - '.$param['info']['user_info']['uid'].'</td></tr>
            <tr><td></td><td style="text-align: right; border-bottom:1px solid #ccc; padding-bottom:5px;">
              <table>
                <tr>
                  <td><img src="src/img/date-icon.png" width="25" height="25" style="float:left"></td>
                  <td><span style="padding-left:5px; float:left">'.rus_date().'</span></td>
                </tr>
              </table>
            </td></tr>
          </table>
        </div>
      <div class="keywords" style="padding-top:50px;">
    <table width="100%">';
    for($i = 0; $i < count($param['deep']['wordCloud']); $i++){
      $key = key($param['deep']['wordCloud']);
      if($i > 15){break;}
      $html .= '<tr><td width="50%" style="padding:8px; background:rgb(63, 195, 128); color:#fff;">'.$key.'</td><td width="50%" style="padding:8px; background:rgb(63, 195, 128); color:#fff;">'.$param['deep']['wordCloud'][$key].'</td></tr>';
      next($param['deep']['wordCloud']);
    }
    $html .= '</table></div></div>';
    $pdf->writeHTML($html, 2);
  }
  
  
  
  
  
  $dictionary = array("Персональный", "Алкоголизм и наркомания", "Представительство в религиозных сектах", "Игромания", "Лица радикального направления", "Кредитомания", "Криминал", "Перверсия", "Подозрительные привычки");
  if($param['deep']['userDic']){
    $pdf->AddPage('','','1','i','on');
    $html = '<div class="ram">
      <div class="top-head">
        <table width="100%">
          <tr><td style="font-size:22px; color: rgb(96, 144, 184);">Контент-анализ публичных сообщений пользователя</td><td style="text-align:right; font-size: 20px;">'.$param['info']['user_info']['fio'].'</td></tr>
          <tr><td></td><td style="text-align: right; padding-top:5px; padding-bottom:3px; font-size:10px; text-align:right;">Идентификатор пользователя - '.$param['info']['user_info']['uid'].'</td></tr>
          <tr><td></td><td style="text-align: right; border-bottom:1px solid #ccc; padding-bottom:5px;">
            <table>
              <tr>
                <td><img src="src/img/date-icon.png" width="25" height="25" style="float:left"></td>
                <td><span style="padding-left:5px; float:left">'.rus_date().'</span></td>
              </tr>
            </table>     
          </td></tr>
        </table>
      </div>      
      <div style="margin-top:50px; width:100%;">
        <table width="100%">';      
        for($j = 0; $j < count($dictionary); $j++){
          $k = $dictionary[$j];
          $flag = false;
          $slova = "";
          $predl = "";
          foreach($param['deep']['userDic'] as $u => $v){          
            $rowName = $param['deep']['userDic'][$u]['name'];          
            if($k == $rowName) {
              foreach($param['deep']['userDic'][$u]['words'] as $key => $value){
                $slova .= $key." ";
                $predl = $value[0];
              }        
              $flag = true;
            }
          }          
          if($flag === true){
            $html .= '<tr>
              <td style="color:#fff; background: rgb(231, 76, 75); padding:8px;" width="33%">'.$k.'</td>
              <td style="color:#fff; background: rgb(231, 76, 75); padding:8px;" width="33%">'.$slova.'</td>
              <td style="color:#fff; background: rgb(231, 76, 75); padding:8px;" width="33%">'.$predl.'</td>
            </tr>';
          }else{
            $html .= '<tr>
              <td style="color:#fff; background: rgb(63, 195, 128); padding:8px;" width="33%">'.$k.'</td>
              <td style="color:#fff; background: rgb(63, 195, 128); padding:8px;" width="33%">Ничего не найдено</td>
              <td style="color:#fff; background: rgb(63, 195, 128); padding:8px;" width="33%"></td>
            </tr>';
          }        
        }      
        $html .= '</table>
      </div>
    </div>';
    $pdf->writeHTML($html, 2);
  }
  
  
  
  
  
  
  
  if($param['deep']['friendDeep']){
    $pdf->AddPage('','','1','i','on');
    $html = '<div class="ram">
      <div class="top-head">
        <table width="100%">
          <tr><td style="font-size:22px; color: rgb(96, 144, 184);">Контент-анализ публичных сообщений участников круга общения</td><td style="text-align:right; font-size: 20px;">'.$param['info']['user_info']['fio'].'</td></tr>
          <tr><td></td><td style="text-align: right; padding-top:5px; padding-bottom:3px; font-size:10px; text-align:right;">Идентификатор пользователя - '.$param['info']['user_info']['uid'].'</td></tr>
          <tr><td></td><td style="text-align: right; border-bottom:1px solid #ccc; padding-bottom:5px;">
            <table>
              <tr>
                <td><img src="src/img/date-icon.png" width="25" height="25" style="float:left"></td>
                <td><span style="padding-left:5px; float:left">'.rus_date().'</span></td>
              </tr>
            </table>     
          </td></tr>
        </table>
      </div>      
      <div style="margin-top:50px; width:100%;">
        <table width="100%">';    
        for($j = 0; $j < count($dictionary); $j++){
          $k = $dictionary[$j];
          $flag = false;
          $slova = "";
          foreach($param['deep']['friendDeep'] as $u => $v){
            foreach($param['deep']['friendDeep'][$u]['Words'] as $b => $t){
              $rowName = $param['deep']['friendDeep'][$u]['Words'][$b]['name'];
              
              if($k == $rowName && count($param['deep']['friendDeep'][$u]['Words'][$b]['words']) > 0) {
                foreach($param['deep']['friendDeep'][$u]['Words'][$b]['words'] as $key => $value){
                  $slova .= $key." ";   
                }        
                $flag = true;
              }
            }
          }
          if($flag === true){
            $html .= '<tr>
              <td style="color:#fff; background: rgb(231, 76, 75); padding:8px;" width="50%">'.$k.'</td>
              <td style="color:#fff; background: rgb(231, 76, 75); padding:8px;" width="50%">'.$slova.'</td>
            </tr>';
          }else{
            $html .= '<tr>
              <td style="color:#fff; background: rgb(63, 195, 128); padding:8px;" width="50%">'.$k.'</td>
              <td style="color:#fff; background: rgb(63, 195, 128); padding:8px;" width="50%">Ничего не найдено</td>
            </tr>';
          }
        }     
        $html .= '</table>
      </div>
    </div>';
    $pdf->writeHTML($html, 2);
  }
  
  
  
  
  
  
  
  if($param['deep']['stat']['temp']){
    $pdf->AddPage('','','1','i','on');
    $html = '<div class="ram">
     <div class="top-head">
        <table width="100%">
          <tr><td style="font-size:22px; color: rgb(96, 144, 184);">Психологический темперамент</td><td style="text-align:right; font-size: 20px;">'.$param['info']['user_info']['fio'].'</td></tr>
          <tr><td></td><td style="text-align: right; padding-top:5px; padding-bottom:3px; font-size:10px; text-align:right;">Идентификатор пользователя - '.$param['info']['user_info']['uid'].'</td></tr>
          <tr><td></td><td style="text-align: right; border-bottom:1px solid #ccc; padding-bottom:5px;">
            <table>
              <tr>
                <td><img src="src/img/date-icon.png" width="25" height="25" style="float:left"></td>
                <td><span style="padding-left:5px; float:left">'.rus_date().'</span></td>
              </tr>
            </table>     
          </td></tr>
        </table>
      </div>      
      <div class="temperament" style="margin-top:50px; width:100%;">
        <div style="width:100%; padding-top:30px;">
          <table width="100%">
            <tr>
              <td width="70%"></td>
              <td width="30%" height="100">              
                <div style="width:100%; padding-top:3px;">
                  <table>
                    <tr>
                      <td style="height:25px; width: 25px; background:rgb(251, 190, 146);"></td>
                      <td style="height:25px; width: 70px; padding-left:10px; color:rgb(96, 144, 184);">Сангвиник</td>
                    </tr>
                  </table>                
                </div>
                <div style="width:100%; padding-top:3px;">
                  <table>
                    <tr>
                      <td style="height:25px; width: 25px; background:rgb(177, 198, 217);"></td>
                      <td style="height:25px; width: 70px; padding-left:10px; color:rgb(96, 144, 184);">Меланхолик</td>
                    </tr>
                  </table>
                </div>
                <div style="width:100%; padding-top:3px;">
                  <table>
                    <tr>
                      <td style="height:25px; width: 25px; background:rgb(211, 229, 155);"></td>
                      <td style="height:25px; width: 70px; padding-left:10px; color:rgb(96, 144, 184);">Флегматик</td>
                    </tr>
                  </table>                
                </div>
                <div style="width:100%; padding-top:3px;">
                  <table>
                    <tr>
                      <td style="height:25px; width: 25px; background:rgb(63, 195, 128);"></td>
                      <td style="height:25px; width: 70px; padding-left:10px; color:rgb(96, 144, 184);">Холерик</td>
                    </tr>
                  </table>                
                </div>
              </td>
            </tr>          
          </table>
          <div style="width:350px; padding-left:40px; padding-bottom:40px;">
            <table width="100%" ><tr><td width="'.$param['deep']['stat']['temp'][1][1].'%" height="40" style="background:rgb(251, 190, 146);"></td><td style="padding-left:10px;">'.$param['deep']['stat']['temp'][1][1].'%</td></tr></table>
            <table width="100%" ><tr><td width="'.$param['deep']['stat']['temp'][2][1].'%" height="40" style="background:rgb(177, 198, 217);"></td><td style="padding-left:10px;">'.$param['deep']['stat']['temp'][2][1].'%</td></tr></table>
            <table width="100%" ><tr><td width="'.$param['deep']['stat']['temp'][3][1].'%" height="40" style="background:rgb(211, 229, 155);"></td><td style="padding-left:10px;">'.$param['deep']['stat']['temp'][3][1].'%</td></tr></table>
            <table width="100%" ><tr><td width="'.$param['deep']['stat']['temp'][4][1].'%" height="40" style="background:rgb(63, 195, 128);"></td><td style="padding-left:10px;">'.$param['deep']['stat']['temp'][4][1].'%</td></tr></table>
          </div>
        </div>
      </div>
    </div>';
    $pdf->writeHTML($html, 2);
  }
  
  
  
  
  
  
  
  if($param['deep']['device']){
    $pdf->AddPage('','','1','i','on');
    $html = '<div class="ram">
      <div class="top-head">
        <table width="100%">
          <tr><td style="font-size:22px; color: rgb(96, 144, 184);">Используемые устройства</td><td style="text-align:right; font-size: 20px;">'.$param['info']['user_info']['fio'].'</td></tr>
          <tr><td></td><td style="text-align: right; padding-top:5px; padding-bottom:3px; font-size:10px; text-align:right;">Идентификатор пользователя - '.$param['info']['user_info']['uid'].'</td></tr>
          <tr><td></td><td style="text-align: right; border-bottom:1px solid #ccc; padding-bottom:5px;">
            <table>
              <tr>
                <td><img src="src/img/date-icon.png" width="25" height="25" style="float:left"></td>
                <td><span style="padding-left:5px; float:left">'.rus_date().'</span></td>
              </tr>
            </table>     
          </td></tr>
        </table>
      </div>      
        <div class="device" style="padding-top:50px;">
          <table width="100%" cellspasing="0" cellpadding="0" border="0">
            <tr style="background: rgb(251, 190, 146);">
              <td width="35%" height="35" style="border:0; padding-left:15px;">Устройство</td>
              <td width="65%" height="35" style="border:0; padding-left:15px;">Количество использований</td>
            </tr>
            <tr style="background: rgb(239, 239, 239);">
              <td width="35%" height="35" style="border:0; padding-left:15px;"><img src="src/img/device/pc.png" height="15" style="margin-right:5px;">Компьютер</td>
              <td width="65%" height="35" style="border:0; padding-left:15px;">'.$param['deep']['device']['pc'].'</td>
            </tr>
            <tr style="background: rgb(239, 239, 239);">
              <td width="35%" height="35" style="border:0; padding-left:15px;"><img src="src/img/device/android.png" height="15" style="margin-right:5px;">Android</td>
              <td width="65%" height="35" style="border:0; padding-left:15px;">'.$param['deep']['device']['android'].'</td>
            </tr>
            <tr style="background: rgb(239, 239, 239);">
              <td width="35%" height="35" style="border:0; padding-left:15px;"><img src="src/img/device/iphone.png" height="15" style="margin-right:5px;">Iphone</td>
              <td width="65%" height="35" style="border:0; padding-left:15px;">'.$param['deep']['device']['iphone'].'</td>
            </tr>
            <tr style="background: rgb(239, 239, 239);">
              <td width="35%" height="35" style="border:0; padding-left:15px;"><img src="src/img/device/instagram.png" height="15" style="margin-right:5px;">Instagram</td>
              <td width="65%" height="35" style="border:0; padding-left:15px;">'.$param['deep']['device']['instagram'].'</td>
            </tr>
            <tr style="background: rgb(239, 239, 239);">
              <td width="35%" height="35" style="border:0; padding-left:15px;"><img src="src/img/device/mobile.png" height="15" style="margin-right:5px;">Мобильный браузер</td>
              <td width="65%" height="35" style="border:0; padding-left:15px;">'.$param['deep']['device']['mobile'].'</td>
            </tr>
            <tr style="background: rgb(239, 239, 239);">
              <td width="35%" height="35" style="border:0; padding-left:15px;"><img src="src/img/device/ipad.png" height="15" style="margin-right:5px;">iPad</td>
              <td width="65%" height="35" style="border:0; padding-left:15px;">'.$param['deep']['device']['ipad'].'</td>
            </tr>
            <tr style="background: rgb(239, 239, 239);">
              <td width="35%" height="35" style="border:0; padding-left:15px;"><img src="src/img/device/wphone.png" height="15" style="margin-right:5px;">Windows Phone</td>
              <td width="65%" height="35" style="border:0; padding-left:15px;">'.$param['deep']['device']['wphone'].'</td>
            </tr>
          </table>
        </div>
    </div>';
    $pdf->writeHTML($html, 2);
  }
  
  
  
  
  
  if($param['deep']['friendDeep']){
    $pdf->AddPage('','','1','i','on');
    $friend = array();
    foreach($param['deep']['friendDeep'] as $key => $value){
      array_push($friend, $value);
    }
    $html = '<div class="ram">
      <div class="top-head">
        <table width="100%">
          <tr><td style="font-size:22px; color: rgb(96, 144, 184);">Анализ круга близких друзей</td><td style="text-align:right; font-size: 20px;">'.$param['info']['user_info']['fio'].'</td></tr>
          <tr><td></td><td style="text-align: right; padding-top:5px; padding-bottom:3px; font-size:10px; text-align:right;">Идентификатор пользователя - '.$param['info']['user_info']['uid'].'</td></tr>
          <tr><td></td><td style="text-align: right; border-bottom:1px solid #ccc; padding-bottom:5px;">
            <table>
              <tr>
                <td><img src="src/img/date-icon.png" width="25" height="25" style="float:left"></td>
                <td><span style="padding-left:5px; float:left">'.rus_date().'</span></td>
              </tr>
            </table>     
          </td></tr>
        </table>
      </div>      
      <div class="blizpeople" style="padding-top:20px;">
        <table style="width: 100%; background:url(src/img/1.png) no-repeat; background-size:100%; border:0; font-size:10px;">
          <tr>
             <td style="border: 0; width: 25%; height:80px; text-align:center;"><table><tr><td align="center"><img src="'.remake_img_url($friend[0]['user_info']['photo']).'" width="80" align="center"></td></tr><tr><td align="center">'.$friend[0]['user_info']['fio'].'</td></tr><tr><td align="center">'.$friend[0]['close_val'].'</td></tr><tr><td align="center">'.$friend[0]['user_info']['uid'].'</td></tr></table></td>
             <td style="border: 0; width: 25%; height:80px; text-align:center;"><table><tr><td align="center"><img src="'.remake_img_url($friend[1]['user_info']['photo']).'" width="80" align="center"></td></tr><tr><td align="center">'.$friend[1]['user_info']['fio'].'</td></tr><tr><td align="center">'.$friend[1]['close_val'].'</td></tr><tr><td align="center">'.$friend[1]['user_info']['uid'].'</td></tr></table></td>
             <td style="border: 0; width: 25%; height:80px; text-align:center;"><table><tr><td align="center"><img src="'.remake_img_url($friend[2]['user_info']['photo']).'" width="80" align="center"></td></tr><tr><td align="center">'.$friend[2]['user_info']['fio'].'</td></tr><tr><td align="center">'.$friend[2]['close_val'].'</td></tr><tr><td align="center">'.$friend[2]['user_info']['uid'].'</td></tr></table></td>
             <td style="border: 0; width: 25%; height:80px; text-align:center;"><table><tr><td align="center"><img src="'.remake_img_url($friend[3]['user_info']['photo']).'" width="80" align="center"></td></tr><tr><td align="center">'.$friend[3]['user_info']['fio'].'</td></tr><tr><td align="center">'.$friend[3]['close_val'].'</td></tr><tr><td align="center">'.$friend[3]['user_info']['uid'].'</td></tr></table></td>
          </tr>
          <tr>
             <td style="border: 0; width: 25%; height:80px; text-align:center;"><table><tr><td align="center"><img src="'.remake_img_url($friend[4]['user_info']['photo']).'" width="80" align="center"></td></tr><tr><td align="center">'.$friend[4]['user_info']['fio'].'</td></tr><tr><td align="center">'.$friend[4]['close_val'].'</td></tr><tr><td align="center">'.$friend[4]['user_info']['uid'].'</td></tr></table></td>
             <td style="border: 0; width: 25%; text-align:center; vertical-align:top;" colspan="2" rowspan="2"><img style="margin-top:40px;" width="150" src="'.remake_img_url($param['info']['user_info']['photo']).'"></td>
             <td style="border: 0; width: 25%; height:80px; text-align:center;"><table><tr><td align="center"><img src="'.remake_img_url($friend[5]['user_info']['photo']).'" width="80" align="center"></td></tr><tr><td align="center">'.$friend[5]['user_info']['fio'].'</td></tr><tr><td align="center">'.$friend[5]['close_val'].'</td></tr><tr><td align="center">'.$friend[5]['user_info']['uid'].'</td></tr></table></td>
          </tr>
          <tr>
             <td style="border: 0; width: 25%; text-align:center;"><table><tr><td align="center"><img src="'.remake_img_url($friend[6]['user_info']['photo']).'" width="80" align="center"></td></tr><tr><td align="center">'.$friend[6]['user_info']['fio'].'</td></tr><tr><td align="center">'.$friend[6]['close_val'].'</td></tr><tr><td align="center">'.$friend[6]['user_info']['uid'].'</td></tr></table></td>
             <td style="border: 0; width: 25%; text-align:center;" colspan="2"><table><tr><td align="center"><img src="'.remake_img_url($friend[7]['user_info']['photo']).'" width="80" align="center"></td></tr><tr><td align="center">'.$friend[7]['user_info']['fio'].'</td></tr><tr><td align="center">'.$friend[6]['close_val'].'</td></tr><tr><td align="center">'.$friend[7]['user_info']['uid'].'</td></tr></table></td>
          </tr>
          <tr>
             <td style="border: 0; width: 25%; height:80px; text-align:center;"><table><tr><td align="center"><img src="'.remake_img_url($friend[8]['user_info']['photo']).'" width="80" align="center"></td></tr><tr><td align="center">'.$friend[8]['user_info']['fio'].'</td></tr><tr><td align="center">'.$friend[8]['close_val'].'</td></tr><tr><td align="center">'.$friend[8]['user_info']['uid'].'</td></tr></table></td>
             <td style="border: 0; width: 25%; height:80px; text-align:center;"><table><tr><td align="center"><img src="'.remake_img_url($friend[9]['user_info']['photo']).'" width="80" align="center"></td></tr><tr><td align="center">'.$friend[9]['user_info']['fio'].'</td></tr><tr><td align="center">'.$friend[9]['close_val'].'</td></tr><tr><td align="center">'.$friend[9]['user_info']['uid'].'</td></tr></table></td>
             <td style="border: 0; width: 25%; height:80px; text-align:center;"><table><tr><td align="center"><img src="'.remake_img_url($friend[10]['user_info']['photo']).'" width="80" align="center"></td></tr><tr><td align="center">'.$friend[10]['user_info']['fio'].'</td></tr><tr><td align="center">'.$friend[10]['close_val'].'</td></tr><tr><td align="center">'.$friend[10]['user_info']['uid'].'</td></tr></table></td>
             <td style="border: 0; width: 25%; height:80px; text-align:center;"><table><tr><td align="center"><img src="'.remake_img_url($friend[11]['user_info']['photo']).'" width="80" align="center"></td></tr><tr><td align="center">'.$friend[11]['user_info']['fio'].'</td></tr><tr><td align="center">'.$friend[11]['close_val'].'</td></tr><tr><td align="center">'.$friend[11]['user_info']['uid'].'</td></tr></table></td>
          </tr>
      </table>
      </div>
    </div>';
    $pdf->writeHTML($html, 2);
  }
  
  
  
  
  
  
  
  if($param['info']['stat']){
    $m = $param['info']['stat']['friend_gender']['m'];
    $w = $param['info']['stat']['friend_gender']['w'];  
    $all = $param['info']['stat']['friend_gender']['m'] + $param['info']['stat']['friend_gender']['w'];
    $m = $m * 100 / $all;
    $w = $w * 100 / $all;  
    $pdf->AddPage('','','1','i','on');
      $html = '<div class="ram">
      <div class="top-head">
        <table width="100%">
          <tr><td style="font-size:22px; color: rgb(96, 144, 184);">Статистика круга общение по половому признаку</td><td style="text-align:right; font-size: 20px;">'.$param['info']['user_info']['fio'].'</td></tr>
          <tr><td></td><td style="text-align: right; padding-top:5px; padding-bottom:3px; font-size:10px; text-align:right;">Идентификатор пользователя - '.$param['info']['user_info']['uid'].'</td></tr>
          <tr><td></td><td style="text-align: right; border-bottom:1px solid #ccc; padding-bottom:5px;">
            <table>
              <tr>
                <td><img src="src/img/date-icon.png" width="25" height="25" style="float:left"></td>
                <td><span style="padding-left:5px; float:left">'.rus_date().'</span></td>
              </tr>
            </table>
          </td></tr>
        </table>
      </div>  
      <div class="krug_obshenia" style="margin-top:50px; width:100%;">
        <div style="width:100%;">
          <table width="100%">
            <tr>
              <td width="70%"></td>
              <td width="30%" height="100">
                <div style="width:100%; height: 70px;"></div>
                <div style="width:100%; padding-top:3px;">
                  <table>
                    <tr>
                      <td style="height:30px; width: 50px; background:rgb(80, 114, 153);"></td>
                      <td style="height:30px; width: 70px; padding-left:10px;"><img src="src/img/male.png" width="20" style="margin-right:3px;">Мужчины</td>
                    </tr>
                  </table>                
                </div>
                <div style="width:100%; padding-top:3px;">
                  <table>
                    <tr>
                      <td style="height:30px; width: 50px; background:rgb(230, 57, 23);"></td>
                      <td style="height:30px; width: 70px; padding-left:10px;"><img src="src/img/female.png" width="20" style="margin-right:3px;">Женщины</td>
                    </tr>
                  </table>
                </div>
              </td>
            </tr>          
          </table>        
          <div style="width:350px; padding-left:40px; padding-bottom:40px;">
            <div style="width:100%; background: #fff;">
              <div style="width:'.round($m, 2).'%; height:40px; background:rgb(80, 114, 153); text-align:center; padding-top:20px;">'.round($m, 2).'%</div>      
            </div>
            <div style="width:100%; background: #fff; margin-top:3px;">
              <div style="width:'.round($w, 2).'%; height:40px; background:rgb(230, 57, 23); text-align:center; padding-top:20px;">'.round($w, 2).'%</div>
            </div>          
          </div>
        </div>      
      </div>
    </div>';
    $pdf->writeHTML($html, 2);
  }
  
  
  
  
  
  function cmp($a, $b){
    if ($a['count'] == $b['count']) {
        return 0;
    }
    return ($a['count'] < $b['count']) ? 1 : -1;
  }
  $vuz = array();
  
  if($param['deep']['frnUniverStat']){
    foreach($param['deep']['frnUniverStat'] as $key => $value){
      array_push($vuz, $value);
    }
    usort($vuz, cmp);
    $pdf->AddPage('','','1','i','on');
    $html = '<div class="ram">
      <div class="top-head">
        <table width="100%">
          <tr><td style="font-size:22px; color: rgb(96, 144, 184);">Статистика учебных заведений</td><td style="text-align:right; font-size: 20px;">'.$param['info']['user_info']['fio'].'</td></tr>
          <tr><td></td><td style="text-align: right; padding-top:5px; padding-bottom:3px; font-size:10px; text-align:right;">Идентификатор пользователя - '.$param['info']['user_info']['uid'].'</td></tr>
          <tr><td></td><td style="text-align: right; border-bottom:1px solid #ccc; padding-bottom:5px;">
            <table>
              <tr>
                <td><img src="src/img/date-icon.png" width="25" height="25" style="float:left"></td>
                <td><span style="padding-left:5px; float:left">'.rus_date().'</span></td>
              </tr>
            </table>
          </td></tr>
        </table>
      </div>
      <div class="vuzstat" style="padding-top:10px;">
        <table width="100%" cellspasing="0" cellpadding="0" border="0">';
          for($i = 0; $i < count($vuz); $i++){
            $html .= '
               <tr style="background: rgb(239, 239, 239);">
                <td width="65%" height="35" style="border:0; padding-left:15px;">'.$vuz[$i]['name'].'</td>
                <td width="35%" height="35" style="border:0; padding-left:15px;">'.$vuz[$i]['count'].'</td>
              </tr>';
          }        
          $html .= '</table>
      </div>
    </div>';
    $pdf->writeHTML($html, 2);
  }
  
  
  
  
  
  if($param['frnPhoto']['FriendsPhotos']){
    $c = 0;
    $pdf->AddPage('','','1','i','on');
    $html = '<div class="ram">
      <div class="top-head">
        <table width="100%">
          <tr><td style="font-size:22px; color: rgb(96, 144, 184);">Анализ фото друзей</td><td style="text-align:right; font-size: 20px;">'.$param['info']['user_info']['fio'].'</td></tr>
          <tr><td></td><td style="text-align: right; padding-top:5px; padding-bottom:3px; font-size:10px; text-align:right;">Идентификатор пользователя - '.$param['info']['user_info']['uid'].'</td></tr>
          <tr><td></td><td style="text-align: right; border-bottom:1px solid #ccc; padding-bottom:5px;">
            <table>
              <tr>
                <td><img src="src/img/date-icon.png" width="25" height="25" style="float:left"></td>
                <td><span style="padding-left:5px; float:left">'.rus_date().'</span></td>
              </tr>
            </table>
          </td></tr>
        </table>
      </div>
      <div style="padding-top:10px;">';
     foreach($param['frnPhoto']['FriendsPhotos'] as $key => $value){
       if($c > 20) break;
       $html .= '<table style="font-size: 12px; width:100%; padding-top:20px;"><tr><td>'.$value['first_name'].' '.$value['last_name'].'</td></tr></table>';
       $html .= '<table width="100%" cellspasing="0" cellpadding="0" border="0"><tr>';
       for($i = 0; $i < 5; $i++){
         if(!$value['photos'][$i]) break;
         $html .= '<td><img src="'.remake_img_url($value['photos'][$i]).'" height="80"></td>';
       }
       $html .= '</tr></table><div style="width:100%; height:1px; border-bottom:1px solid #000;"></div>';
       $c++;
     }
    $html .= '</div></div>';
    $pdf->writeHTML($html, 2);
  }
 
 
 
 
 
 
 
  function getHeader($title, $fio, $uid){
    return '<div class="ram"><div class="top-head"><table width="100%"><tr><td style="font-size:22px; color: rgb(96, 144, 184);">'.$title.'</td><td style="text-align:right; font-size: 20px;">'.$fio.'</td></tr><tr><td></td><td style="text-align: right; padding-top:5px; padding-bottom:3px; font-size:10px; text-align:right;">Идентификатор пользователя - '.$uid.'</td></tr><tr><td></td><td style="text-align: right; border-bottom:1px solid #ccc; padding-bottom:5px;"><table><tr><td><img src="src/img/date-icon.png" width="25" height="25" style="float:left"></td><td><span style="padding-left:5px; float:left">'.rus_date().'</span></td></tr></table></td></tr></table></div><div style="padding-top:30px;">';
  }; 
  
  if($param['group']['Group']){
    $pdf->AddPage('','','1','i','on');
    $html = getHeader('Анализ групп', $param['info']['user_info']['fio'], $param['info']['user_info']['uid']);
    for($i = 0; $i < count($param['group']['Group']); $i++){
      if(gettype($param['group']['Group'][$i]) !== "array" || $i > 9) break;
      if($i > 0 && ($i % 5) === 0) {
        $html .= '</div></div>';
        $pdf->writeHTML($html, 2);
        $pdf->AddPage('','','1','i','on');          
        $html = getHeader('Анализ групп', $param['info']['user_info']['fio'], $param['info']['user_info']['uid']);
      }        
      $html .= '<div style="width:100%; background: #ccc;">';
      $html .= '<div style="width:100%; background: #466991; padding:3px;"><table><tr><td><img src="'.remake_img_url($param['group']['Group'][$i]['img']).'" height="25" style="margin-right:7px;"></td><td>'.$param['group']['Group'][$i]['name'].'</td></tr></table></div>';
      $html .= '<div style="width:100%; padding:3px; font-size: 9px;">';
      $users = $param['group']['Group'][$i]['users'];
        for($j = 0; $j < count($users); $j++){
          $html .= $users[$j]['first_name'].' '.$users[$j]['last_name'].', ';
        }
      $html .= '</div>';
      $html .= '</div>';
    }
    $html .= '</div></div>';
    $pdf->writeHTML($html, 2);
  } 

  
  
  
  
  
  
  $color = array("blue", "red", "yellow", "green", "black");
  $colorRGB = array("148, 186, 254", "245, 97, 90", "234, 192, 83", "160, 190, 49", "129, 129, 129");
  if($param['deep']['GEO']){
    $pdf->AddPage('','','1','i','on');
    $img = '';
    $html = getHeader('География', $param['info']['user_info']['fio'], $param['info']['user_info']['uid']);
    $html .= '<img width="100%" src="http://maps.google.com/maps/api/staticmap?size=640x400&key=AIzaSyBjUUN8XHfAMOfNeEIJixT9zGSWXrlCyz4&language=ru&scale=2&format=png32';
      for($i=0; $i < 5; $i++){
        $img .= '&markers=label:'.($i + 1).'|color:'.$color[$i].'|'.$param['deep']['GEO'][$i]['geo']['lat'].','.$param['deep']['GEO'][$i]['geo']['lng'];
      }
    $html .= $img.'">';
    $html .= '
      <table width="100%">
        <tr><td style="padding:3px; padding-left:10px;">Номер</td><td style="padding:3px; padding-left:10px;">Название города</td><td style="padding:3px; padding-left:10px;">Количество пользователей</td></tr>';
    for($i = 0; $i < 5; $i++){
      $html .= '<tr style="background:rgb('.$colorRGB[$i].');"><td style="padding:3px; padding-left:10px;">'.($i+1).'</td><td style="padding:3px; padding-left:10px;">'.$param['deep']['GEO'][$i]['name'].'</td><td style="padding:3px; padding-left:10px;">'.$param['deep']['GEO'][$i]['count'].'</td></tr>';
    }
    $html .= '</table></div></div>';
    $pdf->writeHTML($html, 2);
  }
  
  
  
  
  
  
  if($param['info']['friends_list']){
    $pdf->AddPage('','','1','i','on');
    $html = getHeader('Список друзей', $param['info']['user_info']['fio'], $param['info']['user_info']['uid']);
    for($i = 0; $i < count($param['info']['friends_list']); $i++){
      if($i > 70) break;
      $html .= '<div style="float:left; width: 150px; height: 140px; margin-left:25px; padding-top:5px; text-align:center;"><img style="max-height:140px; max-width:150px;" src="'.remake_img_url($param['info']['friends_list'][$i]['photo']).'"><div style="width:100%;">'.$param['info']['friends_list'][$i]['fio'].'</div><div style="width:100%;">'.$param['info']['friends_list'][$i]['uid'].'</div></div>';
    }
    $html .= '</div></div>';
    $pdf->writeHTML($html, 2);
  }
  
  
  
  
  
  
  $html = '';
  $html .= '</section></body></html>';
  $pdf->writeHTML($html, 2);
	//$pdf->Output('report.pdf', 'I');
	$pdf->Output('report.pdf', 'D');
?>