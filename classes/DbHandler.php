<?php
class DbHandler{
	private $mysqli;
	
	function connect($pre = ''){
		$r = fopen($pre.'conf.json', 'r');
		$contents = fread($r, filesize($pre.'conf.json'));
		$conf = json_decode($contents);	
		$mysqli = new mysqli($conf->host, $conf->username, $conf->password, 'vline-php-example');
		if (mysqli_connect_error()){
			return false;
		}
		else{
			$this->mysqli = $mysqli;
			return true;	
		}
	}
	
	function getUsers(){
		$query = "select * from `user`";
		return $this->mysqli->query($query);
	}

	function getAceUsers(){  //get the ACE LEARNING users from database
		$query = "select * from `user` where `accent`=0";
		return $this->mysqli->query($query);
	}
	
	function getNovoUsers(){  //get the NOVO LEARNING users from database
		$query = "select * from `user` where `dLearning`=0";
		return $this->mysqli->query($query);
	}


	
	function authAdmin($data){
		$query = "select * from `user` where `username` = '".$this->mysqli->real_escape_string($data['username'])."' and `password` = '".$this->mysqli->real_escape_string($data['password'])."' and `isadmin` = '1'";
		$result = $this->mysqli->query($query);
		if($result->num_rows == 0){
			$_SESSION['authed'] = 0;
			$_SESSION['plainuserauth'] = 0;
			return false;
		}
		else{
			$_SESSION['authed'] = 1;
			$_SESSION['plainuserauth'] = 1;
			$therow = $result->fetch_array(MYSQLI_ASSOC);
			$_SESSION['user'] = $therow;
			return true;
		}	
	}
	
	function authUser($data){
		if($this->authAdmin($data)){
			return true;	
		}
		else{
			$query = "select * from `user` where `username` = '".$this->mysqli->real_escape_string($data['username'])."' and `password` = '".$this->mysqli->real_escape_string($data['password'])."'";
			$result = $this->mysqli->query($query);
			if($result->num_rows == 0){
				$_SESSION['plainuserauth'] = 0;
				return false;
			}
			else{
				$query1="select `dLearning`,`accent` from `user` where `username` = '".$this->mysqli->real_escape_string($data['username'])."' and `password` = '".$this->mysqli->real_escape_string($data['password'])."'";
				$_SESSION['plainuserauth'] = 1;
				$therow = $result->fetch_array(MYSQLI_ASSOC);
				$_SESSION['u_id']=$therow['id'];
				$_SESSION['user'] = $therow;
				$result1 = $this->mysqli->query($query1);
				$therow1 = mysqli_fetch_row($result1);
				//$_SESSION['type']=$therow1[1];
				if(($therow1[0]=='1')&&($therow1[1]=='0'))
				{					
					$_SESSION['type']="novo";	//NOVO session
				}
				elseif(($therow1[0]=='0')&&($therow1[1]=='1'))
				{
					$_SESSION['type']="ace";	//ACE session
				} 
				else 
				{
					$_SESSION['type']="admin";	//admin session
				}

				
				return true;

			}
		}
	}
	
	function addUser($data){
		
		if(isset($_REQUEST['register_type'])){		
         $con=mysqli_connect("localhost","root","","vline-php-example");
         $a=$data['username'];
         $sql="select * from user where username='$a'";
         $res=mysqli_query($con,$sql);
         $row=mysqli_num_rows($res);
         if($row==1)
         {
         	echo"Username already taken.Please select a different username";
         }
     }
         
         else
         {
			if($_REQUEST['register_type']=="ace"){							
				$query = "insert into `user` set 
			`name` = '".$this->mysqli->real_escape_string($data['name'])."',
			`username` = '".$this->mysqli->real_escape_string($data['username'])."',
			`password` = '".$this->mysqli->real_escape_string($data['password'])."',
			 `accent` = '1',
			 `dLearning` = '0'";

			 $this->mysqli->query($query);
			 header("location:login.php");

			}elseif($_REQUEST['register_type']=="novo"){				
				$query = "insert into `user` set 
			`name` = '".$this->mysqli->real_escape_string($data['name'])."',
			`username` = '".$this->mysqli->real_escape_string($data['username'])."',
			`password` = '".$this->mysqli->real_escape_string($data['password'])."',
			 `dLearning` = '1',
			 `accent` = '0'";
			 $this->mysqli->query($query);
			 header("location:login.php");
			}else{

				$error_msg="There is some error! Can't register.";
			}

		}
	}
	}
	
	function checkUsername($data){
		$query = "select * from `user` where `username` = '".$this->mysqli->real_escape_string($data['username'])."' and `id` != ".$data['exclude'];
		$result = $this->mysqli->query($query);
		if($result->num_rows == 0){
			return true;
		}
		else{	
			return false;
		}
	}
	
	function getUser($id){
		$query = "select * from `user` where `id` = '".$id."'";
		$result = $this->mysqli->query($query);
		if($result->num_rows == 0){
			return false;	
		}
		else{
			return mysqli_fetch_array($result, MYSQLI_ASSOC);	
		}
	}
	
	function saveUser($data){
		$query = "update `user` set 
			`name` = '".$this->mysqli->real_escape_string($data['name'])."',
			`username` = '".$this->mysqli->real_escape_string($data['username'])."',
			`password` = '".$this->mysqli->real_escape_string($data['password'])."'
			where `id` = '".$data['id']."'";
		$this->mysqli->query($query);
	}
	
	function deleteUser($id){
		$query = "delete from `user` where `id` = '".$id."'";
		$this->mysqli->query($query);
	}

?>