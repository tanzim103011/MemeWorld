<?php 

namespace Controller;

defined('ROOTPATH') OR exit('Access Denied!');

use \Model\User;
use \Core\Session;
use \Core\Pager;

/**
 * settings class
 */
class Settings
{
	use MainController;

	public function index()
	{
		
		$id = URL('slug') ?? user('id');

		$ses = new Session;
 
		if(!$ses->is_logged_in())
		{
			redirect('login');
		}

		//get user row
		$user = new user;
		$data['row'] = $row = $user->first(['id'=>$id]); 
 
		$this->view('settings',$data);
	}

}
