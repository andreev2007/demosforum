<?php

namespace console\controllers;

use common\models\User;
use Yii;
use yii\console\Controller;

class RbacController extends Controller
{
    public function actionInit()
    {

        //  Yii::$app->db->createCommand()->truncateTable('users')->execute();
        $user = User::findOne(['email' => 'admin@gmail.com']) ?: new User();
        $user->email = 'admin@gmail.com';
        $user->username = 'admin';
        $user->status = 10;
        $user->setPassword('Kavabunga243');
        $user->generateAuthKey();
        $user->save();

        $auth = Yii::$app->authManager;

        $admin = $auth->createRole('admin');
        if (!$auth->getRole('admin')) $auth->add($admin); else $this->stderr('ROLE admin exists');
        $superadmin = $auth->createRole('superadmin');
        if (!$auth->getRole('superadmin')) $auth->add($superadmin); else $this->stderr('ROLE superadmin exists');

        // add "admin" role and give this role the "managePoint" permission
        // as well as the permissions of the "owner" role

        $auth->addChild($superadmin, $admin);

        if ($user = User::findByEmail('admin@gmail.com')) {
            $auth->assign($admin, $user->id);
        }

    }

    /**
     * @param $email and $password
     */

    public function actionAddAdmin()
    {
        $user = new User(['email' => $this->prompt('email:'), 'status' => 10]);
        $user->setPassword($this->prompt('password'));
        $user->generateAuthKey();
        $user->save();
        $auth = Yii::$app->authManager;
        $auth->assign($auth->getRole('admin'), $user->id);
    }

}