<?php
//Page permettant la simulation d'un paiement bancaire

//Clef pour crypter et décrypter les données
const KEY_SSL = 'YourAreBeautifulPeople';

const ID_BANQUE = "3waBanqueSecure";
const VALIDE_ID_SITE = ['restaurant'];

$iv = 'rioz$345FDSD324R'; // vecteur d'initialisation cryptSSL

$returnPaiement = -5; //Code du retour paiement
$error = 'Erreur indéterminée !'; //Message d'erreur
$urlBack = ''; //url de retour client

$key = 0;

function verifData($idSite,$data,&$key,&$urlBack)
{
  $request = $data;

  $datas = json_decode(openssl_decrypt($request , 'aes256' , KEY_SSL));


  if($idSite != $datas['idSite'] || !in_array($idSite,VALIDE_ID_SITE))
    throw new DomainException('Error access 1 - Vous n\'avez pas les droits, mauvais site !',-1);
  $urlBack = $datas['urlBack'];
  $key = $datas['key'];
  return $datas;
}

try {
  //Première requête vers le site de paiement en GET reception des paramètres
  if ($_SERVER['REQUEST_METHOD'] == 'GET' && array_key_exists('key',$_GET) && array_key_exists('data',$_GET))
  {
      $request = verifData($_GET['key'],$_GET['data'],$key,$urlBack);
  }
  elseif($_SERVER['REQUEST_METHOD'] == 'GET')
  {
    throw new DomainException('Error access 2 - Mauvais accès à la page, key ou data manquant !',-2);
  }
  elseif($_SERVER['REQUEST_METHOD'] == 'POST' && array_key_exists('key',$_POST))
  {
      // on récupère les données de sécurisation
      $request = verifData($_POST['key'],$_POST['data'],$key,$urlBack);
      //traitement du paiement et rediretion vers la page de validatio%n du site
      switch($_POST['numCb'])
      {
        case '0000000000000000':
          throw new DomainException('Error access 0 - Paiment refusé !',0);
          break;
        case '1111111111111111':
          $returnPaiement = 1; //paiement OK
      }

  }
  else {
    throw new DomainException('Error access 3 - Mauvais accès à la page POST sans données !',-3);
  }
} catch (DomainException $e) {
  $returnPaiement = $e->getMessage();
  $error = $e->getMessage();
}

  //on construit la réponse de retour
  //On encrypt les données et on redirige
  $response = ['key'=>$key,'idBanque'=>ID_BANQUE,'code'=>$returnPaiement,'dateTime'=>time()];
  if(array_key_exists('numCb',$_POST)) //si on est passé par le paiement on renvoie un bout du num CN
    $response['numCb'] = substr($_POST['numCb'],0,4).'XXXX XXXX'.substr($_POST['numCb'],-1,4);

  $responseURL = $urlBack.'?key='.$key.'&data='.openssl_encrypt(json_encode($response), 'aes256' , KEY_SSL, 0, $iv);
  //echo $responseURL;


?>
<html>

<?php if(is_null($returnPaiement)) :?>
  <h1>La banque 3wa</h1>
  <section>
    <h2>Bonjour <?=$datas['nom']?><h2>
    <p>Afin de finaliser votre paiement merci de saisir vos coordonnées bancaire</p>
    <p>
  <form action="index.php">
    <input type="hidden" name="data" value="<?=$_GET['data']?>">
    <input type="hidden" name="key" value="<?=$_GET['key']?>">
    <label for="nom">Nom du porteur de la carte : </label><input type="text" name="nom" id="nom">
    <label for="numCb">Numéro de carte : </label><input type="text" name="numCb" id="numCb">
    <label for="dateExp">Date d'expériation</label><input type="date" name="date" id="date">
    <label for="dateExp">Cryptogramme (3 chiffres au dos de la carte)</label><input type="text" name="crypto" id="crypto">
    <input type="submit" value="Valider le paiment" />
  </form>
<?php else :
  if($returnPaiement <= 0) :?>
    <p>Une erreur s'est produite : <?=$error?></p>
  <?php elseif ($returnPaiement == 1) :?>
    <p>Votre paiement a été accepté</p>
  <?php endif;?>
  <p><a href="<?=$responseURL?>">Retour au site</a></p>
<?php endif;?>

</html>
