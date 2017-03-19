<?php
defined('mnminclude') or die();

$query = '
    FROM votes, comments
    WHERE (
        vote_type = "comments"
        AND vote_user_id = "'.(int)$user->id.'"
        AND comment_id = vote_link_id
        AND comment_user_id != vote_user_id
    )
';

$count = (int)$db->get_var('SELECT COUNT(*) '.$query.';');

if ($count === 0) {
    return Haanga::Load('user/empty.html');
}

$comments = $db->get_results('
    SELECT vote_link_id AS id, vote_value AS value
    '.$query.'
    ORDER BY vote_date DESC
    LIMIT '.(int)$offset.', '.(int)$limit.';
');

if (empty($comments)) {
    return;
}

echo '<ol class="comments-list">';

foreach ($comments as $c) {
    $comment = Comment::from_db($c->id);

    if ($comment->author == $user->id || $comment->admin) {
        continue;
    }

    $color = ($c->value > 0) ? '#00d' : '#f00';

    echo '<li>';

    $comment->print_summary(1000, false);

    echo '<div class="box" style="margin:0 0 -16px 0;background:' . $color . ';position:relative;top:-34px;left:30px;width:30px;height:16px;border-color:' . $color . ';opacity: 0.5"></div>';
    echo '</li>';
}

echo "</ol>\n";

do_pages($count, $limit);
