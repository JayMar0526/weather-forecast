<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\web\View;
/* @var $this yii\web\View */

$this->title = 'Weather Forecast';
?>
<div class="site-index">
    <div class="row">
        <?php $form = ActiveForm::begin(['id' => 'weather-form', 'enableAjaxValidation' => true]); ?>
        <div class="col-md-8">
            <?= Html::textInput('postalC', '', ['class' => 'form-control', 'placeholder' => 'Postal Code']) ?>
        </div>
        <div class="col-md-4">
            <?= Html::submitButton('Submit', ['class' => 'btn btn-primary', 'name' => 'submit-button']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
    
    <div class="row">
        <div id="weather"></div>
    </div>
</div>


<?php
$script = "
    $('#weather-form').on('beforeSubmit', function(event) {
        var form = $(this);
        if (!form.hasClass('complete')) {
            $.ajax({
                url: '".Url::to(['/site/result'])."',
                data: form.serialize(),
                async: true,
                cache: false,
                success: function(response) {
                    $('#weather').html(response);
                }
            });
            return false;
        }
    });
";
$this->registerJs($script);
?>
