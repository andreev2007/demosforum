<?php

namespace frontend\controllers;

use backend\widgets\FlashAlert;
use common\models\Comments;
use common\models\PostLikes;
use common\models\Posts;
use common\models\Reposts;
use common\models\User;
use common\models\UserSaved;
use frontend\models\ResendVerificationEmailForm;
use frontend\models\VerifyEmailForm;
use Yii;
use yii\base\InvalidArgumentException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Site controller
 */
class PostsController extends Controller
{

    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action); // TODO: Change the autogenerated stub
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $post = new Posts();
        $post->setScenario('insert');

        if ($post->load(Yii::$app->request->post()) && $post->save()) {
            return $this->redirect(['/posts/view', 'id' => $post->id]);
        }

        return $this->render('index', [
            'post' => $post
        ]);
    }

    public function actionDelete($id)
    {
        if ($this->findModel($id)->created_by !== Yii::$app->user->id) {
            Yii::$app->session->setFlash('warning', 'Извините, но мы не нашли что вы хотели');
            return $this->redirect('/site/index');
        } else {
            $this->findModel($id)->delete();
        }
        return $this->redirect(['/site/index']);
    }

    public function actionCommentDelete($id)
    {
        $comment = Comments::findOne($id);
        if ($comment->created_by !== Yii::$app->user->id) {
            Yii::$app->session->setFlash('warning', Yii::t('app', 'Sorry we did not find what you wanted'));
            return $this->redirect(['/posts/view', 'id' => $comment->id]);
        } else {
            $comment->delete();
            Yii::$app->session->setFlash('success', Yii::t('app', 'Your comment was deleted'));
        }
        return $this->redirect(['/posts/view', 'id' => $comment->id]);

    }

    public function actionView($id)
    {
        $post = Posts::findOne($id);
        $comment = Comments::addComment($id);

        if ($comments = $post->comments !== null) {
            $comments = $post->getComments()
                ->with('children')
                ->andWhere(['status' => 10])
                ->andWhere(['parent_id' => null])->orderBy(['created_at' => SORT_DESC])->all();
        }
        if ($comment->load(Yii::$app->request->post()) && $comment->save()) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'The comment is successfully added!'));
            return $this->refresh();
        } else {
            Yii::error($comment->errors);
        }

        if ($post->status == 10) {
            Posts::updateAll(['views' => (int)$post->views + 1], ['id' => $post->id]);
        } else {
            return $this->redirect(['/site/index']);
        }

        return $this->render('view', [
            'model' => $post,
            'comment' => $comment,
            'comments' => $comments,
        ]);
    }

    public function actionShare($id)
    {
        $post = Posts::findOne($id);
        if (!Yii::$app->user->isGuest) {
            if (Yii::$app->request->isPost) {
                if ($post->load(Yii::$app->request->post(), '') && $post->save()) {
                    Yii::$app->session->setFlash('success', 'Ваш репост был сохранен!');
                }
            }
        } else {
            return $this->redirect('/index');
        }

        $repostersId = Reposts::find()->where(['post_id' => $id])->select('owner_id')->column();
        return $this->render('share', [
            'model' => $post,
            'repostersId' => $repostersId,
        ]);
    }

    public function actionUpdate($id)
    {
        $post = Posts::findOne($id);
        if ($post->created_by !== Yii::$app->user->id) {
            Yii::$app->session->setFlash('warning', 'Извините, но мы не нашли что вы хотели');
            return $this->redirect('/site/index');
        }
        $post->setScenario('insert');

        if ($post->load(Yii::$app->request->post()) && $post->save()) {
            return $this->redirect(['view', 'id' => $post->id]);
        }
        return $this->render('update', [
            'model' => $post
        ]);
    }

    public function actionCommentUpdate($id)
    {
        $comment = Comments::findOne($id);
        $post = Posts::findOne($comment->post_id);
        if ($comment->created_by !== Yii::$app->user->id) {
            Yii::$app->session->setFlash('warning', 'Извините, но мы не нашли что вы хотели');
            return $this->redirect('/site/index');
        }

        if ($comment->load(Yii::$app->request->post()) && $comment->save()) {
            return $this->redirect(['/posts/view', 'id' => $post->id]);
        }
        return $this->render('update_comment', [
            'model' => $comment
        ]);
    }

    public function actionLike($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $params = ['post_id' => $id, 'user_id' => Yii::$app->user->id];
        if ($model = PostLikes::findOne($params)) {
            Yii::$app->response->setStatusCode(404);
            return [
                'likesCount' => Posts::findOne($id)->getLikesCount()
            ];
        } else {
            $model = new PostLikes($params);
            $model->save();
            Yii::$app->response->setStatusCode(201);
            return [
                'likesCount' => Posts::findOne($id)->getLikesCount()
            ];
        }
    }

    public function actionUnLike($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $params = ['post_id' => $id, 'user_id' => Yii::$app->user->id];
        if ($model = PostLikes::findOne($params)) {
            $model->delete();
            Yii::$app->response->setStatusCode(201);
            return [
                'likesCount' => Posts::findOne($id)->getLikesCount()
            ];
        } else {
            Yii::$app->response->setStatusCode(404);
            return [
                'likesCount' => Posts::findOne($id)->getLikesCount()
            ];
        }

    }


    public function actionStar($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $params = ['post_id' => $id, 'user_id' => Yii::$app->user->id];
        if ($model = UserSaved::findOne($params)) {
            Yii::$app->response->setStatusCode(404);
        } else {
            $model = new UserSaved($params);
            $model->save();
            Yii::$app->response->setStatusCode(201);
        }
    }

    public function actionUnStar($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $params = ['post_id' => $id, 'user_id' => Yii::$app->user->id];
        if ($model = UserSaved::findOne($params)) {
            $model->delete();
            Yii::$app->response->setStatusCode(201);
        } else {
            Yii::$app->response->setStatusCode(404);
        }

    }

    protected function findModel($id)
    {
        if (($model = Posts::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
