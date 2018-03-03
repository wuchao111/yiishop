<?php

namespace backend\controllers;

use backend\models\Goods;
use backend\models\GoodsCategory;
use backend\models\GoodsDayCount;
use backend\models\GoodsGallery;
use backend\models\GoodsIntro;
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;
use yii\data\Pagination;
use yii\web\UploadedFile;

class GoodsController extends \yii\web\Controller
{
    // 传图片插件使用
    // 防止图片上传失败
    public $enableCsrfValidation = false;
    // 商品列表
    public function actionIndex()
    {
        $request = \Yii::$app->request;
        // 接收get传过来的数据
        $get = $request->get();
        // 定义一个空数组
        $actions = [];

        $sql='';
        // 如果搜索
        if ($get){
            if(!empty($get['name'])){
                $actions[]="name like '%{$get['name']}%'";
            }
            if(!empty($get['sn'])){
                $actions[]="sn like '%{$get['sn']}%'";
            }
            if(!empty($get['moneys'])){
                $actions[]="shop_price >= '{$get['moneys']}'";
            }
            if(!empty($get['money'])){
                $actions[]="shop_price <= '{$get['money']}'";
            }
            $sql.=implode('and  ',$actions);


            if ($sql!=''){
               $sql='  and  '.$sql;
            }
        }


        $sql='status =0 and is_on_sale=0  '.$sql;
    // 商品列表
//        var_dump($sql);exit;
        $query = Goods::find()->where($sql);
        $pager = new Pagination();
        $pager->totalCount = $query->count(); // 总条数
        $pager->defaultPageSize = 5; // 每页显示条数
        $good = $query->offset($pager->offset)->limit($pager->limit)->all();
        return $this->render('index',['goods'=>$good,'pager'=>$pager]);
    }
    // 添加商品
    public function actionAdd(){
        // 实例化request组件
        $request = \Yii::$app->request;
        // 实例化商品表
        $model = new Goods();
        // 实例化商品详情表
        $intro = new GoodsIntro();
        // 实例化商品每日添加数
        $count = new GoodsDayCount();
        if($request->isPost){
//            echo 11;
//            var_dump($_POST);die;
            // 绑定数据
            $intro->load($request->post());
            $model->load($request->post());
            // 获取当前日期
            $day = date('Ymd');
            // 查询商品每日添加数里面有没有当前日期
            $sn = GoodsDayCount::find()->where(['day'=>$day])->one();
//            var_dump($sn);die;
            // 如果数据表的日期为空
            if($sn == null){
                // 每日添加数时间
                $count->day = $day;
                // 条数+1
                $count->count =$count->count+ 1;
                // 货号
                $model->sn = $day.str_pad($count->count,5,"0",STR_PAD_LEFT);
                $count->save();
            }else{
                // 条数在没添加条数之后加1
                $sn->count = $sn->count +1;
                // 货号
                $model->sn = $day.str_pad($sn->count,5,"0",STR_PAD_LEFT);
                $sn->save();
            }
            // 在售
            $model->is_on_sale = 0;
            // 状态  正常
            $model->status = 0;
            // 添加时间
            $model->create_time = time();
            // 验证规则
            if($model->validate() &&$intro->validate() ){
                // 保存商品表
                $model->save();
                // 详情表的goods_id = 商品表的id
                $intro->goods_id=$model->id;
                // 保存商品详情表
                $intro->save();
                // 保存每日添加数
                $count->save();
                \Yii::$app->session->setFlash('success', '添加成功');
                return $this->redirect(['goods/index']);
            }else{
                var_dump($model->getErrors());die;
            }
        }
        // 加载视图
        $nodes = GoodsCategory::find()->select(['id','parent_id','name'])->asArray()->all();
        return $this->render('add', ['model' => $model,'intro'=>$intro,'nodes'=>json_encode($nodes)]);
    }
    // 修改商品
    public function actionEdit($id){
        $request = \Yii::$app->request;
        $model = Goods::findOne(['id'=>$id]);
        $intro = GoodsIntro::findOne(['goods_id'=>$id]);
        if($request->isPost){
            $model->load($request->post());
            $intro->load($request->post());
            if($model->validate() && $intro->validate()){
                $model->save();
                $intro->save();
                \Yii::$app->session->setFlash('success', '添加成功');
                return $this->redirect(['goods/index']);
            }
        }
        $nodes = GoodsCategory::find()->select(['id','parent_id','name'])->asArray()->all();
        return $this->render('add', ['model' => $model,'intro'=>$intro,'nodes'=>json_encode($nodes)]);

    }
    // 删除商品
    public function actionDelete($id){
        $model = Goods::findOne(['id'=>$id]);
        $model->status = 1;
        $model->is_on_sale = 1 ;
        $model->save();
        \Yii::$app->session->setFlash('success', '删除成功');
        return $this->redirect(['goods/index']);
    }
    // 回收站
    public function actionRecycle(){
        $query = Goods::find()->where(['status' => 1 ]);
        $pager = new Pagination();
        $pager->totalCount = $query->count(); // 总条数
        $pager->defaultPageSize = 5; // 每页显示条数
        $good = $query->offset($pager->offset)->limit($pager->limit)->all();
        return $this->render('recycle',['goods'=>$good,'pager'=>$pager]);
    }
    // 恢复
    public function actionRecovery($id){
        $model = Goods::findOne(['id'=>$id]);
        $model->status = 0;
        $model->is_on_sale = 0 ;
        $model->save();
        \Yii::$app->session->setFlash('success', '恢复成功');
        return $this->redirect(['goods/index']);
    }
    // 上传图片
    public function actionLogoUpload()
    {
        //var_dump($_FILES);
        //实例化上传文件类
        $uploadFile = UploadedFile::getInstanceByName('file');
        $fileName = '/upload/goods/' . uniqid() . '.' . $uploadFile->extension;
        $result = $uploadFile->saveAs(\Yii::getAlias('@webroot') . $fileName);
        if ($result) {
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
            $filePath = \Yii::getAlias('@webroot') . $fileName;
            // 上传到七牛后保存的文件名
            $key = $fileName;
            // 初始化 UploadManager 对象并进行文件的上传。
            $uploadMgr = new UploadManager();
            // 调用 UploadManager 的 putFile 方法进行文件的上传。
            list($ret, $err) = $uploadMgr->putFile($token, $key, $filePath);
            if ($err == null) {
                //上传七牛云成功
                //访问七牛云图片的地址http://<domain>/<key>
                return json_encode([
                    'url' => "http://p4t14zhct.bkt.clouddn.com/{$key}"
                ]);
            } else {
                return json_encode([
                    'url' => $err
                ]);
            }
        } else {
            return json_encode([
                'url' => "fail"
            ]);
        }
    }
    // 编译器
    public function actions()
    {
        return [
            'upload' => [
                'class' => 'kucha\ueditor\UEditorAction',
                'config' => [
                    "imageUrlPrefix"  => "http://www.yiishop.com",//图片访问路径前缀
                ]
            ]
        ];
    }
    // 商品详情
    public function actionShow($id){
        $model = Goods::findOne(['id'=>$id]);
        $models = GoodsIntro::findOne(['goods_id'=>$id]);
        return $this->render('show',['model'=>$model,'models'=>$models]);
    }
    // 相册
    public function actionPath($id){
        // 查询出图片表的goods_id 和传过来的id 相等
        $good = GoodsGallery::find()->where(['goods_id'=>$id])->all();
        $request = \Yii::$app->request;
        $model=new GoodsGallery();
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                // 如果上传图片为空
                if($model->path == null){
                    \Yii::$app->session->setFlash('success', '添加失败');
                    return $this->redirect(['goods/path','id'=>$id]);
                }
                $model->save();
                \Yii::$app->session->setFlash('success', '添加成功');
                return $this->redirect(['goods/path','id'=>$id]);
            }
        }

        return $this->render('path', ['model'=>$model,'goods'=>$good,'id'=>$id]);
    }
    // 删除相册
    public function actionOff($id,$goods_id){
        $goods = GoodsGallery::findOne(['id'=>$id]);
        $goods->delete();
        \Yii::$app->session->setFlash('success', '删除成功');
        return $this->redirect(['goods/path','id'=>$goods_id]);
    }
}
