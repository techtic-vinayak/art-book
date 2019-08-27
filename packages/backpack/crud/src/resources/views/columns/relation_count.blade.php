{{-- enumerate the values in an array  --}}
<span>
    <?php
    if ($entry->{$column['entity']}) {
        echo $entry->{$column['entity']}->count();
    } else {
        echo '-';
    }
    ?>
</span>