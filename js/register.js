        function validateEmail() {
            var email = document.forms["registration"]["email"].value;
            if (email == null || email.substring(email.indexOf("@")) != "@mail.fresnostate.edu") {
                alert("This system requires the use of a Fresno State email domain.");
                return false;
            }
            else{
                return true;
            }
        }

        function Validate() {
            var password = document.forms["registration"]["password"];
            var password_confirmation = document.forms["registration"]["password_confirmation"];
            if (password.value != password_confirmation.value) {
                alert("Passwords do not match!");
                return false;
            }
            else{
                return true;
            }

        }


    addEventListener('keyup', validateEmailDisplay, false);
    addEventListener('keyup', passwordMatchDisplay, false);

        function validateEmailDisplay() {
        var email = document.forms["registration"]["email"].value;
        if (email == null || email.substring(email.indexOf("@")) != "@mail.fresnostate.edu") {
            $("#divcheckemail").html("Invalid email: must be a Fresno State email.");
            document.getElementById("divcheckemail").style.color = "red";
            return false;
        }
        else{
            $("#divcheckemail").html("Email is valid.");
            document.getElementById("divcheckemail").style.color = "green";
            return true;
        }
        }

        function passwordMatchDisplay() {
        var password = document.forms["registration"]["password"];
        var password_confirmation = document.forms["registration"]["password_confirmation"];
        if (password.value != password_confirmation.value) {
            $("#divCheckPasswordMatch").html("Passwords do not match!");
            document.getElementById("divCheckPasswordMatch").style.color = "red";
            return false;
        }
        if(password.value != "" && password.value == password_confirmation.value) {
            $("#divCheckPasswordMatch").html("Passwords match.");
            document.getElementById("divCheckPasswordMatch").style.color = "green";
            return true;
        }
        }
        
        
//-----------------PASSWORD STRENGTH STARTS HERE------------------------//
        div('#pass1').focus();
        div('#pass1').addEventListener('keyup', main, false);
    
        
        //Main function that is called like in c/c++..basically our driver for pass strength
        function main() {
        display_strength("pass1","passwordStrength","status");
        }
        
     //To make x an iterator through password entry
     function div(x){
        return document.getElementById(x);
     }
    // Function to display password strength
    function display_strength(x,y,z)
        {
            if(!x || div(x).value === "")
            {
                div(y).style.width = 0 + "%";
                div(z).innerHTML = "&nbsp;";
                return false;
            }
            var psswd = div(x);
            var stren = div(y);
            var stats = div(z);
            var color = ["red","gold","green","lime"];
            var value = ["Weak","Good","Strong","Very Strong"];
        
        score = check_score(psswd.value);
        
            if(score >= 9)
            {
                stren.style.width = "100%";
                stren.style.background = color[3];
                stats.innerHTML = value[3];
            }
            else if(score >= 5 && score < 8)
            {
                stren.style.width = "75%";
                stren.style.background = color[2];
                stats.innerHTML = value[2];
            }
            else if(score >= 4  && score < 5)
            {
                stren.style.width = "50%";
                stren.style.background = color[1];
                stats.innerHTML = value[1];
            }
            else if(score >= 1 && score <= 3)
            {
                stren.style.width = "25%";
                stren.style.background = color[0];
                stats.innerHTML = value[0];
            }
            return false;
    }
    
    function check_score(x){
         
         var total = 0;

        //Give score 3 points if password is bigger than 6 characters
        if (x.length > 6){
            total = total + 3;
        }
        //Give score a point if password contains at least one lowercase and capital character in any order
        if ((/[a-z]/).test(x) && (/[A-Z]/).test(x)){
            total++;
        }
        //Give score a point if at least one number in password along with your characters
        if ((/[\d]/).test(x) && ((/[a-z]/).test(x) || (/[A-Z]/).test(x))){
            total++;
        }
        //Give score a point if at least one special character in password
        if ((/[!,@,#,$,%,^,&,*,?,_,~,_,(,)]/).test(x)){
            total++;
        }
        //Give score 3 points if at least one number and special character and password length is greater than 6
        if ((/[!,@,#,$,%,^,&,*,?,_,~,_,(,)]/).test(x) && (/[\d]/).test(x) && (x.length > 5)){
            total = total + 3;
        }
        //Give score a point if password is > 10
        if (x.length > 10){
            total++;
        }
        //Give score two points if password is > 12
        if (x.length > 12){
            total = total + 2;
        }
        //Give score two points if password is > 15
        if (x.length >= 15){
            total = total + 2;
        }
        //Ensure that if password is long enough that it is strong because it would be harder to brute force anyways
        if (x.length >= 25){
            total = total + 5;
        }
        return total;
    }
