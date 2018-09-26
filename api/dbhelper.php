<?php
class DBHelper
{
    private $server;
    private $user;
    private $password;
    private $my_db;
    private $conn;

    //链接数据库
    function __construct($server, $user, $password, $my_db)
    {
      $this->server = $server;
      $this->user = $user;
      $this->password = $password;
      $this->my_db = $my_db;
      $this->conn =  mysql_connect($this->server, $this->user, $this->password);
      mysql_query("set names 'utf8'");
    }

    //注册到数据库
    public function register($phone, $password, $level=0, $end=0, $timeMark, $lastLoginx){
      mysql_select_db($this->my_db, $this->conn);
      $sql = "INSERT INTO moble_member(phone,password,level,end,timeMark,lastLogin) VALUES ('$phone','$password','$level','$end','$timeMark','$lastLoginx')";
      return mysql_query($sql,$this->conn);
    }

    //更新密码
    public function update($phone, $newPass){
      mysql_select_db($this->my_db, $this->conn);
      $sql = "UPDATE moble_member SET password = '$newPass' WHERE phone = '$phone'";
      return mysql_query($sql,$this->conn);
    }

    // 取得用户信息
    public function getData($phone){
      mysql_select_db($this->my_db, $this->conn);
      $sql = "SELECT * FROM moble_member WHERE phone='$phone'";
      $result  =  mysql_query($sql,$this->conn);
      if ($result)
        return mysql_fetch_array($result, MYSQL_ASSOC);
      else
        return null;
    }

    //添加订单信息
    public function addOrder($id, $phone, $amout, $timeMark, $period, $level){
      mysql_select_db($this->my_db, $this->conn);
      $sql = "INSERT INTO moble_order(order_id,phone_num,amount,status,order_date, period, level) VALUES ('$id','$phone',$amout,0,'$timeMark', $period, $level)";
      return mysql_query($sql,$this->conn);
    }

    //取得订单信息
    public function getOrder($id){
      mysql_select_db($this->my_db, $this->conn);
      $sql = "SELECT * FROM moble_order WHERE order_id = '$id'";
      $result = mysql_query($sql,$this->conn);
      if ($arr=mysql_fetch_array($result)){
        return $arr;
      } else {
        return null;
      }
    }

    //更新用户信息
    public function updateUser($phone, $level, $end){
      mysql_select_db($this->my_db, $this->conn);
      $sql = "UPDATE moble_member SET level = $level, end = '$end' WHERE phone = '$phone'";
      return mysql_query($sql,$this->conn);
    }

    //注册到数据库
    public function updateOrder($id){
      mysql_select_db($this->my_db, $this->conn);
      $sql = "UPDATE moble_order SET status = 1 WHERE order_id = '$id'";
      return mysql_query($sql,$this->conn);
    }

    //检查手机号是否存在
    public function check_phone($phone){
        return $this->getData($phone);
    }
}
?>
