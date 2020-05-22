# Awesome Social Links

[![GitHub tag](https://img.shields.io/github/tag/ginopane/oc-awesomesociallinks-plugin.svg)](https://github.com/GinoPane/oc-awesomesociallinks-plugin)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/GinoPane/oc-awesomesociallinks-plugin/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/GinoPane/oc-awesomesociallinks-plugin/?branch=master)

[_Awesome Social Links_](https://octobercms.com/plugin/ginopane-awesomesociallinks) adds ability to have a list of social or any other branded icons associated with your website. The icons could be listed on the frontend using included frontend component.

## Requirements

The Awesome Social Links plugin requires [Awesome Icons List](https://octobercms.com/plugin/ginopane-awesomeiconslist), which should be installed automatically by [October CMS](http://octobercms.com/). Make sure to install it manually if October CMS fails to do it for some reason.

## Backend Settings

The Awesome Social Links provides a backend settings page where you can configure your icon list which can be used by a [frontend component](#frontend-component).

The list allows you to set the icon (powered by Awesome Icons List), the name of the link item and the link URL itself. Make sure to use meaningful names for you link items because they will be used as configuration keys for the frontend component. Also you can rearrange the order of items.

## Frontend Component

The plugin provides a `linkList` component which can be used to display the list of icons on your website.

> The default markup provided with the plugin is just an example, so make sure to fit it into your website's theme

The component settings allow you to choose which icons from the list you want to display via this component instance. Also as a supplemental option you can set `linkTarget` and use it for you links as target (`_blank`, `_self` etc.) attribute value.
