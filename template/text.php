<?php
global $item;
global $required;

?>

    <input type="text" id="<?php echo $item['id']; ?>" placeholder="<?php echo $item['meta']; ?>"
           name='<?php echo $item['id']; ?>' class='form-control rounded p-2'
           required="<?php echo $required; ?>">
    <!-- border-bottom border-0-->
    <div class="invalid-feedback">
        Muss ausgefüllt werden
    </div>
