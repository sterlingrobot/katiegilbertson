/**
 * This is a sample class that is only here to be loaded by the ClassLoader inthe
 * xataface.tests.ClassLoaderTest class.
 */
//require <xatajax.core.js>
(function(){
	var tests = XataJax.load('xataface.tests');
	tests.SampleRequiredClass2 = SampleRequiredClass2;
	
	function SampleRequiredClass2(){}
})();