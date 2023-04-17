<?php 

namespace Controller;

defined('ROOTPATH') OR exit('Access Denied!');

use \Core\Session;
use \Model\User;
use \Core\Pager;

/**
 * search class
 */
class Search
{
	use MainController;

	public function index()
	{
		$ses = new Session;
		if(!$ses->is_logged_in())
		{
			redirect('login');
		}
		
		$data = [];
		
		/** pagination vars **/
		$limit = 10;
		$data['pager'] = new Pager($limit);
		$offset = $data['pager']->offset;

		$user = new User;
		$arr = [];
		$arr['find'] = $_GET['find'] ?? null;

		if($arr['find'])
		{
			$arr['find'] = "%".$arr['find'] ."%";
			$data['rows'] = $user->query("select * from users where username like :find || email like :find limit $limit offset $offset",$arr);
		}

		$this->view('search',$data);
	}

}
