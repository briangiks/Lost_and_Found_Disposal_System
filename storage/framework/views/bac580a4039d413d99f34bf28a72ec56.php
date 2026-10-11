<?php $__env->startSection('title', 'Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<div class="mx-auto max-w-7xl px-4 sm:px-6 py-6 space-y-6">

    <?php if($approachingCount > 0): ?>
        <div class="rounded-lg border border-amber-300 bg-amber-50 dark:bg-amber-900/20 dark:border-amber-700 p-4 flex flex-col sm:flex-row sm:items-start gap-3">
            <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'clock','class' => 'w-5 h-5 text-amber-700 dark:text-amber-400 shrink-0 mt-0.5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'clock','class' => 'w-5 h-5 text-amber-700 dark:text-amber-400 shrink-0 mt-0.5']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $attributes = $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $component = $__componentOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?>
            <div class="flex-1">
                <div class="font-semibold text-amber-800 dark:text-amber-300">
                    One week to the disposal cut-off · <?php echo e($approachingCount); ?> item<?php echo e($approachingCount > 1 ? 's' : ''); ?> approaching
                </div>
                <p class="text-sm text-slate-600 dark:text-slate-300">
                    Please email Strathmore Communications to remind students to collect their items from
                    the Lost &amp; Found office within the next 7 days before the quarterly disposal.
                </p>
            </div>
            <a href="<?php echo e(route('disposal.index')); ?>" class="inline-flex items-center gap-1.5 px-3 py-2 rounded-md bg-brand-blue text-white text-sm font-medium hover:opacity-95 shrink-0">
                <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'send','class' => 'w-4 h-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'send','class' => 'w-4 h-4']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $attributes = $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $component = $__componentOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?> Email Communications
            </a>
        </div>
    <?php endif; ?>

    <?php if($dueCount > 0): ?>
        <div class="rounded-lg border border-red-300 bg-red-50 dark:bg-red-900/20 dark:border-red-700 p-4 flex flex-col sm:flex-row sm:items-start gap-3">
            <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'alert-triangle','class' => 'w-5 h-5 text-red-700 dark:text-red-400 shrink-0 mt-0.5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'alert-triangle','class' => 'w-5 h-5 text-red-700 dark:text-red-400 shrink-0 mt-0.5']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $attributes = $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $component = $__componentOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?>
            <div class="flex-1">
                <div class="font-semibold text-red-700 dark:text-red-400">
                    Quarterly disposal ready · this quarter's unclaimed items are due for handover
                </div>
                <p class="text-sm text-slate-600 dark:text-slate-300">
                    Every three months all unclaimed items in custody are handed over to the Community
                    Service (Outreach Club). Start the disposal workflow below.
                </p>
            </div>
            <a href="<?php echo e(route('disposal.index')); ?>" class="inline-flex items-center gap-1.5 px-3 py-2 rounded-md bg-brand-red text-white text-sm font-medium hover:opacity-95 shrink-0">
                <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'gift','class' => 'w-4 h-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'gift','class' => 'w-4 h-4']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $attributes = $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $component = $__componentOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?> Start disposal
            </a>
        </div>
    <?php endif; ?>

    <div class="flex items-center justify-between">
        <h1 class="text-xl font-bold">Lost items registry</h1>
        <a href="<?php echo e(route('items.create')); ?>" class="inline-flex items-center gap-1.5 px-3 py-2 rounded-md bg-brand-red text-white text-sm font-medium hover:opacity-95">
            <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'plus','class' => 'w-4 h-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'plus','class' => 'w-4 h-4']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $attributes = $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $component = $__componentOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?> Log new item
        </a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div class="rounded-lg border bg-white dark:bg-slate-800 dark:border-slate-700 p-5">
            <div class="text-xs text-slate-500 dark:text-slate-400 uppercase tracking-wide">Total items in custody</div>
            <div class="text-3xl font-bold mt-2 text-brand-blue"><?php echo e($totalInCustody); ?></div>
            <div class="text-xs text-slate-500 dark:text-slate-400 mt-1">Unclaimed items currently held this quarter.</div>
        </div>
        <div class="rounded-lg border bg-white dark:bg-slate-800 dark:border-slate-700 p-5">
            <div class="text-xs text-slate-500 dark:text-slate-400 uppercase tracking-wide">Total items to be disposed</div>
            <div class="text-3xl font-bold mt-2 text-red-600"><?php echo e($totalToDispose); ?></div>
            <div class="text-xs text-slate-500 dark:text-slate-400 mt-1">Items that have reached or passed the 90-day window.</div>
        </div>
    </div>

    <div class="rounded-lg border bg-white dark:bg-slate-800 dark:border-slate-700 p-5">
        <div class="flex items-center justify-between mb-3">
            <h2 class="font-semibold text-sm">Last 6 months · logged vs. claimed vs. disposed</h2>
            <a href="<?php echo e(route('reports.index')); ?>" class="text-xs text-brand-blue hover:underline">Full reports →</a>
        </div>
        <canvas id="monthlyChart" height="90"></canvas>
    </div>

    <form method="GET" action="<?php echo e(route('dashboard')); ?>" class="rounded-lg border bg-white dark:bg-slate-800 dark:border-slate-700 p-4 space-y-3">
        <div class="flex items-center gap-2 text-sm font-medium"><?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'filter','class' => 'w-4 h-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'filter','class' => 'w-4 h-4']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $attributes = $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $component = $__componentOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?> Filters</div>
        <div class="grid md:grid-cols-12 gap-3">
            <div class="md:col-span-4 relative">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"><?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'search','class' => 'w-4 h-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'search','class' => 'w-4 h-4']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $attributes = $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $component = $__componentOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?></span>
                <input type="text" name="q" value="<?php echo e($filters['q']); ?>" placeholder="Search item, ID, keyword…"
                       class="w-full pl-9 pr-3 py-2 rounded-md border border-slate-300 dark:border-slate-600 dark:bg-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-brand-blue/40">
            </div>
            <select name="category" class="md:col-span-2 px-3 py-2 rounded-md border border-slate-300 dark:border-slate-600 dark:bg-slate-900 text-sm">
                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($c); ?>" <?php if($filters['category'] === $c): echo 'selected'; endif; ?>><?php echo e($c === 'All' ? 'All items' : $c); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <select name="color" class="md:col-span-2 px-3 py-2 rounded-md border border-slate-300 dark:border-slate-600 dark:bg-slate-900 text-sm">
                <?php $__currentLoopData = $colorOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($c); ?>" <?php if($filters['color'] === $c): echo 'selected'; endif; ?>><?php echo e($c === 'All' ? 'All colors' : $c); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <select name="location" class="md:col-span-2 px-3 py-2 rounded-md border border-slate-300 dark:border-slate-600 dark:bg-slate-900 text-sm">
                <?php $__currentLoopData = $locationOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $l): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($l); ?>" <?php if($filters['location'] === $l): echo 'selected'; endif; ?>><?php echo e($l === 'All' ? 'All locations' : $l); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <input type="date" name="date_lost" value="<?php echo e($filters['date_lost']); ?>"
                   class="md:col-span-2 px-3 py-2 rounded-md border border-slate-300 dark:border-slate-600 dark:bg-slate-900 text-sm">
            <input type="text" name="markings" value="<?php echo e($filters['markings']); ?>" placeholder="Markings contain…"
                   class="md:col-span-10 px-3 py-2 rounded-md border border-slate-300 dark:border-slate-600 dark:bg-slate-900 text-sm">
            <div class="md:col-span-2 flex gap-2">
                <button type="submit" class="flex-1 px-3 py-2 rounded-md bg-brand-blue text-white text-sm font-medium">Apply</button>
                <a href="<?php echo e(route('dashboard')); ?>" class="px-3 py-2 rounded-md border text-sm">Reset</a>
            </div>
        </div>
    </form>

    <div class="rounded-lg border bg-white dark:bg-slate-800 dark:border-slate-700 overflow-hidden">
        <div class="flex items-center justify-between p-4 border-b dark:border-slate-700">
            <h2 class="font-semibold">Lost items registry</h2>
            <span class="text-xs text-slate-500 dark:text-slate-400">Showing <?php echo e($items->count()); ?> of <?php echo e($totalCount); ?></span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 dark:bg-slate-900/50 text-slate-500 dark:text-slate-400">
                    <tr class="text-left">
                        <th class="px-4 py-3 font-medium">Photo</th>
                        <th class="px-4 py-3 font-medium">ID</th>
                        <th class="px-4 py-3 font-medium">Item</th>
                        <th class="px-4 py-3 font-medium">Color</th>
                        <th class="px-4 py-3 font-medium">Date lost</th>
                        <th class="px-4 py-3 font-medium">Status</th>
                        <th class="px-4 py-3 font-medium">Location</th>
                        <th class="px-4 py-3 font-medium">Markings</th>
                        <th class="px-4 py-3 font-medium"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="border-t dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700/40">
                            <td class="px-4 py-3">
                                <img src="<?php echo e($item->imageUrl()); ?>" alt="<?php echo e($item->name); ?>" class="w-12 h-12 rounded-md object-cover border dark:border-slate-600">
                            </td>
                            <td class="px-4 py-3 font-mono text-xs text-slate-500 dark:text-slate-400"><?php echo e($item->reference); ?></td>
                            <td class="px-4 py-3 font-medium"><?php echo e($item->name); ?></td>
                            <td class="px-4 py-3"><?php echo e($item->color); ?></td>
                            <td class="px-4 py-3 text-slate-500 dark:text-slate-400"><?php echo e($item->date_lost->toDateString()); ?></td>
                            <td class="px-4 py-3">
                                <?php echo $__env->make('dashboard.partials.age-chip', ['band' => $item->age_band, 'age' => $item->age_in_days], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                            </td>
                            <td class="px-4 py-3"><?php echo e($item->location); ?></td>
                            <td class="px-4 py-3 text-slate-500 dark:text-slate-400 max-w-[220px] truncate" title="<?php echo e($item->markings); ?>"><?php echo e($item->markings); ?></td>
                            <td class="px-4 py-3 text-right">
                                <a href="<?php echo e(route('items.show', $item)); ?>" class="inline-flex items-center gap-1 text-brand-blue hover:underline text-xs font-medium">
                                    View <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'arrow-up-right','class' => 'w-3 h-3']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'arrow-up-right','class' => 'w-3 h-3']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $attributes = $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $component = $__componentOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="9" class="px-4 py-12 text-center text-slate-500 dark:text-slate-400">No items match these filters.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script>
    const chartData = <?php echo json_encode($chartData, 15, 512) ?>;
    const ctx = document.getElementById('monthlyChart');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: chartData.labels,
            datasets: [
                { label: 'Logged', data: chartData.logged, backgroundColor: '#2b4c7e' },
                { label: 'Claimed', data: chartData.claimed, backgroundColor: '#10b981' },
                { label: 'Disposed', data: chartData.disposed, backgroundColor: '#b5253b' },
            ],
        },
        options: {
            responsive: true,
            plugins: { legend: { position: 'bottom' } },
            scales: { y: { beginAtZero: true, ticks: { precision: 0 } } },
        },
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Lost and Found Disposal System\resources\views/dashboard/index.blade.php ENDPATH**/ ?>