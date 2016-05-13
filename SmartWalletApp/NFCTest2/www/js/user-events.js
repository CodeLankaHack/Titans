/**
 * Created by ASUS-PC on 5/10/2016.
 */

$('#signupButton').click(function () {
    //get necessary details from the input forms
    var userName = document.getElementById("login").value;
    var email = document.getElementById("email").value;
    var password = document.getElementById("login-psw").value;
    var repassword = document.getElementById("login-psw-2").value;

    if (password == repassword){

        //send ajax request
        jQuery.ajax({
            type: "GET",
            url: 'http://localhost/travelSL/web/user/register',
            dataType: 'json',
            data: { userName:userName, email:email , password:password},
            success: function (obj, textstatus) {

                //on success, display success msgs, hide current register modal



            }
        });

    }
    else{
        alert();
        $("#modal1").modal("show");
        document.getElementById("modalText").innerHTML = "Both passwords do not match";

    }
});

$('#loginButton').click(function () {
    //get necessary details from the input forms
    var userName = document.getElementById("login").value;
    var password = document.getElementById("login-psw").value;


        //send ajax request
        jQuery.ajax({
            type: "GET",
            url: 'http://localhost/travelSL/web/user/register',
            dataType: 'json',
            data: { userName:userName, password:password},
            success: function (obj, textstatus) {

                //on success, display success msgs, hide current register modal



            }
        });

});

