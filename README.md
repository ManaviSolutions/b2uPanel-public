# b2uPanel - a jQuery Plugin
b2uPanel is a jQuery plugin for an AJAX panel system, which can be used with b2uFramework or independently.

At its heart, b2uPanel is a wrapper around the most common AJAX functionalities that web developers tend to use on their websites. Making asynchronous calls to the back-end server to get dynamic content based on a user's action, and updating the webpage, is its ultimate goal. This plugin works independently from any frameworks, but specifically for b2uFramework, it provides a custom _Action_ to use with projects that want to take advantage of b2uFramework.

A _Panel_ is a section within a web page that can load its content independently from the rest of the page (asynchronously). This helps improve the page's loading performance and provides a mechanism to create, change, add, and remove panels without impacting the rest of the system. 

A good example would be a **User Profile** page that displays the user's image, friends list, recent posts, and other user-related data. Each of these data-sets can be organized into a section, which can be designed in an independent _Panel_. Once a user navigates to the user's profile page, each of the _Panels_ identified earlier would start to load asynchronously and populate the page as the content becomes available - instead of delaying the entire page-load until all the content is ready to be displayed.

## Installing b2uPanel Plugin
Installing b2uPanel involves loading the required stylesheet, jQuery library, and b2uPanel JavaScript.
```HTML
<link href="b2u.panel.css" type="text/css" rel="stylesheet">

<!-- Can use jQuery v3.4.1+ -->
<script src="jquery.min.js" type="text/javascript"></script>

<script src="b2u.panel.min.js" type="text/javascript"></script>
```
## Using b2uPanel
After including the required files into a project, to use b2uPanels there are two (2) parts that need to be implemented.
1. **Front-End** - Declare the b2uPanel element and configure it through HTML tags or via JavaScript, and instantiate the jQuery object.
2. **Back-End** - Implement the server code to respond to the `POST` request sent via AJAX calls.

The b2uPanel will process the rest of the work, and update content based on its configuration. Developers can also capture the responses from the AJAX calls and process them depending on their specific application needs.

## Declaring a b2uPanel Element
A b2uPanel is typically set up on a `<div>`, but theoretically, it can be added to any HTML element (e.g., `<span>`), although the behavior has not been tested in all cases. To declare a b2uPanel object:
1. Add a class `b2upanel` to a `<div>` HTML element.
2. Define the data-endpoint URL, and optionally a data-method.
3. Configure the effect, mode, and other panel settings.
4. Call `b2upanel()` in the `$(document).ready()` function.

```HTML
<div id="my_panel" class="b2upanel" data-endpoint="/endpoint_url">
</div>
```
```javascript
$(document).ready(function() {
    $('#my_panel').b2upanel();
});
```
***@note -*** _The example above is showing the absolute minimum required to set up a b2uPanel object._

***@note -*** _Steps 2 & 3 can be a combination of setting HTML data-* tags on the b2uPanel element, or passing an **options.\*** `JavaScript Object` array with the configuration parameters. If both data-endpoint and options-endpoint are set, the value passed in via data-* takes priority._

The only required parameter for initializing a b2uPanel element is `endpoint / data-endpoint`. Almost all other parameters use a default value or have no impact if not initialized. The default settings for the rest of the parameters are:
* `data-mode` = "none"
* `data-init` = false
* `data-effect` = "replace"
* `data-interrupt` = false
* `data-bind` = true
* `data-overlay` = true
* `data-view` = false
* All other [Parameters](https://github.com/bob2u/b2uPanel-public/blob/master/README.md#parameters) are `undefined` by default.

b2uPanel objects can also be initialized during the jQuery creation step by passing configuration arguments as an object to the constructor.
```javascript
$(document).ready(function() {
    $('#my_panel').b2upanel({
        endpoint: "/endpoint_url",
        ...
    });
});
```
# Parameters
```
endpoint / data-endpoint
```
`string` - The endpoint is the target URL that will receive the `POST` request.
##
```
method / data-method
```
`string` - _(Optional)_ The parameter is appended to the endpoint URL. 

When this parameter is set, the endpoint URL is modified to `endpoint_url/method`, except on ["submit"](https://github.com/bob2u/b2uPanel-public/blob/master/README.md#methods) method calls where the endpoint will always be `endpoint_url/submit`. 
##
```
mode / data-mode
```
`string` - _(Optional)_ Set the interactive mode of the b2uPanel element.

* `"none"` - _(Default)_ - The b2uPanel has no interactive events.
* `"click"` - Clicking on the b2uPanel will fire a `click.b2upanel` event, and call ["refresh"](https://github.com/bob2u/b2uPanel-public/blob/master/README.md#methods) method if `bind` is set to `true`.
##
```
bind / data-bind
```
`bool` - _(Optional)_ Determine how the b2uPanel reacts to clicking events.

* `true` - _(Default)_ - Clicking on a b2uPanel element with its `mode` set to `"click"` will also call its ["refresh"](https://github.com/bob2u/b2uPanel-public/blob/master/README.md#methods) method 
* `false` - Clicking on a b2uPanel element with its `mode` set to `"click"` will **NOT** also call its ["refresh"](https://github.com/bob2u/b2uPanel-public/blob/master/README.md#methods) method 
##
```
overlay / data-overlay
```
`bool` or `string` - _(Optional)_ Modify how the panel overlay functions. The plugin's default behavior is that an overlay is displayed with a loading .gif when the plugin makes an AJAX call. This overlay will also prevent further access to the b2uPanel element, which avoids additional `click`s when `mode` is set to `click`.

* `true` - _(Default)_ - Display the b2uPanel overlay on top of the HTML element that defined the b2uPanel.
* `false` - Do not display the overlay when an AJAX call is made. This will prevent the user from receiving visual feedback that an AJAX request has been issued, and the application is waiting for a response. It is the developer's responsibility to manage the user experience.
* `HTML element's id` - If a valid HTML element `id` is provided, then the overlay will be created over the target element with the given `id`. This is useful in scenarios where an application wants to prevent other areas of a page or the parent section of a page that contains the panel to be inaccessible to the end-user until the AJAX call has returned. 
##
```
init / data-init
```
`bool` - _(Optional)_ - Determine if the b2uPanel should load initial content.

* `false` - _(Default)_ - No action. Panel content will only be updated by calling ["refresh"](https://github.com/bob2u/b2uPanel-public/blob/master/README.md#methods) or ["submit"](https://github.com/bob2u/b2uPanel-public/blob/master/README.md#methods) methods.
* `true` - Once the panel is created it will immediately call its ["refresh"](https://github.com/bob2u/b2uPanel-public/blob/master/README.md#methods) method.  
##
```
effect / data-effect
```
`string` - _(Optional)_ - Determine how new content received from AJAX calls should be applied to the b2uPanel HTML element.

* `"replace"` - _(Default)_ - New content will overwrite the current HTML content within the b2uPanel HTML element. This is the same as calling a jQuery `html()` with the new content.
* `"append"` - New content will be appended to the current HTML content within the b2uPanel HTML element. This is the same as calling a jQuery `append()` with the new content.
##
```
interrupt / data-interrupt
```
`bool` - _(Optional)_ - Allow an AJAX request to be interrupted.

* `false` - _(Default)_ - Once an AJAX request is sent to the endpoint server it cannot be interrupted.
* `true` - Allows the interruption of an AJAX call. Once an AJAX request is interrupted it will display a refresh icon in the overlay that can be clicked to call a new ["refresh"](https://github.com/bob2u/b2uPanel-public/blob/master/README.md#methods) method. 
##
```
view / data-view
```
`bool` - _(Optional)_ - Toggle the visibility of the content inside the b2uPanel HTML element.

* `false` - _(Default)_ - Hide the content when an AJAX call is issued, and only toggle back visibility after the new contet has been updated.
* `true` - Do not hide the content when an AJAX call is issued. If `overlay` is `true` then the current content will be visible through the transparent overlay until the AJAX call completes, and updates the content.

This option can be used in conjunction with `overlay=false` to update a b2uPanel's content without changing the visual state of HTML element (i.e., to not display the overlay, and the loading gif).  
##
```
dest / data-dest
```
`string` - _(Optional)_ - Set a different target HTML element to receive the results of the b2uPanel's AJAX call. 

This can be useful when the results of a b2uPanel event should refresh an entire page or a different page section. It can be used to simulate buttons where the b2uPanel can act as the button element.
##
```
data-height
```
`string` - _(Optional)_ - Set on the b2uPanel HTML element, this parameter will set the element's `min-height` attribute. The value set for this should be in valid style attribute format (i.e., `"60px"`, `"3em"`, etc.).

***@note -*** _Only available via data-height HTML attribute._
##
```
click
```
`callback` - _(Optional)_ - The function to call when the b2uPanel element is clicked. This function will receive the `b2uPanel.Event` object.

***@note -*** _Only available via options._
##
```
error
```
`callback` - _(Optional)_ - The function to call when the b2uPanel's AJAX execution fails, or the plugin has an internal error. This function will receive the `b2uPanel.Event` object.

***@note -*** _Only available via options._
##
```
success
```
`callback` - _(Optional)_ - The function to call when the b2uPanel's AJAX execution succeeds. This function will receive the `b2uPanel.Event` object.

***@note -*** _Only available via options._
##
```
complete
```
`callback` - _(Optional)_ - The function to call when the b2uPanel's AJAX execution completes regardless of AJAX success or error. This function will receive the `b2uPanel.Event` object.

***@note -*** _Only available via options._
##
```
args
```
`object` - _(Optional)_ - Two-way object array used to pass additional parameters to the endpoint and receive parameters from the endpoint. The `options.args` field can be used to provide initializing data to the target endpoint, and it can also be used to receive data from the endpoint and process them on the JavaScript front.  

***@note -*** _Only available via options._

# Methods
```javascript
b2upanel('refresh' [, data])
```
Calling this method on a b2uPanel object will initiate an AJAX call to the target endpoint and method - if defined. The second parameter, **data**, can be provided optionally either as an `object` array or a `JSON` string (using `JSON.stringify()`).
##
```javascript
b2upanel('submit' , jQuery element)
```
This method should be used to pass ***form-style*** data to the endpoint. It will set and call a `submit` method by default. ***form-style*** data includes both HTML `<input>` and `<select>` elements encapsulated inside a `<form>` or a simple `<div>`. The second parameter should be a valid jQuery element using the form element (i.e., `$("#form_id")`).
##
```javascript
b2upanel('bind')
```
Enable ["refresh"](https://github.com/bob2u/b2uPanel-public/blob/master/README.md#methods) on `click` events. The b2uPanel must have mode type `"click"` set.
##
```javascript
b2upanel('unbind')
```
Disable ["refresh"](https://github.com/bob2u/b2uPanel-public/blob/master/README.md#methods) on `click` events. The b2uPanel must have mode type `"click"` set.
##
```javascript
b2upanel('abort')
```
Interrupt an AJAX call initiated by the b2uPanel object via ["refresh"](https://github.com/bob2u/b2uPanel-public/blob/master/README.md#methods) or ["submit"](https://github.com/bob2u/b2uPanel-public/blob/master/README.md#methods).

***@note -*** _This method will ignore the data-interrupt and options.interrupt parameter._
##

# Events
The plugin suports a few events which redirect the `$.ajax` results to the application.
|Event|Description|
|:---:|:---|
|`click.b2upanel`|This event is triggered on b2uPanel `click` events if the `data-mode` or `options.mode` is set to `"click"`, regardless of `bind` status.|
|`success.b2upanel`|This event is triggered on b2uPanel AJAX success **AND** a _status\_code_ of 200 with valid _content_ returned from endpoint.|
|`error.b2upanel`|This event is triggered on b2uPanel AJAX error, _status\_code_ not equal to 200, or _content_ `undefined` returned from endpoint|
|`complete.b2upanel`|This event is triggered on **ALL** b2uPanel AJAX calls|

Example capturing a `b2uPanel.Event`:
```javascript
$('#my_panel').on('success.b2upanel', function (e) {
    console.log(e.post);
    console.log(e.response);
});
```
##
A `b2uPanel.Event` object is returned to the calling JavaScript with all b2uPanel events. This `Event` object will have the following definition:
```javascript
b2uPanel.Event = {
    post: {                                                 // arguments that were posted to the endpoint
        _b2upanel_args: {},                                     // parameters posted to the endpoint
        _b2upanel_endpoint: "endpoint_url[/method]",            // actual endpoint called
        _b2upanel_id: "my_panel",                               // element initiating the AJAX POST
        _b2upanel_options: {                                    // state of the b2uPanel options at time of AJAX call
            args: {},                                               // initialization parameters
            bind: true,
            effect: "replace",
            endpoint: "endpoint_url",
            init: false,
            interrupt: false,
            method: "show",
            mode: "none",
            overlay: true,
            view: false
        }
    },               
    response: {                                             // result of the last AJAX call to endpoint
        args: {},                                               // merged arguments passed in and returned by endpoint
        content: "...",                                         // content sent back by endpoint
        options: [],                                            // b2uPanel options to modify
        status_code: 200,                                       // 200 on success else not on error
        status_message: "success"                               // "success" or error message
    }
}
```
# b2uFramework Integration

@see [b2uFramework](https://github.com/bob2u/b2uFramework-public/blob/master/README.md#b2uframework) for more details.

The b2uPanel was designed independently from any framework, and it was designed to support any RESTful framework. But for b2uFramework, it has out-of-the-box integration prepared in the form of a b2uFramework _Action_. The `\B2uPanel\B2uPanelAction` class definition extends the `\B2U\Core\Action` to provide some additional functionality that will streamline the use of b2uPanels in any project utilizing the b2uFramework.

## Addint b2uPanel to b2uFramework
1. Download a copy of the latest [b2uPanel-public](https://github.com/bob2u/b2uPanel-public), and copy the contents to a location within the website's directory.
2. Modify the main ***index.php*** for the application by adding the following to the `setup()` configuration under the global **Includes** section.
    ```PHP
	\B2U\Core\Manager::instance()->setup([
		"Includes" => [
			"path_to_b2upanel_plugin/b2u.action.php"
		],
	...
	]);
    ```
3. Any _Action_ that is set as a `data-endpoint` of a b2uPanel should derive from `\B2uPanel\B2uPanelAction`.
4. `public function submit() {}` is a **required** _Method_ that must be defined in the _Action_ class. This function will receive the results of `b2upanel("submit", ...)` calls.

## Utility Functions
b2uPanels expect precise responses to be returned when a call to an endpoint is made. For example, the result must be a `JSON` with the parameters `status_code` and `content` properly defined. Furthermore, POST request sent from b2uPanels have a unique structure that contains parameters that may not be conducive to every application's use-case (e.g., `_b2upanel_args` for arguments sent via AJAX). The following methods are provided to simplify sending and receiving data between b2uPanel and an application built on b2uFramework.
##
```PHP
public function buildResponse($content, $args = [], $code = 200, $options = [])
```
@param **$content** - `string` - Typically HTML content returned.

@param **$args** - `Array` - Default `[]`, array to be sent back to JavaScript, and accessible through the `b2uPanel.Event`'s `response.args`.

@param **$code** - `int` - Default 200, the content will not be processed and a `error.b2upanel` event will be triggered if $code is not 200.

@param **$options** - `Array` - Default `[]`, array of b2uPanel `options` to be modified. Not all options can be modified dynamically.
##
```PHP
public function redirect($url, $args = [], $options = [])
```
@param **url** - `string` - URL to redirect to once AJAX call returns on success.

@param **$args** - `Array` - Default `[]`, array to be sent back to JavaScript, and accessible through the `b2uPanel.Event`'s `response.args`.

@param **$options** - `Array` - Default `[]`, array of b2uPanel `options` to be modified. Not all options can be modified dynamically.

Within the b2uFramework an application can redirect to a different endpoint using the `$this->Response->setHeader("Location", "some_url")`, which will preserve any b2uPanels loaded on the page. When an application wants to completely redirect off of a b2uPanel _Action_ they should call this function, since it will set the `document.location` in JavaScript and reload the page with the new URL content provdied.
##
```PHP
public function modifyRequest()
```

[Top](https://github.com/bob2u/b2uPanel-public/blob/master/README.md#b2upanel---a-jquery-plugin)
