<p>Hello Community Outreach Club,</p>
<p>
    Please arrange collection today from the Lost &amp; Found office of this quarter's
    unclaimed items for onward donation.
    <?php if($releasedCount !== null): ?>
        In total, <strong><?php echo e($releasedCount); ?></strong> <?php echo e(\Illuminate\Support\Str::plural('item', $releasedCount)); ?> <?php echo e($releasedCount === 1 ? 'is' : 'are'); ?> being handed over.
    <?php endif; ?>
    These are general disposed items — the full manifest is stored in the audit log.
</p>
<p>📍 <?php echo e($officeLocation); ?></p>
<p>Thank you for the partnership.</p>
<?php /**PATH C:\Lost and Found Disposal System\resources\views/emails/templates/outreach_handover.blade.php ENDPATH**/ ?>