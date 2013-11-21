<!-- test.php working ajax example-->

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="custom/images/databass.png">

    <title>Databass Library</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="custom/css/signin.css" rel="stylesheet">
    <script src="js/jquery-1.10.2.js"></script>
  </head>

  <body>

    <div class="container">

      <form class="form-signin">
        <h2 class="form-signin-heading">Please sign in</h2>
        <input type="text" id="username" name="username" class="form-control" placeholder="Username" required autofocus>
        <input type="password" name="password" id="password" class="form-control" placeholder="Password" required>
	     <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
      </form>

      <div class="ajax_result" style="width:20px; height:20px;"></div>

      <script type="text/javascript">

      	$(".btn").on('click',function(ev){
      		//Needed to prevent the page from reloading on Ajax Success or Error
      		ev.preventDefault();

      		var username = $('#username').val();
      		var password = $('#password').val();

      		$.ajax({
      			url: 'test1.php',
      			type: 'POST',
      			dataType: 'json',
      			data: { 'username': username, 'password':password},
      			success: function(data,status) {
      				$('.form-signin').hide();
      				$('.ajax_result').text(data.FIRST_NAME.toString());
      			},
      			error: function(xhr, desc, err){
      				console.log("Reached error");
      			}
      		});
      	});
      </script>      

<!--       <form method="POST" action="check-account.php" class="form-signin">
          <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in As Guest</button>
      </form>
 -->    </div> <!-- /container -->

  </body>
</html>
