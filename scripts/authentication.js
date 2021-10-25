function createaccount(){
    let full_name = $('#full_name').val();
    let email = $('#email_').val();
    let mobile = $('#mobile_').val();
    let password = $('#password_').val();
let params = "full_name="+full_name+"&mobile="+mobile+"&email="+email+"&password="+password;
dbaction("/action/signup",params, function (feed) {
    console.log(JSON.stringify(feed));
    feedback("SUCCESS", "TOAST", ".feedback_", feed, "4");

})

}

function login(){
    let inp_email = $('#inp_email').val();
    let inp_password = $('#inp_password').val();

    let params = "email="+inp_email+"&password="+inp_password;
    dbaction("/action/login",params, function (feed) {
        console.log(JSON.stringify(feed));
        feedback("DEFAULT", "TOAST", ".feedback_", feed, "4");

    })

}
