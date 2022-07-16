<?php

/** @var yii\web\View $this */

$this->title = 'My Yii Application';
?>
<div class="site-index">
    <div class="body-content">
        <table style="width: 100%;">
            <thead>
            <tr>
                <th>Дата (Перехода по ссылке)</th>
                <th>Ссылка</th>
                <th>Количество переходов</th>
                <th>Позиция в топе месяца по переходам</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($links as $link) : ?>
                <tr>
                    <td><?= $link['month'] ?></td>
                    <td><?= urldecode($link['link']) ?></td>
                    <td><?= $link['count'] ?></td>
                    <td><?= $link['position'] ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<style>
    td, th {
        border: 1px solid #dbdbdb;
        padding: 5px;
    }
</style>
