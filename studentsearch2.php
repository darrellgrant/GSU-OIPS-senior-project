<?php
include $_SERVER['DOCUMENT_ROOT'] . "/connectdb.php";
date_default_timezone_set("America/New_York");
?>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
input[type=text], select {
    padding: 4px 4px;
    margin: 4px 4px;
    border: 1px solid #ccc;
    box-sizing: border-box;
}
input[type=text] {
    width:110px;
}
div#personaldiv,div#educationaldiv,#applicationdiv {
    width:50%;
    float:left;
}
fieldset {
    display:table;
}
div.column {
    display:table-cell;
}
div.column2 {
    display:table-cell;
    text-align:right;
}
div.row {
    display:table-row;
}
.collapsible {
  background-color: #eee;
  color: #444;
  cursor: pointer;
  padding: 18px;
  width: 100%;
  border: none;
  text-align: left;
  outline: none;
  font-size: 16px;
}
.active, .collapsible:hover {
  background-color: #ccc;
}
.content {
  overflow: hidden;
}

.clearbutton {
  background-color: #008CBA; 
  border: none;
  border-radius:8px;
  color: white;
  padding: 5px 8px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 12px;
  -webkit-transition-duration: 0.4s; /* Safari */
  transition-duration: 0.4s;
  border: 1px solid #008CBA;
}

.clearbutton:hover {
  background-color: white;
  color: #008CBA;
}

#formDiv > input {
  background-color: #008CBA; 
  border: none;
  color: white;
  padding: 15px 32px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 16px;
  border-radius:8px;
  -webkit-transition-duration: 0.4s; /* Safari */
  transition-duration: 0.4s;
  border: 1px solid #008CBA;
}

#formDiv > input:hover {
  background-color: white;
  color: #008CBA;
}
</style>
</head>
<body>
<button class="collapsible">Open Search</button>
<div class="content" id="content">
<form method="post" action="studentsearchproc2.php" target="searchoutput" onsubmit="searchCollapse()">
<div id="wrapper">
<div id="personaldiv">
  <fieldset>
  <legend>Personal Information</legend>
    <div class="row">
        <div class="column2">
            EagleID: 
        </div>
        <div class="column">
            <input type="text" id="eid" name="eid">
        </div>
        <div class="column2">
            Email: 
        </div>
        <div class="column">
            <input type="text" id="email" name="email">@georgiasouthern.edu
        </div>
    </div>
    <div class="row">
        <div class="column2">
            First Name:
        </div>
        <div class="column">
            <input type="text" id="fn" name="fn">
        </div>
        <div class="column2">
            Middle Name:
        </div>
        <div class="column">
            <input type="text" id="mn" name="mn">
        </div>
    </div>
    <div class="row">
        <div class="column2">
            Last Name:
        </div>
        <div class="column">
            <input type="text" id="ln" name="ln">
        </div>
        <div class="column2">
            Race:
        </div>
        <div class="column">
            <input id="race" type="text" name="race">
        </div>
    </div>
    <div class="row">
        <div class="column2" style="width:95px;">
            Age:
        </div>
        <div class="column">
            <input type="text" id="age" name="age">
        </div>
        <div class="column2" style="width:110px;">
            Preferred Name:
        </div>
        <div class="column">
            <input type="text" id="pn" name="pn">
        </div>
    </div>
    <div class="row">
        <div class="column2">
            Gender:
        </div>
        <div class="column">
            <input id="gen" type="text" name="gen">
        </div>
        <div class="column2">
            Ethnicity:
        </div>
        <div class="column">
            <input id="eth" type="text" name="eth">
        </div>
    </div>
  <input type="button" class="clearbutton" value="Clear" onclick="clearFields('personaldiv')">
  </fieldset>
</div>

<div id="educationaldiv">
<fieldset>
<legend>Educational Information</legend>
    <div class="row">
        <div class="column2">
            Major:
        </div>
        <div class="column">
            <input id="major" type="text" name="major" style="width:220px;">
        </div>
        <div class="column2" style="width:180px;">
            Good Academic Standing?
        </div>
        <div class="column">
            <select id="acstand" name="acstand" >
            	<option value="">Any
            	<option value="Yes">Yes
            	<option value="No">No
        	</select>
        </div>
    </div>
    <div class="row">
        <div class="column2" style="width:50px;">
           GPA:
        </div>
        <div class="column">
            <select id="gpamodifier" name="gpamod">
                <option value=">=">Greater than or equal
                <option value=">">Greater than
                <option value="=">Equal
                <option value="<=">Less than or equal
                <option value="<">Less than
            </select>
            <input id="gpa" type="text" name="gpa" style="width:50px;">
        </div>
         <div class="column2">
            Class:
        </div>
        <div class="column">
            <select id="educclass" name="educclass" >
                <option value="">Any
        		<option value="Freshman">Freshman
        		<option value="Sophomore">Sophomore
        		<option value="Junior">Junior
        		<option value="Senior">Senior
        		<option value="Graduate">Graduate
        		<option value="Other">Other
        	</select>
        </div>
    </div>
    <div class="row">
        <div class="column2" style="width:140px;">
            Credit Hours earned:
        </div>
        <div class="column">
            <select id="cdhmodifier" name="cdhmod">
                <option value=">=">Greater than or equal
                <option value=">">Greater than
                <option value="=">Equal
                <option value="<=">Less than or equal
                <option value="<">Less than
            </select>
            <input id="credh" type="text" name="credh" style="width:50px;">
        </div>
        <div class="column2">
            Course:
        </div>
        <div class="column">
            <input type="text" id="coursename" name="coursename" style="width:80px;">
        </div>
    </div>
<input type="button" class="clearbutton" value="Clear" onclick="clearFields('educationaldiv')">
</fieldset>
</div>
</div>

<div id="applicationdiv">
<fieldset>
<legend>Application Information</legend>
    <div class="row">
            Program enrolled in:
            <select id="progid" name="progid">
                <option value="">Any
				<?php 
                $result = mysqli_query($conn,"Select ProgramID,ProgramName from Program");
                while($row = mysqli_fetch_array($result)) { ?>
	            <option value="<?php echo $row['ProgramID'] ?>"><?php echo $row['ProgramName'] ?>
                <?php }
				?>
        </select>
    </div>
    <div class="row">
            Judicial Verification:
            <select id="judver" name="judver">
                <option value="">Any
            	<option value="Yes">Yes
            	<option value="No">No
            	<option value="Open">Open
            </select>
            <span style="margin-left:20px;">
            Letter Sent:
            <select id="letsent" name="letsent" >
            	<option value="">Any
            	<option value="Yes">Yes
            	<option value="No">No
            </select>
            </span>
    </div>
    <div class="row">
            Enrollment Verified:
            <select id="enrver" name="enrver">
            	<option value="">Any
            	<option value="Yes">Yes
            	<option value="No">No
            </select>
            <span style="margin-left:20px;">
            GPA Verified:
            <select id="gpaver" name="gpaver" >
            	<option value="">Any
            	<option value="Yes">Yes
            	<option value="No">No
            </select>
            </span>
    </div>
<input type="button" class="clearbutton" value="Clear" onclick="clearFields('applicationdiv')">
</fieldset>
</div><br />
<div id="formDiv">
<input type="submit" name="Search" value="Search">
<input type="button" value="Clear all" onclick="clearFields('personaldiv'); clearFields('educationaldiv'); clearFields('applicationdiv')" style="margin-left:40px;">
</div>
</form>
</div>
<p></p>
<iframe id="searchoutput" name="searchoutput" width="100%" height="100%" frameborder="0"></iframe>
</body>
<script>
var coll = document.getElementsByClassName("collapsible");
var i;
for (i = 0; i < coll.length; i++) {
  coll[i].addEventListener("click", function() {
    this.classList.toggle("active");
    var content = this.nextElementSibling;
    if (content.style.display === "block") {
      content.style.display = "none";
    } else {
      content.style.display = "block";
    }
  });
}
</script>
<script>
function clearFields(div) {
    var container, inputs, index;
    container = document.getElementById(div);
    inputs = container.getElementsByTagName('input');
    for (index = 0; index < inputs.length; ++index) {
        if(inputs[index].type =="text") {
            inputs[index].value = '';
        }
    }
    selects = container.getElementsByTagName('select');
    
        for (index = 0; index < selects.length; ++index) {
            selects[index].selectedIndex = 0;
        }
    
}
function searchCollapse() {
    var x = document.getElementById("content");
    if (x.style.display === "none") {
        x.style.display = "block";
    } else {
        x.style.display = "none";
    }
}
function resizeIframe(obj) {
    obj.style.height = obj.contentWindow.document.body.scrollHeight + 'px';
}
</script>
</html>
