<!DOCTYPE html>
<html lang="en" x-data="themeStore()" x-init="init()" :class="dark ? 'dark' : ''">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $__env->yieldContent('title', 'Dashboard'); ?> · Strathmore Lost &amp; Found</title>
    <meta name="description" content="Strathmore University Lost &amp; Found Disposal System.">
    <link rel="icon" href="<?php echo e(asset('images/strathmore-logo.png')); ?>">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js" defer></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        brand: {
                            red: '#b5253b',
                            blue: '#2b4c7e',
                            bluedark: '#1c3357',
                        },
                    },
                },
            },
        };
        function themeStore() {
            return {
                dark: localStorage.getItem('lnf-theme') === 'dark',
                init() {},
                toggle() {
                    this.dark = !this.dark;
                    localStorage.setItem('lnf-theme', this.dark ? 'dark' : 'light');
                },
            };
        }
    </script>
    <style>
        [x-cloak] { display: none !important; }
        body { font-family: ui-sans-serif, system-ui, -apple-system, 'Segoe UI', sans-serif; }
    </style>
</head>
<body class="min-h-screen flex flex-col bg-slate-50 text-slate-900 dark:bg-slate-900 dark:text-slate-100">

    <header class="border-b bg-white dark:bg-slate-800 dark:border-slate-700 sticky top-0 z-30">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 h-16 flex items-center justify-between gap-4">
            <a href="<?php echo e(route('dashboard')); ?>" class="flex items-center gap-3 shrink-0">
                <img src="<?php echo e(asset('images/strathmore-logo.png')); ?>" alt="Strathmore University" class="h-9 w-auto">
                <div class="leading-tight hidden sm:block">
                    <div class="text-sm font-semibold text-brand-bluedark dark:text-white">Strathmore University</div>
                    <div class="text-[11px] text-slate-500 dark:text-slate-400 -mt-0.5">Lost &amp; Found · Disposal System</div>
                </div>
            </a>

            <nav class="hidden md:flex items-center gap-1">
                <?php
                    $navItems = [
                        ['route' => 'dashboard', 'label' => 'Dashboard', 'icon' => 'layout-dashboard'],
                        ['route' => 'disposal.index', 'label' => 'Disposal Workflow', 'icon' => 'workflow'],
                        ['route' => 'emails.index', 'label' => 'Email Preview', 'icon' => 'mail'],
                        ['route' => 'audit.index', 'label' => 'Audit Log', 'icon' => 'clipboard-list'],
                        ['route' => 'reports.index', 'label' => 'Reports', 'icon' => 'package'],
                    ];
                ?>
                <?php $__currentLoopData = $navItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a href="<?php echo e(route($item['route'])); ?>"
                       class="flex items-center gap-2 px-3 py-2 rounded-md text-sm font-medium transition-colors <?php echo e(request()->routeIs($item['route']) || ($item['route'] === 'dashboard' && request()->routeIs('items.*')) ? 'bg-brand-blue text-white' : 'text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-700'); ?>">
                        <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => $item['icon'],'class' => 'w-4 h-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($item['icon']),'class' => 'w-4 h-4']); ?>
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
                        <?php echo e($item['label']); ?>

                    </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </nav>

            <div class="flex items-center gap-2">
                <button @click="toggle()" class="p-2 rounded-md hover:bg-slate-100 dark:hover:bg-slate-700" aria-label="Toggle light/dark mode">
                    <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'sun','class' => 'w-5 h-5','xShow' => 'dark','xCloak' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'sun','class' => 'w-5 h-5','x-show' => 'dark','x-cloak' => true]); ?>
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
                    <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'moon','class' => 'w-5 h-5','xShow' => '!dark']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'moon','class' => 'w-5 h-5','x-show' => '!dark']); ?>
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
                </button>

                <?php if(auth()->guard()->check()): ?>
                    <?php $unread = auth()->user()->unreadNotifications()->latest()->take(6)->get(); ?>
                    <div class="relative" x-data="{ notifOpen: false }" @click.outside="notifOpen = false">
                        <button @click="notifOpen = !notifOpen" class="relative p-2 rounded-md hover:bg-slate-100 dark:hover:bg-slate-700" aria-label="Notifications">
                            <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'bell','class' => 'w-5 h-5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'bell','class' => 'w-5 h-5']); ?>
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
                            <?php if($unread->count() > 0): ?>
                                <span class="absolute top-1 right-1 w-2 h-2 bg-brand-red rounded-full"></span>
                            <?php endif; ?>
                        </button>
                        <div x-show="notifOpen" x-cloak x-transition
                             class="absolute right-0 mt-2 w-80 rounded-lg border bg-white dark:bg-slate-800 dark:border-slate-700 shadow-xl z-40 overflow-hidden">
                            <div class="flex items-center justify-between px-4 py-2.5 border-b dark:border-slate-700">
                                <span class="text-sm font-semibold">Notifications</span>
                                <a href="<?php echo e(route('notifications.index')); ?>" class="text-xs text-brand-blue hover:underline">View all</a>
                            </div>
                            <div class="max-h-80 overflow-y-auto divide-y dark:divide-slate-700">
                                <?php $__empty_1 = true; $__currentLoopData = $unread; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $n): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <form method="POST" action="<?php echo e(route('notifications.read', $n->id)); ?>">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" class="w-full text-left px-4 py-3 text-sm hover:bg-slate-50 dark:hover:bg-slate-700/40 flex gap-2.5">
                                            <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => $n->data['icon'] ?? 'bell','class' => 'w-4 h-4 mt-0.5 text-brand-blue shrink-0']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($n->data['icon'] ?? 'bell'),'class' => 'w-4 h-4 mt-0.5 text-brand-blue shrink-0']); ?>
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
                                            <span>
                                                <span class="block font-medium"><?php echo e($n->data['title'] ?? 'Notification'); ?></span>
                                                <span class="block text-xs text-slate-500 dark:text-slate-400 mt-0.5"><?php echo e($n->data['body'] ?? ''); ?></span>
                                            </span>
                                        </button>
                                    </form>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <div class="px-4 py-6 text-sm text-center text-slate-500 dark:text-slate-400">You're all caught up.</div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                <div class="hidden sm:flex items-center gap-2 pl-3 border-l dark:border-slate-700">
                    <div class="w-9 h-9 rounded-full bg-brand-red/10 border border-brand-red/20 flex items-center justify-center overflow-hidden">
                        <img src="<?php echo e(asset('images/admin-avatar.png')); ?>" alt="Admin" class="w-7 h-7 object-contain">
                    </div>
                    <div class="text-sm leading-tight">
                        <div class="font-medium"><?php echo e(auth()->user()->name ?? 'Admin'); ?></div>
                        <div class="text-[11px] text-slate-500 dark:text-slate-400">L&amp;F Officer</div>
                    </div>
                    <form method="POST" action="<?php echo e(route('logout')); ?>">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="ml-2 p-2 rounded-md hover:bg-slate-100 dark:hover:bg-slate-700" aria-label="Sign out" title="Sign out">
                            <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'log-out','class' => 'w-4 h-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'log-out','class' => 'w-4 h-4']); ?>
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
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <nav class="md:hidden flex items-center gap-1 px-4 pb-3 overflow-x-auto">
            <?php $__currentLoopData = $navItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e(route($item['route'])); ?>"
                   class="flex items-center gap-1.5 px-3 py-1.5 rounded-md text-xs font-medium whitespace-nowrap <?php echo e(request()->routeIs($item['route']) ? 'bg-brand-blue text-white' : 'text-slate-700 dark:text-slate-200 bg-slate-100 dark:bg-slate-700'); ?>">
                    <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => $item['icon'],'class' => 'w-3.5 h-3.5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($item['icon']),'class' => 'w-3.5 h-3.5']); ?>
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
                    <?php echo e($item['label']); ?>

                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </nav>
    </header>

    <?php if(session('status')): ?>
        <div class="mx-auto max-w-7xl w-full px-4 sm:px-6 pt-4">
            <div class="rounded-lg border border-brand-blue/30 bg-brand-blue/5 dark:bg-brand-blue/10 px-4 py-3 text-sm text-brand-bluedark dark:text-slate-100 flex items-center gap-2">
                <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'check-circle','class' => 'w-4 h-4 shrink-0']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'check-circle','class' => 'w-4 h-4 shrink-0']); ?>
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
                <?php echo e(session('status')); ?>

            </div>
        </div>
    <?php endif; ?>

    <?php if($errors->any()): ?>
        <div class="mx-auto max-w-7xl w-full px-4 sm:px-6 pt-4">
            <div class="rounded-lg border border-red-300 bg-red-50 dark:bg-red-900/20 dark:border-red-800 px-4 py-3 text-sm text-red-700 dark:text-red-300">
                <ul class="list-disc list-inside space-y-0.5">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        </div>
    <?php endif; ?>

    <main class="flex-1">
        <?php echo $__env->yieldContent('content'); ?>
    </main>

    <footer class="border-t bg-white dark:bg-slate-800 dark:border-slate-700">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 py-4 text-xs text-slate-500 dark:text-slate-400 flex flex-col sm:flex-row gap-1 justify-between">
            <span>© <?php echo e(date('Y')); ?> Strathmore University · Lost &amp; Found Disposal System</span>
            <span>Built with Laravel <?php echo e(app()->version()); ?></span>
        </div>
    </footer>
</body>
</html>
<?php /**PATH C:\Lost and Found Disposal System\resources\views/layouts/app.blade.php ENDPATH**/ ?>