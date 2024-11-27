<?php

//$senha = $_GET["psw"];
$senha = "senha789";

$passHash = password_hash($senha, PASSWORD_DEFAULT);

echo " Senha original: ".$senha."</BR></BR>";

echo " senha: ".$passHash."</BR></BR>";

if(password_verify($senha, $passHash))
  {
    echo "1";
  }
  else
  {
    echo "0";
  }

?>