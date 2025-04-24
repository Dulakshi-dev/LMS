<?php

class EmailTemplate
{
    public static function getEmailBody($recipientName, $specificMessage)
    {
        $libraryData = HomeModel::getLibraryInfo();
        $libraryPhone = $libraryData['mobile'];
        $libraryEmail = $libraryData['email'];

        $logoURL = 'https://i.imgur.com/OvmunZQ.png'; // Replace with domain url
        //$logoURL = 'http://localhost/LMS/public/staff/index.php?action=servelogo&image=' . urlencode($libraryData['logo']);

        return '
        <div style="font-family: Arial, sans-serif; background-color: #f8f9fa; padding: 40px 0;">
            <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; padding: 30px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.05);">
                
                <img src="' . $logoURL . '" alt="Library Logo" style="background-color: #2c3e50; padding: 10px; border-radius: 6px; max-width: 150px; margin: 0 auto 20px auto; display: block;">

                <hr style="border: none; border-top: 1px solid #ddd; margin: 20px 0;">
                
                <p style="font-size: 16px; color: #333;">Dear ' . htmlspecialchars($recipientName) . ',</p>

                <p style="font-size: 16px; color: #333;">I hope this message finds you well.</p>

                <div style="font-size: 16px; color: #333; line-height: 1.5;">' . $specificMessage . '</div>

                <p style="font-size: 14px; color: #555; margin-top: 30px;">If you have any issues or questions, please feel free to contact us:</p>
                <ul style="font-size: 14px; color: #555; list-style-type: none; padding-left: 0;">
                    <li><strong>Call:</strong> ' . $libraryPhone . '</li>
                    <li><strong>Email:</strong> ' . $libraryEmail . '</li>
                </ul>

                <p style="font-size: 14px; color: #555; margin-top: 30px;">Best regards,</p>
                <p style="font-size: 14px; font-weight: bold; color: #2c3e50;">Shelf Loom Team</p>
            </div>
        </div>
    ';
    }
}
