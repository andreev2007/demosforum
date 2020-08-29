<?php

namespace common\jobs\mail;

use yii\base\BaseObject;

class SuccessRegistrationMailJob extends MailJob
{
    public $from = 'info@advise-me.site';
    public $to;
    public $subject = "Регистрация на портале tn8.ru";
    public $body = "Вы успешно зарегистрировались на портале tn8.ru";

}