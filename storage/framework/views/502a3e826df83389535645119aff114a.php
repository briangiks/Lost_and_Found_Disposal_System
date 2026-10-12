<?php echo csrf_field(); ?>
<div class="grid sm:grid-cols-2 gap-4">
    <div class="sm:col-span-2">
        <label class="text-sm font-semibold">Item name</label>
        <input type="text" name="name" value="<?php echo e(old('name', $item->name ?? '')); ?>" required placeholder="e.g. Black HP Laptop"
               class="mt-1 w-full px-3 py-2 rounded-md border border-slate-300 dark:border-slate-600 dark:bg-slate-900 text-sm">
    </div>

    <div>
        <label class="text-sm font-semibold">Color</label>
        <input type="text" name="color" value="<?php echo e(old('color', $item->color ?? '')); ?>" placeholder="e.g. Black"
               class="mt-1 w-full px-3 py-2 rounded-md border border-slate-300 dark:border-slate-600 dark:bg-slate-900 text-sm">
    </div>

    <div>
        <label class="text-sm font-semibold">Category</label>
        <select name="category" class="mt-1 w-full px-3 py-2 rounded-md border border-slate-300 dark:border-slate-600 dark:bg-slate-900 text-sm">
            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($c); ?>" <?php if(old('category', $item->category ?? '') === $c): echo 'selected'; endif; ?>><?php echo e($c); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>

    <div>
        <label class="text-sm font-semibold">Date lost</label>
        <input type="date" name="date_lost" value="<?php echo e(old('date_lost', isset($item) ? $item->date_lost->toDateString() : now()->toDateString())); ?>" required
               class="mt-1 w-full px-3 py-2 rounded-md border border-slate-300 dark:border-slate-600 dark:bg-slate-900 text-sm">
    </div>

    <div>
        <label class="text-sm font-semibold">Location found</label>
        <input list="location-options" name="location" value="<?php echo e(old('location', $item->location ?? '')); ?>" required placeholder="e.g. Main Library"
               class="mt-1 w-full px-3 py-2 rounded-md border border-slate-300 dark:border-slate-600 dark:bg-slate-900 text-sm">
        <datalist id="location-options">
            <?php $__currentLoopData = $locations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $l): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($l); ?>"></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </datalist>
    </div>

    <div class="sm:col-span-2">
        <label class="text-sm font-semibold">Special markings</label>
        <textarea name="markings" rows="3" placeholder="Distinguishing features, stickers, names, dents…"
                  class="mt-1 w-full px-3 py-2 rounded-md border border-slate-300 dark:border-slate-600 dark:bg-slate-900 text-sm"><?php echo e(old('markings', $item->markings ?? '')); ?></textarea>
    </div>

    <div>
        <label class="text-sm font-semibold">Logged by (reporter)</label>
        <input type="text" name="reporter" value="<?php echo e(old('reporter', $item->reporter ?? auth()->user()->name)); ?>"
               class="mt-1 w-full px-3 py-2 rounded-md border border-slate-300 dark:border-slate-600 dark:bg-slate-900 text-sm">
    </div>

    <div>
        <label class="text-sm font-semibold">Photo</label>
        <input type="file" name="image" accept="image/*"
               class="mt-1 w-full text-sm file:mr-3 file:px-3 file:py-1.5 file:rounded-md file:border-0 file:bg-brand-blue file:text-white file:text-xs">
        <?php if(isset($item) && $item->image_path): ?>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Leave blank to keep the current photo.</p>
        <?php endif; ?>
    </div>
</div>
<?php /**PATH C:\Lost and Found Disposal System\resources\views/items/_form.blade.php ENDPATH**/ ?>