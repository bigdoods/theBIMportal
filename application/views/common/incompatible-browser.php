	<?php if(!empty($_SERVER['HTTP_USER_AGENT']) && !preg_match('@(trident/|msie )[\d\.]+@i', @$_SERVER['HTTP_USER_AGENT'])){ ?>
		<div id="incompatible-browser">
			<p>We have detected you are using Internet Explorer. Many features on the BIMportal rely on the latest webGL which Internet Explorer doesn't currently support well. Please consider accessing the BIMportal using a different browser which supports the latest webGL such as Google Chrome or Mozilla Firefox.</p>
		</div>
	<?php } ?>