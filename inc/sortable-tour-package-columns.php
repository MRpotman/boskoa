<?php

add_filter('manage_edit-tour_package_sortable_columns', function ($columns) {
    $columns['price'] = 'price';
    $columns['order'] = 'order';
    return $columns;
});
