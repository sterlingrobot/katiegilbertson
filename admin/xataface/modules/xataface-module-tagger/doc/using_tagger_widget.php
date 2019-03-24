<?php
/**
@page usingtaggerwidget Using the Tagger Widget

From a user's perspective there are 4 things you can do with the tagger widget:

-# Add a new tag
-# Add an existing tag (autocomplete)
-# Delete a tag
-# Edit a tag

@section addtag Adding a New Tag

Adding a new tag is as easy as typing a phrase into the tagger widget and pressing "Enter".  The string will then show up as a tag and will be saved as such when the record is saved.  If a matching tag already exists in the relationship, Xataface will be smart enough to match them up as the same tag.

@section addexisting Adding an Existing Tag

As the user is typing into the tagger widget they will see a pull-down menu with autocomplete options of existing tags for the user to choose.  Selecting one of these options will yield an existing tag to be added to the widget.

@section deletetag Deleting a Tag

Users can delete a tag by clicking the "X" icon in the tag itself.  The changes won't be saved until the user submits the edit form and saves the record.

@section edittag Editing a Tag

If the user wants to edit other information about a record that is represented by a tag, they can simply double-click the tag and it will open the edit form for the record in an internal dialog box.

*/