<?php

namespace frontend\controllers;

use app\models\Cart;
use app\models\Mode;
use app\models\Order;
use app\models\OrderGoods;
use app\models\Ress;
use backend\models\Goods;
use frontend\models\Payment;
use yii\db\Exception;
use yii\helpers\Url;
use yii\web\Cookie;

class GoodsController extends \yii\web\Controller
{
    public    $enableCsrfValidation=false;
    // 加入购物车
    public function actionAddCart($goods_id,$amount)
    {
        if(\Yii::$app->user->isGuest){
            // 游客状态
            $cookies = \Yii::$app->response->cookies;
            $value = $cookies->getValue('carts');
            if($value){
                $cart = unserialize($value);
            }else{
                $cart = [];
            }
            if(array_key_exists($goods_id,$cart)){
                $cart[$goods_id] += $amount;
            }else{
                $cart[$goods_id] = $amount;
            }
            $cookie = new Cookie();
            $cookie->name = 'carts';
            $cookie->value = serialize($cart);
            $cookies = \Yii::$app->response->cookies;
            $cookies->add($cookie);

        }else{
            $request = \Yii::$app->request;
            $model = new Cart();
            $goods = Cart::findOne(['goods_id'=>$goods_id]);
            if($goods){
                if($request->isGet){
                    $cookie = \Yii::$app->response->cookies;
                    $cookie->remove('carts');
                    $goods->load($request->get(),'');
                    $goods->member_id = \Yii::$app->user->id;
                    if($goods->goods_id == $request->get('goods_id')){
                        $goods->amount += $request->get('amount');
                    }

                    if($goods->validate()){
                        $goods->save();
                    }
                }
            }
            else{
                $model->load($request->get(),'');
                $model->member_id = \Yii::$app->user->id;
                if($model->validate()){
                    $model->save();
                }
            }
        }
        return $this->render('addcart');
    }
    // 购物车页面
    public function actionFlow(){

        if(\Yii::$app->user->isGuest){
            $cookies = \Yii::$app->response->cookies;
            $cart = $cookies->getValue('carts');
            if($cart){
                $goodes = unserialize($cart);
            }else{
                $goodes = [];
            }
            $cookie = new Cookie();
            $cookie->name = 'carts';
            $cookie->value = serialize($goodes);
            $cookies = \Yii::$app->response->cookies;
            $cookies->add($cookie);
        }else{
            $carts = Cart::find()->where(['member_id'=>\Yii::$app->user->id])->all();
//        var_dump($carts);die;
            $goodes = [];
           foreach ($carts as $cart){
                $goodes[$cart->goods_id] = $cart->amount;
           }
        }

        return $this->render('flow1',['goodss'=>$goodes]);
    }

    //ajax  操作购物车
    public function actionAjaxCart($goods_id,$amount){
        if(\Yii::$app->user->isGuest){
            //获取cookie中的购物车
            $cookies = \Yii::$app->request->cookies;
            $value = $cookies->getValue('carts');
            if($value){
                $carts = unserialize($value);
            }else{
                $carts = [];
            }
            if($amount){
                $carts[$goods_id] = $amount;
            }else{
                unset($carts[$goods_id]);
            }
            //将购物车数据保存到cookie
            $cookie = new Cookie();
            $cookie->name = 'carts';
            $cookie->value = serialize($carts);
            $cookies = \Yii::$app->response->cookies;
            $cookies->add($cookie);

        }else{
            if($amount){
                $goods = Cart::find()->where(['goods_id'=>$goods_id])->one();
                $goods->amount = $amount;
                $goods->save(0);
            }else{
                $goods = Cart::find()->where(['goods_id'=>$goods_id])->one();
                $goods->delete();
                return 'success';
            }

        }
    }

    // 订单页面
    public function actionOrder(){
        if(\Yii::$app->user->isGuest){
            return $this->redirect(['member/login']);
        }else{
            $member_id = \Yii::$app->user->id;
            $ress = Ress::find()->where(['member_id'=>$member_id])->all();
            $carts = Cart::find()->where(['member_id'=>$member_id])->all();
            $goods = [];
            foreach ($carts as $cart){
                $goods[$cart->goods_id] = $cart->amount;
            }
            return $this->render('flow2',['res'=>$ress,'good'=>$goods]);
        }
    }
    // 提交订单
    public function actionPlaceOrder(){
        $request = \Yii::$app->request;
        if($request->isPost){
            $order = new Order();
            //地址表
            $address = Ress::findOne(['id'=>$request->post('address_id')]);
            //名字
            $order->name = $address->name;
            // 省
            $order->province = $address->province;
            // 市
            $order->city = $address->city;
            // 县
            $order->area = $address->county;
            // 详细地址
            $order->address = $address->address;
            // 电话
            $order->tel = $address->tel;
            // 配送方式
            $mode = Mode::findOne(['id'=>$request->post('delivery')]);
//            var_dump($mode);die;
            // id
            $order->delivery_id = $mode->delivery_id;
            // 名字
            $order->delivery_name = $mode->delivery_name;
            // 价格
            $order->total = $mode->delivery_price;
            // 运送方式
            $pay = Payment::findOne(['id'=>$request->post('pay')]);
//            var_dump($pay);die;
            // id
            $order->payment_id = $pay->payment_id;
            // 名字
            $order->payment_name = $pay->payment_name;
            // 价格
            $order->total = 0;
            // 状态
            $order->status = 1;


            // 第三方交易货
            $order->trade_no = '000000'.$order->id;
            // 时间
            $order->create_time = time();
            $transaction = \Yii::$app->db->beginTransaction();
            try{
                $order->save();
//                var_dump($order->getErrors());die;
                // 保存订单详情
                $carts = Cart::find()->where(['member_id'=>\Yii::$app->user->id])->all();
                foreach ($carts as $cart){

                    $goods  = Goods::findOne(['id'=>$cart->goods_id]);
                    //检查库存
                    if($goods->stock < $cart->amount){
                        //如果商品库存不足,抛出异常
                        throw new Exception('商品['.$goods->name.']库存不足');
                    }
                    //扣减商品库存
                    $goods->stock -= $cart->amount;
                    $goods->save();

                    $goods  = Goods::findOne(['id'=>$cart->goods_id]);
                    $orderGoods = new OrderGoods();
                    $orderGoods->order_id = $order->id;
                    $orderGoods->goods_id = $goods->id;
                    $orderGoods->goods_name = $goods->name;
                    $orderGoods->logo = $goods->logo;
                    $orderGoods->price = $goods->shop_price;
                    $orderGoods->amount = $cart->amount;
                    $orderGoods->total = $goods->shop_price * $cart->amount;
                    $order->total+=$orderGoods->total;
                    $orderGoods->save();
                }
                $order->save();
                Cart::deleteAll(['member_id'=>\Yii::$app->user->id]);
                //提交事务
                $transaction->commit();
            }catch (Exception $e){
                //事务回滚
                $transaction->rollBack();
            }
        }
        return $this->render('flow3');
    }
    // 查看订单
    public function actionShowOrder(){
        $order = Order::find()->all();
//var_dump($order);die;
        return $this->render('order',['orders'=>$order]);
    }
    // 删除订单
    public function actionDelete($order_id){
        $goods = Order::find()->where(['id'=>$order_id])->one();
//        Order::find()->where()
//        var_dump($goods);die;
        if($goods->status == 4){
            $goods->delete();
            OrderGoods::deleteAll(['order_id'=>$order_id]);
            return 'success';
        }else{
            return 'flase';
        }
    }
    // 修改状态
    public function actionStatus($id)
    {
        $status = Order::findOne(['id' => $id]);
        $status->status = 4;
        $status->save();
        return $this->redirect(['goods/show-order']);
    }
}
