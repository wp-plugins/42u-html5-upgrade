=== 42U HTML5 Upgrade ===
Contributors: 42urick
Tags: HTML5
Requires at least: 3.0
Tested up to: 3.4.1
Stable tag: 1.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

The 42U HTML5 Upgrade prepares your older site for HTML5 and allows you to start using HTML5 structure tags right now.

== Description ==

The 42U HTML5 Upgrade prepares your older site for HTML5 and allows you to start using HTML5 structure tags right now in your posts and pages (section, aside, article, etc), regardless of the browser viewing them.

The 42U HTML5 Upgrade plugin loads  [Modernizr](http://www.modernizr.com/ "Modernizr"), enables the HTML5 Schema in the [TinyMCE Editor](http://www.tinymce.com/ "TinyMCE") and adds HTML5 elements to the TinyMCE style list. 

The custom Modernizr build includes all CSS3, HTML5 and Misc tests, as well as the html5shiv w/printshiv and Modernizr.load(). See the options we used [here](http://www.42umbrellas.com/files/2009/06/modernizer2.6.1build.png "here").

You can turn these functions off individually if you wish!
        
== Installation ==

1. Upload the `42UHTML5` directory (including all files within) to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress

== Screenshots ==
none

== Frequently Asked Questions ==

= Who should use this plugin? =

Anyone with an older site template that is not written to HTML5 standards.

= Is it multisite compatible? =

You betcha.

= Will this break my current site? =

Probably not. We deployed this on over 350 sites that we host and didn't see a single problem. 

= Will this make old browsers suddenly work with new HTML5 features? =
From the modernizr docs:

The library does allow you to use the new HTML5 sectioning elements in IE, but aside from that, it doesn’t modernize any other features. However! Modernizr still pairs extremely well with scripts that do provide support when native browser support is lacking. In general, these scripts are called polyfills.
polyfill (n): a JavaScript shim that replicates the standard API for older browsers

That means you can develop for the future, with the real API, and only load your compatibility polyfills on browsers that do not support that API or feature.

And good news for you, there is a polyfill for nearly every HTML5 feature that Modernizr detects. Yup. So in most cases, you can use a HTML5 or CSS3 feature and be able to replicate it in non-supporting browsers. Yes, not only can you use HTML5 today, but you can use it in the past, too!

See https://github.com/Modernizr/Modernizr/wiki/HTML5-Cross-browser-Polyfills for an extensive list of available polyfills.

== Changelog ==

= 1.1 =
* Added TinyMCE visualblocks plugin. This makes HTML5 elements easier to work with in the visual editor.

= 1.0 =
* Initial release.


== Upgrade Notice ==
* none
