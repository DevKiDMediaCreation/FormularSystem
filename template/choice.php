<?php
global $item;

$choices = explode(";", $item['meta']);
$lastIndex = count($choices) - 1;

?>

<div class="form-check align-center">
    <?php foreach ($choices as $index => $choice) { ?>
        <input type="radio" class="btn-check" name="<?php echo $item['id'] ?>"
               id="<?php echo $item['id'] . $choice ?>" value="<?php echo $item['id'] . $choice ?>"
            <?php echo $index === $lastIndex ? 'checked' : ''; ?>>
        <label for="<?php echo $item['id'] . $choice; ?>" class="btn btn-outline-black"><?php echo $choice; ?></label>
    <?php } ?>
</div>