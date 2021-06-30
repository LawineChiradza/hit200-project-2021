

<?php $__env->startSection('content'); ?>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-3" style="max-width: 540px;">
                    <img class="card-img-top" src="<?php echo e($meal->fetchLastMedia()->getSecurePath()); ?>" alt="Card image cap">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo e($meal->name); ?></h5>
                        <p class="card-text">RTGS <?php echo e($meal->price); ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <form class="" action="<?php echo e(route('cart.add')); ?>" method="post">
                    <?php echo csrf_field(); ?>
                    <div class="form-group">
                        <label>Quantity</label>
                        <input type="text" name="quantity" class="form-control" value="1">
                        <input type="hidden" name="meal_id" value="<?php echo e($meal->id); ?>">
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-secondary btn-block">
                            Add to basket
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\hit200\resources\views/meals/show.blade.php ENDPATH**/ ?>