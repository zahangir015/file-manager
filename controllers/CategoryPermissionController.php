<?php

namespace app\controllers;

use app\components\Utils;
use app\models\Category;
use app\models\CategoryPermission;
use app\models\CategoryPermissionSearch;
use Yii;
use yii\base\Exception;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CategoryPermissionController implements the CRUD actions for CategoryPermission model.
 */
class CategoryPermissionController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all CategoryPermission models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new CategoryPermissionSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single CategoryPermission model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView(int $id): string
    {
        $permissionList = CategoryPermission::find()->with(['user'])->where(['userId' => $id])->all();
        return $this->render('view', [
            'permissionList' => $permissionList,
        ]);
    }

    /**
     * Creates a new CategoryPermission model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new CategoryPermission();
        if ($this->request->isPost) {
            $postData = $this->request->post();
            $flag = true;
            foreach ($postData['CategoryPermission']['refId'] as $key => $refId) {
                $model = new CategoryPermission();
                if ($model->load($postData)) {
                    $model->refId = $refId;
                    $model->refModel = Category::className();
                    $model->createdBy = Yii::$app->user->id;
                    $model->createdAt = date('Y-m-d h:i:s');
                    if (!$model->save()) {
                        $flag = false;
                        Yii::$app->session->setFlash('error', 'Category permission addition failed - ' . Utils::processErrorMessages($model->getErrors()));
                        break;
                    }
                }
            }

            if ($flag) {
                Yii::$app->session->setFlash('success', 'Category permission added successfully');
                return $this->redirect(['view', 'id' => $postData['CategoryPermission']['userId']]);
            }

        }

        $model->loadDefaultValues();
        $categories = ArrayHelper::map(Category::findAll(['status' => 1]), 'id', 'name');
        return $this->render('create', ['model' => $model,
            'categories' => $categories]);
    }

    /**
     * Updates an existing CategoryPermission model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public
    function actionUpdate(int $id)
    {
        if ($this->request->isPost) {
            $postData = $this->request->post();
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $flag = true;
                CategoryPermission::deleteAll(['userId' => $id]);

                foreach ($postData['CategoryPermission']['refId'] as $key => $refId) {
                    $model = new CategoryPermission();
                    if ($model->load($postData)) {
                        $model->refId = $refId;
                        $model->refModel = Category::className();
                        $model->createdBy = Yii::$app->user->id;
                        $model->createdAt = date('Y-m-d h:i:s');
                        if (!$model->save()) {
                            $flag = false;
                            Yii::$app->session->setFlash('error', 'Category permission addition failed - ' . Utils::processErrorMessages($model->getErrors()));
                            break;
                        }
                    }
                }

                if (!$flag) {
                    $transaction->rollBack();
                } else {
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', 'Category permission updated successfully.');
                    return $this->redirect(['view', 'id' => $postData['CategoryPermission']['userId']]);
                }

            } catch (Exception $e) {
                $transaction->rollBack();
                Yii::$app->session->setFlash('error', $e->getMessage().' - '.$e->getFile().' - '.$e->getLine());
            }

        }

        return $this->render('update', [
            'model' => new CategoryPermission(),
            'categories' => ArrayHelper::map(Category::findAll(['status' => 1]), 'id', 'name'),
            'userPermissionList' => CategoryPermission::find()->with(['user'])->where(['userId' => $id])->all()
        ]);
    }

    /**
     * Deletes an existing CategoryPermission model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public
    function actionDelete(int $id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the CategoryPermission model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return CategoryPermission|array|\yii\db\ActiveRecord[]
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected
    function findModel(int $id)
    {
        if (($model = CategoryPermission::find()->where(['userId' => $id])->all()) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
