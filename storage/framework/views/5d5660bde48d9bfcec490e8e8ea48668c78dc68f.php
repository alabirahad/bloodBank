<?php $__env->startSection('login_content'); ?>
<!-- BEGIN LOGIN FORM -->
<form class="login-form" method="POST" action="<?php echo e(route('login')); ?>">
    <?php echo csrf_field(); ?>
    <div class="row login-form-logo">
        <div class="col-md-12">

            <h3 class="form-title font-white">Blood Bank Software</h3>
            <!-- BEGIN LOGO -->
            <div class="logo">
                <a href="#">
                    <img src="<?php echo e(URL::to('/')); ?>/public/img/login.png" alt="logo" height="150px"/>
                </a>
            </div>
            <!-- END LOGO -->

        </div>
    </div>

    <div class="form-group login-form-group">

        <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
        <?php if($errors->has('username')): ?>
        <span class="invalid-feedback">
            <strong class="text-danger"><?php echo e($errors->first('username')); ?></strong>
        </span>
        <?php endif; ?>
        <label class="control-label visible-ie8 visible-ie9"><?php echo app('translator')->get('label.USERNAME'); ?></label>
        <div class="input-group bootstrap-touchspin width-inherit">
            <span class="input-group-addon bootstrap-touchspin-prefix bold green">
                <img src="<?php echo e(URL::to('/')); ?>/public/img/username_icon.png" alt="username"/>
            </span>
            <input id="userName" type="text" class="form-control form-control-solid placeholder-no-fix <?php echo e($errors->has('username') ? ' is-invalid' : ''); ?>" placeholder="<?php echo app('translator')->get('label.USERNAME'); ?>" name="username" value="<?php echo e(old('username')); ?>" required/>
        </div>
    </div>
    <div class="form-group login-form-group">
        <label class="control-label visible-ie8 visible-ie9"><?php echo app('translator')->get('label.PASSWORD'); ?></label>
        <div class="input-group bootstrap-touchspin width-inherit">
            <span class="input-group-addon bootstrap-touchspin-prefix bold green">
                <img src="<?php echo e(URL::to('/')); ?>/public/img/password_icon.png" alt="password"/>
            </span>
            <input id="password" type="password" class="form-control form-control-solid placeholder-no-fix<?php echo e($errors->has('password') ? ' is-invalid' : ''); ?>" placeholder="<?php echo app('translator')->get('label.PASSWORD'); ?>" name="password" required/>
        </div>
        
        <?php if($errors->has('password')): ?>
        <span class="invalid-feedback">
            <strong class="text-danger"><?php echo e($errors->first('password')); ?></strong>
        </span>
        <?php endif; ?>
    </div>

    <div class="form-actions login-form-group">
        <button type="submit" class="btn green"><?php echo app('translator')->get('label.LOGIN'); ?></button>
        <!--label class="rememberme check mt-checkbox mt-checkbox-outline">
            <input type="checkbox" name="remember" value="1" />Remember
            <span></span>
        </label>
        <a href="<?php echo e(route('password.request')); ?>" id="forget-password" class="forget-password">Forgot Password?</a-->
    </div>
    <div class="login-options">
        <div class="copyright">&copy; <?php echo e(date('Y')); ?> <?php echo app('translator')->get('label.POWERED_BY'); ?> Abir
        </div>
    </div>

</form>
<!-- END LOGIN FORM -->
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.login', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\Xampp\htdocs\bloodBank\resources\views/auth/login.blade.php ENDPATH**/ ?>