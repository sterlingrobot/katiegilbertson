<?php
/**
@page installation Installation Instructions

Installation of the Tagger module is no different than for any other Xataface module.  You simply need to download the module to the appropriate directory, and add a line to your conf.ini file.  However, simply installing the module won't, in itself, make any visible changes to your application.  It simply enables you to use the tagger widget if you want to.

@section stepsxf Installing in the Xataface modules directory:

-# Download the latest version of the tagger widget from the Xataface site and extract it.
-# Copy the tagger directory into the xataface/modules directory.
-# Add the following to the [_modules] section of your conf.ini file
@code
[_modules]
    modules_tagger=modules/tagger/tagger.php
@endcode

@section stepsapp Installing in your Application's modules directory:

Use the same steps as above (for installing in the Xataface directory), except, instead of copying to your xataface/modules directory, you copy it to your application's modules directory.

e.g.
if Your application is installed at path/to/app, then you would copy the tagger
directory to the path/to/app/modules directory.

@see @ref setuptaggerwidget for instructions on setting up fields to use the tagger widget.
@see @ref setuptagcloud for instructions on enabling the tag cloud display component.

*/