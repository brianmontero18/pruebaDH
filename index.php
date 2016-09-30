<?php
require_once('validarFormulario.php');

session_start();

if(!empty($_SESSION['usuario'])) {
header("Location:perfil.php");exit;
}

$defaultName = '';
$defaultEmail = '';
$defaultUsername = '';
$defaultPassword = '';
$defaultPasswordConfirm = '';

if (!empty($_POST)){
  //Aca registramos el usuario
  if (isset($_POST['formulario']) && $_POST['formulario'] == 'registro'){

    $defaultName = isset($_POST['name'])?$_POST['name']:'';
    $defaultEmail = isset($_POST['email'])?$_POST['email']:'';
    $defaultUsername = isset($_POST['username'])?$_POST['username']:'';
    $defaultPassword = isset($_POST['password'])?$_POST['password']:'';
    $defaultPasswordConfirm = isset($_POST['passwordConfirm'])?$_POST['passwordConfirm']:'';

    $validacion = validarRegistro($defaultName, $defaultEmail, $defaultUsername, $defaultPassword, $defaultPasswordConfirm);

    $state = true;

    foreach ($validacion as $value){
      if ($value != 1){
        $state = false;
      }
    }

    if ($state == true){

      $usuario = generarUsuario($defaultName,
                                  $defaultEmail,
                                  $defaultUsername,
                                  $defaultPassword);

      if(guardarUsuario($usuario)) {
        header("Location:exito.php");exit;
      } else {
        die('ERROR al registrate, intenta luego');
      }

    }
  }

  //Aca validamos el login
  if (isset($_POST['formulario']) && $_POST['formulario'] == 'login'){

    $defaultEmail = isset($_POST['email'])?$_POST['email']:'';
    $defaultPassword = isset($_POST['password'])?$_POST['password']:'';

    $validacion = validarLogin($defaultEmail, $defaultPassword);

    $state = true;

    foreach ($validacion as $value){
      if ($value != 1){
        $state = false;
      }
    };

    if ($state == true){
      $usuario = buscarEmailAndPassword($defaultEmail, $defaultPassword);

      if ($usuario != false){
          $_SESSION['usuario'] = $usuario;
          header("Location:perfil.php");exit;
      }
    } else {
      echo 'Hubo un error en el login';
    }

  }
}
?>

<html>
<head>
    <meta http-equiv='Content-Type' content='text/html; charset=utf-8'/>
    <title>Contact us</title>
</head>
<body>

    <div id='fg_membersite'>

        <!--Formulario de Registro-->
        <form id='register' action='index.php' method='post' enctype="multipart/form-data">
            <fieldset >

                <legend>Registrate</legend>

                <div class='container'>
                    <label for='name' >Nombre completo: </label><br/>
                    <input type='text' name='name' id='name' maxlength="50" value="<?=$defaultName ?>"/>
                    <?= (!empty($validacion['nombre']) && $validacion['nombre'] != 1)?$validacion['nombre']:''; ?>
                </div>
                <div class='container'>
                    <label for='email' >Email:</label><br/>
                    <input type='text' name='email' id='email' maxlength="50" value="<?=$defaultEmail ?>" />
                    <?= (!empty($validacion['email']) && $validacion['email'] != 1)?$validacion['email']:''; ?>
                </div>
                <div class='container'>
                    <label for='username' >Nombre de usuario:</label><br/>
                    <input type='text' name='username' id='username' value='<?=$defaultUsername ?>' maxlength="50" />
                    <?= (!empty($validacion['nombreUsuario']) && $validacion['nombreUsuario'] != 1)?$validacion['nombreUsuario']:''; ?>
                </div>
                <div class='container'>
                    <label for='password' >Contraseña:</label><br/>
                    <input type='password' name='password' id='password' maxlength="50" value="<?=$defaultPassword ?>" />
                    <?= (!empty($validacion['password']) && $validacion['password'] != 1)?$validacion['password']:''; ?>
                </div>
                <div class='container'>
                    <label for='passwordConfirm' >Confirmar Contraseña:</label><br/>
                    <input type='password' name='passwordConfirm' id='passwordConfirm' maxlength="50" value="<?=$defaultPasswordConfirm ?>"/>
                    <?= (!empty($validacion['passwordConfirm']) && $validacion['passwordConfirm'] != 1)?$validacion['passwordConfirm']:''; ?>
                </div>

                <br>
                <input type="file" name="imagen" />

                <br/>
                <br/>

                <div class='container'>
                    <input type='submit' name='formulario' value='registro' />
                </div>

            </fieldset>
        </form>

        <!--Formulario de Login-->
        <form id='login' action='index.php' method='post'>
            <fieldset >

                <legend>Conectarse</legend>

                <div class='container'>
                    <label for='email' >Email:</label><br/>
                    <input type='text' name='email' id='email' value='' maxlength="255" />
                    <?= (!empty($validacion['emailLogin']) && $validacion['emailLogin'] != 1)?$validacion['emailLogin']:''; ?>
                </div>
                <div class='container'>
                    <label for='password' >Contraseña:</label><br/>
                    <input type='password' name='password' id='password' maxlength="50" />
                    <?= (!empty($validacion['passwordLogin']) && $validacion['passwordLogin'] != 1)?$validacion['passwordLogin']:''; ?>
                </div>

                <br/>

                <div class='container'>
                    <input type='submit' name='formulario' value='login' />
                </div>

            </fieldset>
        </form>

    </body>
</html>
