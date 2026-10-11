<?php $__env->startSection('title', 'Reports'); ?>

<?php $__env->startSection('content'); ?>
<div class="mx-auto max-w-5xl px-4 sm:px-6 py-6 space-y-6" x-data="{ period: '<?php echo e($period); ?>' }">
    <div>
        <h1 class="text-2xl font-bold">Disposal reports</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400">
            Export the audit log for a given period for university administration records.
        </p>
    </div>

    <form method="GET" action="<?php echo e(route('reports.index')); ?>" class="rounded-lg border bg-white dark:bg-slate-800 dark:border-slate-700 p-4 space-y-3">
        <div class="flex flex-wrap gap-2">
            <?php $__currentLoopData = ['today' => 'Today', 'this_week' => 'This week', 'this_month' => 'This month', 'this_quarter' => 'This quarter', 'this_year' => 'This year', 'custom' => 'Custom range']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <label class="cursor-pointer">
                    <input type="radio" name="period" value="<?php echo e($value); ?>" x-model="period" class="sr-only peer">
                    <span class="inline-block px-3 py-1.5 rounded-md text-sm border dark:border-slate-600 peer-checked:bg-brand-blue peer-checked:text-white peer-checked:border-brand-blue"><?php echo e($label); ?></span>
                </label>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <div x-show="period === 'custom'" x-cloak class="flex gap-3 items-end">
            <div>
                <label class="text-xs text-slate-500 dark:text-slate-400">From</label>
                <input type="date" name="from" value="<?php echo e(request('from', $from->toDateString())); ?>" class="block mt-1 px-3 py-2 rounded-md border border-slate-300 dark:border-slate-600 dark:bg-slate-900 text-sm">
            </div>
            <div>
                <label class="text-xs text-slate-500 dark:text-slate-400">To</label>
                <input type="date" name="to" value="<?php echo e(request('to', $to->toDateString())); ?>" class="block mt-1 px-3 py-2 rounded-md border border-slate-300 dark:border-slate-600 dark:bg-slate-900 text-sm">
            </div>
        </div>

        <button type="submit" class="px-4 py-2 rounded-md bg-brand-blue text-white text-sm font-medium">Apply</button>
    </form>

    <div class="rounded-lg border bg-white dark:bg-slate-800 dark:border-slate-700 p-5">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <div class="text-sm text-slate-500 dark:text-slate-400"><?php echo e($periodLabel); ?> · <?php echo e($from->toDateString()); ?> to <?php echo e($to->toDateString()); ?></div>
                <div class="text-2xl font-bold mt-1"><?php echo e($totalDisposed); ?> items disposed across <?php echo e($batchCount); ?> batch(es)</div>
            </div>
            <div class="flex gap-2">
                <a href="<?php echo e(route('reports.export-pdf', request()->query())); ?>" class="px-3 py-2 rounded-md border dark:border-slate-600 text-sm inline-flex items-center gap-1.5">
                    <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'clipboard-list','class' => 'w-4 h-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'clipboard-list','class' => 'w-4 h-4']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $attributes = $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $component = $__componentOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?> Export PDF
                </a>
                <a href="<?php echo e(route('reports.export-excel', request()->query())); ?>" class="px-3 py-2 rounded-md bg-brand-red text-white text-sm font-medium inline-flex items-center gap-1.5">
                    <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'arrow-up-right','class' => 'w-4 h-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'arrow-up-right','class' => 'w-4 h-4']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $attributes = $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $component = $__componentOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?> Export Excel
                </a>
            </div>
        </div>
    </div>

    <div class="rounded-lg border bg-white dark:bg-slate-800 dark:border-slate-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 dark:bg-slate-900/50 text-slate-500 dark:text-slate-400">
                    <tr class="text-left">
                        <th class="px-4 py-2 font-medium">ID</th>
                        <th class="px-4 py-2 font-medium">Item</th>
                        <th class="px-4 py-2 font-medium">Category</th>
                        <th class="px-4 py-2 font-medium">Batch</th>
                        <th class="px-4 py-2 font-medium">Released</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="border-t dark:border-slate-700">
                            <td class="px-4 py-2 font-mono text-xs text-slate-500 dark:text-slate-400"><?php echo e($i->reference); ?></td>
                            <td class="px-4 py-2 font-medium"><?php echo e($i->name); ?></td>
                            <td class="px-4 py-2"><?php echo e($i->category); ?></td>
                            <td class="px-4 py-2 font-mono text-xs"><?php echo e($i->disposalBatch?->reference); ?></td>
                            <td class="px-4 py-2 text-slate-500 dark:text-slate-400"><?php echo e($i->disposalBatch?->released_at?->toDateString()); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="5" class="px-4 py-8 text-center text-slate-500 dark:text-slate-400">No items were disposed in this period.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Lost and Found Disposal System\resources\views/reports/index.blade.php ENDPATH**/ ?>