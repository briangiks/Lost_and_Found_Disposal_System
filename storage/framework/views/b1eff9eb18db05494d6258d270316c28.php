<?php if($band === 'due'): ?>
    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold bg-red-100 text-red-700 border border-red-300 dark:bg-red-900/30 dark:text-red-300 dark:border-red-700">
        Due · <?php echo e($age); ?>d
    </span>
<?php elseif($band === 'within_quarter'): ?>
    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold bg-amber-100 text-amber-800 border border-amber-300 dark:bg-amber-900/30 dark:text-amber-300 dark:border-amber-700">
        In quarter · <?php echo e($age); ?>d
    </span>
<?php else: ?>
    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-medium bg-slate-100 text-slate-600 border border-slate-300 dark:bg-slate-700 dark:text-slate-300 dark:border-slate-600">
        Fresh · <?php echo e($age); ?>d
    </span>
<?php endif; ?>
<?php /**PATH C:\Lost and Found Disposal System\resources\views/dashboard/partials/age-chip.blade.php ENDPATH**/ ?>