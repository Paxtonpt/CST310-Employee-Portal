/*
    Description: Global JavaScript File that can be used throughout the whole Project
    Version:
        1.0.0 - Initial Version - Paxton Porenta - 20240316.
*/

function confirmPassword (p1, p2, fieldName) {
    /*
        Khan, M. (2018). HTML5 Validations example: Required, Email, Password Strength and Compare Password validations in HTML5. 
        ASP Snippets. Retrieved from https://www.aspsnippets.com/Articles/HTML5-Validations-example-Required-Email-Password-Strength-and-Compare-Password-validations-in-HTML5.aspx#:~:text=The%20HTML5%20pattern%20attribute%20has%20a%20Regular%20Expression,the%20user%20when%20the%20HTML5%20pattern%20validation%20fails.
    */
    var txtPassword = document.getElementById(p1);
    var txtConfirmPassword = document.getElementById(p2);
    txtPassword.onchange = ConfirmPassword;
    txtConfirmPassword.onkeyup = ConfirmPassword;
    
    function ConfirmPassword() {
        txtConfirmPassword.setCustomValidity("");
        if (txtPassword.value != txtConfirmPassword.value) {
            var fieldTxt = fieldName + " do not match.";
            txtConfirmPassword.setCustomValidity(fieldTxt);
        }
    }
}