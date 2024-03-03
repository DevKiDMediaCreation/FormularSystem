<?php
global $item;

$choices = explode(";", $item['meta']);
?>
    <div class="input-group-text">
        <select id="<?php echo $item['id']; ?>" class="form-select" aria-label="Default select example">
            <option selected>Selectiere</option>
            <?php foreach ($choices as $choice) { ?>
                <option value="<?php echo $choice; ?>"><?php echo $choice; ?></option>
            <?php } ?>
        </select>
    </div>

