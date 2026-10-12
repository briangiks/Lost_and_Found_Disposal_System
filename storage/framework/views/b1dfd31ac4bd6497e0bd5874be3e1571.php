<?php $__env->startSection('title', 'Edit '.$item->reference); ?>

<?php $__env->startSection('content'); ?>
<div class="mx-auto max-w-3xl px-4 sm:px-6 py-6 space-y-6">
    <a href="<?php echo e(route('items.show', $item)); ?>" class="inline-flex items-center gap-1 text-sm text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white">
        <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'arrow-left','class' => 'w-4 h-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'arrow-left','class' => 'w-4 h-4']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $attributes = $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $component = $__componentOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?> Back to <?php echo e($item->reference); ?>

    </a>

    <div>
        <h1 class="text-2xl font-bold">Edit <?php echo e($item->reference); ?></h1>
        <p class="text-sm text-slate-500 dark:text-slate-400">Update the details recorded for this item.</p>
    </div>

    <form method="POST" action="<?php echo e(route('items.update', $item)); ?>" enctype="multipart/form-data" class="rounded-lg border bg-white dark:bg-slate-800 dark:border-slate-700 p-6 space-y-6">
        <?php echo method_field('PUT'); ?>
        <?php echo $__env->make('items._form', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <div class="flex justify-end gap-2 pt-2 border-t dark:border-slate-700">
            <a href="<?php echo e(route('items.show', $item)); ?>" class="px-4 py-2 rounded-md border dark:border-slate-600 text-sm">Cancel</a>
            <button type="submit" class="px-4 py-2 rounded-md bg-brand-red text-white text-sm font-medium">Save changes</button>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Lost and Found Disposal System\resources\views/items/edit.blade.php ENDPATH**/ ?>