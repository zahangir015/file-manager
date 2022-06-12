<?php

namespace app\controllers;

use app\components\Utils;
use app\models\File;
use app\models\FileSearch;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\web\UploadedFile;

/**
 * FileController implements the CRUD actions for File model.
 */
class FileController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors(): array
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
     * Lists all File models.
     *
     * @return string
     */
    public function actionIndex(): string
    {
        $searchModel = new FileSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single File model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView(int $id): string
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new File model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|Response
     */
    public function actionCreate()
    {
        $model = new File();
        if ($this->request->isPost) {
            $postData = $this->request->post();
            $pdfFiles = UploadedFile::getInstances($model, 'files');
            if (!empty($pdfFiles)) {
                foreach ($pdfFiles as $key => $file) {
                    $filename = self::processFile($file, $model->categoryId);
                    if ($filename) {
                        $model = new File();
                        if ($model->load($postData)) {
                            $model->title = $model->title.($key+1);
                            $model->path = 'uploads/files/' . $model->categoryId . '/' . $filename;
                            $model->createdBy = Yii::$app->user->id;
                            $model->createdAt = date('Y-m-d h:i:s');

                            if (!$model->save()) {
                                Yii::$app->session->setFlash('error', 'File upload failed - ' . Utils::processErrorMessages($model->getErrors()));
                                break;
                            } else {
                                Yii::$app->session->setFlash('success', 'File uploaded successfully.');
                            }
                        }

                    } else {
                        Yii::$app->session->setFlash('error', 'File process failed');
                        break;
                    }
                }

                Yii::$app->session->setFlash('success', 'File uploaded successfully');
                return $this->redirect(['index']);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing File model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate(int $id)
    {
        $model = $this->findModel($id);
        $oldFilePath = $model->path;
        $filename = null;
        if ($this->request->isPost && $model->load($this->request->post())) {
            $model->updatedBy = Yii::$app->user->id;
            $model->updatedAt = date('Y-m-d h:i:s');
            $file = UploadedFile::getInstance($model, 'pdfFile');
            if (!empty($file) && ($file->extension == 'pdf' || $file->extension == 'PDF')) {
                $filename = self::processFile($file);
            }

            if ($filename) {
                unlink($oldFilePath);
                $model->path = 'uploads/files/' . $filename;
            }

            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'File uploaded successfully');
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                Yii::$app->session->setFlash('error', 'File update failed - ' . Utils::processErrorMessages($model->getErrors()));
            }

            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing File model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete(int $id): Response
    {
        $model = $this->findModel($id);
        $oldFilePath = $model->path;
        if ($model->delete()) {
            unlink($oldFilePath);
            Yii::$app->session->setFlash('success', 'File deleted successfully');
        } else {
            Yii::$app->session->setFlash('error', 'File deletion failed');
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the File model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return File the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = File::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    protected static function processFile($file, $folder)
    {
        $uploadsDir = 'uploads/files/'.$folder;
        $imageUploadPath = $uploadsDir . DIRECTORY_SEPARATOR;
        Utils::checkDir($imageUploadPath);
        $filename = Utils::getRandomName() . '.pdf';
        if ($file->saveAs($imageUploadPath . $filename)) {
            return $filename;
        } else {
            return false;
        }

    }

    /**
     * @param $path
     */
    public static function checkDir($path)
    {
        if (is_array($path)) {
            foreach ($path as $p) {
                self::checkDir($p);
            }
        } else {
            if (!file_exists($path)) {
                mkdir($path, 0755, true);
            }
        }
    }
}
