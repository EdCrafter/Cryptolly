<?php
class HtmlHelper {

    static function select($name, $items, $value, $isEmptyRow = false) {
        ?>
        <select name="<?=$name?>">
            <?php
                if ($isEmptyRow) {
                    echo '<option></option>';
                }
                foreach($items as $item) {
                    if (is_string($item) || is_numeric($item)) {
                        echo '<option ' . (($item == $value) ? 'selected' : '') . '>' . $item . '</option>';
                    } else{
                        echo '<option ' . (($item['id'] == $value) ? 'selected' : '') . ' value="'.$item['id'].'">' . $item['name'] . '</option>';
                    }
                }
            ?>
        </select>
        <?php
    }
    static function inputText($name, $value,$type='text') {
        ?>
        <input type="<?=$type?>" id="<?=$name?>" name="<?=$name?>" value="<?=(!empty($value))?htmlspecialchars($value):''?>">
        <?php
    }
}
