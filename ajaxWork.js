function showAlert(message, alertType, timeout) {
  const alerts=$('<div id="alert_div" class="alert ' + alertType +' alert-dismissible fade in show " role="alert">' +
    '<i class="fa fa-exclamation-circle me-2"></i> <span>' +message +"</span>" +
    '<button  type="button" class="btn-close " data-bs-dismiss="alert" aria-label="Close" ></button>' +"</div>");
  $(".alert_placeholder").append(alerts);
  if (timeout || timeout === 0) {
    setTimeout(function () {
      alerts.alert("close");
    }, timeout);
  }
  // var loadMore = document.getElementById("load_more");
  // loadMore.click();
}

function showLogin() {
  $.ajax({
    url: "./login.php",
    method: "post",
    data: { record: 1 },
    beforeSend: function () {
      $("#spinner").addClass("show");
    },
    success: function (data) {
      spinner();
      $(".allContent-section").html(data);
    },
  });
}

function signUp(typ) {
  // console.log(typ);
  if (typ === 0) {
    var name = $("#Username").val();
    var email = $("#email").val();
    var mobile = $("#mobile").val();
    var password = $("#password").val();
    var password2 = $("#Confirm-Password").val();
  } else {
    var name = $("#floatingUsername").val();
    var password = $("#floatingPassword").val();
  }
  var fd = new FormData();
  fd.append("typ", typ);
  fd.append("name", name);
  fd.append("email", email);
  fd.append("mobile", mobile);
  fd.append("password", password);
  fd.append("password2", password2);
  console.log(name, password);
  $.ajax({
    url: "./loginserver.php",
    method: "post",
    data: fd,
    processData: false,
    contentType: false,
    beforeSend: function () {
      $("#spinner").addClass("show");
    },
    success: function (data) {
      spinner();
      data = JSON.parse(data);
      console.log(data);
      data.errors.forEach((ele, i) => {
        setTimeout(function () {
          showAlert(ele, "alert-warning", 3000);
        }, i * 500);
      });

      if (data.isNew == false && data.data == true && data.errors.length == 0) {
        showAlert("Successfully login ", "alert-success", 3000);
        setTimeout(function () {
          window.location.href = "";
        }, 200);
      } else if ( data.isNew == true && data.data == true && data.errors.length == 0) {
        showAlert("Successfully registered ", "alert-success", 3000);
        signInPages();
      } else {
        // showAlert( "An error occurred while processing your request. Please try again later.", "alert-danger" , 3000);
      }
 
    },
  });
}
