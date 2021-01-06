<?php

session_start();

if(!empty($_SESSION['active']))
{
	header('location: logout.php');
}else
{
	if(!empty($_POST))
	{
		if(empty($_POST['username']) || empty($_POST['password']))
		{
			$alert = 'Ingrese su usuario y su contraseña';
        }else
        {
           require_once("config/odbc.php");
           require_once("config/const.php");

           $usuario = $_POST['username'];
           $password = $_POST['password'];

           $cnn = new conexion(tserver);
           $cnn->conectar();
   
           $sql = "SELECT * FROM usuario where usuario = '$usuario' ";
           $result = $cnn->query($sql);
  
           if ($cnn->num_rows>0) 
           {
              $row = $cnn->fetch_row();
              $password_bd = $cnn->result('password');
              $pass_c = $password;

              if (trim($password_bd) == trim($pass_c)) 
              {
                 $_SESSION['active'] = true;
                 $_SESSION['idUser'] = $cnn->result('idusuario');
                 $_SESSION['nombre'] = $cnn->result('nombre');
                 $_SESSION['user']   = $cnn->result('usuario');
                 $_SESSION['tipo']   = $cnn->result('tipo');

                 header("location: principal.php");
              } else
              {
                $alert = 'El usuario o la clave son incorrectos';
                session_destroy();
              }
              $cnn->cerrar();
           }
        }
    }
}
  	
?>	

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="author" content= "JLG">
        <meta http-equiv="X-UA-Compatible" content="IE-edge">
        <meta name = "viewport" content="width=device-width,initial-scale-1">
        <title>Tablero de indicadores </title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">

        <style class="text">
            #alert, #register-box, #forgot-box {
                display: none;
            }
        </style>
</head>
<body class="bg-dark">
<div class="container mt-4">
    <div class="row">
        <div class="col-lg-4 offset-lg-4" id="alert">
            <div class="alert alert-success">
                <!-- <strong id="result" > Hello World! </strong>            -->
            </div>
        </div>
    </div>
    <!-- login form -->  
    <div class="row">
        <div class="col-lg-4 offset-lg-4 bg-light rounded" id="login-box">
            <h2 class= "text-center mt-2">Entrar</h2>
            <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>"  class="p-2" id="login-frm">
                <div class="form-group">
                    <input type="text" name="username" class="form-control" placeholder="Usuario" required>
                </div>
                <div class="form-group">
                    <input type="password" name="password" class="form-control" placeholder="Contraseña" >
                </div>
                <div class="form-group">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" name="rem" class="custom-control-input" id="customCheck">
                        <label for="customCheck" class="custom-control-label">Recuérdame</label>
                          <a href="#" id="forgot-btn" class="float-right">Olvidaste tú Contraseña?</a>
                    </div>
                </div>
                <div class="form-group">
                    <input type="submit" name="login" id="login" value="Entrar" class="btn btn-primary btn-block">
                </div>
                <div class="form-group">
                    <p class="text-center">Usuario Nuevo?  <a href="#" id="register-btn">Regístrate Aquí</a></p>
                </div>
             </form>
        </div>
    </div>
    <!-- Register Form -->
    <div class="row">
        <div class="col-lg-4 offset-lg-4 bg-light rounded" id="register-box">
            <h2 class= "text-center mt-2">Registrar</h2>
            <form action="" method="post" role="form" class="p-2" id="register-frm">
                <div class="form-group">
                    <input type="text" name="nombre" class="form-control" placeholder="Nombre Completo" required>
                </div>
                <div class="form-group">
                    <input type="text" name="uname" class="form-control" placeholder="usuario" required>
                </div>
                <div class="form-group">
                    <input type="email" name="email" class="form-control" placeholder="correo" required>
                </div>
                <div class="form-group">
                    <input type="password" name="pass" id = "pass" class="form-control" placeholder="Contraseña" required minlength="8">
                </div>
                <div class="form-group">
                    <input type="password" name="cpass" id = "cpass" class="form-control" placeholder="Confirme Contraseña" required minlength="8">
                </div>
                <div class="form-group">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" name="rem" class="custom-control-input" id="customCheck2">
                        <label for="customCheck2" class="custom-control-label">Confirmo</label>
                    </div>
                </div>
                <div class="form-group">
                    <input type="submit" name="registrar" id="register-btn" value="Registrar" class="btn btn-primary btn-block">
                </div>
                <div class="form-group">
                    <p class="text-center">Ya registrado? <a href="#" id="login-btn">Entra aquí</a></p>
                </div>
            </form>
        </div>
    </div>
    <!-- Olvido de Contraseña -->
    <div class="row">
        <div class="col-lg-4 offset-lg-4 bg-light rounded" id="forgot-box">
            <h2 class= "text-center mt-2">Reestablecer Contraseña</h2>
            <form action="" method="post" role="form" class="p-2" id="forgot-frm">
                <div class="form-group">
                    <small class="text-muted">
                        Para reestablecer tu contraseña, introduce el correo registrado y te enviaremos
                        un mensaje con las instrucciones.
                    </small>
                </div>
                <div class="form-group">
                    <input type="email" name="femail" class="form-control" placeholder="Correo" required>
                </div>
                <div class="form-group">
                    <input type="submit" name="forgot" id="forgot-btn" value="Reestablecer" class="btn btn-primary btn-block">
                </div>
                <div class="form-group text-center">
                    <a href="#" id="back-btn">Regresar</a>
                </div>
            </form>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js" integrity="sha384-w1Q4orYjBQndcko6MimVbzY0tgp4pWB4lZ7lr30WKz0vr/aWKhXdBNmNb5D92v7s" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.min.js" ></script>


    <script type="text/javascript">
        $(document).ready(function () {
           $("#register-btn").click(function(){
              $("#register-box").show();
              $("#login-box").hide();
           });
           $("#login-btn").click(function(){
              $("#register-box").hide();
              $("#login-box").show();
           });
           $("#forgot-btn").click(function() {
             $('#login-box').hide();
             $('#forgot-box').show();
           });
           $("#back-btn").click(function() {
              $("#forgot-box").hide();
              $("#login-box").show();
           });

           $("#login-frm").validate();
           $("#register-frm").validate( {
               rules: {
                   cpass:{
                       equalTo:"#pass"
                   }
               }
           });

           $("#forgot-frm").validate();
                      
        });
    </script>
</div>


</body>
</html>

