<?php 
include $_SERVER['DOCUMENT_ROOT'] . "/connectdb.php";
$q = mysqli_query($conn,"Select * From Program where ProgramID='1'");
$r = mysqli_fetch_array($q);
$q2 = mysqli_query($conn,"Select * From Application where AppID='100'");
$r2 = mysqli_fetch_array($q2);
?>
<html>
<head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script>
$(document).ready(function(){
  $("input,textarea,select").change(function(){
    $(this).css("background-color", "#5F9EA0");
  });
  $("#deppaid,#p1paid,#p2paid,#p3paid,#p4paid").change(function(){
    var bal = parseInt($("#totalbal").val(),10);
    var paid = 0;
    if($("#deppaid").val() == "Yes") {
        paid += parseInt($("#depamt").val(),10);
    }
    if($("#p1paid").val() == "Yes") {
        paid += parseInt($("#p1amt").val(),10);
    }
    if($("#p2paid").val() == "Yes") {
        paid += parseInt($("#p2amt").val(),10);
    }
    if($("#p3paid").val() == "Yes") {
        paid += parseInt($("#p3amt").val(),10);
    }
    if($("#p4paid").val() == "Yes") {
        paid += parseInt($("#p4amt").val(),10);
    }
    if($("#insurpaid").val() == "Yes") {
        paid += parseInt($("#insamt").val(),10);
    }
    var a = bal - paid;
    $("#bal").val(a);
  });
});
</script>
</head>
<body>
<div>
<p>Input field: <input type="text" id="bal" value="1700"></p>
<p>dep amt<input type="text" id="depamt" value="<?php echo $r['DepositAmount'] ?>"></p>
<select id="deppaid">
<option value="No">no
<option value="Yes">yes
</select>
</div>
<div>
 <?php
 $selopts = array("Yes","No");
    for($i=1;$i<5;$i++) {
        $pamt = "Payment" . $i . "Amt";
        $pdp = "Payment" . $i . "DatePaid";
        $pdd = "Payment" . $i . "DateDue";
        $pconf = "Payment" . $i . "Paid";
        
        if($r[$pamt] != 0){
            echo "<div> 
            Payment " . $i . " Paid: <select id=\"p".$i."paid\" name=\"p".$i."paid\">
                <option value=\"".$r2[$pconf]."\">".$r2[$pconf];
                foreach($selopts as $c) {
                    if($c!=$r2[$pconf]) {
                        echo "<option value=\"".$c."\">".$c;
                    }
                }
                echo "</select>
                Payment $i Paid Date and Time: <input type=\"text\" class=\"datetime\" name=\"p".$i."dt\" value=\"".$r2[$pdp]."\">
                Payment $i Amount: <input type=\"text\" id=\"p".$i."amt\" class=\"short\" disabled value=\"".$r[$pamt]."\">
                Payment $i Date Due: <input type=\"text\" class=\"datelength\" disabled value=\"".$r[$pdd]."\">
            </div>";
        }
    }
?>
</div>
<div>
<input type="hidden" id="totalbal" value="<?php echo $r['BalanceDue'] ?>">
</div>
</body>
</html>
