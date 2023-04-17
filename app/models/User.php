<?php

namespace Model;

defined('ROOTPATH') OR exit('Access Denied!');

/**
 * User class
 */
class User
{
	
	use Model;

	protected $table = 'users';

	protected $allowedColumns = [

		'image',
		'username',
		'email',
		'password',
		'date',
	];

	public function validate($data, $id = null)
	{
		$this->errors = [];

		if(empty($data['email']))
		{
			$this->errors['email'] = "Email is required";
		}else
		if(!filter_var($data['email'],FILTER_VALIDATE_EMAIL))
		{
			$this->errors['email'] = "Email is not valid";
		}

		if($id)
		{
			if($this->query("select id from $this->table where id != $id && email = '$data[email]' limit 1"))
			{
				$this->errors['email'] = "Email is already in use";
			}
		}

		if(empty($data['username']))
		{
			$this->errors['username'] = "A username is required";
		}else
		if(!preg_match("/^[a-zA-Z]+$/", $data['username']))
		{
			$this->errors['username'] = "Username can only have letters with no spaces";
		}
		
		if(!$id)
		{
			if(empty($data['password']))
			{
				$this->errors['password'] = "Password is required";
			}
			
			if(empty($data['terms']))
			{
				$this->errors['terms'] = "Please accept the terms and conditions";
			}
		}
		

		if(empty($this->errors))
		{
			return true;
		}

		return false;
	}

	public function create_table()
	{

		$query = "
			create table if not exists users
			(
				id int unsigned primary key auto_increment,
				username varchar(50) not null,
				image varchar(1024) null,
				email varchar(100) not null,
				password varchar(255) not null,
				date datetime null,

				key username (username),
				key email (email)

			)
		";

		$this->query($query);
	}
}