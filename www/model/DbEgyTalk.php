<?php

/**
 * Instans av klassen skapar en koppling till databasen egytalk
 * och tillhandahåller ett antal metoder för att hämta och manipulera data i databasen.
 */
class DbEgyTalk
{
   /**
    * Används i metoder genom $this->db</code>
    */
   private $db;

   /**
    * DbEgyTalk constructor.
    *
    * Skapar en koppling till databaseb egytalk
    */
   public function __construct()
   {
      // Definierar konstanter med användarinformation.
      define('DB_USER', 'egytalk');
      define('DB_PASSWORD', '12345');
      define('DB_HOST', 'mariadb');
      define('DB_NAME', 'egytalk');

      // Skapar en anslutning till MySql och databasen world
      $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8';
      $this->db = new PDO($dsn, DB_USER, DB_PASSWORD);
   }

   /**
    * Kontrollerar av användare och lösen.
    * Skapar global sessions-array med användarinformation.
    *
    * @param  $userName  Användarnamn
    * @param  $passWord  Lösenord
    * @return $response användardata eller tom [] om inloggning misslyckas
    */
   function auth($userName, $passWord)
   {
      $userName = trim(filter_var($userName, FILTER_UNSAFE_RAW));
      $response = [];

      /* Bygger upp sql frågan */
      $stmt = $this->db->prepare("SELECT * FROM user WHERE username = :user");
      $stmt->bindValue(":user", $userName);
      $stmt->execute();


      /** Kontroll att resultat finns */
      if ($stmt->rowCount() == 1) {
         // Hämtar användaren, kan endast vara 1 person
         $user = $stmt->fetch(PDO::FETCH_ASSOC);
         // Kontrollerar lösenordet, och allt ok.
         if (password_verify($passWord, $user['password'])) {
            $response['uid'] = $user['uid'];
            $response['firstname'] = $user['firstname'];
            $response['surname'] = $user['surname'];
         }
      }

      return $response;
   }

   /**
    * Hämtar anvädardata från användare med secifikt användarID
    * 
    * @param  $uid  användarID
    * @return $response användardata eller tom [] om ingen anvädare hittats eller fel inträffat
    */
   function getUserFromUid($uid)
   {
      $response = [];
      try {
         /* Bygger upp sql frågan */
         $stmt = $this->db->prepare("SELECT * FROM user WHERE uid = :uid");
         $stmt->bindValue(":uid", $uid);
         $stmt->execute();

         /** Kontroll att resultat finns */
         if ($stmt->rowCount() == 1) {
            // Hämtar användaren, kan endast vara 1 person
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            $response['uid'] = $user['uid'];
            $response['firstname'] = $user['firstname'];
            $response['surname'] = $user['surname'];
         }
      } catch (Exception $e) {}

      return $response;
   }

   /**
    * Hämtar alla poster som gjorts på egytalk
    *
    * @return array med alla poster
    */
   function getAllPosts()
   {
      $posts = [];

      try {
         $sqlkod = "SELECT post.*, user.firstname, user.surname FROM post 
                NATURAL JOIN user ORDER BY post.date DESC";
         $stmt = $this->db->prepare($sqlkod);
         $stmt->execute();

         $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

         for ($i = 0; $i < count($posts); $i++) {
            $posts[$i]['comments'] =  $this->getComments($posts[$i]['pid']);
         }
      } catch (Exception $e) {
      } 

      return $posts;
   }

   /**
    * Hämtar poster för en användare,
    * sorterade efter publiceringsdatum
    *
    * @param   $uid    användar-ID för användaren
    * @return  array med statusuppdateringar sorterade efter datum
    */
   function getPosts($uid)
   {
      $posts = [];

      try {
         $sqlkod = "SELECT post.*, user.firstname, user.surname FROM post 
            NATURAL JOIN user WHERE post.uid = :uid ORDER BY post.date DESC";

         $stmt = $this->db->prepare($sqlkod);
         $stmt->bindValue(":uid", $uid, PDO::PARAM_INT);
         $stmt->execute();

         $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

         for ($i = 0; $i < count($posts); $i++) {
            $posts[$i]['comments'] =  $this->getComments($posts[$i]['pid']);
         }
      } catch (Exception $e) {
      }

      return $posts;
   }

   /**
    * Hämtar alla kommentarer till en post
    *
    * @param  $pid postens ID-nummer
    * @return array med kommentarer sorterade efter datum
    */
   function getComments($pid)
   {
      $comments = [];

      try {
         $sqlkod = "SELECT comment.*,user.firstname, user.surname FROM comment 
                NATURAL JOIN user WHERE comment.pid = :pid ORDER BY comment.date ASC";

         $stmt = $this->db->prepare($sqlkod);
         $stmt->bindValue(":pid", $pid, PDO::PARAM_INT);
         $stmt->execute();

         $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
      } catch (Exception $e) {
      }

      return $comments;
   }

   /**
    * Skapar ny samtalstråd.
    *
    * @param  $uid       Användar-ID
    * @param  $postTxt   Postat inlägg
    * @return true om det lyckades, annars false
    */
   function addPost($uid, $postTxt)
   {
      $postTxt = filter_var($postTxt, FILTER_SANITIZE_SPECIAL_CHARS);

      try {
         $stmt = $this->db->prepare("INSERT INTO post(uid, post_txt, date) VALUES(:uid, :post, :date)");

         $stmt->bindValue(":uid", $uid);
         $stmt->bindValue(":post", $postTxt);
         $stmt->bindValue(":date", date("Y-m-d H:i:s"));

         return $stmt->execute();
      } catch (Exception $e) {
         return false;
      }
   }

   /**
    * Lägger till en ny kommentar till en post.
    *
    * @param  $userID    Användar-ID för den som skriver kommentaren
    * @param  $statusID  Status-ID för statusuppdatering som kommenteras
    * @param  $comment   Kommentar
    * @return true om det lyckades, annars false
    */
   function addComment($uid, $pid, $comment)
   {
      $pid = filter_var($pid, FILTER_SANITIZE_NUMBER_INT);
      $comment = filter_var($comment, FILTER_SANITIZE_SPECIAL_CHARS);

      try {
         $stmt = $this->db->prepare("INSERT INTO comment(uid, pid, comments_txt, date) VALUES(:uid, :pid, :com, :date)");

         $stmt->bindValue(":uid", $uid);
         $stmt->bindValue(":pid", $pid, PDO::PARAM_INT);
         $stmt->bindValue(":com", $comment);
         $stmt->bindValue(":date", date("Y-m-d H:i :s"));

         return $stmt->execute();
      } catch (Exception $e) {
         return false;
      }
   }

   /**
    * Lägger till en ny användare
    *
    * @param  $fname   Förnamn
    * @param  $sname   Efternamn
    * @param  $user    Användarnamn
    * @param  $pwd     Lösenord
    * @return true om det lyckades, annars false
    */
   function addUser($fname, $sname, $user, $pwd)
   {
      $fname = filter_var($fname, FILTER_SANITIZE_SPECIAL_CHARS);
      $sname = filter_var($sname, FILTER_SANITIZE_SPECIAL_CHARS);
      $user = filter_var($user, FILTER_SANITIZE_SPECIAL_CHARS);
      $pwd = password_hash($pwd, PASSWORD_DEFAULT);

      try {
         $stmt = $this->db->prepare("INSERT INTO user(uid, firstname, surname, username, password) VALUES(UUID(), :fn, :sn, :user, :pwd)");

         $stmt->bindValue(":fn", $fname);
         $stmt->bindValue(":sn", $sname);
         $stmt->bindValue(":user", $user);
         $stmt->bindValue(":pwd", $pwd);
      } catch (Exception $e) {
         return false;
      }

      return $stmt->execute();
   }

   /**
    * Hämtar alla avändare i nätverket
    * @return array med användare
    */
   function getUsers()
   {
      $users = [];
      try {
         $stmt = $this->db->prepare("SELECT uid, firstname, surname FROM user WHERE 1 ORDER BY surname, firstname");
         if ($stmt->execute())
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
      } catch (Exception $e) {}

      return $users;
   }

   /**
    * Söker efter användare.
    *
    * @param  $searchWord    Sökord
    * @return array med användare
    */
   function findUsers($searchWord)
   {
      $searchWord = filter_var($searchWord, FILTER_UNSAFE_RAW);
      $sql = "SELECT uid, firstname, surname FROM user WHERE firstname LIKE :search OR surname LIKE :search  ORDER BY surname, firstname";
      $stmt = $this->db->prepare($sql);
      $stmt->bindValue(":search", "%$searchWord%");

      $stmt->execute();

      return $stmt->fetchAll(PDO::FETCH_ASSOC);
   }

   /**
    * Returnerar användarinstälningar
    *
    * @param  $uid    användarens uid
    * @return json-obj med användardata, mail, phone
    */
   function getSettings($uid)
   {
      $settings = [];

      try {
         $stmt = $this->db->prepare("SELECT mail, phone FROM user WHERE uid = :uid");
         $stmt->bindValue(":uid", $uid);

         if ($stmt->execute())
            $settings = $stmt->fetch(PDO::FETCH_ASSOC);
      } catch (Exception $e) {}

      return  $settings;
   }


   /**
    * Uppdaterar användarinstälningar
    *
    * @param  $uid      användarens uid
    * @param  $settings array med inställningar, $settings['phone'], $settings['mail']
    * @return true om uppdateringen lyckades
    */
   function setSettings($uid, $settings)
   {
      $success = false;

      if (isset($settings['phone']))
         $set = 'phone = :phone';

      if (isset($settings['mail'])) {
         if (count($settings) == 1)
            $set = 'mail = :mail';
         else
            $set .= ', mail = :mail';
      }

      try {
         $sql = "UPDATE user SET $set WHERE uid = :uid";
         $stmt = $this->db->prepare($sql);
         $stmt->bindValue(":uid", $uid);

         if (isset($settings['phone']))
            $stmt->bindValue(":phone", $settings['phone']);

         if (isset($settings['mail']))
            $stmt->bindValue(":mail", $settings['mail']);

         $success = $stmt->execute();
      } catch (Exception $e) {}

      return  $success;
   }

   /**
    * Verifierar om lösenord överenstämmer med användarens lösenord
    *
    * @param $uid    Användarens uid 
    * @param $pwd    Lösenord som skall testas
    * @return true   om löseordet är korrekt
    */
    private function verifyPassword($uid, $pwd){
      $verified = false;

      try {
         $stmt = $this->db->prepare("SELECT password FROM user WHERE uid = :uid ");
         $stmt->bindValue(":uid", $uid);

         if ($stmt->execute()){
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            $verified = password_verify($pwd, $user['password']);
         }
            
      } catch (Exception $e) {}

      return $verified;
   }

   /**
    * Uppdaterar lösenorder
    *
    * @param  $uid      användarens uid
    * @param  $oldpwd   Nuvarande lösenord
    * @param  $pwd      Nytt lösenord
    * @return true om uppdateringen lyckades
    */
   function setPassword($uid, $oldpwd, $pwd){
      $success = false;
      if($this->verifyPassword($uid, $oldpwd)){
         $pwd = password_hash($pwd, PASSWORD_DEFAULT);
         try {
            $sql = "UPDATE user SET password = :pwd WHERE uid = :uid";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(":pwd", $pwd);
            $stmt->bindValue(":uid", $uid);
            $success = $stmt->execute();
         } catch (Exception $e) {}
      }

      return  $success;
   }
}
