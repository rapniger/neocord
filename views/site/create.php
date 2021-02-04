<?php
use yii\widgets\ActiveForm;

$this->title = 'Создать запись в БД';

$js = <<<JS
    jQuery(document).ready(function() {
        var Form = jQuery('#create');
        jQuery('body').on('click', '#create-button', function(e) {
            e.preventDefault();
            jQuery.ajax({
                type: "POST",
                url: '/site/create',
                data: Form.serialize(),
                success: function(res) {
                    if(res.status == false){
                        jQuery('#content').append('<div class="alert alert-danger" role="alert"><p>Ошибка записи в БД!</p></div>');
                    }
                    if(res.status == true){
                        jQuery('#content').append('<div class="alert alert-success" role="alert"><p>'+res.message+'</p></div>');
                    }
                }
            })
        })
        return false;
    });
JS;
$this->registerJS($js, $position = yii\web\View::POS_END);
?>
<div class="row">
    <div id="content" class="col-12">
        <h1>Создать запись в БД?</h1>
        <?php $form = ActiveForm::begin(['id' => 'create'])?>
        <?= $form->field($model, 'status')->hiddenInput(['value' => 1])->label(false)?>
        <div class="form-group">
            <button id="create-button" class="btn btn-primary">Да, создать!</button>
        </div>
        <?php ActiveForm::end()?>
    </div>
</div>