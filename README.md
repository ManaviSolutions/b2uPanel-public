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
A b2uPanel is typically setup on a `<div>`, but theoretically it can be added to any HTML element, although the behaviour has not been tested in all cases. To declare a b2uPanel object:
1. Add a class `b2upanel` to a `<div>` HTML element.
2. Define the data-plugin, -action, and -method as required.
3. Configure the effect, mode, and other panel settings.
4. Call `b2upanel()` in the `$(document).ready()` function.

```HTML
<div id="my_panel" class="b2upanel" data-plugin="/plugin_name" data-action="action_name" data-method="method_name">
</div>
```
```javascript
$(document).ready(function() {
    $('#my_panel').b2upanel();
});
```
## Configuring a b2uPanel Element
