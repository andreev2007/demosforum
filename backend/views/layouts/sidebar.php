<?php
use common\models\User;

$user = new User();
?>
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="<?= \yii\helpers\Url::home() ?>" class="brand-link">
        <span class="brand-text font-weight-bold"
              style="border-radius: 40px;
               background: #0c525d;
               color: white; padding: 5px;height: auto">MF</span>
        <span class="brand-text font-weight-light">Demos Forum</span>
    </a>

    <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="info">
                <a href="#" class="d-block"><?= $user->getName() ?></a>
            </div>
        </div>

        <nav class="mt-2">

            <?php
            echo backend\widgets\Menu::widget([
                'items' => [
                    ['label' => 'Посты', 'url' => ['/posts/index'], 'iconStyle' => 'fas fa-newspaper'],
                    ['label' => 'Комментарии', 'url' => ['/comments/index'], 'iconStyle' => 'fas fa-comment'],
                    ['label' => 'Пользователи', 'url' => ['/user/index'], 'iconStyle' => 'fas fa-user'],
                ],
            ]);
            ?>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>