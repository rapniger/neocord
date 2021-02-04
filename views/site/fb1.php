<?php
use yii\widgets\ActiveForm;

$this->title = 'Найти число';

$js = <<<JS
    jQuery(document).ready(function() {
        var FibonachiForm = jQuery('#fibonachi-form');
        jQuery('body').on('click', '#sendDigit', function(e) {
            e.preventDefault();
            jQuery.ajax({
                type: "POST",
                url: '/site/response',
                data: FibonachiForm.serialize(),
                success: function(res) {
                    $('#result p').html(res);
                }
            })
        })
        return false;
    });
JS;
$this->registerJS($js, $position = yii\web\View::POS_END);
?>

<div class="row">
    <div class="col-12">
        <?php $form = ActiveForm::begin([
            'id' => 'fibonachi-form',
            'enableAjaxValidation' => true,
            'validationUrl' => '/site/validation',
        ])?>
        <?= $form->field($model, 'number')->textInput()->label('Введите число')?>
        <button type='submit' id="sendDigit" class="btn btn-primary">RUN!!!</button>
        <?php ActiveForm::end()?>
        <div id="result">
            <h3>Ближайщее число:</h3>
            <p>Введите число!</p>
        </div>
        <div>
            <? //= var_dump($start)?>
        </div>
    </div>
</div>
