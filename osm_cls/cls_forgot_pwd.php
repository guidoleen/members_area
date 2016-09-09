<?php
class gu_forgot_password 
{
	private $user_email;
	private $new_pwd;
	private $time_now;
	private $Id;
	
	public function __construct()
	{
	}
	public function setNewPassw($useremail,$pwd)
	{
		$this->user_email = $useremail;
		$this->new_pwd = $pwd;
		
		//hash our new changePassword
		$hashedPWD = password_hash($this->new_pwd, PASSWORD_DEFAULT);
		
		$isv_db = @new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

			// Retrieve user ID from Email
			$stmt = $isv_db->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
			$stmt->bind_param('s', $this->user_email);
			$stmt->execute();
			$stmt->store_result();
			$stmt->bind_result($id);
			while($stmt->fetch())
			{
				$this->Id = $id;
			}
			
			// For brute force attack
			$this->time_now = time();
			$stmt = $isv_db->prepare("INSERT INTO login_attempts(user_id, time) VALUES ($this->Id,'$this->time_now')");
			$stmt->execute();
			
			// Update New password When no brute force
			if(!brute_force($this->Id, $this->time_now))
			{
				$stmt = $isv_db->prepare("UPDATE users SET pwd=? WHERE email=?");
				$stmt->bind_param('ss',$hashedPWD,$this->user_email);
				$stmt->execute();
			}
			else
			{
				echo "Te vaak geprobeerd...";
			}
		
		$stmt->close();
	}
}

function brute_force($id, $timenow)
{
	$timenow = time()-3600;

	$isv_db = @new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

	$stmt = $isv_db->prepare("SELECT time FROM login_attempts WHERE user_id= ? AND time > ?");
	$stmt->bind_param('is',$id, $timenow);
	$stmt->execute();
	$stmt->store_result();
	$iNumRows = $stmt->num_rows;
	$stmt->close();
	
	if($iNumRows > 4)
	{
		return true;
	}
	else
	{
		return false;
	}
}

?>