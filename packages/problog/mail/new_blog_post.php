<?php       defined('C5_EXECUTE') or die("Access Denied.");
$description = substr($description,0,325).'....';
$bodyHTML = "
<h2><a href=\"$url\" alt=\"blog link\">$name</a></h2>
<p>$description</p>
<br/>
<br/>
<a href=\"$url\" alt=\"blog link\">$url</a>
<hr/>
<br/>
Thanks for reading!
".BASE_URL." Web Team
<br/>
<br/>
<p><i>You are receiving this notice at your request.  To unsubscribe, please go to $parent and click on \"Unsubscribe from this Blog\".</i></p>
";

?>
