<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\Fibonachi;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * Class SiteController
 * @package app\controllers
 */
class SiteController extends Controller
{

    /**
     * Домашняя страница
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Поиск значения
     *
     * @return string
     */
    public function actionFb()
    {
        $model = new Fibonachi(['scenario' => Fibonachi::SCENARIO_SEARCH]);
        return $this->render('fb1', compact('model', 'start'));
    }

    /**
     * Создание записи в БД значения с последовательностью Фибоначчи
     *
     * @return string
     */
    public function actionCreateValue()
    {
        $model = new Fibonachi(['scenario' => Fibonachi::SCENARIO_CREATE]);
        return $this->render('create', compact('model'));
    }

    /**
     * Выдача результата при POST-запросе
     *
     */
    public function actionResponse()
    {
        if(yii::$app->request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            $model = new Fibonachi(['scenario' => Fibonachi::SCENARIO_SEARCH]);
            $post = yii::$app->request->post();
            if($model->load($post) && $model->validate()){
                return $model->requestOne();
            }
            return 'Ошибка запроса.';
        }
    }

    /**
     * Выдача результата при POST-запросе
     * Создание записи в БД последовательности чисел Фибоначчи
     *
     * @return array|bool[]
     */
    public function actionCreate()
    {
        if(yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $model = new Fibonachi(['scenario' => Fibonachi::SCENARIO_CREATE]);
            $post = yii::$app->request->post();
            if($model->load($post) && $model->validate()){
                if($model->insertNew()){
                    return ['status' => true, 'message' => 'Успешно созданы записи в БД'];
                }else{
                    return ['status' => true, 'message' => 'База данных не пустая!'];
                }
            }
            return ['status' => false];
        }
    }

    /**
     * Валидация формы
     *
     * @return array
     */
    public function actionValidation()
    {
        $post = Yii::$app->request->post();
        if(!is_null($post['Fibonachi'])){
            $model = new Fibonachi();
        }
        if(is_null($post)){
            $model = '';
        }
        if(Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
    }
}
