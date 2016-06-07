<html>
	<head>
		<title>MetaGer Quicktips</title>
		<link rel="stylesheet" type="text/css" href="/css/bootstrap.css" />
		<style>
			.quicktip {
				margin-bottom:15px;
				padding: 10px;
				line-height: 1.2 !important;
			    font-family: Georgia,"Times New Roman",Palatino,Times,serif;
			    color: #000;
			    border-left: 3px solid #FB0;
			    font-size: 14px;
			}

			.wikiqtextract {
			    font-family: Georgia,"Times New Roman",Palatino,Times,serif;
			}

			.wikiqtextract p {
				margin-bottom:0;
			}

			.quicktip a {
			    color: #00F;
			}

			.qtheader {
				font-family: verdana,arial,helvetica,sans-serif;
			}

			.mutelink {
				color:black!important;
			}

			#spruch {
				margin-bottom:20px;
				padding: 5px;
				line-height: 1.2 !important;
			    color: #070;
			    border-left: 3px solid #070;
			    font-size: 16px;
			    font-family: Georgia,"Times New Roman",Palatino,Times,serif;
			}

			.author {
				float: right !important;
			}
		</style>
	</head>
	<body>
	<blockquote id="spruch">{!! $spruch !!}</blockquote>

	@foreach( $mqs as $mq)
		<div class="quicktip">
			<b class="qtheader">
				<a href="{{ $mq['URL'] }}" target="_blank">{{ $mq['title'] }}</a>
			</b>
			<br />
			<div>{!! $mq['descr'] !!}</div>
		</div>
	@endforeach

	</body>
</html>