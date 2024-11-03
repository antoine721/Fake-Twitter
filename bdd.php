
<?php 

try{
    $connexion = new PDO(dsn: "mysql:host=localhost;dbname=twitterlike",username: 'root',password: '');
}
catch(Exception $e){
    die($e->getMessage());
}