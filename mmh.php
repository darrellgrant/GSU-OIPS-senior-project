<?php
//make sure users arrive here via the "submit" button and not via URL window
if(isset($_POST['submit']))  {
include $_SERVER['DOCUMENT_ROOT'] . "/connectdb.php";

$fname = $_POST['first'];
$lname = $_POST['last'];
$email = $_POST['email'];
$pass1 = $_POST['password'];
$pass2 = $_POST['confirm_password'];
	
    if(empty($fname)||empty($lname)||empty($email)||empty($pass1)||empty($pass2)){
        echo "Submission Error: Fields are Empty!";
        exit();
    }else{
        if($pass1 != $pass2){
            echo "Submission Error: Passwords Don't Match!";
            exit();
            }else{
            //resolve to one password
            $pwd = $pass1;
    
            if( !preg_match("/^[a-zA-z]*$/", $fname) || !preg_match("/^[a-zA-z]*$/", $lname)   || !preg_match("/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[^a-zA-Z0-9])(?!.*\s).{8,20}$/", $pwd )     ){
            echo "Submission Error: Invalid Entry";
            exit();
            
        }else{
                //check email is valid
                if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
                    echo "Submission Error: Email Invalid";
                    exit();
                }else{
                    //check for duplicate email
                    $sql = "SELECT * FROM student WHERE email = '$email'";
                    $result = mysqli_query($conn, $sql);
                    $resultCheck = mysqli_num_rows($result);
                    if ($resultCheck > 0) {
                        echo "Submission Error: email already in database";
                        exit();
                    }else{
                        $hashedPWD = password_hash($pwd, PASSWORD_DEFAULT);
                        //insert user into database:
                        //hash password 
                        $sql = "INSERT INTO Student (FirstName, LastName, Email, Password) VALUES (?,?,?,?)";
                       		$stmt = mysqli_prepare($conn,$sql);
                            mysqli_stmt_bind_param($stmt, "ssss", $fname, $lname, $email, $hashedPWD);
                            if(mysqli_stmt_execute($stmt)){
                             echo "<br>".'submission success!'."<br>";
                             exit();
							}
                    
                        
                    }//end insert into database
                }//end duplicate email check
            }//end check preg match
    }//end check if fields empty
    }//end isset()
}else{
    echo "Submission Error";
    exit();
}//end submission error
?>