<?php 

namespace Controller;

defined('ROOTPATH') OR exit('Access Denied!');

use \Model\User;
use \Core\Request;

/**
 * signup class
 */
class Signup
{
	use MainController;

	public function index()
	{
		$data = [];

		$req = new Request;
		if($req->posted())
		{

			$user = new User();
			if($user->validate($req->post()))
			{
				//save to database
				$password = password_hash($req->post('password'), PASSWORD_DEFAULT);
				$req->set('password',$password);
				$req->set('date',date("Y-m-d H:i:s"));

				$user->insert($req->post());
				message("Profile created successfully");
				redirect('login');
			}

			$data['errors'] = $user->errors;
		}


		$this->view('signup',$data);
	}

}
