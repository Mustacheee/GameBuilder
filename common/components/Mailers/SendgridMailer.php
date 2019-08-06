<?php

namespace app\common\components\Mailers;

use app\common\interfaces\EmailMessengerInterface;
use SendGrid\Mail\Mail;
use Yii;

class SendgridMailer implements EmailMessengerInterface
{
    public function send(string $toEmail, string $subject, string $body)
    {
        $email = new Mail();
        $email->setFrom(Yii::$app->params['adminEmail']);
        $email->setSubject($subject);
        $email->addTo(YII_ENV_DEV ? Yii::$app->params['adminEmail'] : $toEmail);
        $email->addContent("text/plain", $body);

        $sendgrid = new \SendGrid(getenv('SENDGRID_API_KEY'));

        try {
            $sendgrid->send($email);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}