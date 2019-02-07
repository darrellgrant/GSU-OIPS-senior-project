<!DOCTYPE html>
<html>
<?php
include $_SERVER['DOCUMENT_ROOT'] . "/connectdb.php";
?>
<head>
<style type="text/css">
* {
	margin: 0; padding: 0;
}

#container {
	height: 100%; width:100%; font-size: 0;
}

#left, #middle, #right {
	display: inline-block; *display: inline; zoom: 1; vertical-align: top; font-size:16px;;
}

#left {
	width: 25%;
	position: relative;
	left: 5px;
}

#middle {
	width: 15%;
}

#right {
	position: relative;
	width: 60%;
	right: 5px;
}

.tab {
    overflow: hidden;
    border: 1px solid #ccc;
    background-color: #f1f1f1;
}

/* Style the buttons inside the tab */
.tab button {
    background-color: inherit;
    float: left;
    border: none;
    outline: none;
    cursor: pointer;
    padding: 14px 16px;
    transition: 0.3s;
    font-size: 17px;
}

/* Change background color of buttons on hover */
.tab button:hover {
    background-color: #ddd;
}

/* Create an active/current tablink class */
.tab button.active {
    background-color: #ccc;
}

/* Style the tab content */
.tabcontent {
    display: none;
    padding: 6px 12px;
    border: 1px solid #ccc;
    border-top: none;
}

iframe {
	height: 100px;
}

form.fclass {
    border-style: groove;
    border: 1px solid #000;
}

.centersubmit {
    margin: 0px auto;
    text-align:center;
}

fieldset {
    display:table;
    border: 0px;
}

div.column {
    display:table-cell;
}

div.column > input {
    width:145%;
    padding: 2px;
}

div.column2 {
    display:table-cell;
    text-align:right;
}

div.row {
    display:table-row;
}

textarea {
    padding: 2px;
}
</style>
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="<?php $_SERVER["DOCUMENT_ROOT"]?>/sc/datepicker_script.js"></script>
<script>
  $( function() {
    $( "#datepicker" ).datepicker({
      changeMonth: true,
      changeYear: true
    });
	}
);
</script>
<script>
function openTab(evt, tab) {
    var i, tabcontent, tablinks;
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }
    document.getElementById(tab).style.display = "block";
    evt.currentTarget.className += " active";
}
</script>
<script type="text/javascript">
function filedisplay() {
    var m = document.getElementById("FileSel").value
	if (m == "default000"){
		document.getElementById('ifrbase').src = "";
		document.getElementById('ifrbase').style.visibility = "hidden";
		document.getElementById('hiddenfile').value = m;
	}
	else {
	    document.getElementById('ifrbase').src = <?php $_SERVER["DOCUMENT_ROOT"]?>/files/ + m;
	    document.getElementById('ifrbase').style.visibility = "visible";
	    document.getElementById('hiddenfile').value = m;
	}
}

function resizeIframe(obj) {
    obj.style.height = obj.contentWindow.document.body.scrollHeight + 'px';
}

function getTextArea(target) {
    var i = document.getElementById("txtarea").value;
    document.getElementById(target).value = i;
}

function sendNow() {
    var i = document.getElementById("FileSel").value;
    document.getElementById("fileselcopy").value = i;
}

function setMailDetails(target) {
    var a = document.getElementById("subject").value;
    var b = document.getElementById("replyto").value;
    var c = document.getElementById("cc").value;
    var atarg = "emailsubj" + target;
    var btarg = "emailrep" + target;
    var ctarg = "emailcc" + target;
    document.getElementById(atarg).value = a;
    document.getElementById(btarg).value = b;
    document.getElementById(ctarg).value = c;
}
</script>
</head>
<body>
<?php
if (isset($_POST['submit'])) {
    $emaillist = array();
    $emaillist = $_POST['list'];
    $list;
    $ct = count($emaillist);
	echo "<div id=\"container\">
	<div id=\"left\">
		Email addresses: <textarea id=\"txtarea\" rows=\"4\" cols=\"55\">"; 
		for($i=0;$i<$ct;$i++){
	    echo $emaillist[$i];
	    $list .= $emaillist[$i];
	    if($i<$ct-1){
	        echo ",";
	        $list .= ",";
	    }
	}
	echo "</textarea>
	<br />
	File selection: <select id=\"FileSel\" onchange=\"filedisplay()\">
	<option value=\"default000\"><center> - Select File - </option>";
	$dir = dir($_SERVER['DOCUMENT_ROOT']."/files");
		while (($file = $dir->read()) !== false) {
		   if ((substr($file, -4)=="html")) {
			echo "<option value=\"" . trim($file) . "\">" . $file . "\n";
		   }
		}
	$dir->close();
	echo "</select><br />
	<div>
	    <fieldset>
	    <br />
	        Subject: <br />
	        <div class=\"column\">
	        <textarea id=\"subject\" rows=\"2\" cols=\"45\">Message from International Programs and Services</textarea>
	        </div><br />
	    <div class=\"row\">
	        <div class=\"column2\">Reply To: </div>
	        <div class=\"column\"><input type=\"text\" id=\"replyto\" value=\"stdyabrd@georgiasouthern.edu\"></div><br /><br />
	    </div>
	    <div class=\"row\">
	        <div class=\"column2\">CC: </div>
	        <div class=\"column\"><input type=\"text\" id=\"cc\" value=\"stdyabrd@georgiasouthern.edu\"></div>
	    </div>
	    <br />
	   </fieldset>
	</div><br />
	<p id=\"test\"></p>
	<form class=\"fclass\" action=\"emailsys3.php\"  target=\"ifremail\" method=\"post\"><br />
	    <div class=\"centersubmit\">Date to Send (12:00AM): <input type=\"date\" id=\"start\" name=\"senddate\" required
				   min=\"" . date("Y-m-d") . "\" max=\"2100-12-31\" /><br />
	    <input type=\"hidden\" id=\"hiddenlist\" name=\"emaillist\" value=\"\">
	    <input type=\"hidden\" id=\"hiddenfile\" name=\"FileSel\" value=\"\">
	    <input type=\"hidden\" id=\"emailsubj1\" name=\"emailsubj1\" value=\"\">
	    <input type=\"hidden\" id=\"emailrep1\" name=\"emailrep1\" value=\"\">
	    <input type=\"hidden\" id=\"emailcc1\" name=\"emailcc1\" value=\"\"><br />
	    <input type=\"submit\" name=\"preview\" value=\"Preview Email\" onclick=\"getTextArea('hiddenlist'); openTab(event,  'Email');\"><br /><br />
	    <input type=\"submit\" name=\"schedule\" id=\"schedulesubmit\" value=\"Schedule Emails\" onclick=\"setMailDetails('1');\"></div>
    	<br />
	</form>
	    <br />
    <form class=\"fclass\" action=\"emailsys3.php\"  target=\"ifremail\" method=\"post\">
    	<br />
	    <input type=\"hidden\" id=\"listcopy\" name=\"emaillistcopy\" value=\"\">
	    <input type=\"hidden\" id=\"fileselcopy\" name=\"fileselcopy\" value=\"\">
	    <input type=\"hidden\" id=\"emailsubj2\" name=\"emailsubj2\" value=\"\">
	    <input type=\"hidden\" id=\"emailrep2\" name=\"emailrep2\" value=\"\">
	    <input type=\"hidden\" id=\"emailcc2\" name=\"emailcc2\" value=\"\">
	    <div class=\"centersubmit\"><input type=\"submit\" class=\"centersubmit\" name=\"sendnow\" value=\"Send Emails Now\" onclick=\"getTextArea('listcopy'); setMailDetails('2'); openTab(event, 'Email'); sendNow();\">
	    </div>
	    <br />
	</form>
	</div>
		<div id=\"middle\"></div>
		<div id=\"right\">
		<br />
		<div class=\"tab\">
			<button class=\"tablinks\" onclick=\"openTab(event, 'Base')\" id=\"Open\">Base Document</button>
			<button class=\"tablinks\" onclick=\"openTab(event, 'Email')\">Email Preview</button>
		</div>
		<div id=\"Base\" class=\"tabcontent\">
			<p>Base</p>
			<iframe style=\"visibility:hidden\" width=\"95%\" id=\"ifrbase\" name=\"ifrbase\" src=\"\" frameborder=\"2\" scrolling=\"no\" onload=\"resizeIframe(this)\">
			</iframe>
		</div>
		<div id=\"Email\" class=\"tabcontent\">
		<p>Email</p>
			<iframe width=\"95%\" id=\"ifremail\" name=\"ifremail\" src=\"\" frameborder=\"2\" scrolling=\"no\" onload=\"resizeIframe(this)\"></iframe>
		</div>
	</div>";
}
?>
<p id="test" name="testarea"></p>
</body>
<script>
document.getElementById("Open").click();
</script>
</html>
