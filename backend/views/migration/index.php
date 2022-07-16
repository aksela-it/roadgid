<?php

use backend\models\Migration;
use yii\bootstrap4\Html;

/**
 * @var Migration[] $migrations
 */
?>
<?php foreach ($migrations as $path => $files) : ?>
    <div class="mb-3"><?= $path; ?></div>
    <?php foreach ($files as $file) : ?>
        <div class="row ml-2 mb-2 border-bottom pb-2 border-light">
            <div class="col-6"><?= $file['name']; ?></div>
            <div class="col-6 text-right">
                <?php if ($file['apply']) : ?>
                    <?php if ($file['retry']) : ?>
                        <?= Html::a('Переприменить', ['retry', 'version' => $path, 'migration' => $file['name']], ['class' => 'btn btn-warning btn-sm']); ?>
                        <?= Html::a('Не применять', ['cancel', 'version' => $path, 'migration' => $file['name']], ['class' => 'btn btn-secondary btn-sm']); ?>
                    <?php endif; ?>
                    <?= Html::a('Отменить', ['rollback', 'version' => $path, 'migration' => $file['name']], ['class' => 'btn btn-danger btn-sm']); ?>
                <?php else : ?>
                    <?= Html::a('Применить', ['apply', 'version' => $path, 'migration' => $file['name']], ['class' => 'btn btn-success btn-sm']); ?>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
<?php endforeach; ?>
