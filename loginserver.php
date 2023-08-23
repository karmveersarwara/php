<?php
    include_once ".Push.php";
	$typ=$_POST['typ'];
	$array=array();
	$errors = array();
	$result = array();
	$isNewUser='';

	if ($typ==0) {
		$isNewUser=true;
		$name=$_POST['name'];
		$mobile=$_POST['mobile'];
		$email=$_POST['email'];
		$password_1=$_POST['password'];
		$password_2=$_POST['password2'];
		if ($password_1!==$password_2) { array_push($errors, "Conform password Dose not match");}

		if (empty($name)) { array_push($errors, "Name is required"); } 
		if (empty($email)) { array_push($errors, "Email is required"); } 
		if (empty($mobile)) { array_push($errors, "Mobile is required"); }
		if (empty($password_1)) { array_push($errors, "Password is required"); }

		if(preg_match('/[^a-z0-9\-\_\.]+/i',$_POST['name'])){
			array_push($errors, "Your name contains invalid characters!");
		}

		// if(!checkEmail($_POST['email'])){ $errors[]='Your email is not valid!';}


		if (count($errors) == 0) {
			$password = md5($password_1);
			$condition="  mobile='$mobile'  ";
			$results = $push->listEmployee($condition);
			if (count($results) == 0) {
				$newUser = $push->saveUser($name,$mobile, $email, $password);
				$result=true;
			}else{
				array_push($errors, "Mobile number already registered");
			} 
		}

	}else{
		$isNewUser=false;
		$name=$_POST['name'];
		$password=$_POST['password'];

		if (empty($name)) {array_push($errors, "User name is required");}
		if (empty($password)) {array_push($errors, "Password is required");}

		if (count($errors) == 0) {
			$password = md5($password);
			//SELECT * FROM colors WHERE id IN (?, ?, ?) AND deleted_at IS NULL
			$condition=" user='$name' AND password='$password'  AND status='1' AND deleted_at IS NULL  ";
			$results = $push->listEmployee($condition);
			if (count($results) == 1) { 
				$result=true;
				foreach ($results as $row) { 
					// $_SESSION['id'] = $row['id']; 
					// $_SESSION['company']=$row->company;

					$_SESSION['key']=$row->role_id;
					$_SESSION['id'] = $row->id;
					$_SESSION['name'] = $row->name;
					$_SESSION['mobile'] = $row->mobile;
				 } 

			}else {
				array_push($errors, "Wrong username/password combination");
			}
		}
	}
 

    // $condition=" ";
    // $userList = $push->filterUser($condition);
    // foreach ($userList as $row) { 
    //     echo $row['name'];
    //  }
	// <!-- < ? =$row['id']? > -->
	$array['errors'] = $errors;
	$array['data'] = $result;
	$array['isNew'] = $isNewUser;
	// $array['result'] = true;
	
	echo json_encode($array);
?>
