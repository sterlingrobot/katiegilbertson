<?php

// display 'Powered by' info in bottom right of each page //

echo "        <div class='print_hide container pull-right'><small>Powered by&nbsp;
  <a class=footer_links href='http://httpd.apache.org/'>Apache</a>&nbsp;&#177<a class=footer_links href='http://mysql.org'>&nbsp;MySql</a>
            &#177";

if ($email == "none") {
    echo "<a class=footer_links href='http://php.net'>&nbsp;PHP</a>";
} else {
    echo "<a class=footer_links href='http://php.net'>&nbsp;PHP</a>&nbsp;&#8226;&nbsp;<a class=footer_links href='mailto:$email'>$email</a>";
}

echo "&nbsp;&#8226;<a class=footer_links href='http://timeclock.sourceforge.net'>&nbsp;$app_name&nbsp;$app_version</a>\n";
echo "</small></div>\n";

// include bootstrap js for every page
echo "<script src='//" . $_SERVER['SERVER_NAME'] . "/scripts/bootstrap.min.js'></script>\n";

echo "</body>\n";
echo "</html>\n";
?>
