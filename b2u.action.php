<?php
/* Copyright (C) Manavi Solutions LLC - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 */
namespace B2uPanel;

class B2uPanelAction extends \B2U\Core\Action {

	// b2u.panel.js uses specific JSON responses to properly set content
	// for new panels to load and display. this function will setup all
	// these required fields, and provide an easy to use interfce for a
	// user to communicate back-forth with JavaScript using the $args &
	// $options parameters.
	// 
	// @param $content - the content to apply b2upanel data-effect on
	// @param $code - 200 to allow content to be processed else fires
	//				  error.b2upanel event in JavaScript.
	// @param $args - optional arguments to pass to JavaScript and is
	//				  available to the application in success.b2upanel
	//				  in e.response.args
	// @param $options - allow override of the b2upanel configurations
	//					 for example to turn on/off binding, the modes
	//					 and effects, and other runtime options.
	public function buildResponse($content, $args = [], $code = 200, $options = []) {
		// populate the response with the default JSON used by b2u.panel.js
		$this->Response()->setHeader("Content-Type", "application/json")
						->setContent(json_encode([
								"status_code" => $code,
								"content" => $content,
								"args" =>  ($this->Parameters()["_b2upanel_args"] ?? []) + $args,
								"options" => $options
							]
						));
	}

	// when in panel actions a user can redirect completely off the panel
	// page by calling this method, which will set the special "redirect"
	// parameter that will be used by b2u.panel.js to perform a redirect.
	public function redirect($url, $args = [], $options = []) {
		$this->Response()->setHeader("Content-Type", "application/json")
						->setContent(json_encode([
								"redirect" => true,
								"status_code" => 200,
								"content" => $url,
								"args" =>  ($this->Parameters()["_b2upanel_args"] ?? []) + $args,
								"options" => $options
							]
						));
	}

	// this function is called by the b2uframework prior to calling methods
	// to provide the plugin developers to move any data they want into the
	// main Parameters array. this is useful in cases where the plugin will
	// generate data in arbitrary fields, but they would like them to be in
	// and easy to access first-level key/value entry in the Parameters.
	public function modifyRequest() {
		// b2u.panel.js will post to endpoints with the following parameters:
		// [
		//		_b2upanel_id
		//		_b2upanel_endpoint
		//		_b2upanel_options
		//		_b2upanel_args
		// ]
		// the args will be the form, or refresh data submitted, as well as
		// data provided in the options.args on initial setup. This data is
		// needed to be placed into _REQUEST parameters for b2uFramework to
		// be able to call methods within an action when mapping signatures
		// for parameter to functions.
		if (isset($this->Parameters()["_b2upanel_args"])) {
			// without losing the existing data in _REQUEST append the args
			return array_replace_recursive($this->Parameters(), $this->Parameters()["_b2upanel_args"]);
		}
		return $this->Parameters();
	}

    // this function can be used by b2upanel derived panels to quickly add
    // a utility pagination component to the panel that will automatically
    // create the required buttons and tie-in the necessary submission and
    // logic to submit pagination information to a panel
    //
    // @param $id - b2upanel id to receive the submit command
    // @param $offset - the current offset to use for data display
    // @param $limit - the limit of records in each pagination call
    // @param $total - total number of records to calculate max num pages
    // @param $perpage - maximum number of page buttons to display
    // @param $args - optional parameters to be passed to panel via args
    //
    // @return the HTML component to be displayed in the panel
    public function addPagination($id, $offset, $limit, $total, $perpage, $args = null) {

        $html               = "";
        if ( !is_numeric( $offset ) || !is_numeric( $limit ) ||
             !is_numeric( $total ) || !is_numeric( $perpage ) ) {
            return \AMMS\locale::get( "LABEL_LOADING" );
        }
        $pageCount          = intval( ceil( $total / $limit ) );
        if ( $pageCount > 1 ) {
            $page           = intval( floor( $offset / $limit ) );
            $start          = intval( floor( $page / $perpage ) * $perpage );
            $off            = 1;
            $disable        = "";
            $paging         = "paging";
            if ($start === 0) {
                $off        = 0;
                $paging     = "";
                $disable    = "disabled";
            }
            $html           =  '<div id="' . $id . '-pagination" class="col-md-12 text-center b2upanel-mb-n50">
                                    <nav aria-label=""Page navigation">
                	                    <ul class="pagination justify-content-center">
                		                    <li class="page-item ' . $disable . '">
                			                    <a class="' . $paging . ' page-link" href="javascript:void(0);" data-page="0" aria-label="First">
                				                    <span aria-hidden="true">&laquo;</span>
                				                    <span class="sr-only">First</span>
                			                    </a>
                		                    </li>
                		                    <li class="page-item ' . $disable . '">
                			                    <a class="' . $paging . ' page-link" href="javascript:void(0);" data-page="' . ($start - $off) . '" aria-label="Previous">
                				                    <span aria-hidden="true">&lsaquo;</span>
                				                    <span class="sr-only">Previous</span>
                			                    </a>
                		                    </li>';
            $j              = 0;
            $i              = $start;
            for ( ; $i < $pageCount && $j < $perpage; ++$i, ++$j ) {
                $active     = "";
                if ( $i === $page ) {
                    $active = "active";
                }
                $html      .= '             <li class="page-item ' . $active . '"><a  class="paging page-link" href="javascript:void(0);" data-page="' . $i . '">' . ( $i + 1 ) . '</a></li>';
            }
            $disable        = "";
            $paging         = "paging";
            if ( $j < $perpage || ( $j === $perpage && $perpage === $pageCount ) ) {
                $off        = 0;
                $paging     = "";
                $disable    = "disabled";
            }
            $html          .= '		        <li class="page-item ' . $disable . '">
                			                    <a class="' . $paging . ' page-link" href="javascript:void(0);" data-page="' . $i . '" aria-label="Next">
                				                    <span aria-hidden="true">&rsaquo;</span>
                				                    <span class="sr-only">Next</span>
                			                    </a>
                		                    </li>
                		                    <li class="page-item ' . $disable . '">
                			                    <a class="' . $paging . ' page-link" href="javascript:void(0);" data-page="' . ( $pageCount - 1 ) . '" aria-label="Last">
                				                    <span aria-hidden="true">&raquo;</span>
                				                    <span class="sr-only">Last</span>
                			                    </a>
                		                    </li>
                                            <li class="b2upanel-desktop">
                                                <input type="text" name="' . $id . '-page-num-d" class="form-control b2upanel-page-input" value="' . ($page + 1) . '">
                                            </li>
                	                    </ul>
                                        <ul class="b2upanel-mobile b2upanel-mt-n20 b2upanel-mb-40">
                                            <li>
                                                <input type="text" name="' . $id . '-page-num-m" class="form-control b2upanel-page-input" value="' . ($page + 1) . '">
                                            </li>
                                        </ul>
                                        <ul class="b2upanel-mt-n30 b2upanel-mb-40">
                                            <li>
                                                <select type="text" name="' . $id . '-page-limit" id="' . $id . '-page-limit" class="b2upanel-page-select">';
            foreach([5,10,25,50,100,500,1000] as $option) {
                $html .= '				            <option value="' . $option . '" ' . ($option == $limit ? 'selected' : '') . '>' . $option . '</option>';
            }
            $html .= '				            </select>
                                            </li>
                                        </ul>
                                    </nav>
                                    </div>
                                    <input type="hidden" name="' . $id . '-page" id="' . $id . '-page" value="' . $page . '">
                                    <input type="hidden" name="' . $id . '-limit" id="' . $id . '-limit" value="' . $limit . '">
                                    <input type="hidden" name="' . $id . '-offset" id="' . $id . '-offset" value="' . $offset . '">
                                    <script>
                	                    $(document).ready(function() {
                                            $("#' . $id . '-page-limit").on("change", function(e) {
                                                $("#' . $id . '-page").val(0);
                                                $("#' . $id . '-limit").val(this.value);
                                                $("#' . $id . '").b2upanel("submit", $(this).closest(".content-node"));
                                            });
                                            $("#' . $id . '-pagination .b2upanel-page-input").on("keypress", function(e) {
                                                if (e.which === 13) {
                                                    var pg = $(this).val() - 1;
                                                    if (pg < 0) pg = 0;
                                                    if (pg >= ' . $pageCount . ') pg = ' . $pageCount . ' - 1;
                                                    $("#' . $id . '-page").val(pg);
                                                    $("#' . $id . '-offset").val(pg * $("#' . $id . '-limit").val());';
            if ( !is_null( $args ) ) {
                $html .= '			                $("#' . $id . '").data("args",' . json_encode( $args ) . ');';
            }
            $html .= '				                $("#' . $id . '").b2upanel("submit", $(this).closest(".content-node"));
                                                }
                                            });
                		                    $("#' . $id . '-pagination .page-link").on("click", function() {
                			                    if (!$(this).parent().hasClass("disabled")) {
                				                    $("#' . $id . '-page").val($(this).data("page"));
                				                    $("#' . $id . '-offset").val($(this).data("page") * $("#' . $id . '-limit").val());';
            if ( !is_null( $args ) ) {
                $html .= '			                $("#' . $id . '").data("args",' . json_encode( $args ) . ');';
            }
            $html .= '				                $("#' . $id . '").b2upanel("submit", $(this).closest(".content-node"));
                			                    }
                		                    });
                	                    });
                                    </script>';
        }
        return $html;
    }

	public function submit() {
		throw new \Exception("submit must be overloaded by derive class!");
	}
}