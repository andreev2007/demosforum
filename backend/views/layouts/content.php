<?php
/* @var $content string */

use common\models\User;
use yii\bootstrap4\Breadcrumbs;
?>
<?php if (User::isAdmin()) { ?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">
                        <?php
                        if (!is_null($this->title)) {
                            echo \yii\helpers\Html::encode($this->title);
                        } else {
                            echo \yii\helpers\Inflector::camelize($this->context->id);
                        }
                        ?>
                    </h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <?php
                    echo Breadcrumbs::widget([
                        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                        'options' => [
                            'class' => 'float-sm-right'
                        ]
                    ]);
                    ?>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content container">
        <?= $content ?><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
</div>
<?php } else { ?>
    <div style="background: #efebeb; padding: 50px; height: 100%; position: fixed; width: 100%">
        <div class="content container" style="background: white;padding: 20px;width: 500px">
            <?= $content ?>
        </div>
    </div>

<?php } ?>
