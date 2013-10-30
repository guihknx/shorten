<?php
require_once('conexion.class.php');

class shorten{
	public $hash;
	public $url;
	public $data;
	public $clicks;
	public $pdo;
	private $id;
	public $field;

	public function __construct()
	{	
		$this->conex = new conexion('YOUR_DATABASE'); // put your database name here

		$this->pdo = $this->conex->connect();

		$this->hash = uniqid('');
		$this->data = date('Y/m/d H:i:s');
		$this->clicks = 0;


		if ( !$pdo ) 
			return; 
	}

   	public function addUrl($url){
	   try{
	   		$this->url = $url;

			$query = $this->pdo->prepare("INSERT INTO shorten(hash, url, data, clicks) VALUES (?, ?, ?, ?)");
			$query->bindParam(1, $this->hash, PDO::PARAM_INT);
			$query->bindParam(2, $this->url, PDO::PARAM_STR);
			$query->bindParam(3, $this->data, PDO::PARAM_STR);
			$query->bindParam(4, $this->clicks, PDO::PARAM_INT);
			$end = $query->execute();

			if ( !$end ) 
				return;			

			return true;

	   }
	   catch(PDOException $e){
	      echo $e->getMessage();
	   }
	}
	public function readUrl($id, $field){
		try{
			$this->field = $field;
			$query = $this->pdo->prepare("SELECT * FROM shorten WHERE id = ?");
			$query->bindParam(1, $id , PDO::PARAM_INT);
			$end = $query->execute();

			if( !$end )
				return;
			
			while ( $data = $query->fetch( PDO::FETCH_OBJ ) ) :
				switch ( $this->field ) :

					case 'url' :
					$query = $this->pdo->prepare('UPDATE shorten SET clicks = :clicks WHERE id = :id');
					
					$query->execute(array(
						':id'   => $data->id,
						':clicks' => $data->clicks+1
						));

						echo $data->url;
					break;

					case 'clicks' :
						echo $data->clicks;
					break;

					case 'data' :
						echo $data->data;
					break;

					case 'hash' :
						echo $data->hash;
					break;

				endswitch;
			endwhile;
		}
		catch(PDOException $e){
			echo $e->getMessage();
		}
	}
}

$a = new shorten();
$t = $a->readUrl(14,'url');
