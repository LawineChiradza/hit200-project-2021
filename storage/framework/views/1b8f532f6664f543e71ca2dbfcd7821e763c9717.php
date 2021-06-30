

<?php $__env->startSection('content'); ?>
    <div class="container mb-5">
        <div class="row">
            <div class="col-md-12 mb-3 row justify-content-between align-items-center">
                <div class="col-md-6">
                    <h2>Meals</h2>
                    <p class="lead">Select the meal and order with ease</p>
                </div>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create', \App\Models\Meal::class)): ?>
                    <a href="<?php echo e(route('meals.create')); ?>" class="btn btn-primary mx-3 mx-md-2">
                        New meal
                    </a>

                <?php endif; ?>
            </div>
            <?php $__currentLoopData = $meals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $meal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-md-12">
                    <div class="card mb-3" style="max-width: 540px;">
                            <div class="card-body row">
                                <div class="col-5">
                                    <a href="<?php echo e(route('meals.show', $meal)); ?>" class="default">
                                        <img src="<?php echo e($meal->fetchLastMedia()->getSecurePath()); ?>" class="img-fluid rounded" alt="">
                                    </a>
                                </div>
                                <div class="col-7">
                                    <a href="<?php echo e(route('meals.show', $meal)); ?>" class="default">
                                        <h5 class="card-title"><?php echo e($meal->name); ?></h5>
                                        <p class="card-text">RTGS <?php echo e($meal->price); ?></p>
                                    </a>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $meal)): ?>
                                        <div class="d-flex mt-2">
                                            <a href="<?php echo e(route('meals.edit', $meal)); ?>" class="btn btn-primary mr-1">
                                                <i class="fas fa-pencil-alt"></i>
                                            </a>
                                            <form id="deleteForm<?php echo e($meal->id); ?>" action="<?php echo e(route('meals.destroy', $meal)); ?>" method="post">
                                                <?php echo method_field('DELETE'); ?>
                                                <?php echo csrf_field(); ?>
                                                <button type="button" class="btn btn-danger" onclick="if(confirm('Are you sure you want to delete?')){document.getElementById('deleteForm<?php echo e($meal->id); ?>').submit()}">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    <?php endif; ?>

                                </div>
                            </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\hit200\resources\views/meals/index.blade.php ENDPATH**/ ?>