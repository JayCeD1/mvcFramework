<?php
/***
 * @var $model \app\models\User
 */
?>
<h1>Create an account</h1>
<?php $form = \app\core\form\Form::begin('', 'post')?>
<div class="row">
    <div class="col"><?php echo $form->field($model, 'firstname')?></div>
    <div class="col"><?php echo $form->field($model, 'lastname')?></div>
</div>

<?php echo $form->field($model, 'email')?>
<?php echo $form->field($model, 'password')->passwordField()?>
<?php echo $form->field($model, 'confirmPassword')->passwordField()?>
<button type="submit" class="btn btn-primary">Submit</button>
<?php \app\core\form\Form::end()?>
<!--<form action='' method=''>-->
<!--    <div class="row">-->
<!--        <div class="col">-->
<!--            <div class="form-group">-->
<!--                <label class="form-label">Firstname</label>-->
<!--                <input type="text" class="form-control" name="firstname">-->
<!--            </div>-->
<!--        </div>-->
<!--        <div class="col">-->
<!--            <div class="form-group">-->
<!--                <label class="form-label">Lastname</label>-->
<!--                <input type="text" class="form-control" name="lastname">-->
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->
<!--    <div class="mb-3">-->
<!--        <label class="form-label">Email</label>-->
<!--        <input type="text" class="form-control" name="email">-->
<!--    </div>-->
<!--    <div class="mb-3">-->
<!--        <label class="form-label">Password</label>-->
<!--        <input type="password" class="form-control" name="password">-->
<!--    </div>-->
<!--    <div class="mb-3">-->
<!--        <label class="form-label">Confirm Password</label>-->
<!--        <input type="password" class="form-control" name="confirmPassword">-->
<!--    </div>-->
<!--    <button type="submit" class="btn btn-primary">Submit</button>-->
<!--</form>-->