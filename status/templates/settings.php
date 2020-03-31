<?php include('header.php'); ?>

<ul>
<?php foreach($settings as $item): ?>
    <li>
        <form action="./update" method="POST">
            <input type="hidden" name="id" value="<?php echo $item['settings_id']; ?>">
            <input type="hidden" name="hash" value="<?php echo $hash; ?>">
            <label for="value_<?php echo $item['settings_id']; ?>"><?php echo $item['name']; ?></label>
            <input id="value_<?php echo $item['settings_id']; ?>" type="text" name="value" value="<?php echo $item['value']; ?>">
            <label for="null_<?php echo $item['settings_id']; ?>"> null</label>
            <input id="null_<?php echo $item['settings_id']; ?>" type="checkbox" name="null" value="1" <?php if (is_null($item['value'])){ echo "checked"; }?>>
            <input type="submit" value="save"/>
        </form>
    </li>
<?php endforeach; ?>
</ul>
<script type="text/javascript">
$(document).on('keydown', '[name="value"]', function(e) {
    var target = $(e.target);
    if (target.val()) {
        $('[name="null"]', target.parent()).prop('checked', false);
    }
})
</script>
<?php include('footer.php'); ?>