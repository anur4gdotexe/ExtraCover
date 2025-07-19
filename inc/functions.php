<?php

function getTime($postedAt) {
    $currTime = time();
    $postedAt = strtotime($postedAt);

    $diff = $currTime - $postedAt;
    if ($diff < 60) {
        if ($diff > 1)return $diff . " seconds ago";
        return "a second ago";
    }
    if ($diff < 3600) {
        $diff = floor($diff/60);
        if ($diff > 1) return $diff . " minutes ago";
        return "a minute ago";
    }
    if ($diff < 86400) {
        $diff = floor($diff/3600);
        if ($diff > 1) return $diff . " hours ago";
        return "an hour ago";
    }
    if ($diff < 604800) {
        $diff = floor($diff/86400);
        if ($diff > 1) return $diff . " days ago";
        return "a day ago";
    }
    
    $diff = floor($diff/604800);
    if ($diff > 1) return $diff . " weeks ago";
    return "a week ago";
}

?>