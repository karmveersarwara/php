<?php

// session_set_cookie_params(2*7*24*60*60);
session_set_cookie_params(0,'/','', isset($_SERVER["HTTPS"]));
session_name('KSLOGIN');

// ini_set( "session.gc_maxlifetime", 2*7*24*60*60 );
// ini_set( "session.cookie_lifetime", 2*7*24*60*60 );

// session_start();
if(!session_id()) session_start();

class Push {
	  private $host  = 'localhost';
    private $user  = 'root';
    private $password   = "";
    private $database  = "crm";
    
	  private $userTable = 'users';
	  private $departmentsTbl = 'departments';
    private $rolesTable = 'roles';
    private $permTable = 'permissions';
    private $rolePermTable = 'roles_permissions';
    private $clientsTable = 'clients';
    private $filesTable = 'files';
    private $festivalTable = 'festival';
    private $packageTable = 'package';
    private $socialWorkTable = 'social_work';
    private $servicesTable = 'services';
    private $jobsTable = 'jobs';
    private $jobProTable = 'job_pro';
    private $categoryTable = 'category';
	
	

	private $dbConnect = false;
	private $time = null;
	private $company = null;

    public function __construct(){
        if(!$this->dbConnect){ 
            $conn = new mysqli($this->host, $this->user, $this->password, $this->database);
            if($conn->connect_error){
                die("Error failed to connect to MySQL: " . $conn->connect_error);
            }else{
                $this->dbConnect = $conn;
            }
        }
		if(!$this->time){ 
			date_default_timezone_set("Asia/Kolkata");
			$time = date('YmdHis'); 
			$this->time = $time;
		}
		if(!$this->company){
			$this->company = 2;
		}
    }
	
	public function real_escape_string($data){ 
		return  mysqli_real_escape_string($this->dbConnect, $data) ;
	}
	private function getData($sqlQuery) {
		$result = mysqli_query($this->dbConnect, $sqlQuery);
		if(!$result){
			die('Error in query: '. mysqli_error($this->dbConnect));
		}
		$data= array();
		while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
			// $data[]=$row;     
			$data[]=(object)$row;        
		}
		return $data;
	}

	public function query($query){ 
		$sqlQuery = "$query";
		$result =mysqli_query($this->dbConnect, $sqlQuery);
		if(!$result){
			return ("Error in query: $sqlQuery". mysqli_error($this->dbConnect));
		} else {
			return $result;
		} 
	}

	// $temp = array_unique(array_column($ComJobList, 'id'));
    //     $ComJobListu = array_intersect_key($ComJobList, $temp);
	
	// $sqlQuery="SELECT work.id, work.name, work.clientid, work.donetime, work.viwetime, work.status FROM work INNER JOIN works ON work.id = works.workid   WHERE works.status=1 AND works.empid='$cusid'  AND works.donetime>=$reDate ORDER BY works.donetime ASC ";


	public function CheckPermissions($role_id,$permissions=null){
		$sqlQuery ="SELECT p.perm_mod FROM ".$this->rolePermTable."  r LEFT JOIN ".$this->permTable." p USING (`perm_id`) WHERE r.`role_id`=$role_id AND p.`perm_mod`='$permissions' ";
		$result =$this->getData($sqlQuery);
		return count($result)==0? false:true;
	}
	public function permissions($role_id){
		$sqlQuery ="SELECT p.perm_mod FROM ".$this->rolePermTable." r LEFT JOIN ".$this->permTable." p USING (`perm_id`) WHERE r.`role_id`=$role_id "; 
		$result =$this->getData($sqlQuery);
		return array_column($result, 'perm_mod') ; 
	}

	public function userDetail($id){
		$sqlQuery = "SELECT * FROM ".$this->userTable." WHERE id='$id' ";
		$data=$this->getData($sqlQuery);
		if(!$data){$user = (object) ['id' => 0, 'user' => null, 'name' => null, 'img' => '','email' => 'karmveersarwara@gmail.com', 'mobile' => 8058088078, 
			'password' => '', 'role_id' => 0, 'department' => 0, 'attend' => 0, 'web' => 0, 'created_at' => '2023-05-11 11:09:52', 'modify_at' => '',
			 'deleted_at' => '', 'status' => 1,'role_name' => 'Web Developer','department_name' => 'Web Developer',];}
		else{$user=$data[0];}
		if ($user->role_id==0) {
			$user->role_name = null;
			$user->department =null;
			$user->department_name = null;
		}else{
			$role=$this->role($user->role_id);
			$department=$this->department($role->depart_id);
			$user->role_name = $role->name;
			$user->department = $role->depart_id;
			$user->department_name = $department->name;
			// $user->permissions = $this->permissions($user->role_id);
		}
		return  $user;
	}
	public function department($id){
		$sqlQuery = "SELECT * FROM ".$this->departmentsTbl." WHERE id='$id'  ";
		$data=$this->getData($sqlQuery);
		if(!$data){return null;}
		return  $data[0];
	}
	public function role($id){
		$sqlQuery = "SELECT * FROM ".$this->rolesTable." WHERE id='$id'   ";
		$data=$this->getData($sqlQuery);
		if(!$data){return null;}
		return  $data[0];
	}

	public function services($id){
		$sqlQuery = "SELECT * FROM ".$this->servicesTable." WHERE id='$id'   ";
		$data=$this->getData($sqlQuery);
		if(!$data){return null;}
		return  $data[0];
	}

	
	public function listServices($condition){
		if(!$condition){$condition=' id>0 ' ;}
		$sqlQuery = "SELECT * FROM ".$this->servicesTable." WHERE $condition  AND deleted_at IS NULL  ";
		// ORDER BY name DESC
		return  $this->getData($sqlQuery);
	}

	public function listEmployee($condition){
		// $companyId=$this->companyId();
		if(!$condition){$condition=' id>0 ' ;}
		$sqlQuery = "SELECT * FROM ".$this->userTable." WHERE $condition  AND deleted_at IS NULL ORDER BY role_id ASC ";
		return  $this->getData($sqlQuery);
	}

	public function listClients($condition){
		if(!$condition){$condition=' id>0 ' ;}
		$sqlQuery = "SELECT * FROM ".$this->clientsTable." WHERE $condition  AND deleted_at IS NULL ORDER BY package_id DESC, post DESC, name ASC ";
		// return  $this->getData($sqlQuery);
		$result = mysqli_query($this->dbConnect, $sqlQuery);
		if(!$result){ die('Error in query: '. mysqli_error($this->dbConnect));}
		$data= array();
		while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
			$rows=(object)$row; 
			if ($rows->package_id!=10) {
				$Packages = $this->listPackages(" id=$rows->package_id ");
				$rows->post=$Packages[0]->post;
				$rows->video=$Packages[0]->video;
				$rows->carousel=$Packages[0]->carousel;
				$rows->package=$Packages[0]; 
			}
			$rows->total=$rows->post+$rows->video+$rows->carousel;
			$data[]=$rows; $rows->history=false;
		} 
		return  $data;
	} 
	
	public function listDepartments($condition){
		if(!$condition){$condition=' id>0 ' ;}
		$sqlQuery = "SELECT * FROM ".$this->departmentsTbl." WHERE $condition  AND deleted_at IS NULL ORDER BY id ASC ";
		return  $this->getData($sqlQuery);
	}
	public function listRoles($condition){
		if(!$condition){$condition=' id>0 ' ;}
		$sqlQuery = "SELECT * FROM ".$this->rolesTable." WHERE $condition  AND deleted_at IS NULL ORDER BY id ASC ";
		return $this->getData($sqlQuery);;
	} 
	
	public function listCategory(){
		$sqlQuery = "SELECT * FROM ".$this->categoryTable." WHERE status='1'  AND deleted_at IS NULL ORDER BY id ASC ";
		return $this->getData($sqlQuery);;
	}
	

	public function listPackages($condition){
		if(!$condition){$condition=' id>0 ' ;}
		$sqlQuery = "SELECT * FROM ".$this->packageTable." WHERE $condition  AND deleted_at IS NULL ORDER BY id ASC ";
		return  $this->getData($sqlQuery);
	} 

	public function listFestival($condition){
		if(!$condition){$condition=' id>0 ' ;}
		$sqlQuery = "SELECT * FROM ".$this->festivalTable." WHERE $condition  AND deleted_at IS NULL ORDER BY date ASC ";
		return  $this->getData($sqlQuery);
	} 
	
	public function listSocialWork($condition){
		if(!$condition){$condition=' id>0 ' ;}
		$sqlQuery = "SELECT * FROM ".$this->socialWorkTable." WHERE $condition  AND deleted_at IS NULL ORDER BY id DESC ";
		return  $this->getData($sqlQuery);
	}
	
	public function  listSocialClient($month,$year,$pack=0,$id=0){
		$time=$this->time;
		$dateCr=date_create("$time");
		$thismonth= date_format($dateCr,"m");
		$thisyear= date_format($dateCr,"Y");
		$condition="social='1' ";
		if(!$pack==0){ $condition="social='1' AND package_id='$pack' ";}
		if(!$id==0){ $condition=" id='$id' ";}
		$sqlQuery = "SELECT * FROM ".$this->clientsTable." WHERE $condition  AND deleted_at IS NULL ORDER BY package_id DESC, post DESC, name ASC ";
		$result = mysqli_query($this->dbConnect, $sqlQuery);
		if(!$result){ die('Error in query: '. mysqli_error($this->dbConnect));}
		$data= array();
		while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
			$rows=(object)$row; 
			if($thisyear>$year){$history=true; 
			}else if($thisyear==$year){
				if ($thismonth==$month || $thismonth<$month ) {$history=false;
				}else{$history=true;}
			}else{$history=false;}

			if($history==true){

			}else{
				if ($rows->package_id!=10) {
					$Packages = $this->listPackages(" id=$rows->package_id ");
					$rows->post=$Packages[0]->post;
					$rows->video=$Packages[0]->video;
					$rows->carousel=$Packages[0]->carousel;
					$rows->success=$Packages[0]->success;
					$rows->package=$Packages[0];
				}
			}
			$rows->total=$rows->post+$rows->video+$rows->carousel;
			$rows->history=$history;
			$data[]=$rows;
		} 
		return  $data;
	}
	
	public function compJobPro($is,$emp,$time=0,$status=1,$services=null){
	    if ($services) {
			$sqlQuery="SELECT * FROM ".$this->jobsTable." INNER JOIN ".$this->jobProTable." ON jobs.id = job_pro.job_ids   
			WHERE job_pro.typ IS NULL AND jobs.service_id='$services' AND job_pro.state='$status' AND job_pro.emp_ids='$emp'  AND job_pro.done LIKE '$time%' ORDER BY job_pro.done ASC ";
		}else{
		  //  LIKE 'a%' AND job_pro.done>=$time
		    if($is==0){
            $sqlQuery="SELECT * FROM ".$this->jobsTable." INNER JOIN ".$this->jobProTable." ON jobs.id = job_pro.job_ids   
    		WHERE   job_pro.state='$status' AND job_pro.emp_ids='$emp'  AND job_pro.done LIKE '$time%' ORDER BY job_pro.done ASC ";
		    }else{
            $sqlQuery="SELECT * FROM ".$this->jobsTable." INNER JOIN ".$this->jobProTable." ON jobs.id = job_pro.job_ids   
    		WHERE  job_pro.typ IS NULL AND job_pro.state='$status' AND job_pro.emp_ids='$emp'  AND job_pro.done LIKE '$time%' ORDER BY job_pro.done ASC ";
		    }
		}
		$result =  $this->getData($sqlQuery);
		if($is==0){return $result;}
		$temp = array_unique(array_column($result, 'id')); 
        return array_intersect_key($result, $temp);
	}

	public function proceedJobPro($job_ids){
		$sqlQuery = "SELECT * FROM ".$this->jobProTable." WHERE job_ids='$job_ids' ORDER BY job_id ASC ";
		$result = mysqli_query($this->dbConnect, $sqlQuery);
		if(!$result){ die('Error in query: '. mysqli_error($this->dbConnect));}
		$data= array();
		while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
			$rows=(object)$row;
			$emp = $this->userDetail($rows->emp_ids);
			$rows->emp=$emp;
			$data[]=$rows;
		}//return $this->getData($sqlQuery);
		return  $data;
	}

	public function jobLists($condition){
		if(!$condition){$condition=' id>0 ' ;}
		$sqlQuery = "SELECT * FROM ".$this->jobsTable." WHERE $condition  AND deleted_at IS NULL ORDER BY id ASC ";
		return  $this->getData($sqlQuery);
	}
	public function listJobs($condition,$order=null){
		if(!$condition){$condition=' id>0 ';}
		if(!$order){$order=' id DESC ';}
		$sqlQuery = "SELECT * FROM ".$this->jobsTable." WHERE $condition  AND deleted_at IS NULL ORDER BY $order ";
		$result = mysqli_query($this->dbConnect, $sqlQuery);
		if(!$result){ die('Error in query: '. mysqli_error($this->dbConnect));}
		$data= array();
		while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
			$rows=(object)$row;
			$clients = $this->listClients(" id='$rows->client_id' ") ;
			if (count($clients)==1) {$client= $clients[0];}else{$client=null;}
			$rows->client=$client;
			$emp = $this->userDetail($rows->emp_id);
			$rows->emp=$emp;
			$manager = $this->userDetail($rows->emp_id);
			$rows->manager=$manager;
			$proceedJobPro = $this->proceedJobPro($rows->id);
			$rows->proceed=$proceedJobPro;
			$services = $this->services($rows->service_id);
			$rows->service=$services;
			$data[]=$rows;        
		}// $result =$this->getData($sqlQuery);
		return  $data;
	} 




	public function updateUserProfile($id,$img) {
		$sqlUpdate = "UPDATE ".$this->userTable." SET img='$img' WHERE id='$id'";
		$result =mysqli_query($this->dbConnect, $sqlUpdate);
		if(!$result){
			return ("Error in query: $sqlUpdate". mysqli_error($this->dbConnect));
		} else {
			return $result;
		}
	}

	public function updateClientProfile($id,$img) {
		$sqlUpdate = "UPDATE ".$this->clientsTable." SET logo='$img' WHERE id='$id'";
		$result =mysqli_query($this->dbConnect, $sqlUpdate);
		if(!$result){
			return ("Error in query: $sqlUpdate". mysqli_error($this->dbConnect));
		} else {
			return $result;
		}
	}
	
	public function updateSocialWork($id,$clientId,$name,$date,$dis,$caption,$notes,$url) { 
		$dateCr=date_create("$date");
		$month= date_format($dateCr,"m");
		$year= date_format($dateCr,"Y");
		$time=$this->time;
	    if($id==0){
			$clients =$this->listClients(" id='$clientId'");
			$typ=$dis; 
			$total = $clients[0]->post+$clients[0]->video+$clients[0]->carousel;
			if ($typ == 1) {
			    $number = $clients[0]->post;
			} else if ($typ == 2) {
			    $number = $clients[0]->video;
			} else if ($typ == 3) {
			    $number = $clients[0]->carousel;
			}
	        $sqlUpdate = "INSERT INTO ".$this->socialWorkTable."(client_id,title,start,month,year,typ,className,number,total,created_at,modify_at,status) VALUES('$clientId','$name','$date','$month','$year','$dis','$url','$number','$total','$time','$time','0')";
	    }else{
	        $sqlUpdate = "UPDATE ".$this->socialWorkTable." SET title='$name',start='$date',dis='$dis',month='$month',year='$year',caption='$caption',notes='$notes',modify_at='$time' WHERE id='$id'";
	        if($url!==''){
	            $sqlUpdateImg = "UPDATE ".$this->socialWorkTable." SET img='$url' WHERE id='$id'";
	            $resultImg =mysqli_query($this->dbConnect, $sqlUpdateImg);
	        }
	    }
		$result =mysqli_query($this->dbConnect, $sqlUpdate);
		if(!$result){
			return ("Error in query: $sqlUpdate". mysqli_error($this->dbConnect));
		} else {
			return $result;
		}
	}

	public function updateHoliday($id,$name,$date,$clientId,$category) { 
		$time=$this->time;
	    if($id==0){
	        $sqlUpdate = "INSERT INTO ".$this->festivalTable."(name,date,client,category,created_at,modify_at,status) VALUES('$name','$date','$clientId','$category','$time','$time','0')";
	    }else{
	        $sqlUpdate = "UPDATE ".$this->festivalTable." SET name='$name',date='$date',category='$category',client='$clientId',modify_at='$time' WHERE id='$id'";
	    }
		$result =mysqli_query($this->dbConnect, $sqlUpdate);
		if(!$result){
			return ("Error in query: $sqlUpdate". mysqli_error($this->dbConnect));
		} else {
			return $result;
		}
	}

	public function updateService($id,$name,$track){ 
		$time=$this->time;
	    if($id==0){
	        $sqlUpdate = "INSERT INTO ".$this->servicesTable."(name,status) VALUES('$name','0')";
	    }else{
	        $sqlUpdate = "UPDATE ".$this->servicesTable." SET name='$name',track='$track',modify_at='$time' WHERE id='$id'";
	    }
		$result =mysqli_query($this->dbConnect, $sqlUpdate);
		if(!$result){
			return ("Error in query: $sqlUpdate". mysqli_error($this->dbConnect));
		} else {
			return $result;
		}
	}
	
	

	public function saveJobs($client_id,$service_id,$name,$dis,$caption,$workDate,$deadline,$img,$empId=null){ $time=$this->time;
		$sqlQuery = "INSERT INTO ".$this->jobsTable."( client_id,service_id,name,content,caption,date,deadline,idea_file,created_at,modify_at, status) 
		VALUES('$client_id','$service_id','$name','$dis', '$caption','$workDate','$deadline','$img','$time','$time','0')";
		$result = mysqli_query($this->dbConnect, $sqlQuery);
		$last_id = mysqli_insert_id($this->dbConnect);
		if ($service_id==1) {
			$sqlJobPro = "INSERT INTO ".$this->jobProTable."(job_ids,emp_ids,creates,view,done,remarks,state)VALUES('$last_id','3','$time','$time','$time','','1')";
			$results = mysqli_query($this->dbConnect, $sqlJobPro);
		}
		$sqlJobPros = $this->saveJobPro($last_id," ",$empId);
		if(!$result){return ('Error in query: '. mysqli_error($this->dbConnect));
		} else {return $last_id;}
	}
  
	public function saveJobPro($jobId,$remarks='',$empId=null,$typ=NULL){
		$time=$this->time;
		if ($empId!=null) {
		    if ($typ!=NULL) {
		        $sqlJobPro = "INSERT INTO ".$this->jobProTable."(job_ids,emp_ids,creates,remarks,typ,state)VALUES('$jobId','$empId','$time','$remarks','$typ','0')";
		    }else{
		        $sqlJobPro = "INSERT INTO ".$this->jobProTable."(job_ids,emp_ids,creates,remarks,state)VALUES('$jobId','$empId','$time','$remarks','0')";
		    }
		}else{
			$sqlJobPro = "INSERT INTO ".$this->jobProTable."(job_ids,creates,remarks,state)VALUES('$jobId','$time','$remarks','0')";
		}
		$result = mysqli_query($this->dbConnect, $sqlJobPro);
		if(!$result){
			return ('Error in query: '. mysqli_error($this->dbConnect));
		} else {
			return $result;
		}
	}

	public function saveUser($name,  $mobile, $email, $password){
		$time=$this->time;
		$sqlQuery = "INSERT INTO ".$this->userTable."(name,user, mobile, email, password,img,created_at,modify_at, status) VALUES('$name','$name','$mobile','$email', '$password','profile-user.jpg','$time','$time','0')";
		$result = mysqli_query($this->dbConnect, $sqlQuery);
		$last_id = mysqli_insert_id($this->dbConnect);
		if(!$result){
			return ('Error in query: '. mysqli_error($this->dbConnect));
		} else {
			return $last_id;
		}
	}


	public function saveFiles($name,$status){
		$time=$this->time;
		$sqlQuery = "INSERT INTO ".$this->filesTable."(name,created_at, status) VALUES('$name','$time','$status')";
		$result = mysqli_query($this->dbConnect, $sqlQuery);
		$last_id = mysqli_insert_id($this->dbConnect);
		if ($status==0) {
			$sqlUpdate = "UPDATE ".$this->filesTable." SET deleted_at='$time',status='1' WHERE id='$last_id'";
			$results =mysqli_query($this->dbConnect, $sqlUpdate);
		}
		if(!$result){
			return ('Error in query: '. mysqli_error($this->dbConnect));
		} else {

			return $last_id;
		}
	}
	// public function deleteSuccessStory($id) {
	// 	$time=$this->time;
	// 	$sqlUpdate = "UPDATE ".$this->success_storyTable." SET deleted_at='$time',status='0' WHERE id='$id'";
	// 	$result =mysqli_query($this->dbConnect, $sqlUpdate);
	// 	if(!$result){
	// 		return ("Error in query: $sqlUpdate". mysqli_error($this->dbConnect));
	// 	} else {
	// 		return $result;
	// 	}
	// } 

}



if(isset($_GET['logout'])){
	$_SESSION = array();
	session_destroy();
	header("Location: ./");
	exit;
}


$push = new Push();
if(isset($_SESSION['key'])){
    $user = $push->userDetail($_SESSION['id']);
 }

// ini_set('upload_max_filesize', '1000M');
// echo ini_get('upload_max_filesize'), ", " , ini_get('post_max_size');
