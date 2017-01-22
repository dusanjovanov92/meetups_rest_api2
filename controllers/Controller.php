<?php

abstract class Controller{
	protected $db;

	public function __construct(\PDO $db){
		$this->db = $db;
	}
}