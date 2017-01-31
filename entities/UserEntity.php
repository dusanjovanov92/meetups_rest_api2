<?php

class UserEntity implements JsonSerializable{
	protected $id;
	protected $display_name;
	protected $email;
	protected $photo_url;

	public function __construct(array $data){
		$this->id = $data["id"];
		$this->display_name = $data["display_name"];
		$this->email = $data["email"];
		$this->photo_url = $data["photo_url"];
	}

	/**
	 * @return integer
	 */
	public function getId()
	{
	    return $this->id;
	}

	/**
	 * @return string
	 */
	public function getDisplayName()
	{
	    return $this->display_name;
	}		

	/**
	* @return string
	*/
	public function getEmail()
	{
		return $this->email;
	}	

	/**
	 * @return string
	 */
	public function getPhotoUrl()
	{
	    return $this->photo_url;
	}

	public function jsonSerialize(){
		return ["id" => $this->id,
				"displayName" => $this->display_name,
				"email" => $this->email,
				"photoUrl" => $this->photo_url];
	}
}