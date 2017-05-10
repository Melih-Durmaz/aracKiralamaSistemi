<?php

include 'kullanici.php';


class SuperKullanici extends Kullanici {
	public function __construct($vty){
		Kullanici::Kullanici(1, $vty);
	}
	
	public function musterileriGetir() {
		return $this->vty->runQuery("select * from musteri where aktif=true", true);
	}
	
	public function musteriOlustur($isim) {
		$this->vty->runQuery("insert into musteri(isim, tarih) 
			values ('".$isim."','".date('Y-m-d')."')", false);
	}
	
	public function musterileriSil($idler) {
		foreach ($idler as $id ) {
			$this->vty->runQuery("update musteri set aktif=false where id=".$id, false);
			$this->vty->runQuery("update arac set aktif=false, musteri_id=0  where musteri_id=".$id, false);
			$this->vty->runQuery("delete from arac where musteri_id=".$id." 
				and ozmal=true", false);
		}
	}
	
	public function aracOlustur($plaka, $ozmal) {
		$this->vty->runQuery("insert into arac(plaka, ozmal) 
			values ('".$plaka."',".$ozmal.")", false);
	}
	
	public function araclariMusteriyeAta($m_id, $plakalar) {
		foreach($plakalar as $plaka) {
			$this->vty->runQuery("update arac set musteri_id=".$m_id.", aktif=true
				where plaka='".$plaka."'", false);
		}
	}
	
	public function araclariDeaktifEt($plakalar) {
		foreach($plakalar as $plaka) {
			$this->vty->runQuery("update arac set musteri_id=1, aktif=false
				where plaka='".$plaka."'", false);
		}
	}
	
	public function araclariGetir($musteri_id){
		if ($musteri_id == null) {
			return $this->vty->runQuery("select * from arac ", true);
		}
		return $this->vty->runQuery("select * from arac where musteri_id=".$musteri_id, true);
	}
	
	public function deaktifAraclariGetir(){
		return $this->vty->runQuery("select * from arac where aktif=false", true);
	}
	
	
	public function durumOlustur($arac_id, $km, $depo, $bakimda) {
		$this->vty->runQuery("insert into durum(arac_id, km, depo, bakimda, tarih) 
			values ('".$arac_id."','".$km."','".$depo."',".$bakimda.",'".date('Y-m-d')."')", false);
	}
	/*
	public function durumlariGetir(){
		return $this->vty->runQuery("select * from durum", true);
	}
	*/
	public function durumlariGetir($plaka){
		if ($plaka==null) {
			return $this->vty->runQuery("select * from durum", true);
		}
		return $this->vty->runQuery("select * from durum
			where arac_id='".$plaka."'", true);
	}
	
	public function bakimdakiDurumlariGetir() {
		return $this->vty->runQuery("select * from durum 
			where bakimda=true", true);
	}
	
	public function bakimOlustur($durum_id,$note,$tamirde) {
		$this->vty->runQuery("insert into bakim(durum_id, note, tamirde, tarih) 
			values ('".$durum_id."','".$note."',".$tamirde.",'".date('Y-m-d')."')", false);
	}
	
	/*
	public function bakimlariGetir(){
		return $this->vty->runQuery("select * from bakim", true);
	}
	*/
	
	public function bakimlariGetir($plaka){
		if ($plaka == null) {
			return $this->vty->runQuery("select * from bakim", true);
		}
		return $this->vty->runQuery("select bakim.* from bakim, durum
			where durum_id = durum.id
			and arac_id = '".$plaka."'", true);
	}
	
	public function tamirdekiBakimlariGetir() {
		return $this->vty->runQuery("select * from bakim where tamirde=true", true);
	}
	
	public function tamirOlustur($bakim_id, $note, $hurda) {
		$this->vty->runQuery("insert into tamir(bakim_id, note, hurda, tarih) 
			values ('".$bakim_id."','".$note."',".$hurda.",'".date('Y-m-d')."')", false);
		
		if ($hurda == "true") {
			$this->vty->runQuery("delete from arac where plaka 
				in (select arac.plaka from (select * from arac) as arac,durum,bakim 
					where bakim.id=".$bakim_id." 
					and durum.id = bakim.durum_id
					and arac.plaka = durum.arac_id)");	
		}
	}
	
	/*
	public function tamirleriGetir(){
		return $this->vty->runQuery("select * from tamir", true);
	}
	*/
	
	public function tamirleriGetir($plaka){
		if ($plaka == null) {
			return $this->vty->runQuery("select * from tamir", true);
		}
		
		return $this->vty->runQuery("select tamir.* from tamir,bakim,durum
			where durum.arac_id = '".$plaka."' 
			and bakim.durum_id = durum.id 
			and tamir.bakim_id = bakim.id", true);
	}

	public function raporla(){
		$raporlar = array();
		foreach ($this->araclariGetir() as $arac) {
			$rapor = array();
			$rapor[] = $arac;
			foreach ($this->vty->runQuery("select tarih,bakimda,km,depo from durum  
			where durum.arac_id =".$arac['plaka'], true) as $durum) {
				$rapor[] = $durum;
				foreach ($this->vty->runQuery("select tarih,note,tamirde from bakim  
				where bakim.durum_id =".$durum['id'], true) as $bakim) {
					$rapor[] = $bakim;
					foreach ($this->vty->runQuery("select tarih,note,hurda from tamir  
					where tamir.bakim_id =".$bakim['id'], true) as $tamir) {
						$rapor[] = $tamir;						
					}
				}
			}
			$raporlar[] = $rapor;
		}
		return $raporlar;
	}
}
/*
$vty = new VtYardim('root', '43951515', 'filodb');
$sk = new SuperKullanici($vty);
//$sk->musteriOlustur("a şirketi");
//$sk->musteriOlustur("b şirketi");
//print_r($sk->musterileriGetir());
//$sk->musteriSil(2);
//$sk->aracOlustur("34abc56", "false");
//$sk->aracOlustur("34abc78", "false");
$sk->aracOlustur("34def67", "false");
//print_r($sk->araclariGetir(null));
//print_r($sk->araclariGetir(2));
//$sk->aracSil('34abc78');
//$sk->araclariMusteriyeAta(3, array('34abc56','34def34'));
//$sk->durumOlustur('34abc56', 10000, 35.4, 'false');
//$sk->durumOlustur('34abc56', 10050, 10.4, 'false');
//$sk->durumOlustur('34abc56', 10100, 50.4, 'true');
//print_r($sk->durumlariGetir(null));
//print_r($sk->durumlariGetir('34def34'));
//print_r($sk->bakimdakiDurumlariGetir());
//$sk->bakimOlustur(13,'some notes','true');
//print_r($sk->bakimlariGetir(null));
*/
?>
