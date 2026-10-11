<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Helvetica, Arial, sans-serif; font-size: 11px; color: #1e293b; }
        h1 { font-size: 18px; color: #1c3357; margin-bottom: 2px; }
        .meta { color: #64748b; font-size: 10px; margin-bottom: 16px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background: #f1f5f9; text-align: left; padding: 6px 8px; font-size: 10px; border-bottom: 1px solid #cbd5e1; }
        td { padding: 6px 8px; border-bottom: 1px solid #e2e8f0; font-size: 10px; }
        .summary { margin-top: 10px; font-size: 12px; font-weight: bold; }
        .footer { margin-top: 24px; font-size: 9px; color: #94a3b8; }
    </style>
</head>
<body>
    <h1>Strathmore University · Lost &amp; Found Disposal Audit Report</h1>
    <div class="meta">
        Period: <?php echo e($periodLabel); ?> (<?php echo e($from->toDateString()); ?> to <?php echo e($to->toDateString()); ?>) ·
        Generated <?php echo e($generatedAt->toDateTimeString()); ?>

    </div>

    <div class="summary"><?php echo e($items->count()); ?> item(s) disposed in this period.</div>

    <table>
        <thead>
            <tr>
                <th>Reference</th>
                <th>Item</th>
                <th>Category</th>
                <th>Location</th>
                <th>Date Lost</th>
                <th>Batch</th>
                <th>Released</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><?php echo e($item->reference); ?></td>
                    <td><?php echo e($item->name); ?></td>
                    <td><?php echo e($item->category); ?></td>
                    <td><?php echo e($item->location); ?></td>
                    <td><?php echo e($item->date_lost->toDateString()); ?></td>
                    <td><?php echo e($item->disposalBatch?->reference); ?></td>
                    <td><?php echo e($item->disposalBatch?->released_at?->toDateString()); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="7">No items were disposed in this period.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="footer">
        Strathmore University · Lost &amp; Found Disposal System — this is a system-generated record for administrative use.
    </div>
</body>
</html>
<?php /**PATH C:\Lost and Found Disposal System\resources\views/reports/pdf.blade.php ENDPATH**/ ?>