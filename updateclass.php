<?php 
include $_SERVER['DOCUMENT_ROOT'] . "/connectdb.php";
if($_POST['Update']) {
    $courseid = $_POST['courseid'];
	$course = $_POST['course'];
	$coursename = $_POST['coursename'];
	$credithours = $_POST['credithours'];
	$crn = $_POST['crn'];
    $query = "UPDATE Classes SET Course='$course', CourseName='$coursename', CreditHours='$credithours', CRN='$crn' where CourseID='$courseid'";
    echo $query;
	if(mysqli_query($conn,$query)) {
		echo "Updated $course";
	}
	else {
	    echo "Update failed";
	}
}
?>