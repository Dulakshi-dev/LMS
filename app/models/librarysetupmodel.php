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

    public static function sendEmailToAllStaff($subject, $message)
    {
        // Search for active members only
        $rs = Database::search("SELECT * FROM `user` WHERE `status_id` = '1'");
        
        // Check if any active members exist
        if ($rs->num_rows > 0) {
            // Require the email service
            require_once Config::getServicePath('emailService.php');
            
            // Define the email body with the message
            $body = '<h1 style="padding-top: 30px;">Shelf Loom</h1>
                <p style="font-size: 30px; color: black; font-weight: bold; text-align: center;">Welcome!</p> 
                <p>Dear Staff Member,</p>
                <div style="max-width: 600px; margin: 0 auto; padding: 20px; text-align: left;">
                    <p>We are pleased to connect with you! Here’s some important information:</p>
                    <h2>'.$message.'</h2>
                 
                    <p>If you have any questions or issues, please reach out to us.</p>
                    <p>Call: [tel_num]</p>
                    <div style="margin-top: 20px;">
                        <p>Best regards,</p>
                        <p>Shelf Loom Team</p>
                    </div>
                </div>';
    
            // Create the email service instance
            $emailService = new EmailService();
            
            // Loop through all active members and send the email
            while ($row = $rs->fetch_assoc()) {
                $email = $row['email'];
                // Send email to the current member
                $emailSent = $emailService->sendEmail($email, $subject, $body);
                
                // If the email fails for any member, you might want to log it or return false
                if (!$emailSent) {
                    // Log failure or handle accordingly
                    return false;
                }
            }
    
            return true;
        } else {
            return false;
        }
    }

    public static function sendEmailToAllMembers($subject, $message)
    {
        // Search for active members only
        $rs = Database::search("SELECT * FROM `member` WHERE `status_id` = '1'");
        
        // Check if any active members exist
        if ($rs->num_rows > 0) {
            // Require the email service
            require_once Config::getServicePath('emailService.php');
            
            // Define the email body with the message
            $body = '<h1 style="padding-top: 30px;">Shelf Loom</h1>
                <p style="font-size: 30px; color: black; font-weight: bold; text-align: center;">Welcome!</p> 
                <p>Dear Member,</p>
                <div style="max-width: 600px; margin: 0 auto; padding: 20px; text-align: left;">
                    <p>We are pleased to connect with you! Here’s some important information:</p>
                    <h2>'.$message.'</h2>
                 
                    <p>If you have any questions or issues, please reach out to us.</p>
                    <p>Call: [tel_num]</p>
                    <div style="margin-top: 20px;">
                        <p>Best regards,</p>
                        <p>Shelf Loom Team</p>
                    </div>
                </div>';
    
            // Create the email service instance
            $emailService = new EmailService();
            
            // Loop through all active members and send the email
            while ($row = $rs->fetch_assoc()) {
                $email = $row['email'];
                // Send email to the current member
                $emailSent = $emailService->sendEmail($email, $subject, $body);
                
                // If the email fails for any member, you might want to log it or return false
                if (!$emailSent) {
                    // Log failure or handle accordingly
                    return false;
                }
            }
    
            return true;
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
