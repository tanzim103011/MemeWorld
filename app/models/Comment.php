<?php

namespace Model;

defined('ROOTPATH') OR exit('Access Denied!');

/**
 * Comment class
 */
class Comment
{
	
	use Model;

	protected $table = 'comments';

	protected $allowedColumns = [

		'image',
		'comment',
		'user_id',
		'post_id',
		'date',
	];

	public function add_user_data($rows)
	{

		foreach ($rows as $key => $row) {
			
			$res = $this->get_row("select * from users where id = :id ",['id'=>$row->user_id]);
			$rows[$key]->user = $res;
		}

		return $rows;
	}

	public function validate($post_data,$files_data, $id = null)
	{
		$this->errors = [];

		if(empty($post_data['comment']) && empty($files_data['image']['name']))
		{
			$this->errors['comment'] = "Please type something to comment";
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
			create table if not exists comments
			(
				id int unsigned primary key auto_increment,
				user_id int unsigned,
				post_id int unsigned,
				comment text null,
				image varchar(1024) null,
				date datetime null,

				key user_id (user_id),
				key post_id (post_id),
				key date (date)

			)
		";

		$this->query($query);
	}
}