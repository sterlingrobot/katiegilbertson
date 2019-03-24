<?php
/**

@mainpage Xataface Tagger Module v0.1

@section Synopsis

The Xataface Tagger module adds two significant features:

-# A "tagger" widget for use in any of Xataface's forms.
-# A "tag cloud" component that displays a tag cloud of all of the tags in the current table.

@see @ref setuptaggerwidget for information on setting up the tagger widget.
@see @ref setuptagcloud for information on setting up the tag cloud component.

@subsection taggerwidget The Tagger Widget

The "Tagger" widget is a widget type that can be used on transient fields which edit a relationship.  It enables users to add and remove related records through a simple and familiar tagging interface.  

<img src="http://media.weblite.ca/files/photos/Screen%20shot%202011-05-12%20at%203.32.26%20PM.png?max_width=700"/>

When users type into a tagger widget they will immediately be shown a menu of existing tags (i.e. existing records in the target relationship) from which to choose for autocompletion.  

<img src="http://media.weblite.ca/files/photos/Screen%20shot%202011-05-12%20at%203.46.49%20PM.png?max_width=640"/>

They are not, however obligated to choose an existing tag.  New tags are automatically added to the relationship.

In addition, the user is able to edit any tag's underlying record by simply double-clicking the tag to reveal an internal dialog with an edit form for the tag (similar to the lookup widget).

<img src="http://media.weblite.ca/files/photos/Screen%20shot%202011-05-12%20at%203.38.55%20PM.png?max_width=700"/>

@subsection tagcloud The Tag Cloud Component

The "Tag Cloud" component is a simple component that, if enabled, will show the values of a relationship in the left column of your Xataface application.  The tag cloud can be enabled or disabled independently of the Tagger widget and it can be enabled on a per-relationship basis.

<img src="http://media.weblite.ca/files/photos/Screen%20shot%202011-05-12%20at%203.51.38%20PM.png?max_width=640"/>

@section Requirements

-# Xataface 1.3.1

@section next What's next?

@see @ref installation
@see @ref setuptaggerwidget
@see @ref setuptagcloud

*/