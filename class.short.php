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
		$this->url = $url;

		if($this->logged_in()) 
			$this->owner = $_SESSION['id'];

		try{

			$query = $this->pdo->prepare("INSERT INTO shorten(hash, url, data, clicks, owner) VALUES (?, ?, ?, ?, ?)");
			$query->bindParam(1, $this->hash, PDO::PARAM_INT);
			$query->bindParam(2, $this->url, PDO::PARAM_STR);
			$query->bindParam(3, $this->data, PDO::PARAM_STR);
			$query->bindParam(4, $this->clicks, PDO::PARAM_INT);
			$query->bindParam(5, $this->owner, PDO::PARAM_INT);
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

	public function readUser($username, $password =  '') 
	{

		$query = $this->pdo->prepare("SELECT `password`, `id` FROM `users` WHERE `username` = ?");
		$query->bindValue( 1, $username );

		try{

			$query->execute();
			$data = $query->fetch();

			$stored_password = $data['password'];

			if ( $stored_password != @md5( $password ) )
				return;	

			
			$this->setLoggedIn( $data['id'] );

		}catch( PDOException $e ){
			echo $e->getMessage();
		}

	}

	public function checkNick($username) 
	{

		$query = $this->db->prepare("SELECT COUNT(`id`) FROM `users` WHERE `username`= ?");
		$query->bindValue(1, $username);

		try{			
			$query->execute();
			$rows = $query->fetchColumn();

			if( $rows != 1 )
				return;

			return true;

		} catch ( PDOException $e ){
			echo $e->getMessage();
		}

	}
	public function checkEmail($email) 
	{

		$query = $this->pdo->prepare("SELECT COUNT(`id`) FROM `users` WHERE `email`= ?");
		$query->bindValue(1, $email);

		try{

			$query->execute();
			$rows = $query->fetchColumn();

			if($rows != 1)
				return;

			return true;
			

		} catch (PDOException $e){
			die($e->getMessage());
		}

	}
}

$a = new shorten();
$t = $a->readUrl(14,'url');
