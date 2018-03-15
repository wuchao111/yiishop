<?php

namespace backend\controllers;

use backend\filters\RbacFilter;
use backend\models\Rbac;
use backend\models\Role;

class RbacController extends \yii\web\Controller
{

    // 权限列表
    public function actionIndexper()
    {
        $authmanager = \Yii::$app->authManager;
        $per = $authmanager->getPermissions();
        return $this->render('indexper', ['pers' => $per]);

    }

    // 添加权限
    public function actionAddper()
    {
        $model = new Rbac();
        $request = \Yii::$app->request;
        if ($request->isPost) {
            $model->load($request->post());
            if ($model->validate()) {
                $authmanager = \Yii::$app->authManager;
                $permission = $authmanager->createPermission($model->name);
                $permission->description = $model->description;
                $authmanager->add($permission);
                \Yii::$app->session->setFlash('success', '添加成功');
                return $this->redirect(['rbac/indexper']);
            }
        }
        return $this->render('addper', ['model' => $model]);
    }

    // 修改权限
    public function actionEditper($name)
    {
        $authmanager = \Yii::$app->authManager;
        $permission = $authmanager->getPermission($name);
        $model = new Rbac();
        $model->name = $permission->name;
        $model->description = $permission->description;
        $request = \Yii::$app->request;
        if ($request->isPost) {
            $model->load($request->post());
            if ($model->validate()) {
                $permission->name = $model->name;
                $permission->description = $model->description;
                $authmanager->update($name, $permission);
                \Yii::$app->session->setFlash('success', '修改成功');
                return $this->redirect(['rbac/indexper']);
            }
        }
        return $this->render('editper', ['model' => $model]);
    }

    // 删除权限
    public function actionDeleteper($name)
    {
        $authmanager = \Yii::$app->authManager;
        $permission = $authmanager->getPermission($name);
        $authmanager->remove($permission);
        \Yii::$app->session->setFlash('success', '删除成功');
        return $this->redirect(['rbac/indexper']);
    }

    // 角色列表
    public function actionIndexrole()
    {
        $authmanager = \Yii::$app->authManager;
        $role = $authmanager->getRoles();
        return $this->render('indexrole', ['roles' => $role]);
    }

    // 添加角色
    public function actionAddrole()
    {
        $authmanager = \Yii::$app->authManager;
        $model = new Role();
        $model->scenario = Role::SCENARIO_ADD;
        $request = \Yii::$app->request;
        if ($request->isPost) {
            //添加角色
            $model->load($request->post());
            if($model->validate()){
//                var_dump($model->description);die;
                $role = $authmanager->createRole($model->name);
                $role->description = $model->description;
                $authmanager->add($role);
                // 给角色加权限

                if (is_array($model->permission)) {
                    foreach ($model->permission as $permissionName) {
                        $permission = $authmanager->getPermission($permissionName);
                        $authmanager->addChild($role, $permission);

                    }
                }
                \Yii::$app->session->setFlash('success', '添加成功');
                return $this->redirect(['rbac/indexrole']);
            }
        }

        //获取所有权限
        $permissions = $authmanager->getPermissions();
        $items = [];
        foreach ($permissions as $permission) {
            $items[$permission->name] = $permission->description;
        }
        return $this->render('addrole', ['model' => $model, 'items' => $items]);
    }

    // 修改角色
    public function actionEditrole($name)
    {
        $authmanager = \Yii::$app->authManager;
        $role = $authmanager->getRole($name);
        $model = new Role();
        $model->scenario = Role::SCENARIO_EDIT;
        $model->name = $role->name;
        $model->description = $role->description;
        // 获取该角色拥有的权限
        $permissions = $authmanager->getPermissionsByRole($role->name);
        $model->permission = [];
        foreach ($permissions as $permission) {
            $model->permission[] = $permission->name;
        }
        $request = \Yii::$app->request;
        if ($request->isPost) {
            $model->load($request->post());
            if ($model->validate()) {
                $authManager = \Yii::$app->authManager;
                //修改角色
                $role->name = $model->name;
                $role->description = $model->description;
                $authManager->update($name, $role);
                //给角色关联权限
                //清除该角色关联的所有权限
                $authManager->removeChildren($role);
                if (is_array($model->permission)) {
                    foreach ($model->permission as $permissionName) {
                        $permission = $authManager->getPermission($permissionName);
                        $authManager->addChild($role, $permission);
                    }
                }
                \Yii::$app->session->setFlash('success', '修改成功');
                return $this->redirect(['rbac/indexrole']);
            }

        }
        //获取所有权限
        $permissions = $authmanager->getPermissions();
        $items = [];
        foreach ($permissions as $permission) {
            $items[$permission->name] = $permission->description;
        }
        return $this->render('addrole', ['model' => $model, 'items' => $items]);
    }
    // 删除角色
    public function actionDeleterole($name){
        $authmanager = \Yii::$app->authManager;
        $role = $authmanager->getRole($name);
        $authmanager->remove($role);
        \Yii::$app->session->setFlash('success', '删除成功');
        return $this->redirect(['rbac/indexrole']);
    }
    //过滤器
    public function behaviors()
    {
        return [
            'rbac'=>[
                'class'=>RbacFilter::class,
                //默认情况对所有操作生效
                //排除不需要授权的操作
                'except'=>['indexper','indexrole']
            ]
        ];
    }
}
