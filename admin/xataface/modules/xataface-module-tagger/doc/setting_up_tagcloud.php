<?php
/**

@page setuptagcloud Setting up the Tag Cloud

The Tagger module comes with a tag cloud visual component that can be configured to appear in the left column of your Xataface application.  The tag cloud shows the possible tags that the current record set contains, with common tags appearing larger than sparsely used tags.

Setting up the tag cloud is as easy as adding the "tag_cloud" directive to an field definition in the fields.ini file.

@attention Currently this only works on fields that use the tagger widget future versions will expand this ability for any field.

e.g.

@code
[countries]
        widget:type=tagger
        transient=1
        relationship=countries
        order=9
        tag_cloud=1
@endcode

And the final result looks like:
<img src="http://media.weblite.ca/files/photos/Screen%20shot%202011-05-12%20at%203.51.38%20PM.png?max_width=640"/>

*/