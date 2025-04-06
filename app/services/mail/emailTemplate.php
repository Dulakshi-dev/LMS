<?php
class EmailTemplate
{
    public static function getEmailBody($recipientName, $specificMessage)
    {
        return '
            <h1 style="padding-top: 30px;">Shelf Loom - Notification</h1>
            <p style="font-size: 30px; color: black; font-weight: bold; text-align: center;">Shelf Loom</p>

            <div style="max-width: 600px; margin: 0 auto; padding: 20px; text-align: left;">
                <p>Dear ' . $recipientName . ',</p>

                <!-- Insert Specific Email Content Here -->
                <p>' . $specificMessage . '</p>

                <div>
                    <p style="margin: 0px;">If you have any issues or questions, please don\'t hesitate to contact us.</p>
                    <p style="margin: 0px;">Call: [tel_num]</p>
                    <p style="margin: 0px;">Email: [email_address]</p>
                </div>

                <div>
                    <p style="margin-bottom: 0px;">Best regards,</p>
                    <p style="margin: 0px;">Shelf Loom</p>
                </div>
            </div>
        ';
    }
}
?>
