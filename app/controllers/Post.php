<?php 

namespace Controller;

defined('ROOTPATH') OR exit('Access Denied!');

use \Model\User;
use \Model\Post as myPost;
use \Model\Comment as myComment;
use \Core\Session;
use \Core\Pager;

/**
 * post class
 */
class Post
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

		$post = new myPost;
		$comment = new myComment;

		$data['post'] = $post->where(['id'=>$id]);
		if($data['post'])
		{
			$data['post'] = $post->add_user_data($data['post']);
			$data['post'] = $post_row = $data['post'][0];

			/** get comments for this post **/
			$comment->order_type 	= 'asc';
			$comment->offset 		= $offset;
			
			$data['comments'] 		= $comment->where(['post_id'=>$post_row->id]);
			if($data['comments'])
			{
				$data['comments'] = $comment->add_user_data($data['comments']);
			}
		}		

		$this->view('post',$data);
	}

	public function edit($id = null)
	{
		$ses = new Session;
		if(!$ses->is_logged_in())
		{
			redirect('login');
		}
		
		$post = new myPost;
		$user_id = user('id');

		$data['post'] = $post->where(['id'=>$id,'user_id'=>$user_id]);
		if($data['post'])
		{
			$data['post'] = $post->add_user_data($data['post']);
			$data['post'] = $data['post'][0];
		}		

		$this->view('post-edit',$data);
	}

	public function delete($id = null)
	{
		$ses = new Session;
		if(!$ses->is_logged_in())
		{
			redirect('login');
		}
		
		$post = new myPost;
		$user_id = user('id');
		
		$data['post'] = $post->where(['id'=>$id,'user_id'=>$user_id]);
		if($data['post'])
		{
			$data['post'] = $post->add_user_data($data['post']);
			$data['post'] = $data['post'][0];
		}		

		$this->view('post-delete',$data);
	}

}
