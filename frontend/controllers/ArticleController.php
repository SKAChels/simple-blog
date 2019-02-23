<?php

namespace frontend\controllers;

use common\models\Comment;
use Yii;
use common\models\Article;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ArticleController implements the CRUD actions for Article model.
 */
class ArticleController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['create'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['create'],
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Article models.
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->redirect(['/']);
    }

    /**
     * Displays a single Article model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $comments_query = Comment::find()->where(['article_id' => $id])->orderBy(['created_at' => SORT_DESC]);
        $pagination = new Pagination(['totalCount' => $comments_query->count(), 'pageSize' => 10]);
        $comments = $comments_query->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();
        $comment_model = new Comment();
        if (isset(Yii::$app->user->identity->username)) {
            $comment_model->author = Yii::$app->user->identity->username;
        }
        return $this->render('view', [
            'comments' => $comments,
            'comment_model' => $comment_model,
            'pagination' => $pagination,
            'model' => $this->findModelRestricted($id),
        ]);
    }

    /**
     * Creates a new Article model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Article();
        if ($model->load(Yii::$app->request->post())){
            $model->user_id = Yii::$app->user->id;
            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Article model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @throws ForbiddenHttpException
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModelRestricted($id);
        if (!Yii::$app->user->can('updateArticle', ['article' => $model])) {
            throw new ForbiddenHttpException('You are not allowed to edit this article');
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Article model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @throws ForbiddenHttpException
     * @throws \Throwable
     */
    public function actionDelete($id)
    {
        $model = $this->findModelRestricted($id);
        if (!Yii::$app->user->can('updateArticle', ['article' => $model])) {
            throw new ForbiddenHttpException('You are not allowed to edit this article');
        }
        $model->status = Article::STATUS_DELETED;
        $model->save();

        return $this->redirect(['index']);
    }

    /**
     * @return string
     * @throws BadRequestHttpException
     */
    public function actionCreateComment()
    {
        if (Yii::$app->request->isAjax) {
            $model = new Comment();
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return $this->renderPartial('_comment', [
                    'model' => new Comment(),
                    'article' => $model->article_id,
                    'success_message' => true,
                ]);
            }

            return $this->renderPartial('_comment', [
                'model' => $model,
                'article' => $model->article_id,
                'success_message' => false,
            ]);
        }

        throw new BadRequestHttpException();
    }

    /**
     * Finds the Article model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Article the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Article::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Finds the Article model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Article the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModelRestricted($id)
    {
        $model = Article::findOne($id);
        if ($model !== null && $model->status !== Article::STATUS_DELETED) {
            if ($model->status === Article::STATUS_PUBLIC || $model->user_id === \Yii::$app->user->id || \Yii::$app->user->can('admin')) {
                return $model;
            }
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
