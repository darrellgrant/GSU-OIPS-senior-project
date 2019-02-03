<?php
$email = "";
$fname = "";
$lname = "";
$stuId = "";
?>


<html>
    <head>
        <style type="text/css">
            input{
                float: right;
                margin-left: 15px;
            }
            
            #container {
                width: 310px;
                margin: 0 auto;
            }
            
            #errorMessage {
                
                font-family: sans-serif;
                color: red;
                width:275px;
                
            }
            
            #passInfo {
                margin-top:30px;
                font-family: sans-serif;
                color: #aaa;
                width:275px;
                                

            }

        </style>
    
    
    </head>

<div id="container">
    <div id="errorMessage">
        

    </div>
    <form action="register_process_copy.php" onsubmit="return validateForm()" method="POST">
        <label>First Name
            <input type="text" value="<?php echo $fname; ?>" id="first" name="first" placeholder="first name" >
        </label><br><br> 
        <label>Last Name
            <input type="text" value="<?php echo $lname; ?>"id="last" name="last" placeholder="lastname">
        </label><br><br> 
        <label>Email
            <input type="text" id="email" name="email" placeholder="email" value="<?php echo $email;?>">
        </label><br><br>
        <label>Student ID
            <input type="text" id="stuId" name="stuId" placeholder="eg. 900000001" value="<?php echo $stuId;?>">
        </label><br><br>
        
        
        <label>Password
            <input type="password" id="pass1" name="password" placeholder="password" >
        </label><br><br> 
        
        
        
        <label>Confirm Password
            <input type="password" id="pass2" name="confirm_password" placeholder="confirm password">
        </label><br><br>
        <input type="submit" name="submit" value="Register">
    </form>
    <div id="passInfo"><p>Password must be between 8 and 20 characters in length</p><p>and contain <strong>at least one each </strong>of the following:</p>
        <p>an uppercase letter, a lowercase letter</p>
        <p>a numeral and a special character<br>(eg., !@#$%)</p>
    </div>
    
</div>
    <script>

        function validateForm(){
            var fname = document.getElementById("first").value.trim();
            var lname = document.getElementById("last").value.trim();
            var email = document.getElementById("email").value.trim();
            var pass1 = document.getElementById("pass1").value.trim();
            var pass2 = document.getElementById("pass2").value.trim();
            var stuId = document.getElementById("stuId").value.trim();
            var errorMessage = "";
            var letters = /^[a-zA-Z]+$/;
            var emailCheck = /^([a-zA-Z0-9\.-_]+)@([a-zA-Z0-9-]+)(\.[a-z]{2,20})$/;
            var passCheck = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[^a-zA-Z0-9])(?!.*\s).{8,20}$/;
            var numCheck = /^[0-9]{4,11}$/;
            
            if(fname == "" || lname == "" || email == "" || pass1 == "" || pass2 == "" ) {
                errorMessage += "<p>Please fill in all fields<p>";
                document.getElementById("errorMessage").innerHTML = errorMessage;
                return false;//check all fields are filled
            
            } else if (!letters.test(fname)) {
                errorMessage += "<p>First Name field is Invalid<p>";
                document.getElementById("errorMessage").innerHTML = errorMessage;
                return false;//check name field is all letters
   
            }else if (!letters.test(lname)) {
                errorMessage += "<p>Last Name field is Invalid<p>";
                document.getElementById("errorMessage").innerHTML = errorMessage;
                return false;//check name field is all letters
                
            } else if (!emailCheck.test(email)){
                errorMessage += "<p>Your Email is Invalid<p>";
                document.getElementById("errorMessage").innerHTML = errorMessage;
                return false;//check email valid  
                
            } else if (!numCheck.test(stuId)){
                errorMessage += "<p>Your Student Number is Invalid<p>";
                document.getElementById("errorMessage").innerHTML = errorMessage;
                return false; //check student ID all numbers  
                
            }else if (!passCheck.test(pass1)){
                        errorMessage += "<p>Your password is Invalid. Check the requirements below<p>";
                        document.getElementById("errorMessage").innerHTML = errorMessage;
                        document.getElementById("passInfo").style.color = "red";
                        return false; //check password valid
                
            } else if (pass1 != pass2){
                        errorMessage += "<p>Your passwords do not match<p>";
                        document.getElementById("errorMessage").innerHTML = errorMessage;
                        return false; //check password match
            }

            else{
                return true;
            }
                
        }//end function

//if user has JavaScript disabled
    </script>
    <noscript>
      <div style="border: 1px solid purple; padding: 10px">
        <span style="color:red">JavaScript is not enabled!</span>
      </div>
    </noscript>
</html>