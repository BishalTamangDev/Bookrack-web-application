<?php
function deleteFileFromStorageBucket($folder, $filename)
// function to delete images form storage bucket
{
    $status = false;
    global $bucket;

    if ($folder == "users") {
        $prefix = 'users/';
    } elseif ($folder == "document") {
        $prefix = 'document/';
    } else {
        $prefix = '';
    }

    $options = [
        'prefix' => $prefix,
        'delimiter' => '/'
    ];

    $objects = $bucket->objects($options);

    foreach ($objects as $object) {
        // Check if the object's name (filename) matches the filename we are looking for
        if ($object->name() === $prefix . $filename) {
            // Delete the object if the filename matches
            $object->delete();
            $status = true;
            break; // Exit the loop once we find and delete the matching filename
        }
    }
    return $status;
}