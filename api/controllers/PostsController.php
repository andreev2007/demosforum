<?php

namespace api\controllers;

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
use yii\rest\ActiveController;

use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Site controller
 */
class PostsController extends ActiveController
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
        return $this->redirect(['index']);
    }

    public function actionView($id)
    {
        $post = Posts::findOne($id);
        $comment = Comments::addComment($id);
        $comments = $post->getComments()
            ->with('children')
            ->andWhere(['status' => 10])
            ->andWhere(['parent_id' => null])->orderBy(['created_at' => SORT_DESC])->all();

        if ($comment->load(Yii::$app->request->post()) && $comment->save()) {
            Yii::$app->session->setFlash('success', ' Комментарий успешно сохранен!');
            return $this->refresh();
        } else {
            Yii::error($comment->errors);
        }

        Yii::error($comment->toArray());
        return $this->render('view', [
            'model' => $post,
            'comment' => $comment,
            'comments' => $comments,
        ]);
    }

    public function actionShare($id)
    {
        $post = Posts::findOne($id);
        if (Yii::$app->request->isPost) {
            if ($post->load(Yii::$app->request->post(), '') && $post->save()) {

            }
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
