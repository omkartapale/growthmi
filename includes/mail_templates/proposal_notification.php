<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <title>{subject}</title>
    <style type="text/css">
        html, body {
            font-family: Verdana, Arial;
            font-weight: normal;
        }
    </style>
</head>
<body>
<p>Hi there,</p>

<p>You have received {requestType} request from GrowthMI Website. The details are as follows:</p>

<p>
<ul style="list-style: none">
    <li><b>Request Details</b></li>
    <li><b>Request Type:</b> {requestType}</li>
    <li><b>Name:</b> {requestName}</li>
    <li><b>Email:</b> {requestEmail}</li>
    <li><b>Phone:</b> {requestPhone}</li>
    <li><b>Comment:</b> {requestComment}</li>
    <li>&nbsp;</li>
    <li><b>Additional Details:</b></li>
    <li><b>IP:</b> {ip}</li>
    <li><b>Hostname:</b> {hostname}</li>
    <li><b>City:</b> {city} (based on IP)</li>
    <li><b>Region:</b> {region} (based on IP)</li>
    <li><b>Latitude-Longitude:</b> {latlong} (based on IP)</li>
    <li><b>Timestamp:</b> {timestamp}</li>
</ul>
</p>

</body>
</html>