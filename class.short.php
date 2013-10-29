<?php

/**
 * Powered by Guilherme Henrique <guih[at]hotmail[dot]com[dot]br>
 * Author URL: http://guih.us/
 * 
 * Make tiny urls form long urls, 
 * It's have been stored into mysql table;
 * You can see stats about urls.
 * As like clicks, id, hash, and datetime when it posted
 * 
 **/

class shorten {
	/**
	 * The hash no need edit it! 
	 * 
	 **/
	public $hash;
	/* Our URL propetiye */
	public $url;
	/* Our data timestamp No need edit it! */
	public $data;
	/* OPur current number of clicks starts at 0 */
	public $clicks;
	/* Our PDO object class will handle querys for us */
	public $pdo;
	/* The uniq identifyer about the URL store into database */
	public $id;
	/* If you reading details about url you can sort what do you want to show/redirect/print/display */
	public $field;

	public function __construct()
	{
		$this->pdo = new PDO("
			mysql:host=HOST;
			dbname=DATABASE", 
			"USER", 
			"PASS",
		);
		$this->hash = uniqid('');
		$this->data = date('Y/m/d H:i:s');
		$this->clicks = 0;


		if ( !$pdo ) 
			return; 
	}

   	/**
   	 * 
   	 * Recipes a  long url
	 * 
	 * $url @string
	 * @since v1.0
	 * 
	 **/
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
	
	/**
	* This function acess database and return data about the url
	* 
	* $id (intval)
	* $field (string)
	*
	* @since v1.0
	**/
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
