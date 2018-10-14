$args = array (
'status' => 'approve',
'number' => '20'
);
$d='d/m';
$number_of_words = 25;
$comments = get_comments( $args );
if ( !empty( $comments ) ) :
echo '<ul>';
    foreach( $comments as $comment ) :
    $trimmed_comment = trim_comment($comment->comment_content, $number_of_words);
    echo '<li><a href="'  . get_permalink( $comment->comment_post_ID ) . '#comment-' . $comment->comment_ID . '">' .  '<span style="color:orange">O χρήστης </span>' . '<span style="color:DarkSlateGray">' . $comment->comment_author . '</span>' . '<span style="color:orange"> σχολίασε στο </span>' . get_the_title( $comment->comment_post_ID ) . '<span style="color:DarkSlateGray"> : </span>' . '<span style="color:orange">' . $trimmed_comment . ((strlen($comment->comment_content)>strlen($trimmed_comment))?'...':"") . '</span>' . '</a></li>';
    endforeach;
    echo '</ul>';
endif;

function trim_comment($comment, $quantity){
$comment_arr  = explode(" ", $comment);
$ctr=0;
$ncomment = "";
foreach($comment_arr as $word){
$ctr++;
if($ctr <= $quantity){
$ncomment = $ncomment . $word . " ";
}else{
break;
}
}
return $ncomment;
}