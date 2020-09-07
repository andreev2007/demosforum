<?php

namespace app\components;

use common\models\Auth;
use common\models\User;
use Yii;
use yii\authclient\ClientInterface;
use yii\helpers\ArrayHelper;

/**
 * AuthHandler handles successful authentication via Yii auth component
 */
class AuthHandler
{
    /**
     * @var ClientInterface
     */
    private $client;

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    public function handle()
    {
        $attributes = $this->client->getUserAttributes();

        // Yii::$app->session->addFlash('error', json_encode($attributes));
        $email = ArrayHelper::getValue($attributes, 'email');
        $id = ArrayHelper::getValue($attributes, 'id');

        $nickname = ArrayHelper::getValue($attributes, 'login');
        if ($this->client->getId() == 'vkontakte') {
            $name = ArrayHelper::getValue($attributes, 'first_name');
            $surname = ArrayHelper::getValue($attributes, 'last_name');
            $image = ArrayHelper::getValue($attributes, 'photo');
        } elseif ($this->client->getId() == 'google') {
            $image = ArrayHelper::getValue($attributes, 'picture');
            $name = ArrayHelper::getValue($attributes, 'given_name');
            $surname = ArrayHelper::getValue($attributes, 'family_name');

        } elseif ($this->client->getId() == 'facebook') {
            $name_surname = ArrayHelper::getValue($attributes, 'name');

            $name_surname = preg_split("/\s/", $name_surname);

            $name = ArrayHelper::getValue($name_surname, 0);
            $surname = ArrayHelper::getValue($name_surname, 1);
            $image = "https://graph.facebook.com/" . ArrayHelper::getValue($attributes, 'id') . "/picture?type=normal";
        } elseif ($this->client->getId() == 'yandex') {

            $name = ArrayHelper::getValue($attributes, 'first_name');
            $surname = ArrayHelper::getValue($attributes, 'last_name');
            $image = "";
            $email = ArrayHelper::getValue($attributes, 'default_email');

        }




        /*   Yii::$app->session->addFlash('error', [
               'email' => $email,
               'image' => $image ?: '',
               'network' => $this->client->getId() ?: '',
               'last_name' => $surname ?: '',
               'first_name' => $name ?: ''
           ]);*/


        /* @var Auth $auth */
        $auth = Auth::find()->where([
            'source' => $this->client->getId(),
            'source_id' => $id,
        ])->one();
        // Yii::$app->session->addFlash('error',json_encode($auth));

        if (Yii::$app->user->isGuest) {
            if ($auth) { // login
                /* @var User $user */
                $user = $auth->user;
                $response = Yii::$app->user->login($user);
                //  Yii::$app->session->addFlash('error',$response);
                return $user->id;
            } else { // signup
                if ($email !== null && $user = User::find()->where(['email' => $email])->one()) {

                    /*   Yii::$app->getSession()->addFlash('error', [
                           Yii::t('app', "User with the same email as in {client} account already exists but isn't linked to it. Login using email first to link it.", ['client' => $this->client->getTitle()]),
                       ]);*/
                    return $user->id;
                } else {
                    $password = Yii::$app->security->generateRandomString(6);
                    $user = new User([
                        'email' => $email,
                        'password' => $password,
                        'avatar' => $image ?: '',
                        'network' => $this->client->getId() ?: '',
                        'last_name' => $surname ?: '',
                        'first_name' => $name ?: '',
                        'status' => 10,
                    ]);
                    $user->generateAuthKey();
                    $user->generatePasswordResetToken();

                    $transaction = User::getDb()->beginTransaction();

                    if ($user->save()) {

                        $auth = new Auth([
                            'user_id' => $user->id,
                            'source' => $this->client->getId(),
                            'source_id' => (string)$id,
                        ]);
                        if ($auth->save()) {
                            $transaction->commit();
                            Yii::$app->user->login($user);
                            return $user->id;
                        } else {
                            /* Yii::$app->getSession()->addFlash('error', [
                                 Yii::t('app', 'Unable to save {client} account: {errors}', [
                                     'client' => $this->client->getTitle(),
                                     'errors' => json_encode($auth->getErrors()),
                                 ]),
                             ]);*/
                        }
                    } else {
                        /*  Yii::$app->getSession()->addFlash('error', [
                              Yii::t('app', 'Unable to save user: {errors}', [
                                  'client' => $this->client->getTitle(),
                                  'errors' => json_encode($user->getErrors()),
                              ]),
                          ]);*/
                        echo var_dump($user->getErrors());
                        die;
                    }
                }
            }
        } else { // user already logged in
            if (!$auth) { // add auth provider
                $auth = new Auth([
                    'user_id' => Yii::$app->user->id,
                    'source' => $this->client->getId(),
                    'source_id' => (string)$attributes['id'],
                ]);
                if ($auth->save()) {
                    /** @var User $user */
                    /*Yii::$app->getSession()->setFlash('success', [
                        Yii::t('app', 'Linked {client} account.', [
                            'client' => $this->client->getTitle()
                        ]),
                    ]);*/
                } else {
                    /* Yii::$app->getSession()->setFlash('error', [
                         Yii::t('app', 'Unable to link {client} account: {errors}', [
                             'client' => $this->client->getTitle(),
                             'errors' => json_encode($auth->getErrors()),
                         ]),
                     ]);*/
                }
            } else { // there's existing auth
                /* Yii::$app->getSession()->setFlash('error', [
                     Yii::t('app',
                         'Unable to link {client} account. There is another user using it.',
                         ['client' => $this->client->getTitle()]),
                 ]);*/
            }
        }
        if ($user = User::find()->where(['email' => $email])->one()) {

            /*   Yii::$app->getSession()->addFlash('error', [
                   Yii::t('app', "User with the same email as in {client} account already exists but isn't linked to it. Login using email first to link it.", ['client' => $this->client->getTitle()]),
               ]);*/
            return $user->id;
        } else return false;


    }
}