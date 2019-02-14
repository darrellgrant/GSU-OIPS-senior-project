<?php
include $_SERVER['DOCUMENT_ROOT'] . "/connectdb.php";
?>
<html>
<style>
#table {
    border-collapse: collapse; 
    border: 1px solid #ddd;
    z-index:1;
    position:absolute;
}

th, td, td > input {
    text-align: center;
    padding: 4px; 
}

.long {
    width: 250px;
}

.short {
    width:40px;
}

#table tr {
    border-bottom: 1px solid #ddd; 
}

#table tr.header, #myTable tr:hover {
    background-color: #f1f1f1;
}

.tooltip {
    position: relative;
}

.tooltip .tooltiptext {
    visibility: hidden;
    width: 250px;
    background-color: black;
    color: #fff;
    text-align: center;
    border-radius: 6px;
    position: absolute;
    z-index: 1;
}

.tooltip:hover .tooltiptext {
    visibility: visible;
}

div.hidden {
    visibility: hidden;
    height:0px;
    width:0px;
}

.modal {
  display: none; 
  position: fixed; 
  z-index: 1; 
  padding-top: 100px; 
  left: 0;
  top: 0;
  width: 100%; 
  height: 80%; 
  overflow: auto; 
  background-color: rgb(0,0,0); 
  background-color: rgba(0,0,0,0.4); 
  overflow-y: scroll;
}

.modal-content {
  background-color: #fefefe;
  margin: auto;
  padding: 20px;
  border: 1px groove #888;
  width: 80%;
  overflow:auto;
}


fieldset {
    display:table;
}

fieldset * {
    margin-bottom:8px;
}

.column {
    display:table-cell;
    text-align:right;
    width: 30%;
    padding:4px;
}

div.row {
    display:table-row;
}

#btndiv input,button {
    margin-left: 20px;
    margin-right: 20px;
}
</style>
<script>
//# function to filter table rows based on input characters
function search(col) {
  var input, filter, table, tr, td, i, txtValue, column, inp1, inp2, inp3, inp4;
  inp1 = document.getElementById("courseInput");
  inp2 = document.getElementById("courseNameInput")
  inp3 = document.getElementById("creditHoursInput");
  inp4 = document.getElementById("crnInput");
  if (col == 1) {
    input = inp1;
    inp2.value=inp3.value=inp4.value="";
  }
  else if (col == 2) {
    input = inp2;
    inp1.value=inp3.value=inp4.value="";
  }
  else if (col == 3) {
    input = inp3;
    inp1.value=inp2.value=inp4.value="";
  }
  else if (col == 4) {
    input = inp4;
    inp1.value=inp2.value=inp3.value="";
  }
  filter = input.value.toUpperCase(input);
  table = document.getElementById("table");
  tr = table.getElementsByTagName("tr");
  column = col;
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[column];
    if (td) {
      txtValue = td.textContent;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    }       
  }
}
</script>
<?php 
//# getting Update
if(isset($_POST['Add'])) {
    $course = $_POST['course'];
    $coursename = $_POST['coursename'];
    $credithours = $_POST['credithours'];
    $crn = $_POST['crn'];
    
    //# execute insert query
    $query = "INSERT INTO Classes (Course,CourseName,CreditHours,CRN) values ('$course','$coursename','$credithours','$crn')";
    if(mysqli_query($conn,$query)){
        
    }
    else {
        echo "Error adding class $course";
    }
}

//# getting Update
if(isset($_POST['Update'])) {
    $courseid = $_POST['courseid'];
	$course = $_POST['course'];
	$coursename = $_POST['coursename'];
	$credithours = $_POST['credithours'];
	$crn = $_POST['crn'];

    //# filter inputs
    
    //# execute update query
    $query = "UPDATE Classes SET Course='$course', CourseName='$coursename', CreditHours='$credithours', CRN='$crn' where CourseID='$courseid'";
	if(mysqli_query($conn,$query)) {
		
	}
	else {
	    echo "Error updating $course";
	}
}

if(isset($_POST['removecl'])) {
    $classid = $_POST['rmvclass'];
    $query = "DELETE FROM Classes where CourseID='$classid'";
    if(mysqli_query($conn,$query)) {
        
    }
    else {
        echo "Error deleting ";
    }
}
function varFilter($s){
	$val = mysqli_real_escape_string($conn,$s);
	$val = filter_var($val,FILTER_SANITIZE_STRING);
	$val = trim($val);
	return $val;
}
?>

<table id="table">
    <tr>    
        <th></th>
        <th><input type="text" id="courseInput" onkeyup="search(1)" placeholder="Search Course"></th>
        <th><input type="text" id="courseNameInput" onkeyup="search(2)" placeholder="Search Course Name"></th>
        <th><input type="text" id="creditHoursInput" onkeyup="search(3)" placeholder="Search Credit Hours"></th>
        <th><input type="text" id="crnInput" onkeyup="search(4)" placeholder="Search CRN"></th>
   </tr>
    <tr>
       <th><b>Course ID (Database)</b></th>
       <th><b>Course</b></th>
       <th><b>Course Name</b></th>
       <th><b>Credit Hours</b></th>
       <th><b>CRN</b></th>
        <th>
        </th>
       <th><form><input type="button" name="addclass" value="Add New Class" onclick="openModal('addModal')">
       </form></th>
   </tr>
<?php 
//# select Classes data
    $query = mysqli_query($conn,"Select * from Classes");
    while($row = mysqli_fetch_array($query)) { ?>
    <tr>
        <td class="tooltip"><span class="tooltiptext">References ProgramIDs in other functions</span><?php echo $row['CourseID'] ?></td>
        <td><?php echo $row['Course'] ?></td>
        <td class="long"><?php echo $row['CourseName'] ?></td>
        <td class="short"><?php echo $row['CreditHours'] ?></td>
        <td class="short"><?php echo $row['CRN'] ?></td>
        <td><form method="post" action="">
            <input type="hidden" name="classrow[]" value="<?php echo $row['CourseID']?>">
            <input type="hidden" name="classrow[]" value="<?php echo $row['Course']?>">
            <input type="hidden" name="classrow[]" value="<?php echo $row['CourseName']?>">
            <input type="hidden" name="classrow[]" value="<?php echo $row['CreditHours']?>">
            <input type="hidden" name="classrow[]" value="<?php echo $row['CRN']?>">
            <button type="submit" name="editbutton" onclick="openModal('editModal');">Edit</button>
            </form>
        </td>
        <td><form method="post" action="" onsubmit="return confirm('Delete this entry? Course: <?php echo $row['Course'] ?> Course Name: <?php echo $row['CourseName'] ?> Credit Hours: <?php echo$row['CRN'] ?>')">
            <input type="hidden" name="rmvclass" value="<?php echo $row['CourseID']?>">
            <input type="submit" name="removecl" value="Delete">
        </form></td>
    </tr>
 <?php } ?>
</table>
<div class="modal" id="addModal">
    <div class="modal-content">
        <p>Adding new Course</p>
       <form method="post" action="" class="form-container" onsubmit='document.getElementById("newclassform").style.display="hidden"'>
        <fieldset>
            <div class="row">
                <div class="column">Course: </div><input type="text" name="course" required>
            </div>
            <div class="row">
                <div class="column">Course Name: </div>
                <textarea rows="2" cols="50" name="coursename"> </textarea>
            </div>
            <div class="row">
                <div class="column">Credit Hours: </div>
                <input type="text" name="credithours" required>
            </div>
            <div class="row">
                <div class="column">CRN:</div> 
                <input type="text" name="crn" style="width:70px;">
            </div>
            <div class="row">
                <div class="column"></div>
                <div id="btndiv">
                <button type="submit" class="btnsubmit" name="Add" value="Add">Add</button>
                <button type="button" class="btncancel" onclick="closeModal('addModal')">Cancel</button>
                </div>
            </div>
        </fieldset>
        </form>
    </div>
</div>
<div id="editModal" class="modal">
    <div class="modal-content">
    <?php
        if(isset($_POST['classrow'])) {
            $classrow = $_POST['classrow'];
        }
    ?>
    <p>Editing <b><?php echo $classrow[1] ?> - <?php echo $classrow[2] ?></b></p>
    <form method="post" action="" onsubmit="closeModal('Modal')">
    <input type="hidden" name="courseid" value="<?php echo $classrow[0] ?>">
    <fieldset>
    <div class="row">
        <div class="column">Course: </div>
        <input type="text" name="course" value="<?php echo $classrow[1] ?>" required>
    </div>
    <div class="row">
        <div class="column">Course Name: </div>
        <textarea rows="2" cols="50" name="coursename"><?php echo $classrow[2] ?></textarea>
    </div>
    <div class="row">
        <div class="column">Credit Hours: </div>
        <input type="text" name="credithours" value="<?php echo $classrow[3] ?>" required>
    </div>
    <div class="row">
        <div class="column">CRN: </div>
        <input type="text" name="crn" style="width:70px;" value="<?php echo $classrow[4] ?>">
    </div>
    <div class="row">
        <div class="column"></div>
            <div id="btndiv">
                <button type="submit" id="btnsubmit" name="Update" value="Update">Update</button>
                <button type="button" id="btncancel" onclick="closeModal('editModal')">Cancel</button>
            </div>
        </div>
    </div>
    </fieldset>
    </form>
    </div>
</div>
</body>
<script>
function openModal(tar) {
    var modal = document.getElementById(tar);
    modal.style.display = "block";
}

function closeModal(tar) {
    var modal = document.getElementById(tar);
    modal.style.display = "none";
}

<?php 
if(isset($_POST['editbutton'])) {
    ?> openModal('editModal'); <?php
}
?>
</script>
</html>
