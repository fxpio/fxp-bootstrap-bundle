Sonatra Bootstrap Bundle
========================

[![Latest Stable Version](https://poser.pugx.org/sonatra/bootstrap-bundle/v/stable.svg)](https://packagist.org/packages/sonatra/bootstrap-bundle)
[![Latest Unstable Version](https://poser.pugx.org/sonatra/bootstrap-bundle/v/unstable.svg)](https://packagist.org/packages/sonatra/bootstrap-bundle)
[![Build Status](https://travis-ci.org/sonatra/SonatraBootstrapBundle.svg)](https://travis-ci.org/sonatra/SonatraBootstrapBundle)
[![Coverage Status](https://img.shields.io/coveralls/sonatra/SonatraBootstrapBundle.svg)](https://coveralls.io/r/sonatra/SonatraBootstrapBundle)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/sonatra/SonatraBootstrapBundle/badges/quality-score.png)](https://scrutinizer-ci.com/g/sonatra/SonatraBootstrapBundle)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/08121cec-02b1-444e-8958-dea31cfff0e7/mini.png)](https://insight.sensiolabs.com/projects/08121cec-02b1-444e-8958-dea31cfff0e7)

The Sonatra BootstrapBundle is an twig DSL for construct a Twitter Bootstrap 3 interface.

Features include:

- Configures twitter bootstrap stylesheets
- Configures stylesheets to be added on the common base page
- Configures twitter bootstrap javascripts
- Configures javascripts to be added on the common base page
- Assetic fonts loader (for dump command and use controller)
- Assetic filter for replace the string parameter ('%foo.bar%') by the value in parameter bag of the service container
- Assetic filter for replace the target path by the path starting since the current asset directory
- Assetic filter for replace the bundle alias (@AcmeDemoBundle) by the real path
- Renderer Twig tag for config assets in debug or prod mod
- Twig base template for the HTML5 responsive container
- Block type for fundamental HTML element with responsive utilities
  * Grid system
  * Typography and links
  * Code
  * Tables
  * Forms
  * Buttons
  * Images
  * Helper classes
- Block type for components
  * Glyphicons
  * Dropdowns
  * Button groups
  * Button dropdowns
  * Input groups
  * Navs
  * Navbar
  * Breadcrumbs
  * Pagination
  * Labels
  * Badges
  * Jumbotron
  * Page header
  * Thumbnails
  * Alerts
  * Progress bars
  * Media object
  * List group
  * Panels
  * Wells
- Javascript comportment capability for all blocks
  * Transitions
  * Modals
  * Dropdowns
  * ScrollSpy
  * Togglable tabs
  * Tooltips
  * Popovers
  * Alert messages
  * Buttons
  * Collapse
  * Carousel
  * Affix

Documentation
-------------

The bulk of the documentation is stored in the `Resources/doc/index.md`
file in this bundle:

[Read the Documentation](Resources/doc/index.md)

Installation
------------

All the installation instructions are located in [documentation](Resources/doc/index.md).

License
-------

This bundle is under the MIT license. See the complete license in the bundle:

[Resources/meta/LICENSE](Resources/meta/LICENSE)

About
-----

Sonatra BootstrapBundle is a [sonatra](https://github.com/sonatra) initiative.
See also the list of [contributors](https://github.com/sonatra/SonatraBootstrapBundle/contributors).

Reporting an issue or a feature request
---------------------------------------

Issues and feature requests are tracked in the [Github issue tracker](https://github.com/sonatra/SonatraBootstrapBundle/issues).

When reporting a bug, it may be a good idea to reproduce it in a basic project
built using the [Symfony Standard Edition](https://github.com/symfony/symfony-standard)
to allow developers of the bundle to reproduce the issue by simply cloning it
and following some steps.
