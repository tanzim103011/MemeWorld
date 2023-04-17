<?php 

namespace Controller;

defined('ROOTPATH') OR exit('Access Denied!');

use \Core\Session;
use \Model\User;
use \Model\Post;
use \Model\Comment as myComment;
use \Core\Request;
use \Model\Image;

/**
 * ajax class
 */
class Ajax
{
	use MainController;

	public function index()
	{
		$ses = new Session;
		if(!$ses->is_logged_in())
		{
			die;
		}

		$req = new Request;
		$user = new User();
		$info['success'] = false;
		$info['message'] = "";

		if($req->posted())
		{
			$data_type = $req->input('data_type');
			$info['data_type'] = $data_type;

			if($data_type == 'profile-image')
			{
				$image_row = $req->files('image');

				if($image_row['error'] == 0)
				{

					$folder = "uploads/";
					if(!file_exists($folder))
					{
						mkdir($folder,0777,true);
					}

					$destination = $folder . time() . $image_row['name'];
					move_uploaded_file($image_row['tmp_name'], $destination);

					$image_class = new Image;
					$image_class->resize($destination,1000);

					$id = user('id');
					$row = $user->first(['id'=>$id]);

					if(file_exists($row->image))
						unlink($row->image);

					$user->update($id, ['image'=>$destination]);
					$row->image = $destination;
					$ses->auth($row);

					$info['message'] = "Profile image changed successfully";
					$info['success'] = true;
				}

			}else
			if($data_type == 'create-post')
			{
				
				$id = user('id');
 				$post = new Post;


				if($post->validate($req->post(), $req->files()))
				{

	 				$image_row = $req->files('image');

					if(!empty($image_row['name']) && $image_row['error'] == 0)
					{

						$folder = "uploads/";
						if(!file_exists($folder))
						{
							mkdir($folder,0777,true);
						}

						$destination = $folder . time() . $image_row['name'];
						move_uploaded_file($image_row['tmp_name'], $destination);

						$image_class = new Image;
						$image_class->resize($destination,1000);

					}


	 				$arr = [];
	 				$arr['post'] 	= $req->input('post');
	 				$arr['image'] 	= $destination ?? '';
	 				$arr['user_id'] = $id;
	 				$arr['date'] 	= date("Y-m-d H:i:s");

					$post->insert($arr);
					$info['message'] = "Post created successfully";
					$info['success'] = true;
				}else
				{
					$info['message'] = "Please type something to post";
					$info['success'] = false;
				}

			}else
			if($data_type == 'create-comment')
			{
				
				$id = user('id');
 				$comment = new myComment;


				if($comment->validate($req->post(), $req->files()))
				{

	 				$image_row = $req->files('image');

					if(!empty($image_row['name']) && $image_row['error'] == 0)
					{

						$folder = "uploads/";
						if(!file_exists($folder))
						{
							mkdir($folder,0777,true);
						}

						$destination = $folder . time() . $image_row['name'];
						move_uploaded_file($image_row['tmp_name'], $destination);

						$image_class = new Image;
						$image_class->resize($destination,1000);

					}


	 				$arr = [];
	 				$arr['comment'] = $req->input('comment');
	 				$arr['post_id'] = $req->input('post_id');
	 				$arr['image'] 	= $destination ?? '';
	 				$arr['user_id'] = $id;
	 				$arr['date'] 	= date("Y-m-d H:i:s");

					$comment->insert($arr);
					$info['message'] = "Comment created successfully";
					$info['success'] = true;
				}else
				{
					$info['message'] = "Please type something to comment";
					$info['success'] = false;
				}

			}else
			if($data_type == 'edit-post')
			{
				
				$user_id = user('id');
				$post_id 	= $req->input('post_id');
 				$post = new Post;

				$row = $post->first(['id'=>$post_id,'user_id'=>$user_id]);

				if($row)
				{
					
	 				$image_row = $req->files('image');

					if(!empty($image_row['name']) && $image_row['error'] == 0)
					{

						$folder = "uploads/";
						if(!file_exists($folder))
						{
							mkdir($folder,0777,true);
						}

						$destination = $folder . time() . $image_row['name'];
						move_uploaded_file($image_row['tmp_name'], $destination);

						$image_class = new Image;
						$image_class->resize($destination,1000);

					}


	 				$arr = [];
	 				$arr['post'] 	= $req->input('post');

	 				if(!empty($destination))
	 					$arr['image'] 	= $destination;

					$post->update($post_id, $arr);

					$info['message'] = "Post edited successfully";
					$info['success'] = true;
				}
				
			}else
			if($data_type == 'profile-settings')
			{
				
				$user_id = user('id');
				$username 	= $req->input('username');
				$email 	= $req->input('email');
				$password 	= $req->input('password');

 				$user = new User;

				$row = $user->first(['id'=>$user_id]);

				if($row)
				{
 					
 					if($user->validate($req->post(), $user_id))
 					{

		 				$arr = [];
		 				$arr['username'] 	= $req->input('username');
		 				$arr['email'] 		= $req->input('email');

		 				if(!empty($password))
		 					$arr['password'] 		= password_hash($req->input('password'), PASSWORD_DEFAULT);
	 
						$user->update($user_id, $arr);

						$info['message'] = "Profile edited successfully";
						$info['success'] = true;
 					}else{
 						$info['message'] = "ERROR: " . implode(" & ", $user->errors);
						$info['success'] = false;
 					}

				}
				
			}else
			if($data_type == 'edit-comment')
			{
				
				$user_id = user('id');
				$comment_id 	= $req->input('comment_id');
 				$comment = new myComment;

				$row = $comment->first(['id'=>$comment_id,'user_id'=>$user_id]);

				if($row)
				{
					
	 				$image_row = $req->files('image');

					if(!empty($image_row['name']) && $image_row['error'] == 0)
					{

						$folder = "uploads/";
						if(!file_exists($folder))
						{
							mkdir($folder,0777,true);
						}

						$destination = $folder . time() . $image_row['name'];
						move_uploaded_file($image_row['tmp_name'], $destination);

						$image_class = new Image;
						$image_class->resize($destination,1000);

					}


	 				$arr = [];
	 				$arr['comment'] 	= $req->input('comment');

	 				if(!empty($destination))
	 					$arr['image'] 	= $destination;

					$comment->update($comment_id, $arr);

					$info['message'] = "Comment edited successfully";
					$info['success'] = true;
				}
				
			}else
			if($data_type == 'delete-post')
			{
				
				$user_id = user('id');
 				$post_id = $req->input('post_id');

 				$post = new Post;
 				$row = $post->first(['id'=>$post_id,'user_id'=>$user_id]);

 				if($row)
 				{
 					if($row->user_id == $user_id)
 					{

						$post->delete($post_id);
		  				
		  				if(file_exists($row->image ?? ''))
		  					unlink($row->image);

						$info['message'] = "Post deleted successfully";
						$info['success'] = true;
 					}
 				}
			}else
			if($data_type == 'delete-comment')
			{
				
				$user_id = user('id');
 				$comment_id = $req->input('comment_id');

 				$comment = new myComment;
 				$row = $comment->first(['id'=>$comment_id,'user_id'=>$user_id]);

 				if($row)
 				{
 					if($row->user_id == $user_id)
 					{

						$comment->delete($comment_id);
		  				
		  				if(file_exists($row->image ?? ''))
		  					unlink($row->image);

						$info['message'] = "Comment deleted successfully";
						$info['success'] = true;
 					}
 				}
			}

			echo json_encode($info);
		}

	}

}
