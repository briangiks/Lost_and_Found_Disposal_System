<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['status']));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['status']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<?php
    $styles = [
        'active' => 'bg-slate-100 text-slate-700 border border-slate-300 dark:bg-slate-700 dark:text-slate-200 dark:border-slate-600',
        'claimed' => 'bg-emerald-100 text-emerald-700 border border-emerald-300 dark:bg-emerald-900/30 dark:text-emerald-300 dark:border-emerald-700',
        'donated' => 'bg-brand-blue/10 text-brand-blue border border-brand-blue/30',
    ];
    $labels = [
        'active' => 'In Custody',
        'claimed' => 'Claimed by Owner',
        'donated' => 'Donated',
    ];
?>
<span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-medium <?php echo e($styles[$status] ?? $styles['active']); ?>">
    <?php echo e($labels[$status] ?? ucfirst($status)); ?>

</span>
<?php /**PATH C:\Lost and Found Disposal System\resources\views/components/status-badge.blade.php ENDPATH**/ ?>