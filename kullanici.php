<?php

include 'vtyardim.php';

class Kullanici {
	protected $id;
	protected $vty;
	public function Kullanici($id, $vty) {
		$this->id = $id;
		$this->vty = $vty;
	}

	public function setId ($id) {
		$this->id = $id;
	}

	public function araclariGetir($null){
		return $this->vty->runQuery("select plaka, aktif, ozmal from arac 
			where musteri_id=".$this->id, true);
	}
	
	public function durumlariGetir($null){
		return $this->vty->runQuery("select  plaka,tarih,bakimda,km,depo from durum, arac 
			where arac.musteri_id=".$this->id." 
			and durum.arac_id = arac.plaka", true);
	}
	
	public function bakimlariGetir($null){
		return $this->vty->runQuery("select  plaka,bakim.tarih,note,tamirde from bakim, durum, arac 
			where arac.musteri_id=".$this->id." 
			and durum.arac_id = arac.plaka 
			and bakim.durum_id = durum.id", true);
	}
	
	public function tamirleriGetir($null){
		return $this->vty->runQuery("select  plaka,tamir.tarih,tamir.note,tamir.hurda from tamir, bakim, durum, arac 
			where arac.musteri_id=".$this->id." 
			and durum.arac_id = arac.plaka 
			and bakim.durum_id = durum.id 
			and tamir.bakim_id = bakim.id", true);
	}

	public function raporla(){
		$raporlar = array();
		foreach ($this->araclariGetir(null) as $arac) {
			$rapor = array();
			$rapor[] = $arac;
			foreach ($this->vty->runQuery("select id, tarih,bakimda,km,depo from durum  
			where durum.arac_id ='".$arac['plaka']."'", true) as $durum) {
				$rapor[] = $durum;
				foreach ($this->vty->runQuery("select id,tarih,note,tamirde from bakim  
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
$k = new Kullanici(3,$vty);
echo '<pre>';
print_r($k->raporla());
*/
?>
