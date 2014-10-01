<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Untitled Document</title>
</head>

<body>
	<div class="test" id="test1">
    	<h3>Test1</h3>
        <p class="testdescription">Who am I ?</p>
        <div class="testResult">
        	<span><?php echo exec('whoami');?></span>
        </div>
    </div>
    
    <div class="test" id="test2">
    	<h3>Test2</h3>
        <p class="testdescription">Is upload/site_photograph/medium folder writable?</p>
        <div class="testResult">
        	<span><?php echo is_writable('../upload/site_photograph/medium') ? 'Yes' : 'No';?></span>
        </div>
    </div>
    
    <div class="test" id="test2">
    	<h3>Test3</h3>
        <p class="testdescription">Is upload/site_renders/original folder writable?</p>
        <div class="testResult">
        	<span><?php echo is_writable('../upload/site_renders/original') ? 'Yes' : 'No';?></span>
        </div>
    </div>

</body>
</html>