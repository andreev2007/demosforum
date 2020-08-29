<?php

namespace common\jobs\mail;

use yii\base\BaseObject;

class MailJob extends BaseObject
{
    public $from = 'info@advise-me.site';
    public $to;
    public $subject = "Регистрация на портале forum-medic";
    public $body = "Вы успешно зарегистрировались на портале forum-medic";

    public function execute($queue)
    {
        $mailer = \Yii::$app->mailer;
        $mailer->compose()->setTo($this->to)->setSubject($this->subject)->setFrom($this->from)->setTextBody($this->body)->send();
    }
}