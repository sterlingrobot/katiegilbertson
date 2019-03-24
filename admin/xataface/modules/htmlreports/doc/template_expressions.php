<?php
/**

@see @ref template_creation

@page template_expressions Template Expressions

Support has been added in version 0.2 for template expressions so that you can perform numerical calculations inside the template. 

You can perform calculations on:

- number literals (e.g. 2, 2.5, 45000.34455)
- Database fields (e.g. {$record_id}
- Summary fields (e.g. {\@sum(cost)})

Calculations may include all arithmetic operators, parenthesis for operation ordering, and the following PHP mathematical functions:

- <a href="http://php.net/floor">floor</a>
- <a href="http://php.net/ceil">ceil</a>
- <a href="http://php.net/round">round</a>
- <a href="http://php.net/max">max</a>
- <a href="http://php.net/min">min</a>
- <a href="http://php.net/abs">abs</a>
- <a href="http://php.net/acos">acos</a>
- <a href="http://php.net/acosh">acosh</a>
- <a href="http://php.net/asinh">asinh</a>
- <a href="http://php.net/asin">asin</a>
- <a href="http://php.net/atan">atan</a>
- <a href="http://php.net/atan2">atan2</a>
- <a href="http://php.net/atanh">atanh</a>
- <a href="http://php.net/base_convert">base_convert</a>
- <a href="http://php.net/bindec">bindec</a>
- <a href="http://php.net/cos">cos</a>
- <a href="http://php.net/cosh">cosh</a>
- <a href="http://php.net/decbin">decbin</a>
- <a href="http://php.net/dechex">dechex</a>
- <a href="http://php.net/decoct">decoct</a>
- <a href="http://php.net/deg2rad">deg2rad</a>
- <a href="http://php.net/exp">exp</a>
- <a href="http://php.net/expm11">expm11</a>
- <a href="http://php.net/fmod">fmod</a>
- <a href="http://php.net/getrandmax">getrandmax</a>
- <a href="http://php.net/hexdec">hexdec</a>
- <a href="http://php.net/hypot">hypot</a>
- <a href="http://php.net/lcd_value">lcd_value</a>
- <a href="http://php.net/log">log</a>
- <a href="http://php.net/log10">log10</a>
- <a href="http://php.net/log1p">log1p</a>
- <a href="http://php.net/mt_getrandmax">mt_getrandmax</a>
- <a href="http://php.net/mt_rand">mt_rand</a>
- <a href="http://php.net/mt_srand">mt_srand</a>
- <a href="http://php.net/octdec">octdec</a>
- <a href="http://php.net/pi">pi</a>
- <a href="http://php.net/pow">pow</a>
- <a href="http://php.net/deg2rad">deg2rad</a>
- <a href="http://php.net/rand">rand</a>
- <a href="http://php.net/sin">sin</a>
- <a href="http://php.net/sinh">sinh</a>
- <a href="http://php.net/sqrt">sqrt</a>
- <a href="http://php.net/srand">srand</a>
- <a href="http://php.net/tan">tan</a>
- <a href="http://php.net/tanh">tanh</a>


@para Expression Syntax

Numerical expressions are defined with the following syntax:

@code
{% expression here  %}
@endcode

I.e. An expression begins with a @code {% @endcode tag and ends with a @code %} @endcode tag.

@para Examples

- {% 3*4 %}  - Displays "12"
- {% {$age} * 4 %}  - Displays 4 times the age field.
- {% {\@sum(cost)} - {\@sum(income)} %}  - Displays the difference between the sum of cost and sum of income summaries.
- {% round({\@sum(cost)}/12, 2) %}  - Displays the sum of the cost field divided by 12, but rounded to 2 decimal places.

@see @ref summary_fields
@see @ref syntax

@see @ref template_creation
*/
?>