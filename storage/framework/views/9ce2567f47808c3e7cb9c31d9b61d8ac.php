<?php $__env->startSection('title', 'Email Preview'); ?>

<?php $__env->startSection('content'); ?>
<div class="mx-auto max-w-4xl px-4 sm:px-6 py-6 space-y-6">
    <div>
        <h1 class="text-2xl font-bold">Email templates</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400">
            Drafts generated during a quarterly disposal cycle. Once a stage is actually sent from the
            disposal workflow, the real, logged copy is shown here instead of the draft.
        </p>
    </div>

    <div class="flex gap-2 border-b dark:border-slate-700 overflow-x-auto">
        <?php $__currentLoopData = $types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(route('emails.index', ['type' => $key])); ?>"
               class="px-3 py-2 text-sm font-medium border-b-2 -mb-px whitespace-nowrap <?php echo e($activeType === $key ? 'border-brand-red text-brand-red' : 'border-transparent text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'); ?>">
                <?php echo e($label); ?>

            </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <div class="rounded-lg border bg-white dark:bg-slate-800 dark:border-slate-700 overflow-hidden">
        <div class="border-b dark:border-slate-700 bg-slate-50 dark:bg-slate-900/40 p-4 text-sm space-y-1">
            <div class="flex gap-2"><span class="text-slate-500 dark:text-slate-400 w-16">From:</span><span class="font-medium"><?php echo e($preview['from']); ?></span></div>
            <div class="flex gap-2"><span class="text-slate-500 dark:text-slate-400 w-16">To:</span><span class="font-medium"><?php echo e($preview['to']); ?></span></div>
            <?php if($preview['cc']): ?>
                <div class="flex gap-2"><span class="text-slate-500 dark:text-slate-400 w-16">CC:</span><span class="font-medium"><?php echo e($preview['cc']); ?></span></div>
            <?php endif; ?>
            <div class="flex gap-2"><span class="text-slate-500 dark:text-slate-400 w-16">Subject:</span><span class="font-medium"><?php echo e($preview['subject']); ?></span></div>
        </div>

        <div class="p-6 text-sm leading-relaxed [&_p]:mb-3 [&_strong]:font-semibold max-w-none">
            <div class="flex items-center gap-2 mb-4">
                <div class="w-8 h-8 rounded bg-brand-red text-white grid place-content-center font-bold text-sm">S</div>
                <div class="font-semibold text-brand-bluedark dark:text-white">Strathmore University</div>
            </div>

            <?php echo $preview['body']; ?>


            <hr class="my-4 border-slate-200 dark:border-slate-700">
            <p class="text-xs text-slate-500 dark:text-slate-400">Strathmore University · Ole Sangale Road, Madaraka Estate, Nairobi</p>
        </div>

        <div class="px-6 pb-4">
            <?php if($preview['is_live']): ?>
                <span class="inline-flex items-center gap-1.5 text-xs px-2 py-1 rounded-md bg-emerald-100 text-emerald-700 border border-emerald-300 dark:bg-emerald-900/30 dark:text-emerald-300 dark:border-emerald-700">
                    <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'check-circle','class' => 'w-3 h-3']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'check-circle','class' => 'w-3 h-3']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $attributes = $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $component = $__componentOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?> Actually sent <?php echo e($preview['sent_at']?->diffForHumans()); ?>

                </span>
            <?php else: ?>
                <span class="inline-flex items-center gap-1.5 text-xs px-2 py-1 rounded-md bg-slate-100 text-slate-600 border border-slate-300 dark:bg-slate-700 dark:text-slate-300 dark:border-slate-600">
                    Preview only — not yet sent for the current cycle
                </span>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Lost and Found Disposal System\resources\views/emails/index.blade.php ENDPATH**/ ?>