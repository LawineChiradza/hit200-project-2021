

<?php $__env->startSection('content'); ?>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1>Order #<?php echo e($order->id); ?></h1>
                <?php if($order->collected): ?>
                    <span class="badge badge-success badge-lg">Collected</span>
                <?php else: ?>
                    <span class="badge badge-warning badge-lg">Not collected</span>
                <?php endif; ?>
                <?php if($order->paid): ?>
                    <span class="badge badge-success badge-lg">Paid</span>
                <?php else: ?>
                    <span class="badge badge-danger badge-lg">Unpaid</span>
                <?php endif; ?>
            </div>
        </div>
        <div class="row mt-3">
            <?php $__currentLoopData = $order->details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $meal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-md-12">
                    <div class="card mb-3" style="max-width: 540px;">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo e($meal->item); ?> x <?php echo e($meal->quantity); ?></h5>
                            <p class="card-text">RTGS <?php echo e($meal->unit_price); ?></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('collect', $order)): ?>
            <div class="row mt-3">
                <div class="col-md-12">
                    <a href="<?php echo e(route('orders.collect', $order)); ?>" class="btn btn-primary">Mark as collected</a>
                </div>
            </div>
        <?php endif; ?>
        <?php if(!$order->paid && !auth()->user()->admin): ?>
            <div class="row mt-3">
                <div class="col-md-12 d-flex">
                    <a href="<?php echo e(route('payments.checkout', $order)); ?>" class="btn btn-primary">
                        Make payment
                    </a>
                    <form class="ml-2" action="<?php echo e(route('orders.destroy', $order)); ?>" method="post" id="form">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <button type="button" class="btn btn-danger" onclick="if(confirm('Are you sure you want to cancel order?')){document.getElementById('form').submit()}">
                            Cancel order
                        </button>
                    </form>
                </div>
            </div>
        <?php endif; ?>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\hit200\resources\views/orders/show.blade.php ENDPATH**/ ?>