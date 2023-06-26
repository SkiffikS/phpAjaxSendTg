<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Form</title>
  <link rel="icon" href="logo.png" type="image/x-icon">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <style>
    body {
      background: url("../background.jpg") 0 0/100% 100% no-repeat;
      color: #fff;
    }

    .form-background {
      background: #ffffff2a;
    }

    .iti.iti--allow-dropdown {
      width: 100%;
    }

    .iti__country-name {
      color: black;
    }
  </style>
</head>

<body>
  <main class="vh-100 d-flex align-items-center justify-content-center">
    <div class="container text-center">
      <div class="form-background p-5 mx-auto rounded col-sm-12 col-md-6">
        <form id="myForm">
          <div class="mb-3">
            <label for="firstName" class="form-label">First Name</label>
            <input type="text" class="form-control bg-transparent text-white" id="firstName" required minlength="5">
          </div>
          <div class="mb-3">
            <label for="lastName" class="form-label">Last Name</label>
            <input type="text" class="form-control bg-transparent text-white" id="lastName" required minlength="5">
          </div>
          <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control bg-transparent text-white" id="email" required>
          </div>
          <div class="mb-4 w-100">
            <label for="phoneNumber" class="form-label d-block">Phone Number</label>
            <input type="tel" class="form-control bg-transparent text-white" id="phoneNumber" required>
          </div>
          <div class="text-light text-center font-weight-bold" id="errorMessage"></div>
          <button type="submit" class="btn btn-outline-light w-50 mt-4">Submit</button>
        </form>
      </div>
    </div>
  </main>
</body>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js"></script>

<script>
  $(document).ready(function() {
    var input = document.querySelector("#phoneNumber");
    var iti = window.intlTelInput(input, {
      initialCountry: "auto",
      geoIpLookup: function(success, failure) {
        $.get('https://ipinfo.io', function() {}, "jsonp").always(function(resp) {
          var countryCode = (resp && resp.country) ? resp.country : "";
          success(countryCode);
        });
      },
      utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js"
    });

    $('#myForm').on('submit', function(e) {
      e.preventDefault();
      var errorMessage = "";
      var isValid = true;
      data = {
        firstName: $("#firstName").val(),
        lastName: $("#lastName").val(),
        email: $("#email").val(),
        phoneNumber: iti.getNumber(),
      };

      $(this).find('input').each(function() {
        if (!$(this).val().replace(/\s/g, "")) {
          isValid = false;
          errorMessage += "<span class='text-danger'>*</span>fields are not filled in <br>";
        }
      });

      if (data.firstName.replace(/\s/g, "").length < 5 || data.lastName.replace(/\s/g, "").length < 5) {
        isValid = false;
        errorMessage += "<span class='text-danger'>*</span>short text in the Name data <br>";
      }

      if (!iti.isValidNumber()) {
        isValid = false;
        errorMessage += "<span class='text-danger'>*</span>incorrect phone number <br>";
      }

      var re = /^[\w-]+(\.[\w-]+)*@([\w-]+\.)+[a-zA-Z]{2,7}$/;
      if (!re.test(data.email)) {
        isValid = false;
        errorMessage += "<span class='text-danger'>*</span>incorrect email <br>";
      }

      if (isValid) {
        $.ajax({
          type: "POST",
          url: "send.php",
          data: data,
          dataType: "json",
          success: function(result) {
            if (result.status === false) {
              $("#errorMessage").html(result.error.message);
            } else {
              window.location.href = "thank_you.php";
            }
          },
          error: function(jqXHR, textStatus, errorThrown) {
            $("#errorMessage").html("An error occurred while processing your request: " + textStatus + " " + errorThrown + " " + jqXHR.responseText);
          }
        });
      } else {
        $("#errorMessage").html(errorMessage);
      }
    });
  });
</script>

</html>