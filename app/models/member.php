<?php 

require_once "../../main.php";

class Member extends Model {
    // Fields
    public $memberId;
    public $nic;
    public $fname;
    public $lname;
    public $email;
    public $address;
    public $password;
    public $mobile;

    // For querying purpouses (note: sanitize the name)
    private static $table = "member";

    public function __construct($memberId, $nic, $fname, $lname, $mobile, $email, $address, $password) {
        $this->memberId = $memberId;
        $this->nic = $nic;
        $this->fname = $fname;
        $this->lname = $lname;
        $this->mobile = $mobile;
        $this->email = $email;
        $this->address = $address;
        $this->password = $password;
    }

    public static function all()
    {
        $result = Database::search("SELECT * FROM " . Member::$table);
        $members = [];
        while($row = $result->fetch_assoc())
        {
            $member = new Member(
                $row["member_id"],
                $row["nic"],
                $row["fname"],
                $row["lname"],
                $row["mobile"],
                $row["email"],
                $row["address"],
                $row["password"]
                
            );

            array_push($members, $member);
        }

        return $members;
    }

    public static function searchByIDorNIC($memID, $nic) {
        return Database::search("SELECT * FROM `member` WHERE `member_id` = '$memID' OR `nic`='$nic'");
    }

    public static function createUser($data) {
        Database::iud("INSERT INTO `member` (`nic`, `fname`, `lname`, `mobile`, `address`, `email`, `status_id`, `receipt`, `role_id`) 
                       VALUES ('{$data['nic']}', '{$data['fname']}', '{$data['lname']}', '{$data['phoneNumber']}', '{$data['address']}', '{$data['email']}', '1', '{$data['receipt']}', '4')");
        $result = Database::search("SELECT `id` FROM `user` WHERE `nic` = '{$data['nic']}' AND `email`='{$data['email']}'");
        return $result->fetch_assoc();
    }

    public static function createLogin($userID, $hashedPassword) {
        Database::iud("INSERT INTO `login` (`user_id`, `password`, `userId`) VALUES ('$userID', '$hashedPassword', '{$userID}');");
    }
}