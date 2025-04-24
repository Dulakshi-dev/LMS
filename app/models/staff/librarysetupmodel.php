<?php

require_once config::getdbPath();

class LibrarySetupModel
{

    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }


    public static function changeOpeningHours($weekdaysfrom, $weekdaysto, $weekendfrom, $weekendto, $holidayfrom, $holidayto)
    {
        // Check if the times are '00:00:00' and set them as NULL
        if ($weekdaysfrom == '00:00:00') {
            $weekdaysfrom = NULL;
        }
        if ($weekdaysto == '00:00:00') {
            $weekdaysto = NULL;
        }
        if ($weekendfrom == '00:00:00') {
            $weekendfrom = NULL;
        }
        if ($weekendto == '00:00:00') {
            $weekendto = NULL;
        }
        if ($holidayfrom == '00:00:00') {
            $holidayfrom = NULL;
        }
        if ($holidayto == '00:00:00') {
            $holidayto = NULL;
        }

        // Perform the update with NULL where the time was '00:00:00'
        Database::ud("UPDATE `opening_hours` SET 
                  `open_time` = CASE 
                                WHEN `day` = 'Week Day' THEN '$weekdaysfrom' 
                                WHEN `day` = 'Week End' THEN '$weekendfrom' 
                                WHEN `day` = 'Holiday' THEN '$holidayfrom' 
                                ELSE `open_time`
                                END,
                  `close_time` = CASE 
                                WHEN `day` = 'Week Day' THEN '$weekdaysto' 
                                WHEN `day` = 'Week End' THEN '$weekendto' 
                                WHEN `day` = 'Holiday' THEN '$holidayto' 
                                ELSE `close_time`
                                END
                  WHERE `day` IN ('Week Day', 'Week End', 'Holiday')");


        return true;
    }

    public static function changeNewsUpdates($boxId, $title, $date, $description, $image)
    {

        $rs = Database::search("SELECT * FROM `news` WHERE `id`='$boxId'");

        if ($rs && $rs->num_rows > 0) {
            Database::ud("UPDATE `news` SET 
            `title` = '$title', 
            `date` = '$date', 
            `description` = '$description', 
            `image_path` = '$image' 
        WHERE `id` = '$boxId'");
            return true;
        } else {
            Database::insert("INSERT INTO `news` VALUES('$boxId','$title','$date','$description','$image');");
            return false;
        }
    }

    public static function changeLibraryInfo($name, $address, $email, $phone, $fee, $logo, $fine)
    {
        
        // Perform the update with NULL where the time was '00:00:00'
        Database::ud("UPDATE `library_info` SET `name`='$name',`address`='$address',`email`='$email',`mobile`='$phone',`membership_fee`='$fee',`logo`='$logo',`fine_amount`='$fine' WHERE `id`='1'");
        return true;
    }

    public static function getAllActiveStaff()
    {
        // Search for active members only
        $rs = Database::search("SELECT * FROM `staff` WHERE `status_id` = '1'");
        
        // Check if any active members exist
        if ($rs->num_rows > 0) {
            return $rs;
    
        } else {
            return false;
        }
    }

     public static function getAllActiveMembers()
    {
        // Search for active members only
        $rs = Database::search("SELECT * FROM `member` WHERE `status_id` = '1'");
        
        // Check if any active members exist
        if ($rs->num_rows > 0) {
            return $rs;
    
        } else {
            return false;
        }
    }

    public static function getLibraryInfo()
    {
        $result = Database::search("SELECT * FROM `library_info` LIMIT 1");

        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }

        return false;
    }

    public static function getOpeningHours()
    {
        // Execute the query and return the results
        $result = Database::search("SELECT 
            CASE 
                WHEN `day` = 'Week Day' THEN 'Weekday' 
                WHEN `day` = 'Week End' THEN 'Weekend' 
                WHEN `day` = 'Holiday' THEN 'Holiday' 
                ELSE `day` 
            END AS `day_label`,
            `open_time`, 
            `close_time`
        FROM `opening_hours`;");

        // Check if there are results and return them
        if ($result && $result->num_rows > 0) {
            $openingHours = [];

            // Fetch each row and add it to the result array
            while ($row = $result->fetch_assoc()) {
                $openingHours[] = [
                    'day_label' => $row['day_label'],
                    'open_time' => $row['open_time'],
                    'close_time' => $row['close_time']
                ];
            }

            return $openingHours; // Return the array of opening hours
        }

        return false; // If no results are found, return false
    }
    
}
