<?php

include 'superkullanici.php';

class KullaniciArayuzu{

	var $yetkili;
	var $musteri;
		
	public function __construct() {	
		$vty = new VtYardim('root', '43951515', 'filodb');
		$this->musteri = new Kullanici(1,$vty);
		$this->yetkili = new SuperKullanici($vty);
	}
	
	public function pageDetector() {
		if ( (!empty($_POST)) && (isset($_POST['page'])) ) {
			switch($_POST['page']) {
				
			}
		} else {
			$this->yetkiliMusteriSecimi();
		}
	}
	
	private function head() {
		echo '
<html>
<head>
	<title>ARAC KIRALAMA</title>
	<style>
		table {
			border : 1px solid black;
		}
		body {
			width: 50%;
			margin-left: 25%;
			margin-top: 100px;
		}
	</style>
</head>				
<body>
	<a href="index.php" ><h1>ARAC KIRALAMA SISTEMI</h1> </a>
	<br><br><br>
		';
	}
	
	private function tail() {
		echo '
</body>
		';
	}
	
	public function yetkiliMusteriSecimi() {
		$this->head();
		echo '
<form action="index.php" method="post">
	<input type="submit" name="musteriMenu" value="MUSTERI MENU"/><br><br> 
	<input type="submit" name="yetkiliMenu" value="YETKILI MENU"/>
</form>
		';
		$this->tail();
	}
	
	public function musteriMenu() {
		
		$musteriler = $this->yetkili->musterileriGetir();
		$this->head();
		echo '
		<h3>Musteri Menu</h3>
<form action="index.php" method="post">
	<select name="musteriler">
';

		foreach ($musteriler as $musteri) {
			echo'
			<option value="'.$musteri['id'].'">'.$musteri['isim'].'</option>
			';
		}

		echo '
	</select></br></br>
	<input type="submit" name="musteriAracGoruntule" value="ARAC GORUNTULE"/> </br></br>
	<input type="submit" name="musteriDurumGoruntule" value="DURUM GORUNTULE"/> </br></br>
	<input type="submit" name="musteriBakimGoruntule" value="BAKIM GORUNTULE"/> </br></br>
	<input type="submit" name="musteriTamirGoruntule" value="TAMIR GORUNTULE"/> </br></br>
	<input type="submit" name="musteriRaporGoruntule" value="RAPOR GORUNTULE"/> </br></br>
</form>		
		';
		$this->tail();
	}
	
	public function yetkiliMenu() {
		$this->head();
		echo '
		<h3>Yetkili Menu</h3>
<form action="index.php" method="post"> 
	<input type="submit" name="yetkiliMusteriIslemleri" value="MUSTERI ISLEMLERI"/> </br></br>
	<input type="submit" name="yetkiliAracIslemleri" value="ARAC ISLEMLERI"/> </br></br>
	<input type="submit" name="yetkiliDurumIslemleri" value="DURUM ISLEMLERI"/> </br></br>
	<input type="submit" name="yetkiliBakimIslemleri" value="BAKIM ISLEMLERI"/> </br></br>
	<input type="submit" name="yetkiliTamirIslemleri" value="TAMIR ISLEMLERI"/> </br></br>
	<input type="submit" name="yetkiliRaporIslemleri" value="RAPOR ISLEMLERI"/> </br></br>
</form>		
		';
		$this->tail();
	}
	
	public function basitGoruntuleme($things, $keys) {
		echo '
<table>
	<tr>
';
		$control = 0;
		foreach($keys as $key) {
			echo '<th>'.$key.'</th>';
			$control++;
		}
		if ($control==0) {
			echo '<td>KAYIT BULUNAMADI</td>';
		}
		echo'
	</tr>
';
		foreach($things as $thing) {
			echo '
	<tr>
'; 
			foreach($keys as $key) {
				echo '
		<td>'.$thing[$key].'</td>
';
			}
			echo '		
	</tr>
';
		}
		echo '
</table>
		';		
	}
	
	public function raporGoruntuleme($things) {
		echo '
<table>
	<tr>
		<td>RAPOR<td>
	</tr>
';
		foreach($things as $thing) {
			echo '
	<tr>
		<td> ----------------</td>
	</tr>
';
			foreach ($thing as $tt) {
				echo '
	<tr>
'; 	
				foreach($tt as $t) {
					echo'
		<td>'.$t.'<td>
';
				}
				echo '		
	</tr>
';
			}
			
		}
		echo '
</table>
		';	
	}
	
	public function musteriAracGoruntule() {
		$araclar = $this->musteri->araclariGetir(null);
		$keys = array_keys($araclar[0]);
		$this->head();
		echo '<h4>Araclar</h4>';
		$this->basitGoruntuleme($araclar, $keys);
		$this->tail();
	}
	
	public function musteriDurumGoruntule() {
		$durumlar = $this->musteri->durumlariGetir(null);
		$keys = array_keys($durumlar[0]);
		$this->head();
		echo '<h4>Durumlar</h4>';
		$this->basitGoruntuleme($durumlar, $keys);
		$this->tail();
	}
	
	public function musteriBakimGoruntule() {
		$bakimlar = $this->musteri->bakimlariGetir(null);
		$keys = array_keys($bakimlar[0]);
		$this->head();		
		echo '<h4>Bakimlar</h4>';
		$this->basitGoruntuleme($bakimlar, $keys);
		$this->tail();
	}
	
	public function musteriTamirGoruntule() {
		$tamirler = $this->musteri->tamirleriGetir(null);
		$keys = array_keys($tamirler[0]);
		$this->head();
		echo '<h4>Tamirler</h4>';
		$this->basitGoruntuleme($tamirler, $keys);
		$this->tail();
	}
	
	public function musteriRaporGoruntule() {
		$raporlar = $this->musteri->raporla();
		$this->head();
		echo '<h4>Raporlar</h4>';
		$this->raporGoruntuleme($raporlar);
		$this->tail();
	}
	
	public function yetkiliMusteriEkleSilGoruntule() {
		$musteriler = $this->yetkili->musterileriGetir();
		$this->head();
		echo '
		<h4>Musteri Sil</h4>
<form action="index.php" method="post">
';
		foreach($musteriler as $thing) {
			echo '
	<input type="checkbox" name = "musteriler[]" value="'.$thing['id'].'"/>'.$thing['isim'].'<br><br>
			';
		}
		echo '
	<input type="submit" name="yetkiliMusteriSil" value="SIL"/>
</form>
		';
		
		echo '
		<br><br>
		<h4>Musteri Ekle</h4>
<form action="index.php" method="post">
	Sirket Adi : <input type="text" name="musteri_adi"><br><br>
	<input type="submit" name="yetkiliMusteriEkle" value="EKLE"/>
</form>
		';
		
		$this->tail();
	}
	
	public function yetkiliAracEkleSilGoruntule() {
		$this->head();
		echo '
		<h4>Arac Olustur</h4>
<form action="index.php" method="post">
	Plaka : <input type="text" name="plaka"/><br><br>
	<select name="ozmal">
		<option value="false">Firma Malı</option>
		<option value="true">Ozmal</option>
	</select><br><br>
	<input type="submit" name="yetkiliAracOlustur" value="OLUSTUR"/>
</form>		
		';
		
////////////////////////////////////////////////////////////////////////
		$araclar = $this->yetkili->deaktifAraclariGetir(null);
		$musteriler = $this->yetkili->musterileriGetir();
		echo '
		<br><br>
		<h4>Arac Ata</h4>
<form action="index.php" method="post">
';
		foreach($araclar as $thing) {
			echo '
	<input type="checkbox" name="araclar[]" value="'.$thing['plaka'].'"/>'.$thing['plaka'].'<br><br>
			';
		}
		echo '
		<select name="musteriler">
';

		foreach ($musteriler as $musteri) {
			echo'
			<option value="'.$musteri['id'].'">'.$musteri['isim'].'</option>
			';
		}

		echo '
	</select><br><br>
	<input type="submit" name="yetkiliMusteriyeAta" value="ATA"/>
</form>
		';
////////////////////////////////////////////////////////////////////////
		$araclar = $this->yetkili->araclariGetir(null);
		echo '
		<br><br>
		<h4>Arac Deaktif Et</h4>
<form action="index.php" method="post">
';
		foreach($araclar as $thing) {
			echo '
	<input type="checkbox" name="araclar[]" value="'.$thing['plaka'].'"/>'.$thing['plaka'].'<br><br>
			';
		}

		echo '
	<input type="submit" name="yetkiliDeaktifEt" value="DEAKTIF"/>
</form>
		';
		$this->tail();
	}
	
	
	public function yetkiliDurumEkleGoruntule() {
		$durumlar = $this->yetkili->durumlariGetir(null);
		$this->head();
		echo '<h4>Durumlar</h4>';
		$this->basitGoruntuleme($durumlar, array_keys($durumlar[0]));
		
		
////////////////////////////////////////////////////////////////////////
		$araclar = $this->yetkili->araclariGetir(null);
		echo '
		<br><br>
		<h4>Durum Girisi</h4>
<form action="index.php" method="post">
	<select name="arac">
';
		foreach($araclar as $arac) {
			echo '<option value="'.$arac['plaka'].'">'.$arac['plaka'].'</option>';
		}
		echo '
	</select><br><br>
	Km : <input type="text" name="km"> <br><br>
	Depo : <input type="text" name="depo"> <br><br>
	<select name="bakimda">
		<option value="false">Sorun Yok</option>
		<option value="true">Bakıma Gonder</option>
	</select><br><br>
	<input type="submit" name="yetkiliDurumEkle" value="OLUSTUR"/>
</form>	
		';
	
		$this->tail();
	}
	
	public function yetkiliBakimEkleGoruntule() {
		$bakimlar = $this->yetkili->bakimlariGetir(null);
		$this->head();
		echo '<h4>Bakimlar</h4>';
		$this->basitGoruntuleme($bakimlar, array_keys($bakimlar[0]));
////////////////////////////////////////////////////////////////////////
		$durumlar = $this->yetkili->bakimdakiDurumlariGetir();
		$keys = array_keys($durumlar[0]);
		
		echo '
		<br><br>
		<h4>Bakim Girisi</h4>
<form action="index.php" method="post">
	<table>
		<tr>
';
		$control = 0;
		foreach($keys as $key) {
			echo '<th>'.$key.'</th>';
			$control++;
		}
		if ($control==0) {
			echo '<td>KAYIT BULUNAMADI</td>';
		}
		echo '
		</tr>';
		
		foreach($durumlar as $thing) {
			echo '
	<tr>
'; 
			foreach($keys as $key) {
				echo '
		<td>'.$thing[$key].'</td>
';
			}
			echo '
		<td><input type="radio" name="durum" value="'.$thing['id'].'"></td>		
	</tr>
';
		}
		
		echo '
	</table><br><br>
	<textarea rows="4" cols="50" name="note">...</textarea><br><br>
	<select name="tamirde">
		<option value="false">Sorun Yok</option>
		<option value="true">Tamire Gonder</option>
	</select><br><br>
	<input type="submit" name="yetkiliBakimOlustur" value="OLUSTUR">
</form>
		';
		$this->tail();
	}
	
	public function yetkiliTamirEkleGoruntule() {
		$tamirler = $this->yetkili->tamirleriGetir(null);
		$this->head();
		echo '<h4>Tamirler<h4>';
		$this->basitGoruntuleme($tamirler, array_keys($tamirler[0]));
////////////////////////////////////////////////////////////////////////
		$bakimlar = $this->yetkili->tamirdekiBakimlariGetir();
		$keys = array_keys($bakimlar[0]);
		
		echo '
		<br><br>
		<h4>Tamir Girisi</h4>
<form action="index.php" method="post">
	<table>
		<tr>
';
		$control = 0;
		foreach($keys as $key) {
			echo '<th>'.$key.'</th>';
			$control++;
		}
		if ($control==0) {
			echo '<td>KAYIT BULUNAMADI</td>';
		}
		echo '
		</tr>';
		
		foreach($bakimlar as $thing) {
			echo '
	<tr>
'; 
			foreach($keys as $key) {
				echo '
		<td>'.$thing[$key].'</td>
';
			}
			echo '
		<td><input type="radio" name="bakim" value="'.$thing['id'].'"></td>		
	</tr>
';
		}
		
		echo '
	</table><br><br>
	<textarea rows="4" cols="50" name="note">...</textarea><br><br>
	<select name="hurda">
		<option value="false">Tamir Edildi</option>
		<option value="true">Hurdaya Cikar</option>
	</select><br><br>
	<input type="submit" name="yetkiliTamirOlustur" value="OLUSTUR">
</form>
		';
		$this->tail();

	}
	
	public function yetkiliRaporGoruntule() {
		$raporlar = $this->yetkili->raporla();
		$this->head();
		$this->raporGoruntuleme($raporlar);
		$this->tail();
	}
	
	public function sayfaYoneticisi() {
		if ( !empty($_POST) ) {
			if (isset($_POST['musteriMenu'])) {
				$this->musteriMenu();
			} else if (isset($_POST['musteriAracGoruntule'])) {
				$this->musteri->setId($_POST['musteriler']);
				$this->musteriAracGoruntule();
			} else if (isset($_POST['musteriDurumGoruntule'])) {
				$this->musteri->setId($_POST['musteriler']);
				$this->musteriDurumGoruntule();
			} else if (isset($_POST['musteriBakimGoruntule'])) {
				$this->musteri->setId($_POST['musteriler']);
				$this->musteriBakimGoruntule();
			} else if (isset($_POST['musteriTamirGoruntule'])) {
				$this->musteri->setId($_POST['musteriler']);
				$this->musteriTamirGoruntule();
			} else if (isset($_POST['musteriRaporGoruntule'])) {
				$this->musteri->setId($_POST['musteriler']);
				$this->musteriRaporGoruntule();
			} else if (isset($_POST['yetkiliMenu'])) {
				$this->yetkiliMenu();
			} else if (isset($_POST['yetkiliMusteriIslemleri'])) {
				$this->yetkiliMusteriEkleSilGoruntule();
			} else if (isset($_POST['yetkiliAracIslemleri'])) {
				$this->yetkiliAracEkleSilGoruntule();
			} else if (isset($_POST['yetkiliDurumIslemleri'])) {
				$this->yetkiliDurumEkleGoruntule();
			} else if (isset($_POST['yetkiliBakimIslemleri'])) {
				$this->yetkiliBakimEkleGoruntule();
			} else if (isset($_POST['yetkiliTamirIslemleri'])) {
				$this->yetkiliTamirEkleGoruntule();
			} else if (isset($_POST['yetkiliRaporIslemleri'])) {
				$this->yetkiliRaporGoruntule();
////////////////////////////////////////////////////////////////////////
			} else if (isset($_POST['yetkiliMusteriSil'])) {
				$this->yetkili->musterileriSil($_POST['musteriler']);
				$this->yetkiliMusteriEkleSilGoruntule();
			} else if (isset($_POST['yetkiliMusteriEkle'])) {
				$this->yetkili->musteriOlustur($_POST['musteri_adi']);
				$this->yetkiliMusteriEkleSilGoruntule();
			} else if (isset($_POST['yetkiliAracOlustur'])) {
				$this->yetkili->aracOlustur($_POST['plaka'], $_POST['ozmal']);
				$this->yetkiliAracEkleSilGoruntule();
			} else if (isset($_POST['yetkiliMusteriyeAta'])) {
				$this->yetkili->araclariMusteriyeAta($_POST['musteriler'],$_POST['araclar']);
				$this->yetkiliAracEkleSilGoruntule();
			} else if (isset($_POST['yetkiliDeaktifEt'])) {
				$this->yetkili->araclariDeaktifEt($_POST['araclar']);
				$this->yetkiliAracEkleSilGoruntule();
			} else if (isset($_POST['yetkiliDurumEkle'])) {
				$this->yetkili->durumOlustur($_POST['arac'], $_POST['km'], $_POST['depo'], $_POST['bakimda']);
				$this->yetkiliDurumEkleGoruntule();
			} else if (isset($_POST['yetkiliBakimOlustur'])) {
				$this->yetkili->bakimOlustur($_POST['durum'], $_POST['note'], $_POST['tamirde']);
				$this->yetkiliBakimEkleGoruntule();
			} else if (isset($_POST['yetkiliTamirOlustur'])) {
				$this->yetkili->tamirOlustur($_POST['bakim'], $_POST['note'], $_POST['hurda']);
				$this->yetkiliTamirEkleGoruntule();
			}
		} else {
			$this->yetkiliMusteriSecimi();
		}
	}
	
}


//$ka = new KullaniciArayuzu($sk, $k);
//$ka->yetkiliMusteriSecimi();
//$ka->musteriMenu();
//$ka->musteriAracGoruntule();
//$ka->musteriDurumGoruntule();
//$ka->musteriBakimGoruntule();
//$ka->musteriTamirGoruntule();
//$ka->musteriRaporGoruntule();
//$ka->yetkiliMenu();
//$ka->yetkiliMusteriEkleSilGoruntule();
//$ka->yetkiliAracEkleSilGoruntule();
//$ka->yetkiliDurumEkleGoruntule();
//$ka->yetkiliBakimEkleGoruntule();
//$ka->yetkiliTamirEkleGoruntule();
?>
