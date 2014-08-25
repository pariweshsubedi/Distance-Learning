<?php
/**
 * JSON Web Token implementation
 *
 * Simple implementation of the vLine authentication process
 *
 */

class Vline{
	private $serviceID = "cmo";
	private $apiSecret = "iV9nZkwQLTxT0-1haIJGjuESnNFKGSD45-fxZWPF-DM";
	private $userID;
	private $userDisplayName;
	private $jwt;
	
	
	/**
     * @param string      $userID    The user ID
     *
     * @return void
     */
	function setUser($userID, $userDisplayName){
		$this->userID = $userID;	
		$this->userDisplayName = $userDisplayName;
	}
	
	
	/**
     * @return void 
     */
	function init(){
		$expiry = 48 * 60 * 60;
		$sub = $this->serviceID. ":" . $this->userID;
		$exp = time() + $expiry;
		$apisecret = $this->apiSecret;
		$apiSecretKey = JWT::urlsafeB64Decode($apisecret);

		$payload = array(
			"sub" => $sub,
			"iss" => $this->serviceID,
			"exp" => $exp
		);
		
		$this->jwt = JWT::encode($payload, $apiSecretKey);	
	}
	
	
	/**
     * @return jwt  
     */
	function getJWT(){
		return $this->jwt;	
	}
	
	
	/**
     * @return serviceID  
     */
	function getServiceID(){
		return $this->serviceID;	
	}
	
	/**
     * @return user ID
     */
	function getUserID(){
		return $this->userID;
	}
	
	
	/**
     * @return user display name
     */
	function getUserDisplayName(){
		return $this->userDisplayName;
	}
}
?>
