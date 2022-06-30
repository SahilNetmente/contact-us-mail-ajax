//*submit contact form
$("#contact-us").submit(function (e) {
  e.preventDefault(); // avoid to execute the actual submit of the form.
  sendContact();
});

//validate contact form
function validateContact() {
  var valid = true;

  if (!$("#userName").val()) {
    $("#userName-info").html("Please Enter Name.");
    valid = false;
  } else {
    $("#userName-info").html("");
  }

  if (!$("#message").val()) {
    $("#message-info").html("Please Enter Message.");
    valid = false;
  } else {
    $("#message-info").html("");
  }

  if (!$("#subject").val()) {
    $("#subject-info").html("Please Enter Subject.");
    valid = false;
  } else {
    $("#subject-info").html("");
  }

  if (!$("#email").val()) {
    $("#email-info").html("Please Enter Email.");
    valid = false;
  } else if (
    !$("#email")
      .val()
      .match(/^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/)
  ) {
    $("#email-info").html("Please Enter Valid Email.");
    valid = false;
  } else {
    $("#email-info").html("");
  }

  if (!$("#g-recaptcha-response").val()) {
    $("#g-recaptcha-response-info").html("Please Check captcha.");
    valid = false;
  } else {
    $("#g-recaptcha-response-info").html("");
  }

  // if (!$("#phoneNumber").val()) {
  //   $("#phoneNumber-info").html("Please Enter Phone Number.");
  //   valid = false;
  // } else if (
  //   !$("#phoneNumber")
  //     .val()
  //     .match(/^[0-9]{10}$/)
  // ) {
  //   $("#phoneNumber-info").html("Please Enter Valid 10 Digit Phone Number.");
  //   valid = false;
  // } else {
  //   $("#phoneNumber-info").html("");
  // }

  return valid;
}

//send contact form
function sendContact() {
  var valid;
  valid = validateContact();
  if (valid) {
    $("#submit").attr("disabled", true);
    $.ajax({
      url: "contact-us.php",
      data:
        "userName=" +
        $("#userName").val() +
        "&email=" +
        $("#email").val() +
        "&subject=" +
        $("#subject").val() +
        "&message=" +
        $("#message").val() +
        "&captcha=" +
        $("#g-recaptcha-response").val(),
      type: "POST",
      success: function (data) {
        console.log(data);
        // $("#contact-us").hide();
        $("#contact-us")[0].reset();
        $("#mail-status").empty();
        $("#mail-status").html(data);
        $("#submit").attr("disabled", false);
      },
      error: function (data) {
        $("#mail-status").empty();
        $("#mail-status").html(data);
        $("#submit").attr("disabled", false);
      },
    });
  }
}
