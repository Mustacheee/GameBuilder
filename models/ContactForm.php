<?php

namespace app\models;

use app\common\components\Mailers\SendgridMailer;
use app\common\interfaces\EmailMessengerInterface;
use Yii;
use yii\base\Model;

class ContactForm extends Model
{
    public $name;
    public $email;
    public $comment;

    /** @var EmailMessengerInterface $mailer */
    public $mailer;

    public function init()
    {
        parent::init();
        $this->mailer = $this->mailer ?? new SendgridMailer();
    }

    public function rules()
    {
        return [
            [['name', 'email', 'comment'], 'required'],

            [['name', 'comment'], 'string'],
            ['email', 'email'],
        ];
    }

    public function send()
    {
        $emailBody = $this->generateMessageBody();
        return $this->validate() && $this->mailer->send(Yii::$app->params['adminEmail'], "Someone is interested in Growie!", $emailBody);
    }

    private function generateMessageBody(): string
    {
        return "Name: {$this->name}\nEmail: {$this->email}\nMessage: {$this->comment}";
    }
}
