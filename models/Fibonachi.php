<?php


namespace app\models;


use yii;
use yii\db\ActiveRecord;

/**
 * Class Fibonachi
 * @package app\models
 */
class Fibonachi extends ActiveRecord
{
    /**
     * @var int
     */
    public $status = 0;
    /**
     * @var array
     */
    private $result = [];
    /**
     * Сценарии
     */
    const SCENARIO_SEARCH = 'search';
    const SCENARIO_CREATE = 'create';

    /**
     * Имя таблицы из БД
     *
     * @return string
     */
    public static function tableName()
    {
        return '{{%fibonachi}}';
    }

    /**
     * Валидация
     *
     * @return array|array[]
     */
    public function rules()
    {
        return [
            ['number', 'required', 'message' => 'Поле обязательно к заполнению! Введите число до 10000.', 'on' => SELF::SCENARIO_SEARCH],
            ['number', 'integer', 'min' => 1, 'max' => 10000, 'message' => 'Введите числа от 1 до 10000', 'on' => SELF::SCENARIO_SEARCH],
            ['status', 'boolean', 'on' => SELF::SCENARIO_CREATE]
        ];
    }

    /**
     * Поиск цифр из БД по последовательности Фибоначчи
     * Расчет ближайщих цифр
     * Вывод результата
     *
     * @return mixed
     * @throws yii\db\Exception
     */
    public function requestOne()
    {
        $digit = $this->number;
        $min = yii::$app->db->createCommand('SELECT * FROM fibonachi WHERE number <= '.$digit.' ORDER BY CAST(number AS UNSIGNED INTEGER) DESC LIMIT 1')->queryAll();
        $max = yii::$app->db->createCommand('SELECT * FROM fibonachi WHERE number >= '.$digit.' ORDER BY CAST(number AS UNSIGNED INTEGER) ASC LIMIT 1')->queryAll();
        $result['min'] = $digit - $min[0]['number'];
        $result['max'] = $max[0]['number'] - $digit;
        if($this->insertOne()){
            if($result['min'] < $result['max']){
                return $min[0]['number'];
            }else if($result['min'] > $result['max']){
                return $max[0]['number'];
            }else if($result['min'] === $result['max']){
                return $max[0]['number'];
            }
        }
    }

    /**
     * Сохранение цифр в БД
     * пользовательские данные
     *
     * @return bool
     */
    public function insertOne()
    {
        if($this->validate()){
            return $this->save();
        }
        return false;
    }

    /**
     * Создание записи в БД последовательности Фибоначчи
     *
     * @return bool
     */
    public function insertNew()
    {
        $this->calculation();
        if(is_array($this->result)){
            if($this->checkFibonachi() === false){
                foreach($this->result as $value) {
                    /* @todo
                     * Пробовал. Потому что внутри модели имеет один id.. И поэтому перезаписывает.
                     * // Пришлось так сделать... */
                    $model = new Fibonachi();
                    $model->number = $value;
                    $model->save(false);
                    //yii::$app->db->createCommand("INSERT INTO fibonachi(number) VALUE ($value);");
                    //Fibonachi::find()
                    //var_dump($value);
                    //unset($this->id); - не работает, для сброса ID и добавления новой записи.
                    //$this->number = $value;
                    //$this->save();*/
                }
                return true;
            }
        }
        return false;
    }

    /**
     * Проверка существования в БД цифр из последовательности Фибоначчи
     *
     * @return bool
     */
    public function checkFibonachi()
    {
        return static::find()->where(['number' => end($this->result)])->exists();
    }

    /**
     * Калькуляция Фибоначчи
     *
     * @return mixed
     */
    private function calculation()
    {
        if ($this->validate(false)) {
            $a = 0;
            $b = 1;
            $count = 0;
            while($count <= 20){
                $c[$count] = $a + $b;
                $a = $b;
                $b = $c[$count];
                $count++;
            }
            return $this->result = $c;
        }
    }
}