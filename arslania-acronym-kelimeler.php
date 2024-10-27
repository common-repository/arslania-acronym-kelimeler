<?php
/*
Plugin Name: Arslania Acronym Kelimeler
Plugin URI: http://www.arslania.com/arslania-acronym-kelimeler.html
Description: Yazılarınızda yer alan kısaltılmış kelimeleri 'acronym' etiketiyle kolay bir şekilde kullanmanızı sağlar.
Author: Ali Arslan
Version: 1.0
Author URI: http://www.arslania.com
*/

add_filter('the_content', 'acronymWordsKelime', 1);
add_action('admin_menu', 'acronymWordsPanel'); 
register_activation_hook( __FILE__, 'acronymWordsYukle' );
register_deactivation_hook (__FILE__, 'acronymWordsKaldir');
/* Veritabanı tablo ayarları CISS!!  */
$my_table ="arslaniacronym";

function acronymWordsPanel(){   
      if (function_exists('add_options_page')) {
         add_options_page('Acronym Kelimeler', 'Acronym Kelimeler', 8, basename(__FILE__), 'acronymWordsMenu');
      }
   }

   
function acronymWordsYukle(){

	global $wpdb,$my_table;
	 
	require_once(ABSPATH . 'wp-admin/upgrade-functions.php');
		
	$table_name= $wpdb->prefix."arslaniacronym";	
	
	$sql = " CREATE TABLE $table_name(
		id mediumint( 9 ) NOT NULL AUTO_INCREMENT ,
		text tinytext NOT NULL ,
		url tinytext NOT NULL ,
		anchortext tinytext NOT NULL ,
		rel tinytext NOT NULL ,
		type tinytext NOT NULL ,
		visits tinytext NOT NULL ,
		PRIMARY KEY ( `id` )	
	) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;";
	
	$wpdb->query($sql);

   }
   
function acronymWordsKaldir(){
   
	/*global $wpdb;	
	global $my_table;
	 
	require_once(ABSPATH . 'wp-admin/upgrade-functions.php');
		
	$table_name= $wpdb->prefix.$my_table;
	$sql = "DROP TABLE $table_name;";
	
	$wpdb->query($sql);*/
   
}

function acronymWordsDeleteDB(){
	
	global $wpdb;	
	global $my_table;
	 
	require_once(ABSPATH . 'wp-admin/upgrade-functions.php');
		
	$table_name= $wpdb->prefix.$my_table;
	$sql = "DROP TABLE $table_name;";
	
	$wpdb->query($sql);
}

   
function acronymWordsMenu(){  
	
	isset($_GET['acc']) ? $_acc=$_GET['acc']:$_acc="showKelime";
	
	if($_POST['url']!=""){
		echo "<br>";
		if($_POST['id']!=""){
			if(acronymWordsUpdateKelime($_POST['id'],$_POST['url'],$_POST['text'],$_POST['alt'],$_POST['rel'],$_POST['type']))
				echo '<div id="message" class="updated fade" style="background-color: rgb(255, 251, 204);"><b><br/>Kelime bilgileri güncellendi!</b><br/><br/></div>';
			$_acc="showKelime";
		}
		else{
			if(acronymWordsYeniKelime($_POST['url'],$_POST['text']))
				echo '<div id="message" class="updated fade" style="background-color: rgb(255, 251, 204);"><b><br/>Kelime veritabanına kaydedildi!</b><br/><br/></div>';
			else 
				echo '<div id="message" class="error fade" style="background-color: rgb(218, 79, 33);"><br/><b>Kelime veritabanında zaten yer alıyor!</b><br/><br/></div>';
			$_acc="addKelime";
		}
	}else{
		if($_GET['acc']=="del") {
			acronymWordsDeleteKelime($_GET['id']); 
			echo '<br><div id="message" class="updated fade" style="background-color: rgb(255, 251, 204);"> <br/>Kelime veritabanından silindi.<br/><br/></div>';
			$_acc="showKelime";
		}
		else if($_GET['acc']=="delDB"){
			acronymWordsDeleteDB();
			echo '<br><div id="message" class="updated fade" style="background-color: rgb(255, 251, 204);"> <br/>Veritabanı temizlendi. Eklentiyi pasif hale getirebilirsiniz :( <br/><br/></div>';
			$_acc="dataBase";
		}
	}  
	
	
	
	

echo '<div class="wrap">
		<h2>Arslania Acronym Kelimeler</h2>';
		
echo'<ul class="subsubsub">
		<li><a href="'.$PHP_SELF.'?page=arslania-acronym-kelimeler.php&acc=showKelime">Kelimeler</a> |</li>
		<li><a href="'.$PHP_SELF.'?page=arslania-acronym-kelimeler.php&acc=addKelime">Kelime Ekle</a> |</li>
		<li><a href="'.$PHP_SELF.'?page=arslania-acronym-kelimeler.php&acc=dataBase">Veritabanı</a> |</li>
		<li><a href="'.$PHP_SELF.'?page=arslania-acronym-kelimeler.php&acc=hakkinda">Hakkında</a> |</li>
		<li><a href="'.$PHP_SELF.'?page=arslania-acronym-kelimeler.php&acc=yardim">Yardım</a> </li>
	</ul>
	<br/><br/>
	
	<script>
	function deleteKelime(id){
		var opc = confirm("Kelime veritabanından kaldırılsın mı?");
		if (opc==true) window.location.href="'.$PHP_SELF.'?page=arslania-acronym-kelimeler.php&acc=del&id="+id;
	}
	</script>
';  
/* Kelime Ekle  */
if($_acc=="addKelime"){

 if (acronymWordsInfoDB()==false) {
		echo "<br/><b>Acronym kelimeler veritabanı temizlenmiş!</b> Eklentiyi yeniden etkinleştirmeyi deneyin.";
	  }
	  else{

echo '<h3>Kelime Ekle</h3>

	<fieldset>

	<form method="post" action ="">
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row">
						<label for="default_post_edit_rows"> Kelime</label>
					</th>
					<td>
						<input type="text" name="text" />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="default_post_edit_rows"> Açıklaması</label>
					</th>
					<td>
						<input type="text" name="url" style="width:300px;" />
					</td>
				</tr>
			</tbody>
		</table>


		
		<p class="submit"><input type="submit" name="acronymwords" value="Ekle" /></p>
	</form>
	</fieldset>';
	}
	}
	
	
	else if($_acc=="edit"){

 if (acronymWordsInfoDB()==false) {
		echo "<br/><b>Acronym kelimeler veritabanı temizlenmiş!</b> Eklentiyi yeniden etkinleştirmeyi deneyin.";
	  }
	  else{
	  
	  $_id = $_GET['id'];
	  $_text = base64_decode($_GET['text']);
	  $_url = base64_decode($_GET['url']);
	  $_anchortext = base64_decode($_GET['anchortext']);
	  $_rel = $_GET['rel'];
	  $_type = $_GET['type'];

echo '<h3>Kelime Düzenle</h3>

	<fieldset>

	<form method="post" action ="">
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row">
						<label for="default_post_edit_rows"> Kelime</label>
					</th>
					<td>
						<input type="hidden" name="id" value="'.$_id.'" /><input type="text" value="'.$_text.'" name="text" />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="default_post_edit_rows"> Açıklaması</label>
					</th>
					<td>
						<input type="text" name="url" value="'.$_url.'" style="width:300px;" />
					</td>
				</tr>
			</tbody>
		</table>


		
		<p class="submit"><input type="submit" name="acronymwords" value="Güncelle" /></p>
	</form>
	</fieldset>';
	}
	}
/* Kelime Göster */	
	else if($_acc=="showKelime"){
	  
	   if (acronymWordsInfoDB()==false) {
		echo "<br/><b>Acronym kelimeler veritabanı temizlenmiş!</b> Eklentiyi yeniden etkinleştirmeyi deneyin.";
	  }
	  else{
	  
	 echo' 
	 <h3>Kelime Listesi </h3>
	 <table class="widefat">
		<thead>
			<tr>
				<th style="display:none" scope="col">Kelime Veritabanı</th>
				<th scope="col">Kelime (<a href="'.$PHP_SELF.'?page=arslania-acronym-kelimeler.php&acc=showKelime&orderBy=text&order=asc">+</a>|<a href="'.$PHP_SELF.'?page=arslania-acronym-kelimeler.php&acc=showKelime&orderBy=text&order=desc">-</a>) </th>
				<th scope="col">Açıklaması (<a href="'.$PHP_SELF.'?page=arslania-acronym-kelimeler.php&acc=showKelime&orderBy=url&order=asc">+</a>|<a href="'.$PHP_SELF.'?page=arslania-acronym-kelimeler.php&acc=showKelime&orderBy=url&order=desc">-</a>)</th>
				<th scope="col">Gösterim Sayısı</th>
				<th scope="col">Sil</th>
				<th scope="col">Düzenle</th>
			</tr>
		</thead>
		<tbody id="the-comment-list" class="list:comment">
			<tr id="comment-1" class="">
				';
				if($_GET['orderBy']=="") $_GET['orderBy'] = "id";
				if($_GET['order']=="") $_GET['order'] = "desc";
				acronymWordsGetKelime($_GET['orderBy'],$_GET['order']);
				echo'
			</tr>
		</tbody>
		<tbody id="the-extra-comment-list" class="list:comment" style="display: none;"> </tbody>
		</table>

</div>';
}
}
/* Veritabanı Bilgi*/
else if($_acc=="dataBase"){
	  
	  if (acronymWordsInfoDB()==false) {
		echo "<br/><b>Acronym kelimeler veritabanı temizlenmiş!</b> Eklentiyi yeniden etkinleştirmeyi deneyin.";
	  }
	  else{
		  echo '<h3>Veritabanı</h3>Eklentiyi pasif duruma geçirdiğinizde kelime veritabanı silinmemektedir. <br>
			Kelime veritabanını temizledikten sonra eklentiyi pasif konuma geçirdiğinizde hiçbir kalıntı bırakmaz. <br>
			Arslania Acronym Kelimeler veritabanını temizlemek için
		  <b><a href="'.$PHP_SELF.'?page=arslania-acronym-kelimeler.php&acc=delDB">buraya</a></b> tıklayınız. <br> 
		  Daha sonra <b>Eklentiler</b> sekmesinden pasif hale getirebilirsiniz.<br><br>';
		  acronymWordsDataBaseInfo();
	  }
   }

/* Hakkımda  */

   else if($_acc=="hakkinda"){
	  
	  if (acronymWordsInfoDB()==false) {
		echo "<br/><b>Acronym kelimeler veritabanı temizlenmiş!</b> Eklentiyi yeniden etkinleştirmeyi deneyin.";
	  }
	  else{
		  echo '<h3>Hakkında</h3>
		  Merhabalar, Arslania Acronym Kelimeler eklentisini kullandığınız için teşekkürler.
		  <br><br>
		  Ali Arslan, Adıyamanda doğdu. Gaziantepte eğitimine devam ediyor. 2007 yılından beri WordPress ile ilgileniyor.
		  <br>
		  WordPress ile alakalı rehberler hazırladı ve birçok sitede yazılar yayımladı. WordPress Türkiye Takımında yer alıyor.
		  <br><br>
		  WordPress deneyimlerini <b><a target="blank" title="Arslan" href="http://www.arslania.com/">Arslania</a></b>da WordPress kullanıcıları ile paylaşıyor.
		  <br>
		  Soru ve görüşlerinizi patron[at]arslania[dot]com adresinden iletebilirsiniz.
		  
		  <h3>Emeği Geçenler</h3>
		  Sayın Yavuz Gümüştepe, Erhan Yakut(yakuter), Beyazıt Kölemen(anarschi), Enes Ateş ve Mehmet Emreye sonsuz teşekkürler..
		  ';
	  }
   }
 
/* Yardıma ihtiyacım var!  */
   else if($_acc=="yardim"){
	  
	  if (acronymWordsInfoDB()==false) {
		echo "<br/><b>Acronym kelimeler veritabanı temizlenmiş!</b> Eklentiyi yeniden etkinleştirmeyi deneyin.";
	  }
	  else{
		  echo '<h3>Yardım / Sık Sorulan Sorular</h3>
		  
		  <b>Bir yazıda kaç kelime gösteriliyor?</b><br>
		  Bir kelime her yazıda sadece bir defa acronym etiketi içerisine alınır. <br>
		  Acronym etiketine sahip farklı kelimeler varsa onlar da birer defa etiket içerisinde kullanılır.
		  
		  <br><br><b>Yazılara herhangi bir yan etkisi var mı?</b><br>
		  Hayır, veritabanınızda yer alan yazılarınızda hiç bir değişiklik olmaz. <br>
		  Arslania Acronym Kelimeler veritabanı silindiğinde sitenizde eklenti hiç kurulmamış gibi tertemizdir :)		  
		  <br><br><b>Nasıl kullanılır?</b><br>
		  Kullanımı oldukça basit.  <br>
		  1- Kelime Ekle menüsüne tıklayın. <br>		  
		  2- Kelime kısmına "Kısaltma" halini, Açıklaması kısmına ise "Kelimeyi" yazmanız yeterlidir. <br>
		  Örnek verecek olursak Kelime: SEO Açıklama: Search Engine Optimization
		  		  
		  <br><br><b>Farklı bir problemle karşılaştım?</b>
		  <br>
		  O zaman sizi <a target="blank" title="Arslania Acronym Kelimeler" href="http://www.arslania.com/arslania-acronym-kelimeler.html">Arslania Acronym Kelimeler</a> eklenti sayfasına alalım.
		  Orada sorunlarınızı rahatça dile getirebilirsiniz ;) <br>
		  Eklenti ile alakalı güncellemeleri de eklenti sayfasından takip edebilirsiniz.
		  
		   ';
	  }
   }
   
   }
   
  
   function acronymWordsDataBaseInfo(){
   
		global $wpdb;
		global $my_table;
		
		
		$table_name= $wpdb->prefix.$my_table;
		
				$query = "select count(id) as links ,SUM(visits) as visits from $table_name ";
				$links = $wpdb->get_results($query);
				
				echo 'Veritabanında yer alan kelime sayısı: <b>'.$links[0]->links.'</b> <br> Kelimelerin toplam gösterim sayısı: <b>'.$links[0]->visits.'</b>';
   }
   
   function acronymWordsGetKelime($orderBy="id",$order="desc"){
   
		global $wpdb;
		global $my_table;
		
		echo '<tbody id="the-comment-list" class="list:comment">
			';
		
		$table_name= $wpdb->prefix.$my_table;
		
				$query = "select * from $table_name order by ".$orderBy." ".$order;
				$links = $wpdb->get_results($query);

				foreach($links as $link){
					echo '<tr id="comment-1" class="">';
					echo '<td style="display:none">'; echo $link->id; echo'</td>';
					echo '<td>'; echo $link->text; echo'</td>';
					echo '<td>'; echo $link->url; echo'</td>';
					echo '<td>'; echo $link->visits; echo'</td>';
					echo '<td><a href="javascript:deleteKelime('.$link->id.');">Sil</a></td>';	
					$_url = base64_encode($link->url);
					$_text = base64_encode($link->text);
					echo '<td><a href="'.$PHP_SELF.'?page=arslania-acronym-kelimeler.php&acc=edit&id='.$link->id.'&text='.$_text.'&url='.$_url.'">Düzenle</a></td>';
					echo '</tr>';
				}
				
		echo '</tbody>';
   }

/* Kelime Ekle  */	
function acronymWordsYeniKelime($url,$text)
	{
		global $wpdb;
		global $my_table;
		
		$table_name= $wpdb->prefix . $my_table;

				$queryprev = "select `url` from $table_name where `text` = '$text'";
				$result = $wpdb->get_results($queryprev);

				if(count($result)>0) return false;
				
				$query = "INSERT INTO $table_name ( `url`, `text`, `anchortext`,`rel`,`type`,`visits` ) VALUES ";
					$query .= " (
						'".mysql_real_escape_string($url)."',
						'".mysql_real_escape_string($text)."',
						'".mysql_real_escape_string($anchor_text)."',
						'".mysql_real_escape_string($rel)."',
						'".mysql_real_escape_string($type)."',
						'0'
					),";

				$query = substr($query, 0, strlen($query)-1);
				$wpdb->query($query);
				return true;
	}
	
	function acronymWordsUpdateKelime($id,$url,$text)
	{
		global $wpdb;
		global $my_table;
		
		$table_name= $wpdb->prefix . $my_table;
				
				$query = "UPDATE $table_name set `url` = '".mysql_real_escape_string($url)."',
         				`text` = '".mysql_real_escape_string($text)."' ,
						`anchortext` = '".mysql_real_escape_string($anchor_text)."',
						`rel` = '".mysql_real_escape_string($rel)."',
						`type` = '".mysql_real_escape_string($type)."' where id ='".mysql_real_escape_string($id)."' ";

				$wpdb->query($query);
				return true;
	}
	
function acronymWordsInfoDB(){
	global $wpdb;
	global $my_table;
		
		$table_name= $wpdb->prefix . $my_table;
				
		($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != "") ? $back= true : $back= false;
		return $back;

}

function acronymWordsShowKlm($id)
	{
		global $wpdb;
		global $my_table;
		
		$table_name= $wpdb->prefix.$my_table;
				$query = "update $table_name set `visits` = `visits`+1 where id= $id ";	
				$query = substr($query, 0, strlen($query)-1);
				$wpdb->query($query);
	}

/* Kelime temizleyici  */
function acronymWordsDeleteKelime($id){
	 global $wpdb;	 
	 global $my_table;
	 
	require_once(ABSPATH . 'wp-admin/upgrade-functions.php');
		
	$table_name= $wpdb->prefix . $my_table;
	$sql = "DELETE FROM $table_name where id = $id;";
	
	$wpdb->query($sql);

}
	
/* Video ve içeriğe dikkat oynamayalım :) */
	
function acronymWordsKelime($content='')
	{
		$text = $content;		
		global $wpdb;
		global $my_table;
		global $notAllowToChange;
		
		$table_name= $wpdb->prefix.$my_table;

		$query = "select * from $table_name";
		$links = $wpdb->get_results($query);		

		foreach($links as $link){			
				
			$find = '/'.$link->text.'/i';
				$isFind = false;

				$matches = array();
				preg_match_all($find, $content, $matches, PREG_OFFSET_CAPTURE);
				$matchData = $matches[0];
				

					$noChanges = array(
						'/<h[1-6][^>]*>[^<]*'.$link->text.'[^<]*<\/h[1-6]>/i',
						'/<a[^>]+>[^<]*'.$link->text.'[^<]*<\/a>/i',
						'/href=("|\')[^"\']+'.$link->text.'(.*)[^"\']+("|\')/i',
						'/src=("|\')[^"\']*'.$link->text.'[^"\']*("|\')/i',
						'/alt=("|\')[^"\']*'.$link->text.'[^"\']*("|\')/i',
						'/title=("|\')[^"\']*'.$link->text.'[^"\']*("|\')/i',
						'/content=("|\')[^"\']*'.$link->text.'[^"\']*("|\')/i',
						'/<script[^>]*>[^<]*'.$link->text.'[^<]*<\/script>/i',
						'/<embed[^>]+>[^<]*'.$link->text.'[^<]*<\/embed>/i',
						'/wmode=("|\')[^"\']*'.$link->text.'[^"\']*("|\')/i'
					);

					foreach($noChanges as $noChange){
						$results = array();
						preg_match_all($noChange, $content, $results, PREG_OFFSET_CAPTURE);
						$matches = $results[0];

						if(!count($matches) == 0) {
							foreach($matches as $match){
								$start = $match[1];
								$end = $match[1] + strlen($match[0]);
								foreach($matchData as $index => $data){
									if($data[1] >= $start && $data[1] <= $end){
										$matchData[$index][2] = true;
									}
								}
							}
						}		
					}

					foreach($matchData as $index => $match){
						if($match[2] != true){
							$isFind = $match;
							break;
						}
					}

				if(is_array($isFind)){$replacement = '<acronym title="'.$link->url.'">'.$isFind[0].'</acronym>';
					
					acronymWordsShowKlm($link->id);
					$content = substr($content, 0, $isFind[1]) . $replacement . substr($content, $isFind[1] + strlen($isFind[0]));
				}

			}


		return $content;
	}

?>
