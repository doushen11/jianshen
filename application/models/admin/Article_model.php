<?php
	//后台的管理员控制器

	defined('BASEPATH') OR exit('No direct script access allowed');
	
	class Article_model extends CI_Model
	{

		private $dbprefix="";
		
		function __construct()
		{
			parent::__construct();
			$this->dbprefix=$this->db->dbprefix;
		}
		
		//更新文章内容
		public function edit_subs($id)
		{
			$_array=array(
				"title"=>$this->input->post("title"),
				"contents"=>addslashes($_POST["contents"]),
				"times"=>time(),
			);
			if($this->db->update("articles",$_array,array("id"=>$id)))
			{
				ajaxs(10000,"更新成功");
			}
			else
			{
				ajaxs(30000,"更新失败，网络连接失败");
			}
		}
		
	}