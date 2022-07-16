<?php

/** @var yii\web\View $this */

$this->title = 'My Yii Application';
?>
<div class="site-index">

    <div class="jumbotron text-center bg-transparent">
        <h1 class="display-4">Сократи ссылку!</h1>
    </div>

    <div class="body-content">
        <?= \yii\bootstrap4\Html::input('text', 'link', null, ['class' => 'form-control mb-4', 'placeholder' => 'Введите ссылку!']) ?>
        <div class="link"><a href=""></a></div>
        <div class="text-center">
            <?= \yii\bootstrap4\Html::button('Сократить!', ['class' => 'btn btn-lg btn-primary', 'id' => 'shorten']) ?>
        </div>
    </div>
</div>

<script>
    $(function () {
       $('body').on('click', '#shorten', function () {
           let link = $('[name=link]').val();
            $.ajax({
                url: '<?= \yii\helpers\Url::to(['site/shorten']) ?>',
                type: 'GET',
                data: {
                    link: link,
                },
                success: function (data) {
                    $('.link a').html(data);
                    $('.link a').attr('href', data);
                }
            });
       });

       $('body').on('click', '.link', function (e) {
           e.preventDefault();
           let text = $(this).attr('href');
           var $temp = $("<input>");
           $("body").append($temp);
           $temp.val($(this).text()).select();
           document.execCommand("copy");
           $temp.remove();
           alert('Тест скопирован!');
       })
    });
</script>
