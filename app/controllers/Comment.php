<?php 

namespace Controller;

defined('ROOTPATH') OR exit('Access Denied!');

use \Model\User;
use \Model\Comment as myComment;
use \Core\Session;
use \Core\Pager;

/**
 * comment class
 */
class Comment
{
	
	use MainController;

	
	public function index($id = null)
	{
		$ses = new Session;
		if(!$ses->is_logged_in())
		{
			redirect('login');
		}
		

		/** pagination vars **/
		$limit = 10;
		
		$data['pager'] = new Pager($limit);
		
		$offset = $data['pager']->offset;

		$comment = new myComment;

		$data['comment'] = $comment->where(['id'=>$id]);
		if($data['comment'])
		{
			$data['comment'] = $comment->add_user_data($data['comment']);
			$data['comment'] = $comment_row = $data['comment'][0];

		}		

		$this->view('comment',$data);
	}

	public function edit($id = null)
	{
		$ses = new Session;
		if(!$ses->is_logged_in())
		{
			redirect('login');


		}
		
		$comment = new myComment;
		$user_id = user('id');

		$data['comment'] = $comment->where(['id'=>$id,'user_id'=>$user_id]);
		if($data['comment'])
		{
			$data['comment'] = $comment->add_user_data($data['comment']);
			$data['comment'] = $data['comment'][0];
		}		

		$this->view('comment-edit',$data);
	}

	public function delete($id = null)

	{
		$ses = new Session;
		if(!$ses->is_logged_in())
		{
			redirect('login');
			
		}
		
		$comment = new myComment;
		$user_id = user('id');
		
		$data['comment'] = $comment->where(['id'=>$id,'user_id'=>$user_id]);
		if($data['comment'])
		{
			$data['comment'] = $comment->add_user_data($data['comment']);
			$data['comment'] = $data['comment'][0];
		}		

		$this->view('comment-delete',$data);
	}

}
