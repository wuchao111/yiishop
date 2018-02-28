<?php

namespace backend\controllers;

use backend\models\Brand;
use yii\web\UploadedFile;
// 引入鉴权类
use Qiniu\Auth;
// 引入上传类
use Qiniu\Storage\UploadManager;

class BrandController extends \yii\web\Controller
{
    public $enableCsrfValidation = false;

    // 品牌列表
    public function actionIndex()
    {
        $brand = Brand::find()->where(['is_deleted' => 0])->all();
        return $this->render('index', ['brands' => $brand]);
    }

    // 添加品牌
    public function actionAdd()
    {
        $request = \Yii::$app->request;
        $model = new Brand();
        if ($request->isPost) {
//            var_dump($_POST);die;
            $model->load($request->post());
            $model->is_deleted = 0;
            if ($model->validate()) {
                $model->save();
                \Yii::$app->session->setFlash('success', '添加成功');
                return $this->redirect(['brand/index']);
            } else {
                var_dump($model->getErrors());
                die;
            }
        }
        return $this->render('add', ['model' => $model]);
    }

    public function actionEdit($id)
    {
        $request = \Yii::$app->request;
        $model = Brand::findOne(['id' => $id]);
        if ($request->isPost) {
            $model->load($request->post());
            if ($model->validate()) {
                $model->save();
                \Yii::$app->session->setFlash('success', '添加成功');
                return $this->redirect(['brand/index']);
            }
        }
        return $this->render('add', ['model' => $model]);
    }

    //删除
    public function actionDelete($id)
    {
        $model = Brand::findOne(['id' => $id]);
        $model->is_deleted = 1;
        $model->save();
        return $this->redirect(['brand/index']);
    }

    // 图片上传


//    }

    public function actionLogoUpload(){
        //var_dump($_FILES);
        //实例化上传文件类
        $uploadFile = UploadedFile::getInstanceByName('file');
        $fileName = '/upload/brand/' . uniqid() . '.' . $uploadFile->extension;
        $result = $uploadFile->saveAs(\Yii::getAlias('@webroot') . $fileName);
        if($result){
            //文件保存成功
            //将图片上传到七牛云
            // 需要填写你的 Access Key 和 Secret Key
            $accessKey = "uwAwyPveZlfFyS6snALW_qx_kT-Ryj42OKlNABSs";
            $secretKey = "19wbXkXpq9jbpBfdbmKl36xXppZyXVtGCr-F-6_P";
            $bucket = "wuchao123";
            // 构建鉴权对象
            $auth = new Auth($accessKey, $secretKey);
            // 生成上传 Token
            $token = $auth->uploadToken($bucket);
            // 要上传文件的本地路径
            $filePath = \Yii::getAlias('@webroot').$fileName;
            // 上传到七牛后保存的文件名
            $key = $fileName;
            // 初始化 UploadManager 对象并进行文件的上传。
            $uploadMgr = new UploadManager();
            // 调用 UploadManager 的 putFile 方法进行文件的上传。
            list($ret, $err) = $uploadMgr->putFile($token, $key, $filePath);
            if($err == null){
                //上传七牛云成功
                //访问七牛云图片的地址http://<domain>/<key>
                return json_encode([
                    'url' => "http://p4t14zhct.bkt.clouddn.com/{$key}"
                ]);
            }else{
                return json_encode([
                    'url'=>$err
                ]);
            }



        }else{
            return json_encode([
                'url'=>"fail"
            ]);
        }
    }
}
