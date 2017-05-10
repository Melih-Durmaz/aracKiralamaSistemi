<?php
class VtYardim {
	var $un;
	var $pw;
	var $db;
	public function __construct ($un,$pw,$db) {
		$this->un = $un;
		$this->pw = $pw;
		$this->db = $db;
	}

	public function runQuery ($sql, $returnBool) {
		$conn = mysqli_connect("localhost", $this->un, $this->pw, $this->db);
		$result = mysqli_query($conn, $sql);
		if ($returnBool) {
			$rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
			mysqli_close($conn);
			return $rows;
		}
		mysqli_close($conn);
	}
}
/*
$dt = date('Y-m-d');
echo $dt;
$vty = new VtYardim('root', '43951515', 'filodb');
$vty->runQuery("insert into musteri (isim, tarih) values ('"."safa"."','".$dt."')");
*/
?>
