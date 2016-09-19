<head>
    <meta charset="UTF-8">
    <title>Url Shortener</title>
    <link rel="stylesheet" type="text/css" href="stylesheet.css">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-T8Gy5hrqNKT+hzMclPo118YTQO6cYprQmhrYwIiQ/3axmI1hQomh7Ud2hPOy8SP1" crossorigin="anonymous">
    <script src="jquery-3.1.0.min.js"></script>
    <script src="script.js"></script>

</head>

<body>
    <div id="header">
        <div id="userpanelHead"><p>Short url</p></div>
        <div id="userpanelHead"><p>User panel</p></div>
    </div>

    <div id="footer">
    	<div class="linkbox">
            <div id="linkboxHeader"><h2>Your Active Short Urls</h2></div>
    		<table class="linktable" id="activeLinksTable">
                <thead>
    			 <tr>
                    <th><div class="selectRow">Select</div></th>
    				<th><div class="tableCell" id="longTD">Long Url</div></th>
    				<th><div class="tableCell" id="mediumTD">Short Url</div></th>
    				<th><div class="tableCell" id="smallTD">Counter</div></th>
    				<th><div class="tableCell" id="smallTD">Duration</div></th>
                    <th><div class="tableCell" id="mediumTD">Expiration Date</div></th>
    				<th><div class="tableCell" id="tinyTD">Password</div></th>
    			 </tr>
                </thead>
                <tbody id="activeLinks">
    			 <tr>
                    <td><div class="selectRow"></div></td>
    				<td><div class="tableCell" id="longTD">http://www.w3schools.com/tags/ref_standardattributes.asp</div></td>
    				<td><div class="tableCell" id="mediumTD">homestead.app/NZ719dDg</div></td>
    				<td><div class="tableCell" id="smallTD">11</div></td>
    				<td><div class="tableCell" id="smallTD">80</div></td>
                    <td><div class="tableCell" id="mediumTD">2016-09-15 11:08:03</div></td>
    				<td><div class="tableCell" id="tinyTD">No</div></td>
    			 </tr>
                </tbody>
    		</table>
    	</div>
    	<div class="linkbox" id="inactiveLinks">
            <div id="linkboxHeader"><h2>Your expired Short Urls</h2></div>
    		<table class="linkTable" id="inactiveLinksTable">
    			<tr>
                    <th><div class="selectRow">Select</div></th>
    				<th><div class="tableCell" id="longTD">Long Url</div></th>
                    <th><div class="tableCell" id="mediumTD">Short Url</div></th>
                    <th><div class="tableCell" id="smallTD">Counter</div></th>
                    <th><div class="tableCell" id="mediumTD">Expired</div></th>
                    <th><div class="tableCell" id="tinyTD">Password</div></th>
    			</tr>
    		</table>
    	</div>
    </div>
