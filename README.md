# b2uPanel - a jQuery Plugin
b2uPanel is a jQuery plugin for an AJAX panel system used with b2uFramework.

At its heart, b2uPanel is a wrapper around the most common AJAX functionalities that web developers tend to use on their websites. Making asynchronous calls to the back-end server to get dynamic content based on a user's action, and updating the webpage, is the ultimate goal.

## Installing b2uPanel Plugin
Installing b2uPanel involves loading the required stylesheet, jQuery libraries, and b2uPanel plugin.
```HTML
<link href="b2u.panel.css" type="text/css" rel="stylesheet">

<!-- Using jQuery v3.4+ -->
<script src="jquery.min.js" type="text/javascript"></script>

<script src="b2u.panel.min.js" type="text/javascript"></script>
```
## Declaring a b2uPanel Element
A b2uPanel is typically set up on a `<div>`, but theoretically, it can be added to any HTML element, although the behavior has not been tested in all cases. To declare a b2uPanel object:
1. Add a class `b2upanel` to a `<div>` HTML element.
2. Define the data-plugin, -action, and -method as required.
3. Configure the effect, mode, and other panel settings.
4. Call `b2upanel()` in the `$(document).ready()` function.

```HTML
<div id="my_panel" class="b2upanel" data-plugin="/plugin_name">
</div>
```
```javascript
$(document).ready(function() {
    $('#my_panel').b2upanel();
});
```
***@note -*** _The HTML element setup for b2uPanel is showing the absolute minimum required to set up a b2uPanel object._

The only required parameter for initializing a b2uPanel element is the `data-plugin` argument. Almost all other parameters use a default value or have no impact if not initialized. The default settings for a b2uPanel object parameters are:
* `data-mode` = "none"
* `data-init` = false
* `data-effect` = "replace"
* `data-interrupt` = false
* `data-bind` = true
* `data-overlay` = true
* `data-view` = false

b2uPanel objects can also be initialized during the jQuery creation by passing in configuration arguments in an object to the constructor.
```javascript
$(document).ready(function() {
    $('#my_panel').b2upanel({
        plugin: "/plugin_name",
        ...
    });
});
```
# Parameters

# Methods
```javascript
b2upanel('refresh' [, data])
```
##
```javascript
b2upanel('submit' , jQuery element)
```
##
```javascript
b2upanel('bind')
```
##
```javascript
b2upanel('unbind')
```
##
```javascript
b2upanel('abort')
```
##
