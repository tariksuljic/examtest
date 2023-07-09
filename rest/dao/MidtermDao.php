<?php

class MidtermDao
{

  private $conn;

  /**
   * constructor of dao class
   */
  public function __construct()
  {
    try {

      /** TODO
       * List parameters such as servername, username, password, schema. Make sure to use appropriate port
       */
      $username = "root";
      $servername = "localhost";
      $password = "root";
      $schema = "midterm-2023";
      $port = "3308";


      /*options array neccessary to enable ssl mode - do not change*/
      // $options = array(
      // 	PDO::MYSQL_ATTR_SSL_CA => 'https://drive.google.com/file/d/1g3sZDXiWK8HcPuRhS0nNeoUlOVSWdMAg/view?usp=share_link',
      // 	PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false,

      // );
      /** TODO
       * Create new connection
       * Use $options array as last parameter to new PDO call after the password
       */
      $this->conn = new PDO("mysql:host=$servername;dbname=$schema;port=$port", $username, $password);

      // set the PDO error mode to exception
      $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      echo "Connected successfully";
    } catch (PDOException $e) {
      echo "Connection failed: " . $e->getMessage();
    }
  }

  /** TODO
   * Implement DAO method used to get cap table
   */
  public function cap_table()
  {
    $stmt = $this->conn->prepare("SELECT * FROM cap_table");
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $result;
  }

  public function get_share_class($share_class_id)
  {
    $stmt = $this->conn->prepare("SELECT * FROM share_classes WHERE id=:share_class_id");
    $stmt->execute(["share_class_id" => $share_class_id]);
    return $stmt->fetch();
  }

  public function get_investor($investor_id)
  {
    $stmt = $this->conn->prepare("SELECT * FROM investors WHERE id=:investor_id");
    $stmt->execute(["investor_id" => $investor_id]);
    return $stmt->fetch();
  }

  public function get_share_class_category_id($share_class_category_id)
  {
    $stmt = $this->conn->prepare("SELECT * FROM share_class_categories WHERE id=:share_class_category_id");
    $stmt->execute(["share_class_category_id" => $share_class_category_id]);
    return $stmt->fetch();
  }

  /** TODO
   * Implement DAO method used to get summary
   */
  public function summary()
  {
    $stmt = $this->conn->prepare("SELECT COUNT(DISTINCT investor_id) AS total_investors,SUM(diluted_shares) AS total_shares FROM cap_table");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  /** TODO
   * Implement DAO method to return list of investors with their total shares amount
   */
  public function investors()
  {
    $stmt = $this->conn->prepare("SELECT  i.first_name,i.last_name , i.company, SUM(c.diluted_shares) AS total_diluted_shares
    FROM investors i
    JOIN cap_table c ON i.id = c.investor_id
    GROUP BY i.id;");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
}
